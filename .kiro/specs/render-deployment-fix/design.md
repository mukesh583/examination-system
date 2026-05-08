# Render Deployment Fix Design

## Overview

This design addresses critical deployment issues preventing the Laravel examination management system from functioning on Render's hosting platform. The bugs stem from improper proxy configuration, HTTPS detection failures, ephemeral filesystem limitations, and development server usage in production. The fix involves five key changes: (1) configuring TrustProxies middleware to trust all proxies, (2) adding APP_URL to render.yaml environment variables, (3) migrating from file-based to database-backed sessions, (4) adding storage directory permissions setup to the build command, and (5) replacing `php artisan serve` with a production-ready server configuration.

## Glossary

- **Bug_Condition (C)**: The condition that triggers deployment failures - when the application runs on Render's platform with default proxy/session/server configuration
- **Property (P)**: The desired behavior - application successfully handles HTTPS, maintains sessions across restarts, and serves requests with production-ready infrastructure
- **Preservation**: Existing local development functionality, database operations, authentication logic, and business logic that must remain unchanged
- **TrustProxies Middleware**: Laravel middleware in `app/Http/Middleware/TrustProxies.php` that configures which proxy servers to trust for forwarded headers
- **Render Platform**: Cloud hosting platform that uses reverse proxies to handle HTTPS termination and load balancing
- **X-Forwarded Headers**: HTTP headers (X-Forwarded-For, X-Forwarded-Proto, X-Forwarded-Port) set by proxies to indicate original client information
- **Session Driver**: Laravel configuration determining where session data is stored (file, database, redis, etc.)
- **Ephemeral Filesystem**: Temporary storage on Render containers that is wiped on restart, unsuitable for persistent data
- **php artisan serve**: Laravel's built-in development server, not recommended for production use
- **APP_URL**: Environment variable defining the application's base URL, used for generating absolute URLs

## Bug Details

### Bug Condition

The bug manifests when the Laravel application is deployed to Render's platform with the current configuration. The application fails to recognize Render's proxy infrastructure, incorrectly detects HTTP instead of HTTPS, loses sessions on container restarts, may generate incorrect URLs, may fail to write sessions due to permission issues, and runs with a development server instead of production-ready infrastructure.

**Formal Specification:**
```
FUNCTION isBugCondition(input)
  INPUT: input of type DeploymentContext
  OUTPUT: boolean
  
  RETURN input.platform == 'Render'
         AND (input.trustProxiesConfig == null 
              OR input.sessionDriver == 'file'
              OR input.appUrlConfigured == false
              OR input.storagePermissionsInitialized == false
              OR input.webServer == 'php artisan serve')
         AND httpsDetectionFails(input)
         AND (sessionPersistenceFails(input) OR loginFails(input))
END FUNCTION
```

### Examples

- **Proxy Trust Issue**: User accesses `https://examination-system.onrender.com` → Render's proxy forwards request with X-Forwarded-Proto: https → TrustProxies middleware has `$proxies = null` → Laravel doesn't trust the header → `request()->secure()` returns false → Session cookies with `secure` flag fail to set → Login returns 419 CSRF token mismatch
- **Session Persistence Issue**: User logs in successfully → Session stored in `storage/framework/sessions/` → Render container restarts (deployment, scaling, or maintenance) → Ephemeral filesystem wiped → Session file lost → User logged out unexpectedly
- **URL Generation Issue**: Application generates password reset link → `APP_URL` not configured in render.yaml → Laravel uses default `http://localhost` → Email contains incorrect URL → User cannot reset password
- **Storage Permission Issue**: Application starts → Attempts to write session file → `storage/framework/sessions/` has incorrect permissions → Session write fails → Login fails with 500 error
- **Development Server Issue**: Application serves requests with `php artisan serve` → Single-threaded server cannot handle concurrent requests efficiently → Performance degradation under load → Potential timeout issues

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- Local development environment must continue to work without requiring proxy trust configuration
- All database operations (migrations, queries, transactions) must continue to function correctly
- Authentication and authorization logic must remain unchanged
- Business logic for GPA calculations, result management, and exports must remain unchanged
- Admin and student role-based access control must continue to work
- CSRF protection must continue to function correctly
- All existing routes and controllers must continue to work
- Configuration caching, route caching, and view caching must continue to optimize performance

**Scope:**
All inputs that do NOT involve Render's deployment platform should be completely unaffected by this fix. This includes:
- Local development with `php artisan serve` or Laravel Valet
- Other hosting platforms (AWS, DigitalOcean, Heroku, etc.)
- Docker-based local development
- Testing environments with PHPUnit

## Hypothesized Root Cause

Based on the bug description and requirements analysis, the root causes are:

1. **Proxy Trust Configuration**: The `TrustProxies` middleware has `$proxies = null`, which means Laravel doesn't trust any proxy servers. Render uses reverse proxies for HTTPS termination, so the X-Forwarded-Proto header indicating HTTPS is ignored. This causes Laravel to detect all requests as HTTP, breaking secure cookie functionality and CSRF protection.

2. **File-Based Sessions on Ephemeral Filesystem**: The `render.yaml` configures `SESSION_DRIVER=file`, storing sessions in `storage/framework/sessions/`. Render containers use ephemeral filesystems that are wiped on restart, causing all sessions to be lost. This breaks user authentication persistence.

3. **Missing APP_URL Configuration**: The `render.yaml` does not include an `APP_URL` environment variable. Laravel defaults to `http://localhost`, causing incorrect URL generation in emails, redirects, and asset URLs.

4. **Storage Directory Permissions**: The build command in `render.yaml` does not initialize storage directory permissions. Laravel needs write access to `storage/framework/sessions/`, `storage/logs/`, and other directories. Without proper permissions, session writes and logging fail.

5. **Development Server in Production**: The `render.yaml` uses `php artisan serve` as the start command. This is Laravel's built-in development server, which is single-threaded and not optimized for production traffic. It cannot handle concurrent requests efficiently and may cause performance issues.

## Correctness Properties

Property 1: Bug Condition - Render Deployment Success

_For any_ deployment context where the application runs on Render's platform, the fixed configuration SHALL correctly detect HTTPS connections, maintain session persistence across container restarts, generate correct URLs, have proper storage permissions, and serve requests with production-ready infrastructure, allowing users to successfully login and use the application.

**Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9, 2.10**

Property 2: Preservation - Local Development and Other Platforms

_For any_ deployment context that is NOT Render's platform (local development, other hosting platforms, testing environments), the fixed configuration SHALL produce exactly the same behavior as the original configuration, preserving all existing functionality for non-Render deployments.

**Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10**

## Fix Implementation

### Changes Required

Assuming our root cause analysis is correct:

**File 1**: `app/Http/Middleware/TrustProxies.php`

**Change**: Configure proxy trust for Render platform

**Specific Changes**:
1. **Set `$proxies` to trust all proxies**: Change `protected $proxies;` to `protected $proxies = '*';`
   - This tells Laravel to trust all proxy servers, which is safe on Render since all traffic goes through Render's infrastructure
   - Alternative approach: Use `protected $proxies = '**';` (Laravel 10+) or check for `TRUST_PROXIES` environment variable
   - The `*` value means "trust all proxies" and is appropriate for platforms like Render, Heroku, and AWS ELB

**File 2**: `render.yaml`

**Change**: Add APP_URL environment variable and update server configuration

**Specific Changes**:
1. **Add APP_URL environment variable**: Add to the `envVars` section:
   ```yaml
   - key: APP_URL
     value: https://examination-system.onrender.com
   ```
   - This ensures Laravel generates correct HTTPS URLs for redirects, emails, and assets
   - The value should match the actual Render deployment URL

2. **Change SESSION_DRIVER to database**: Update existing environment variable:
   ```yaml
   - key: SESSION_DRIVER
     value: database
   ```
   - This migrates from file-based to database-backed sessions
   - Database sessions persist across container restarts

3. **Update startCommand to use production server**: Replace `php artisan serve` with a production-ready option:
   ```yaml
   startCommand: php -S 0.0.0.0:$PORT -t public
   ```
   - This uses PHP's built-in server with the public directory as document root
   - Alternative: Install and configure a proper web server like nginx or Apache (more complex but better for high traffic)
   - The `-S` flag starts PHP's built-in server, `-t public` sets the document root

4. **Add storage permissions to buildCommand**: Update the build command to include permission setup:
   ```yaml
   buildCommand: composer install --optimize-autoloader --no-dev && php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && chmod -R 775 storage bootstrap/cache
   ```
   - The `chmod -R 775 storage bootstrap/cache` ensures Laravel can write to storage directories
   - This must run during build, not at runtime, to persist across container restarts

**File 3**: `database/migrations/YYYY_MM_DD_HHMMSS_create_sessions_table.php` (new file)

**Change**: Create database migration for sessions table

**Specific Changes**:
1. **Generate sessions table migration**: Run `php artisan session:table` to create the migration file
   - This creates a migration with the correct schema for database sessions
   - The migration will be run automatically during deployment via the existing `php artisan migrate --force` command in render.yaml

2. **Migration schema**: The generated migration should create a table with columns:
   - `id` (string, primary key) - session identifier
   - `user_id` (bigint, nullable, indexed) - associated user ID
   - `ip_address` (string, nullable) - client IP address
   - `user_agent` (text, nullable) - client user agent
   - `payload` (longtext) - serialized session data
   - `last_activity` (integer, indexed) - timestamp of last activity

## Testing Strategy

### Validation Approach

The testing strategy follows a two-phase approach: first, surface counterexamples that demonstrate the bugs on the unfixed code deployed to Render, then verify the fixes work correctly and preserve existing behavior in local development and other environments.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexamples that demonstrate the bugs BEFORE implementing the fix. Confirm or refute the root cause analysis. If we refute, we will need to re-hypothesize.

**Test Plan**: Deploy the UNFIXED code to Render and manually test the failure scenarios. Document the exact error messages, logs, and behavior. This confirms the root causes before implementing fixes.

**Test Cases**:
1. **HTTPS Detection Test**: Deploy unfixed code → Access application via HTTPS → Check Laravel logs for `request()->secure()` value → Observe it returns false (will fail on unfixed code)
2. **Login Test**: Deploy unfixed code → Attempt to login → Observe 419 CSRF token mismatch error (will fail on unfixed code)
3. **Session Persistence Test**: Deploy unfixed code → Login successfully (if possible) → Trigger container restart → Observe user is logged out (will fail on unfixed code)
4. **URL Generation Test**: Deploy unfixed code → Trigger password reset email → Check generated URL → Observe it uses `http://localhost` (will fail on unfixed code)
5. **Storage Permission Test**: Deploy unfixed code → Check Laravel logs → Observe session write failures or permission errors (may fail on unfixed code)
6. **Server Performance Test**: Deploy unfixed code → Send concurrent requests → Observe slow response times or timeouts (may fail on unfixed code)

**Expected Counterexamples**:
- Laravel logs show `request()->secure() = false` despite HTTPS access
- Login fails with 419 CSRF token mismatch error
- Sessions are lost after container restart
- Generated URLs use `http://localhost` instead of actual domain
- Laravel logs show "failed to open stream: Permission denied" errors
- Concurrent requests experience delays or timeouts

### Fix Checking

**Goal**: Verify that for all inputs where the bug condition holds (Render deployment), the fixed configuration produces the expected behavior.

**Pseudocode:**
```
FOR ALL deploymentContext WHERE isBugCondition(deploymentContext) DO
  result := deployWithFixedConfiguration(deploymentContext)
  ASSERT httpsDetectedCorrectly(result)
  ASSERT loginSucceeds(result)
  ASSERT sessionsPersistAcrossRestarts(result)
  ASSERT urlsGeneratedCorrectly(result)
  ASSERT storagePermissionsCorrect(result)
  ASSERT productionServerUsed(result)
END FOR
```

### Preservation Checking

**Goal**: Verify that for all inputs where the bug condition does NOT hold (local development, other platforms), the fixed configuration produces the same result as the original configuration.

**Pseudocode:**
```
FOR ALL deploymentContext WHERE NOT isBugCondition(deploymentContext) DO
  ASSERT originalConfiguration(deploymentContext) = fixedConfiguration(deploymentContext)
END FOR
```

**Testing Approach**: Property-based testing is recommended for preservation checking because:
- It generates many test cases automatically across different deployment contexts
- It catches edge cases that manual testing might miss (different PHP versions, different database configurations, etc.)
- It provides strong guarantees that behavior is unchanged for all non-Render deployments

**Test Plan**: Observe behavior on UNFIXED code first in local development, then write tests capturing that behavior and verify it continues after the fix.

**Test Cases**:
1. **Local Development Preservation**: Run unfixed code locally with `php artisan serve` → Verify login works → Apply fix → Verify login still works with same behavior
2. **Database Operations Preservation**: Run unfixed code → Execute migrations, queries, transactions → Document behavior → Apply fix → Verify identical behavior
3. **Authentication Preservation**: Run unfixed code → Test login, logout, password reset → Document behavior → Apply fix → Verify identical behavior
4. **Business Logic Preservation**: Run unfixed code → Test GPA calculations, result management, exports → Document behavior → Apply fix → Verify identical behavior
5. **Configuration Caching Preservation**: Run unfixed code → Run `php artisan config:cache` → Verify application works → Apply fix → Verify identical behavior

### Unit Tests

- Test TrustProxies middleware configuration with mocked proxy headers
- Test session driver configuration reads from environment variable correctly
- Test APP_URL configuration is used for URL generation
- Test storage directory permissions are set correctly during build
- Test database session driver can read/write session data

### Property-Based Tests

- Generate random deployment contexts (Render, local, AWS, etc.) and verify correct behavior for each
- Generate random proxy header combinations and verify HTTPS detection works correctly
- Generate random session data and verify persistence across simulated container restarts
- Test URL generation across many different APP_URL configurations

### Integration Tests

- Deploy to Render staging environment and test full login flow
- Test session persistence by triggering container restart and verifying user remains logged in
- Test URL generation in emails by triggering password reset flow
- Test concurrent user sessions to verify database session driver handles multiple users
- Test application performance under load with production server configuration
- Verify local development still works after applying all fixes

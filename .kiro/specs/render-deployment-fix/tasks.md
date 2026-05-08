
# Implementation Plan

- [x] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Render Deployment Failures
  - **CRITICAL**: This test MUST FAIL on unfixed code - failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior - it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate the deployment bugs exist
  - **Scoped PBT Approach**: Deploy unfixed code to Render and manually test failure scenarios to confirm root causes
  - Test implementation details from Bug Condition in design:
    - Deploy unfixed code to Render staging environment
    - Test HTTPS detection: Access via HTTPS and verify `request()->secure()` returns false (bug)
    - Test login: Attempt login and verify 419 CSRF token mismatch error occurs (bug)
    - Test session persistence: Login (if possible), trigger container restart, verify user is logged out (bug)
    - Test URL generation: Trigger password reset email and verify URL uses `http://localhost` (bug)
    - Test storage permissions: Check Laravel logs for session write failures or permission errors (bug)
    - Test server performance: Send concurrent requests and observe slow response times or timeouts (bug)
  - The test assertions should match the Expected Behavior Properties from design:
    - HTTPS should be detected correctly (Property 1)
    - Login should succeed without CSRF errors (Property 1)
    - Sessions should persist across container restarts (Property 1)
    - URLs should be generated with correct HTTPS domain (Property 1)
    - Storage directories should have proper write permissions (Property 1)
    - Server should handle concurrent requests efficiently (Property 1)
  - Run test on UNFIXED code
  - **EXPECTED OUTCOME**: Test FAILS (this is correct - it proves the bugs exist)
  - Document counterexamples found to understand root causes:
    - Laravel logs showing `request()->secure() = false` despite HTTPS
    - 419 CSRF token mismatch error on login
    - Sessions lost after container restart
    - Generated URLs using `http://localhost` instead of actual domain
    - Permission denied errors in Laravel logs
    - Slow response times or timeouts under concurrent load
  - Mark task complete when test is written, run, and failures are documented
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 1.10_

- [x] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Local Development and Other Platforms
  - **IMPORTANT**: Follow observation-first methodology
  - Observe behavior on UNFIXED code for non-Render deployments (local development, other platforms)
  - Write property-based tests capturing observed behavior patterns from Preservation Requirements:
    - Local development with `php artisan serve` should work without proxy trust configuration
    - Database operations (migrations, queries, transactions) should function correctly
    - Authentication and authorization logic should work unchanged
    - Business logic (GPA calculations, result management, exports) should work unchanged
    - Admin and student role-based access control should work correctly
    - CSRF protection should function correctly
    - All existing routes and controllers should work
    - Configuration caching, route caching, and view caching should optimize performance
  - Property-based testing generates many test cases for stronger guarantees:
    - Generate random deployment contexts (local, Docker, other platforms)
    - Generate random database operations and verify identical behavior
    - Generate random authentication flows and verify identical behavior
    - Generate random business logic operations and verify identical behavior
  - Run tests on UNFIXED code in local development environment
  - **EXPECTED OUTCOME**: Tests PASS (this confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10_

- [x] 3. Fix Render deployment issues

  - [x] 3.1 Configure TrustProxies middleware to trust all proxies
    - Open `app/Http/Middleware/TrustProxies.php`
    - Change `protected $proxies;` to `protected $proxies = '*';`
    - This tells Laravel to trust all proxy servers (safe on Render since all traffic goes through Render's infrastructure)
    - The `*` value means "trust all proxies" and is appropriate for platforms like Render, Heroku, and AWS ELB
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.trustProxiesConfig == null_
    - _Expected_Behavior: Laravel correctly detects HTTPS connections by trusting X-Forwarded-Proto headers from Render's proxies_
    - _Preservation: Local development must continue to work without requiring proxy trust configuration (Requirement 3.1)_
    - _Requirements: 1.3, 1.4, 2.3, 2.4, 3.1_

  - [x] 3.2 Add APP_URL environment variable to render.yaml
    - Open `render.yaml`
    - Add to the `envVars` section (after FORCE_HTTPS):
      ```yaml
      - key: APP_URL
        value: https://examination-system.onrender.com
      ```
    - This ensures Laravel generates correct HTTPS URLs for redirects, emails, and assets
    - The value should match the actual Render deployment URL
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.appUrlConfigured == false_
    - _Expected_Behavior: Laravel generates correct HTTPS URLs using the APP_URL environment variable_
    - _Preservation: Environment variables must continue to load correctly in all environments (Requirement 3.10)_
    - _Requirements: 1.8, 2.8, 3.10_

  - [x] 3.3 Change SESSION_DRIVER to database in render.yaml
    - Open `render.yaml`
    - Update the existing SESSION_DRIVER environment variable:
      ```yaml
      - key: SESSION_DRIVER
        value: database
      ```
    - This migrates from file-based to database-backed sessions
    - Database sessions persist across container restarts
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.sessionDriver == 'file'_
    - _Expected_Behavior: Sessions persist across container restarts using database-backed storage_
    - _Preservation: Database operations must continue to function correctly (Requirement 3.3)_
    - _Requirements: 1.7, 2.7, 3.3_

  - [x] 3.4 Generate sessions table migration
    - Run `php artisan session:table` to create the migration file
    - This creates a migration with the correct schema for database sessions
    - The migration will be run automatically during deployment via the existing `php artisan migrate --force` command in render.yaml
    - Migration schema should create a table with columns:
      - `id` (string, primary key) - session identifier
      - `user_id` (bigint, nullable, indexed) - associated user ID
      - `ip_address` (string, nullable) - client IP address
      - `user_agent` (text, nullable) - client user agent
      - `payload` (longtext) - serialized session data
      - `last_activity` (integer, indexed) - timestamp of last activity
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.sessionDriver == 'database' AND sessions table does not exist_
    - _Expected_Behavior: Database sessions table exists and can store session data_
    - _Preservation: Database migrations must continue to execute successfully during deployment (Requirement 3.8)_
    - _Requirements: 1.7, 2.7, 3.8_

  - [x] 3.5 Add storage permissions to buildCommand in render.yaml
    - Open `render.yaml`
    - Update the `buildCommand` to include permission setup at the end:
      ```yaml
      buildCommand: composer install --optimize-autoloader --no-dev && php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && chmod -R 775 storage bootstrap/cache
      ```
    - The `chmod -R 775 storage bootstrap/cache` ensures Laravel can write to storage directories
    - This must run during build, not at runtime, to persist across container restarts
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.storagePermissionsInitialized == false_
    - _Expected_Behavior: Storage directories have proper write permissions initialized via the build command_
    - _Preservation: Configuration caching, route caching, and view caching must continue to optimize performance (Requirement 3.9)_
    - _Requirements: 1.9, 2.9, 3.9_

  - [x] 3.6 Replace php artisan serve with production server in render.yaml
    - Open `render.yaml`
    - Update the `startCommand` to use PHP's built-in server with proper configuration:
      ```yaml
      startCommand: php -S 0.0.0.0:$PORT -t public
      ```
    - This uses PHP's built-in server with the public directory as document root
    - The `-S` flag starts PHP's built-in server, `-t public` sets the document root
    - This is more production-ready than `php artisan serve` (though a proper web server like nginx would be even better for high traffic)
    - _Bug_Condition: isBugCondition(input) where input.platform == 'Render' AND input.webServer == 'php artisan serve'_
    - _Expected_Behavior: Application serves requests with a production-ready server configuration_
    - _Preservation: All existing routes and controllers must continue to work (Requirement 3.7)_
    - _Requirements: 1.10, 2.10, 3.2, 3.7_

  - [x] 3.7 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Render Deployment Success
    - **IMPORTANT**: Re-run the SAME test from task 1 - do NOT write a new test
    - The test from task 1 encodes the expected behavior
    - When this test passes, it confirms the expected behavior is satisfied
    - Deploy FIXED code to Render staging environment
    - Run bug condition exploration test from step 1:
      - Test HTTPS detection: Access via HTTPS and verify `request()->secure()` returns true (fixed)
      - Test login: Attempt login and verify successful authentication and redirect (fixed)
      - Test session persistence: Login, trigger container restart, verify user remains logged in (fixed)
      - Test URL generation: Trigger password reset email and verify URL uses correct HTTPS domain (fixed)
      - Test storage permissions: Check Laravel logs for no permission errors (fixed)
      - Test server performance: Send concurrent requests and verify efficient handling (fixed)
    - **EXPECTED OUTCOME**: Test PASSES (confirms bugs are fixed)
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9, 2.10_

  - [x] 3.8 Verify preservation tests still pass
    - **Property 2: Preservation** - Local Development and Other Platforms
    - **IMPORTANT**: Re-run the SAME tests from task 2 - do NOT write new tests
    - Run preservation property tests from step 2 in local development environment:
      - Verify local development with `php artisan serve` still works
      - Verify database operations function identically
      - Verify authentication and authorization logic unchanged
      - Verify business logic (GPA calculations, result management, exports) unchanged
      - Verify admin and student role-based access control works
      - Verify CSRF protection functions correctly
      - Verify all existing routes and controllers work
      - Verify configuration caching, route caching, and view caching optimize performance
    - **EXPECTED OUTCOME**: Tests PASS (confirms no regressions)
    - Confirm all tests still pass after fix (no regressions)
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10_

- [x] 4. Checkpoint - Ensure all tests pass
  - Verify all bug condition tests pass on Render deployment
  - Verify all preservation tests pass in local development
  - Verify application is fully functional on Render:
    - Users can access the application over HTTPS without security warnings
    - Users can successfully login and access their dashboards
    - Sessions persist across container restarts
    - URLs are generated correctly with HTTPS
    - No permission errors in logs
    - Application handles concurrent requests efficiently
  - Verify application is fully functional in local development:
    - All existing functionality works unchanged
    - No regressions in database operations, authentication, or business logic
  - If any issues arise, investigate and resolve before marking complete
  - Ask the user if questions arise

# Render Deployment Guide - Examination Management System

## 🎯 Current Status

**Date:** May 8, 2026  
**Status:** ✅ All fixes applied and pushed to GitHub  
**Deployment:** 🔄 Automatic deployment should be in progress on Render

---

## 🔧 Fixes Applied

### 1. **Database Connection Fix (CRITICAL)**
**Problem:** Application was trying to use SQLite instead of PostgreSQL  
**Root Cause:** Configuration was cached before environment variables were loaded  
**Solution:** Moved `config:cache` from `buildCommand` to `startCommand`

**Before:**
```yaml
buildCommand: ... && php artisan config:cache  # ❌ Too early
startCommand: php -S 0.0.0.0:$PORT -t public
```

**After:**
```yaml
buildCommand: composer install --optimize-autoloader --no-dev && chmod -R 775 storage bootstrap/cache
startCommand: php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php -S 0.0.0.0:$PORT -t public
```

### 2. **HTTPS Detection Fix**
**Problem:** Laravel not recognizing HTTPS connections through Render's proxy  
**Solution:** Configured `TrustProxies` middleware to trust all proxies (`$proxies = '*'`)

### 3. **Session Persistence Fix**
**Problem:** File-based sessions lost on container restart  
**Solution:** Changed to database-backed sessions (`SESSION_DRIVER=database`)

### 4. **URL Generation Fix**
**Problem:** URLs generated as `http://localhost`  
**Solution:** Added `APP_URL=https://examination-system.onrender.com`

### 5. **Storage Permissions Fix**
**Problem:** Laravel unable to write logs/sessions  
**Solution:** Added `chmod -R 775 storage bootstrap/cache` to build command

### 6. **Production Server Fix**
**Problem:** Using development server (`php artisan serve`)  
**Solution:** Using `php -S 0.0.0.0:$PORT -t public` for better performance

---

## 📋 Deployment Checklist

### Phase 1: Pre-Deployment (✅ COMPLETED)
- [x] Fix database connection configuration
- [x] Configure TrustProxies middleware
- [x] Set up database sessions
- [x] Create sessions table migration
- [x] Configure APP_URL
- [x] Set storage permissions
- [x] Configure production server
- [x] Commit changes to Git
- [x] Push to GitHub

### Phase 2: Render Deployment (🔄 IN PROGRESS)
- [ ] Render detects GitHub push
- [ ] Build starts automatically
- [ ] Dependencies installed
- [ ] Storage permissions set
- [ ] Configuration cached
- [ ] Routes cached
- [ ] Views cached
- [ ] Migrations run
- [ ] Admin user seeded
- [ ] Server starts

### Phase 3: Post-Deployment Testing (⏳ PENDING)
- [ ] Application loads without errors
- [ ] Login page displays correctly
- [ ] Admin login works
- [ ] Dashboard loads
- [ ] HTTPS works (green padlock)
- [ ] Sessions persist
- [ ] No database errors

---

## 🧪 Testing Instructions

### Test 1: Basic Access
1. Open browser
2. Go to: `https://examination-system-uzqjp.onrender.com`
3. **Expected:** Login page loads without errors
4. **Check:** Green padlock in address bar (HTTPS working)

### Test 2: Admin Login
1. On login page, enter:
   - **Email:** `admin@examination-system.com`
   - **Password:** `admin123`
2. Click "Sign in"
3. **Expected:** Redirect to admin dashboard
4. **Expected:** Welcome message displayed

### Test 3: Session Persistence
1. After successful login
2. Wait 2-3 minutes
3. Refresh the page
4. **Expected:** Still logged in (not redirected to login)

### Test 4: Database Connection
1. On admin dashboard
2. Try to view any data (students, results, etc.)
3. **Expected:** Data loads without errors
4. **Expected:** No SQLite errors

### Test 5: CSRF Protection
1. Try to login with correct credentials
2. **Expected:** No 419 CSRF token mismatch errors
3. **Expected:** Login succeeds

---

## 🔍 Monitoring Deployment

### How to Check Deployment Status

1. **Go to Render Dashboard:**
   - URL: https://dashboard.render.com
   - Login with your credentials

2. **Find Your Service:**
   - Look for `examination-system` in the services list
   - Click on it

3. **Check Deployment Status:**
   - Look at the top of the page
   - You should see: "Deploying..." or "Live"

4. **View Deployment Logs:**
   - Click on "Logs" tab
   - Watch the deployment progress in real-time

### What to Look For in Logs

**✅ Good Signs:**
```
==> Installing dependencies...
==> Running: composer install --optimize-autoloader --no-dev
==> Running: chmod -R 775 storage bootstrap/cache
==> Build successful
==> Starting service...
==> Running: php artisan config:cache
Configuration cached successfully!
==> Running: php artisan route:cache
Routes cached successfully!
==> Running: php artisan view:cache
Blade templates cached successfully!
==> Running: php artisan migrate --force
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
...
==> Running: php artisan db:seed --class=AdminSeeder --force
Admin user created successfully!
Email: admin@examination-system.com
Password: admin123
==> Server started on port 10000
```

**❌ Bad Signs (and what to do):**

1. **"Database connection failed"**
   - Check if PostgreSQL database is running
   - Verify database credentials in render.yaml

2. **"Class 'AdminSeeder' not found"**
   - Run: `composer dump-autoload` locally
   - Commit and push again

3. **"Permission denied" errors**
   - Check if `chmod` command ran successfully
   - May need to add to startCommand instead

4. **"SQLSTATE[HY000] [2002] Connection refused"**
   - Database not ready yet
   - Wait a few seconds and it should retry

---

## 🆘 Troubleshooting Guide

### Issue 1: Still Getting SQLite Error

**Symptoms:**
- Error: "Database file at path [/var/www/database/database.sqlite] does not exist"
- Connection: sqlite

**Diagnosis:**
Configuration cache is still using old settings

**Solution:**
1. Go to Render dashboard
2. Click on your service
3. Click "Manual Deploy" dropdown
4. Select "Clear build cache & deploy"
5. Wait for deployment to complete

### Issue 2: 500 Internal Server Error

**Symptoms:**
- White page with "500 Internal Server Error"
- No specific error message

**Diagnosis:**
Check Render logs for the actual error

**Solution:**
1. Go to Render dashboard → Logs
2. Look for PHP errors or stack traces
3. Common causes:
   - Missing environment variable
   - Migration failed
   - Permission issue

### Issue 3: 419 CSRF Token Mismatch

**Symptoms:**
- Login fails with "419 Page Expired"
- CSRF token mismatch error

**Diagnosis:**
Session not being set correctly

**Solution:**
1. Check if sessions table exists:
   - Look in logs for migration output
2. Verify `SESSION_DRIVER=database` in render.yaml
3. Clear browser cookies and try again

### Issue 4: Admin User Not Found

**Symptoms:**
- Login fails with "credentials do not match"
- Using correct email/password

**Diagnosis:**
Admin seeder didn't run or failed

**Solution:**
1. Check Render logs for seeder output
2. Look for: "Admin user created successfully!"
3. If not found, manually trigger seeder:
   - Add to startCommand: `php artisan db:seed --class=AdminSeeder --force`

### Issue 5: Deployment Stuck or Failed

**Symptoms:**
- Deployment shows "In Progress" for >10 minutes
- Deployment failed with unclear error

**Solution:**
1. Cancel the deployment
2. Check render.yaml syntax
3. Try manual deploy with cache clear
4. Check Render status page: https://status.render.com

---

## 📊 Expected Deployment Timeline

| Phase | Duration | What's Happening |
|-------|----------|------------------|
| **Build** | 2-3 min | Installing dependencies, setting permissions |
| **Cache** | 30 sec | Caching config, routes, views |
| **Migrate** | 30 sec | Running database migrations |
| **Seed** | 10 sec | Creating admin user |
| **Start** | 10 sec | Starting PHP server |
| **Total** | ~4 min | Complete deployment |

---

## 🔐 Admin Credentials

**Email:** `admin@examination-system.com`  
**Password:** `admin123`

⚠️ **IMPORTANT:** Change this password after first login!

---

## 🌐 Application URLs

**Production:** https://examination-system-uzqjp.onrender.com  
**Login:** https://examination-system-uzqjp.onrender.com/login  
**Admin Dashboard:** https://examination-system-uzqjp.onrender.com/admin/dashboard

---

## 📞 Support

If you encounter issues not covered in this guide:

1. **Check Render Logs:**
   - Most issues are visible in deployment logs
   - Look for error messages and stack traces

2. **Check Laravel Logs:**
   - Logs are in `storage/logs/laravel.log`
   - Render may show these in the Logs tab

3. **Common Commands:**
   ```bash
   # Clear all caches
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   
   # Re-run migrations
   php artisan migrate:fresh --force --seed
   
   # Check database connection
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

---

## ✅ Success Indicators

You'll know everything is working when:

1. ✅ Application loads at https://examination-system-uzqjp.onrender.com
2. ✅ Green padlock in browser (HTTPS working)
3. ✅ Login page displays correctly
4. ✅ Admin login succeeds with provided credentials
5. ✅ Dashboard loads with data
6. ✅ No database errors in logs
7. ✅ Sessions persist after page refresh
8. ✅ All features work as expected

---

## 📝 Next Steps After Successful Deployment

1. **Change Admin Password:**
   - Login as admin
   - Go to profile/settings
   - Change password from `admin123` to something secure

2. **Test All Features:**
   - Create test student
   - Add test results
   - Generate reports
   - Export data

3. **Monitor Performance:**
   - Check response times
   - Monitor database queries
   - Watch for errors in logs

4. **Set Up Monitoring:**
   - Configure Render alerts
   - Set up uptime monitoring
   - Enable error tracking

---

**Last Updated:** May 8, 2026  
**Version:** 1.0  
**Status:** Ready for deployment testing

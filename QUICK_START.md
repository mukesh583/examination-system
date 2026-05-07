# Quick Start Guide - Examination Management System

## Step 1: Start XAMPP MySQL

1. **Open XAMPP Control Panel**
2. **Click "Start" next to MySQL** (it should turn green)
3. Wait for MySQL to fully start

## Step 2: Create Database

### Option A: Using phpMyAdmin (Recommended - Easiest)

1. Open browser and go to: **http://localhost/phpmyadmin**
2. Click **"New"** in the left sidebar
3. Enter database name: **examination_system**
4. Choose collation: **utf8mb4_unicode_ci**
5. Click **"Create"**

### Option B: Using XAMPP Shell

1. In XAMPP Control Panel, click **"Shell"** button
2. Run these commands:
```bash
mysql -u root
CREATE DATABASE examination_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

## Step 3: Run Migrations and Seed Data

Open your terminal (PowerShell or CMD) in the `examination-system` folder and run:

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

You should see:
- ✅ Migrations running successfully
- ✅ Seeders creating demo data
- ✅ 5 demo students created

## Step 4: Start Laravel Server

```bash
php artisan serve
```

The server will start at: **http://localhost:8000**

## Step 5: Login and Test

Open your browser and go to: **http://localhost:8000**

### Demo Accounts

Login with any of these accounts:

| Email | Password | Student Profile |
|-------|----------|----------------|
| alice.johnson@university.edu | password | Excellent student (CGPA ~9.0) |
| bob.smith@university.edu | password | Good student (CGPA ~8.0) |
| carol.williams@university.edu | password | Average student (CGPA ~6.5) |
| david.brown@university.edu | password | Struggling student (CGPA ~5.5) |
| emma.davis@university.edu | password | Improving student (CGPA ~7.0) |

## What to Test

After logging in, you should see:

### 1. Dashboard
- ✅ CGPA displayed
- ✅ Total credits earned
- ✅ Semesters completed
- ✅ Pass percentage
- ✅ Performance category
- ✅ Top/bottom performing subjects
- ✅ Failed subjects (if any)

### 2. Results Page
- ✅ List of all semesters
- ✅ Click on a semester to see detailed results
- ✅ Search for subjects
- ✅ Filter by pass/fail status
- ✅ Sort by marks, grade, or subject name

### 3. Progress Page
- ✅ CGPA trend chart
- ✅ Semester-by-semester comparison
- ✅ Highest/lowest performing semesters

### 4. Export Functions
- ✅ Export semester results to PDF
- ✅ Export semester results to CSV

## Troubleshooting

### Issue: "Access denied for user 'root'@'localhost'"

**Solution**: XAMPP MySQL is not running or has a password set.

1. **Check XAMPP**: Make sure MySQL is running (green in XAMPP Control Panel)
2. **If MySQL has a password**: Edit `.env` file and add your password:
   ```
   DB_PASSWORD=your_password_here
   ```
3. **Clear config**: Run `php artisan config:clear`

### Issue: "Database 'examination_system' doesn't exist"

**Solution**: Create the database using phpMyAdmin (see Step 2 above)

### Issue: "No results displayed after login"

**Solution**: Database is empty. Run seeders:
```bash
php artisan db:seed
```

### Issue: "Class not found" errors

**Solution**: Regenerate autoload files:
```bash
composer dump-autoload
```

### Issue: Charts not displaying

**Solution**: Frontend assets need to be built:
```bash
npm install
npm run dev
```

## Next Steps

Once everything is working:

1. ✅ Test all features with different student accounts
2. ✅ Try searching and filtering results
3. ✅ Export results to PDF and CSV
4. ✅ Check the progress tracking charts
5. ✅ Test logout and login again

## Need Help?

- Check `DATABASE_FIX.md` for detailed database troubleshooting
- Check `TESTING_GUIDE.md` for comprehensive testing instructions
- Check `USER_GUIDE.md` for feature documentation
- Check Laravel logs: `storage/logs/laravel.log`

## Success Criteria

Everything is working if:
- ✅ You can login with demo accounts
- ✅ Dashboard shows correct metrics
- ✅ Results display for all semesters
- ✅ Search and filters work
- ✅ Export to PDF/CSV works
- ✅ No errors in browser console
- ✅ No errors in Laravel logs

---

**Ready to start? Follow Step 1 above!** 🚀

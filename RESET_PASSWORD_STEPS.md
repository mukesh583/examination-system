# Reset MySQL Password - Step by Step Guide

Follow these steps carefully to reset your MySQL password to empty (no password).

## Step 1: Stop MySQL

1. Open **XAMPP Control Panel**
2. Find the **MySQL** row
3. Click the **"Stop"** button next to MySQL
4. Wait until it says "Stopped" (the button turns to "Start")

## Step 2: Open XAMPP Shell

1. In XAMPP Control Panel, click the **"Shell"** button (top right)
2. A black command window will open

## Step 3: Navigate to MySQL Bin Folder

In the Shell window, type this command and press Enter:

```bash
cd C:\xampp\mysql\bin
```

## Step 4: Start MySQL in Safe Mode

Type this command and press Enter:

```bash
mysqld --skip-grant-tables --skip-networking
```

**IMPORTANT**: This window will stay open and show some text. **DO NOT CLOSE IT!**

## Step 5: Open a Second Shell Window

1. Go back to XAMPP Control Panel
2. Click the **"Shell"** button again (this opens a SECOND shell)
3. A new black command window will open

## Step 6: Navigate to MySQL Bin (in the second shell)

In the NEW shell window, type:

```bash
cd C:\xampp\mysql\bin
```

## Step 7: Connect to MySQL

Type this command:

```bash
mysql -u root
```

You should see:
```
Welcome to the MySQL monitor...
mysql>
```

## Step 8: Reset the Password

Now type these commands ONE BY ONE (press Enter after each):

```sql
FLUSH PRIVILEGES;
```

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
```

```sql
FLUSH PRIVILEGES;
```

```sql
EXIT;
```

## Step 9: Close Both Shell Windows

1. Close the FIRST shell window (the one running mysqld)
2. Close the SECOND shell window

## Step 10: Restart MySQL Normally

1. Go back to XAMPP Control Panel
2. Click **"Stop"** next to MySQL (if it's running)
3. Wait 5 seconds
4. Click **"Start"** next to MySQL
5. It should turn green and say "Running"

## Step 11: Test phpMyAdmin

1. Open your browser
2. Go to: **http://localhost/phpmyadmin**
3. It should open WITHOUT asking for a password! ✅

## Step 12: Create Database

In phpMyAdmin:

1. Click **"New"** in the left sidebar
2. Enter database name: **examination_system**
3. Choose collation: **utf8mb4_unicode_ci**
4. Click **"Create"**

## Step 13: Run Laravel Migrations

Open your terminal (PowerShell or CMD) and run:

```bash
cd examination-system
php artisan config:clear
php artisan migrate:fresh --seed
```

You should see:
- ✅ Migrations running successfully
- ✅ Seeders creating demo data

## Step 14: Start Laravel Server

```bash
php artisan serve
```

## Step 15: Test the Application

1. Open browser: **http://localhost:8000**
2. Login with: **alice.johnson@university.edu** / **password**
3. You should see the dashboard! 🎉

---

## Troubleshooting

### Issue: "mysqld is not recognized"

**Solution**: Make sure you're in the correct folder:
```bash
cd C:\xampp\mysql\bin
```

### Issue: "Access denied" still appears

**Solution**: Try setting a specific password instead:

In Step 8, instead of `''`, use `'root'`:
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';
```

Then update `.env`:
```
DB_PASSWORD=root
```

### Issue: MySQL won't start after reset

**Solution**: 
1. Restart your computer
2. Start XAMPP as Administrator (right-click → Run as Administrator)
3. Try starting MySQL again

---

## Alternative: Quick Fix with Config File

If the above doesn't work, try this:

1. Open: `C:\xampp\phpMyAdmin\config.inc.php`
2. Find: `$cfg['Servers'][$i]['password'] = '';`
3. Try changing to: `$cfg['Servers'][$i]['password'] = 'root';`
4. Save and refresh phpMyAdmin

---

**Need help? Let me know which step you're stuck on!**

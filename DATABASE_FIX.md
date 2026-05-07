# Database Connection Fix

## Issue
The application cannot connect to the database because MySQL credentials are not configured.

## Error
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: NO)
```

## Solution

### Option 1: Update .env file (Recommended)

Edit `examination-system/.env` file and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examination_system
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

**Replace `your_mysql_password_here` with your actual MySQL root password.**

### Option 2: Create Database and User

If you don't know your MySQL password or want to create a new user:

```sql
-- Login to MySQL as root
mysql -u root -p

-- Create database
CREATE DATABASE examination_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create a new user (optional)
CREATE USER 'exam_user'@'localhost' IDENTIFIED BY 'exam_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON examination_system.* TO 'exam_user'@'localhost';
FLUSH PRIVILEGES;

EXIT;
```

Then update `.env`:
```env
DB_USERNAME=exam_user
DB_PASSWORD=exam_password
```

### Option 3: Use XAMPP Default (No Password)

If using XAMPP with default settings (no password):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examination_system
DB_USERNAME=root
DB_PASSWORD=
```

Make sure XAMPP MySQL is running!

## After Fixing

1. **Clear config cache:**
```bash
php artisan config:clear
```

2. **Test database connection:**
```bash
php artisan migrate:status
```

3. **Run migrations and seed:**
```bash
php artisan migrate:fresh --seed
```

4. **Start server:**
```bash
php artisan serve
```

5. **Login at http://localhost:8000**
   - Email: alice.johnson@university.edu
   - Password: password

## Verification

If successful, you should see:
- ✅ No database connection errors
- ✅ Migrations run successfully
- ✅ Demo data seeded
- ✅ Login page loads
- ✅ Dashboard displays after login

## Still Having Issues?

Check:
1. MySQL/XAMPP is running
2. Database `examination_system` exists
3. Credentials in `.env` are correct
4. Port 3306 is not blocked
5. Run: `php artisan config:clear` after any `.env` changes

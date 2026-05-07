# Reset MySQL Password in XAMPP

Your MySQL has a password set, but XAMPP works best with no password. Follow these steps to reset it:

## Method 1: Using XAMPP Shell (Recommended)

1. **Stop MySQL** in XAMPP Control Panel (click "Stop")

2. **Click "Shell"** button in XAMPP Control Panel

3. **Run these commands** one by one:

```bash
cd C:\xampp\mysql\bin
mysqld --skip-grant-tables --skip-networking &
```

4. **Open a NEW Shell** (click Shell again) and run:

```bash
cd C:\xampp\mysql\bin
mysql -u root
```

5. **In MySQL prompt**, run these commands:

```sql
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
FLUSH PRIVILEGES;
EXIT;
```

6. **Close both Shell windows**

7. **In XAMPP Control Panel**:
   - Click "Stop" next to MySQL (if running)
   - Wait 5 seconds
   - Click "Start" next to MySQL

8. **Test**: Try opening http://localhost/phpmyadmin (should work now!)

## Method 2: Edit phpMyAdmin Config (If Method 1 doesn't work)

1. **Open file**: `C:\xampp\phpMyAdmin\config.inc.php`

2. **Find this line**:
```php
$cfg['Servers'][$i]['password'] = '';
```

3. **Change it to** (try common XAMPP passwords):
```php
$cfg['Servers'][$i]['password'] = 'root';
```
OR
```php
$cfg['Servers'][$i]['password'] = '';
```
OR
```php
$cfg['Servers'][$i]['password'] = 'password';
```

4. **Save the file**

5. **Refresh phpMyAdmin** in your browser

## Method 3: Use the Password in Laravel

If you know your MySQL password, just update the `.env` file:

1. **Open**: `examination-system/.env`

2. **Find**:
```
DB_PASSWORD=
```

3. **Change to** (replace with your actual password):
```
DB_PASSWORD=your_mysql_password
```

4. **Save the file**

5. **Clear Laravel config**:
```bash
cd examination-system
php artisan config:clear
```

## Common XAMPP Default Passwords

Try these common passwords:
- `` (empty - no password)
- `root`
- `password`
- `admin`

## After Fixing

Once MySQL is accessible:

1. **Open phpMyAdmin**: http://localhost/phpmyadmin
2. **Create database**: Click "New" → Enter `examination_system` → Click "Create"
3. **Run migrations**:
```bash
cd examination-system
php artisan config:clear
php artisan migrate:fresh --seed
php artisan serve
```
4. **Test**: http://localhost:8000

## Still Having Issues?

If none of these work, you can:

1. **Reinstall XAMPP** (fresh install has no password)
2. **Use SQLite instead** (no MySQL needed):
   - Edit `.env`: Change `DB_CONNECTION=mysql` to `DB_CONNECTION=sqlite`
   - Create file: `touch database/database.sqlite`
   - Run: `php artisan migrate:fresh --seed`

---

**Need help? Let me know which method you tried and what error you got!**

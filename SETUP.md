# Quick Setup Guide

This guide will help you set up the Examination Management System quickly.

## Prerequisites Checklist

Before starting, ensure you have:

- [ ] PHP 8.1 or higher installed
- [ ] Composer installed
- [ ] MySQL 8.0+ or PostgreSQL 14+ installed and running
- [ ] Redis server installed and running
- [ ] Node.js 18+ and npm installed

## Quick Start (5 Minutes)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (requires Node.js and npm)
npm install
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Database

Edit `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_DATABASE=examination_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Create Database

**MySQL:**
```bash
mysql -u root -p
CREATE DATABASE examination_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**PostgreSQL:**
```bash
psql -U postgres
CREATE DATABASE examination_system;
\q
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Seed Demo Data (Optional)

```bash
php artisan db:seed
```

### Step 7: Build Frontend Assets

```bash
# Development
npm run dev

# Or for production
npm run build
```

### Step 8: Start Application

```bash
php artisan serve
```

Visit: http://localhost:8000

## Verification

### Check PHP Version
```bash
php -v
# Should show PHP 8.1 or higher
```

### Check Composer
```bash
composer --version
```

### Check Node.js and npm
```bash
node --version
npm --version
```

### Check MySQL/PostgreSQL
```bash
# MySQL
mysql --version

# PostgreSQL
psql --version
```

### Check Redis
```bash
redis-cli ping
# Should return: PONG
```

## Common Issues

### Issue: Redis not running

**Solution:**
```bash
# Windows (if using Redis for Windows)
redis-server

# Linux
sudo service redis-server start

# Mac
brew services start redis
```

### Issue: Node.js/npm not installed

**Solution:**

**Windows:**
1. Download from https://nodejs.org/
2. Install the LTS version
3. Restart terminal

**Linux:**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

**Mac:**
```bash
brew install node
```

### Issue: Permission denied on storage/logs

**Solution:**
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache

# Windows
# Run as Administrator or adjust folder permissions in Properties
```

### Issue: Database connection failed

**Solution:**
1. Verify database server is running
2. Check credentials in `.env`
3. Ensure database exists
4. Test connection:
```bash
php artisan migrate:status
```

### Issue: Composer install fails

**Solution:**
```bash
# Clear composer cache
composer clear-cache

# Try again
composer install --no-cache
```

## Development Workflow

### 1. Start Development Environment

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server (if using Vite)
npm run dev

# Terminal 3: Start queue worker (optional)
php artisan queue:work
```

### 2. Watch for File Changes

```bash
npm run dev
```

### 3. Run Tests

```bash
php artisan test
```

### 4. Clear Caches During Development

```bash
php artisan optimize:clear
```

## Next Steps

After setup is complete:

1. **Create Admin User**: Run seeder or create manually
2. **Configure Email**: Set up mail settings in `.env`
3. **Review Configuration**: Check all settings in `config/` directory
4. **Run Tests**: Ensure all tests pass
5. **Review Documentation**: Read README.md for detailed information

## Production Deployment

For production deployment, see the "Production Deployment" section in README.md.

Key steps:
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Run `npm run build`
7. Configure web server (Nginx/Apache)
8. Set up queue workers with Supervisor
9. Configure SSL certificate
10. Set up automated backups

## Getting Help

- Check README.md for detailed documentation
- Review Laravel documentation: https://laravel.com/docs/10.x
- Check application logs: `storage/logs/laravel.log`
- Run diagnostics: `php artisan about`

## System Requirements Summary

| Component | Minimum Version | Recommended |
|-----------|----------------|-------------|
| PHP | 8.1 | 8.2+ |
| MySQL | 8.0 | 8.0+ |
| PostgreSQL | 14 | 14+ |
| Redis | 6.0 | 7.0+ |
| Node.js | 18.x | 20.x LTS |
| Composer | 2.0 | Latest |

## Configuration Checklist

- [ ] `.env` file configured
- [ ] Database created
- [ ] Database credentials set
- [ ] Redis running
- [ ] Application key generated
- [ ] Migrations run
- [ ] Frontend assets built
- [ ] Storage permissions set
- [ ] Queue worker configured (if needed)
- [ ] Mail settings configured (if needed)

## Success!

If you can access http://localhost:8000 and see the application, you're all set!

For detailed information about features and usage, see README.md.

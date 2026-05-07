# Deployment Guide - Examination Management System

This guide provides step-by-step instructions for deploying the Examination Management System to a production environment.

## Prerequisites

- Ubuntu 22.04 LTS or similar Linux distribution
- Root or sudo access
- Domain name (optional but recommended)
- SSL certificate (Let's Encrypt recommended)

## Server Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- MySQL 8.0+ or PostgreSQL 14+
- Redis Server
- Nginx or Apache web server
- Supervisor (for queue workers)

## Step 1: Server Setup

### Update System Packages

```bash
sudo apt update
sudo apt upgrade -y
```

### Install PHP 8.2 and Extensions

```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-pgsql php8.2-redis php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl
```

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### Install Node.js and npm

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### Install MySQL

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Create database:

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE examination_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'exam_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON examination_system.* TO 'exam_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Install Redis

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

Verify Redis is running:

```bash
redis-cli ping
# Should return: PONG
```

### Install Nginx

```bash
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

## Step 2: Application Deployment

### Create Application Directory

```bash
sudo mkdir -p /var/www/examination-system
sudo chown -R $USER:$USER /var/www/examination-system
```

### Clone or Upload Application

Option 1: Clone from Git

```bash
cd /var/www
git clone <repository-url> examination-system
cd examination-system
```

Option 2: Upload via SCP/SFTP

```bash
# From local machine
scp -r examination-system user@server:/var/www/
```

### Install Dependencies

```bash
cd /var/www/examination-system

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm ci --production

# Build assets
npm run build
```

### Configure Environment

```bash
cp .env.example .env
nano .env
```

Update the following in `.env`:

```env
APP_NAME="Examination Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examination_system
DB_USERNAME=exam_user
DB_PASSWORD=strong_password_here

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Generate Application Key

```bash
php artisan key:generate
```

### Run Migrations

```bash
php artisan migrate --force
```

### Seed Initial Data

```bash
php artisan db:seed --class=GradeScaleSeeder --force
```

### Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/examination-system
sudo chmod -R 755 /var/www/examination-system
sudo chmod -R 775 /var/www/examination-system/storage
sudo chmod -R 775 /var/www/examination-system/bootstrap/cache
```

## Step 3: Web Server Configuration

### Nginx Configuration

Create Nginx configuration:

```bash
sudo nano /etc/nginx/sites-available/examination-system
```

Add the following configuration:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    
    root /var/www/examination-system/public;
    index index.php index.html;

    # SSL Configuration (update paths after obtaining certificates)
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Logging
    access_log /var/log/nginx/examination-system-access.log;
    error_log /var/log/nginx/examination-system-error.log;

    # Client body size
    client_max_body_size 20M;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { 
        access_log off; 
        log_not_found off; 
    }
    
    location = /robots.txt  { 
        access_log off; 
        log_not_found off; 
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/examination-system /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Step 4: SSL Certificate (Let's Encrypt)

### Install Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### Obtain Certificate

```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

Follow the prompts to complete the certificate installation.

### Auto-renewal

Certbot automatically sets up renewal. Test it:

```bash
sudo certbot renew --dry-run
```

## Step 5: Queue Worker Setup

### Create Supervisor Configuration

```bash
sudo nano /etc/supervisor/conf.d/examination-system-worker.conf
```

Add the following:

```ini
[program:examination-system-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/examination-system/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/examination-system/storage/logs/worker.log
stopwaitsecs=3600
```

### Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start examination-system-worker:*
```

Check status:

```bash
sudo supervisorctl status
```

## Step 6: Scheduled Tasks (Cron)

Add Laravel scheduler to crontab:

```bash
sudo crontab -e -u www-data
```

Add this line:

```cron
* * * * * cd /var/www/examination-system && php artisan schedule:run >> /dev/null 2>&1
```

## Step 7: Monitoring and Logging

### Log Rotation

Create log rotation configuration:

```bash
sudo nano /etc/logrotate.d/examination-system
```

Add:

```
/var/www/examination-system/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### Monitor Application Logs

```bash
# View Laravel logs
tail -f /var/www/examination-system/storage/logs/laravel.log

# View Nginx access logs
tail -f /var/log/nginx/examination-system-access.log

# View Nginx error logs
tail -f /var/log/nginx/examination-system-error.log
```

## Step 8: Firewall Configuration

```bash
# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable
```

## Step 9: Backup Strategy

### Database Backup Script

Create backup script:

```bash
sudo nano /usr/local/bin/backup-examination-db.sh
```

Add:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/examination-system"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="examination_system"
DB_USER="exam_user"
DB_PASS="strong_password_here"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Keep only last 7 days of backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete
```

Make executable:

```bash
sudo chmod +x /usr/local/bin/backup-examination-db.sh
```

Add to crontab (daily at 2 AM):

```bash
sudo crontab -e
```

Add:

```cron
0 2 * * * /usr/local/bin/backup-examination-db.sh
```

## Step 10: Post-Deployment Verification

### Check Application Status

1. Visit `https://your-domain.com`
2. Verify login functionality
3. Test result display
4. Check chart rendering
5. Test PDF export
6. Verify responsive design on mobile

### Performance Testing

```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check Nginx status
sudo systemctl status nginx

# Check Redis status
sudo systemctl status redis-server

# Check queue workers
sudo supervisorctl status
```

### Security Checklist

- [ ] SSL certificate installed and working
- [ ] Firewall configured
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] Strong database passwords
- [ ] File permissions set correctly
- [ ] Security headers configured
- [ ] Regular backups scheduled

## Updating the Application

### Pull Latest Changes

```bash
cd /var/www/examination-system
git pull origin main
```

### Update Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build
```

### Run Migrations

```bash
php artisan migrate --force
```

### Clear and Rebuild Cache

```bash
php artisan down
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
php artisan up
```

### Restart Services

```bash
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
sudo supervisorctl restart examination-system-worker:*
```

## Troubleshooting

### Issue: 500 Internal Server Error

Check logs:
```bash
tail -f /var/www/examination-system/storage/logs/laravel.log
tail -f /var/log/nginx/examination-system-error.log
```

Common causes:
- Incorrect file permissions
- Missing `.env` file
- Database connection issues
- PHP extensions not installed

### Issue: Queue Jobs Not Processing

```bash
# Check supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart examination-system-worker:*

# Check worker logs
tail -f /var/www/examination-system/storage/logs/worker.log
```

### Issue: Redis Connection Failed

```bash
# Check Redis status
sudo systemctl status redis-server

# Test connection
redis-cli ping

# Restart Redis
sudo systemctl restart redis-server
```

## Support

For deployment issues:
- Check application logs: `/var/www/examination-system/storage/logs/`
- Check web server logs: `/var/log/nginx/`
- Review this guide carefully
- Contact system administrator

## Security Updates

Regularly update system packages:

```bash
sudo apt update
sudo apt upgrade -y
```

Monitor security advisories for:
- PHP
- Laravel
- MySQL/PostgreSQL
- Nginx
- Redis

## Performance Optimization

### Enable OPcache

Edit PHP configuration:

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Ensure OPcache is enabled:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

Restart PHP-FPM:

```bash
sudo systemctl restart php8.2-fpm
```

### Redis Optimization

Edit Redis configuration:

```bash
sudo nano /etc/redis/redis.conf
```

Optimize for Laravel:

```
maxmemory 256mb
maxmemory-policy allkeys-lru
```

Restart Redis:

```bash
sudo systemctl restart redis-server
```

---

**Deployment Complete!** Your Examination Management System should now be running in production.

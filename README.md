# Examination Management System

A comprehensive web-based application built with Laravel 10 that enables students to view their semester-wise examination results, track academic progress, and analyze performance trends.

## Features

- **Semester-wise Result Display**: View examination results organized by semester with detailed subject information
- **GPA Calculation**: Automatic calculation of SGPA (Semester GPA) and CGPA (Cumulative GPA)
- **Performance Analytics**: Visual charts showing grade distribution, SGPA trends, and semester comparisons
- **Progress Tracking**: Track academic progress with metrics like total credits, pass percentage, and performance categories
- **Result Export**: Export semester results to PDF and CSV formats
- **Real-time Search & Filtering**: Search subjects and filter results by pass/fail status
- **Responsive Design**: Optimized for mobile, tablet, and desktop devices
- **Accessibility**: WCAG compliant with ARIA labels and keyboard navigation support

## Technology Stack

- **Backend**: PHP 8.2+ with Laravel 10.x
- **Database**: MySQL 8.0 / PostgreSQL 14+
- **Cache & Queue**: Redis
- **Frontend**: Blade Templates, Alpine.js, Tailwind CSS
- **Charts**: Chart.js
- **PDF Generation**: DomPDF
- **Testing**: PHPUnit, Pest

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL 8.0+ or PostgreSQL 14+
- Redis Server
- Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd examination-system
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Edit `.env` and configure the following:

```env
# Application
APP_NAME="Examination Management System"
APP_URL=http://localhost:8000

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examination_system
DB_USERNAME=root
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database (Optional)

To populate the database with demo data:

```bash
php artisan db:seed --class=GradeScaleSeeder
php artisan db:seed --class=DemoDataSeeder
```

### 8. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 9. Start the Application

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### 10. Start Queue Worker (Optional)

If using background jobs:

```bash
php artisan queue:work
```

## Demo Credentials

After seeding the database, you can login with:

- **Email**: student@example.com
- **Password**: password

## Usage

### For Students

1. **Login**: Access the system using your student credentials
2. **Dashboard**: View your overall academic performance metrics (CGPA, total credits, pass percentage)
3. **Results**: Browse semester-wise results with detailed subject information
4. **Progress**: Track your academic progress with visual charts and trends
5. **Export**: Download semester results as PDF or CSV files

### Filtering and Search

- Use the search box to find specific subjects by name or code
- Filter results by pass/fail status
- Sort results by subject name, marks, or grade

### Performance Analytics

- View SGPA trends across semesters
- Analyze grade distribution
- Compare performance across semesters
- Identify top and bottom performing subjects

## Configuration

### Grading Scale

The system uses a 10-point GPA scale:

| Grade | Marks Range | Grade Point |
|-------|-------------|-------------|
| A+    | 90-100      | 10.0        |
| A     | 80-89       | 9.0         |
| B     | 70-79       | 8.0         |
| C     | 60-69       | 7.0         |
| D     | 50-59       | 6.0         |
| E     | 40-49       | 5.0         |
| F     | 0-39        | 0.0         |

### Performance Categories

Based on CGPA:

- **Distinction**: CGPA >= 7.5
- **First Class**: CGPA >= 6.0
- **Second Class**: CGPA >= 5.0
- **Pass**: CGPA >= 4.0

### Cache Configuration

Results are cached for 1 hour (3600 seconds) by default. To change this, update `CACHE_TTL` in `.env`:

```env
CACHE_TTL=3600
```

## Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## Deployment

### Production Optimization

Before deploying to production:

```bash
# Optimize configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build production assets
npm run build

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Environment Configuration

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### Web Server Configuration

#### Apache

Ensure `mod_rewrite` is enabled and point the document root to the `public` directory.

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/examination-system/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Queue Workers

Set up a supervisor configuration for queue workers:

```ini
[program:examination-system-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/examination-system/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/examination-system/storage/logs/worker.log
stopwaitsecs=3600
```

## Troubleshooting

### Common Issues

**Issue**: "Class 'Redis' not found"
- **Solution**: Install PHP Redis extension: `sudo apt-get install php-redis` or `pecl install redis`

**Issue**: Charts not displaying
- **Solution**: Ensure JavaScript is enabled and run `npm run build`

**Issue**: PDF export fails
- **Solution**: Check DomPDF configuration and ensure write permissions on storage directory

**Issue**: Cache not working
- **Solution**: Verify Redis is running: `redis-cli ping` should return `PONG`

### Clearing Cache

```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```

## Security

- All user inputs are validated and sanitized
- CSRF protection enabled on all forms
- SQL injection prevention through Eloquent ORM
- XSS prevention through Blade templating
- Password hashing using bcrypt
- Session security with HTTP-only cookies

## Performance

- Redis caching for frequently accessed data
- Eager loading to prevent N+1 queries
- Database indexing on frequently queried columns
- Asset minification and compression
- Browser caching headers

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.

## Support

For issues and questions:
- Create an issue in the repository
- Contact: support@example.com

## Changelog

### Version 1.0.0 (2024)
- Initial release
- Semester-wise result display
- GPA calculation (SGPA and CGPA)
- Performance analytics with charts
- PDF and CSV export
- Responsive design
- Accessibility features

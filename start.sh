#!/bin/bash

# Exit on error
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Seeding admin user..."
php artisan db:seed --class=AdminSeeder --force || echo "Admin seeder skipped (may already exist)"

echo "Starting PHP server..."
php -S 0.0.0.0:$PORT -t public

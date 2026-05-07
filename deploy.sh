#!/bin/bash

###############################################################################
# Deployment Script for Examination Management System
# This script automates the deployment process
###############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/examination-system"
PHP_VERSION="8.2"

# Functions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}→ $1${NC}"
}

check_requirements() {
    print_info "Checking requirements..."
    
    # Check PHP
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed"
        exit 1
    fi
    
    # Check Composer
    if ! command -v composer &> /dev/null; then
        print_error "Composer is not installed"
        exit 1
    fi
    
    # Check Node
    if ! command -v node &> /dev/null; then
        print_error "Node.js is not installed"
        exit 1
    fi
    
    # Check npm
    if ! command -v npm &> /dev/null; then
        print_error "npm is not installed"
        exit 1
    fi
    
    print_success "All requirements met"
}

enable_maintenance_mode() {
    print_info "Enabling maintenance mode..."
    php artisan down || true
    print_success "Maintenance mode enabled"
}

disable_maintenance_mode() {
    print_info "Disabling maintenance mode..."
    php artisan up
    print_success "Maintenance mode disabled"
}

pull_latest_code() {
    print_info "Pulling latest code from repository..."
    git pull origin main
    print_success "Code updated"
}

install_dependencies() {
    print_info "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
    print_success "PHP dependencies installed"
    
    print_info "Installing Node dependencies..."
    npm ci --production
    print_success "Node dependencies installed"
}

build_assets() {
    print_info "Building frontend assets..."
    npm run build
    print_success "Assets built"
}

run_migrations() {
    print_info "Running database migrations..."
    php artisan migrate --force
    print_success "Migrations completed"
}

clear_caches() {
    print_info "Clearing caches..."
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    print_success "Caches cleared"
}

optimize_application() {
    print_info "Optimizing application..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    print_success "Application optimized"
}

set_permissions() {
    print_info "Setting file permissions..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
    print_success "Permissions set"
}

restart_services() {
    print_info "Restarting services..."
    
    # Restart PHP-FPM
    sudo systemctl reload php${PHP_VERSION}-fpm
    print_success "PHP-FPM restarted"
    
    # Restart Nginx
    sudo systemctl reload nginx
    print_success "Nginx restarted"
    
    # Restart queue workers
    if command -v supervisorctl &> /dev/null; then
        sudo supervisorctl restart examination-system-worker:*
        print_success "Queue workers restarted"
    fi
}

verify_deployment() {
    print_info "Verifying deployment..."
    
    # Check if application is responding
    if curl -f -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200\|302"; then
        print_success "Application is responding"
    else
        print_error "Application is not responding correctly"
        return 1
    fi
    
    # Check queue workers
    if command -v supervisorctl &> /dev/null; then
        if sudo supervisorctl status examination-system-worker:* | grep -q "RUNNING"; then
            print_success "Queue workers are running"
        else
            print_error "Queue workers are not running"
            return 1
        fi
    fi
    
    print_success "Deployment verified"
}

create_backup() {
    print_info "Creating backup..."
    
    BACKUP_DIR="/var/backups/examination-system"
    DATE=$(date +%Y%m%d_%H%M%S)
    
    mkdir -p $BACKUP_DIR
    
    # Backup database
    if [ ! -z "$DB_DATABASE" ]; then
        mysqldump -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz
        print_success "Database backed up"
    fi
    
    # Backup .env file
    cp .env $BACKUP_DIR/env_backup_$DATE
    print_success "Environment file backed up"
}

rollback() {
    print_error "Deployment failed! Rolling back..."
    
    # Revert to previous commit
    git reset --hard HEAD~1
    
    # Reinstall dependencies
    composer install --no-dev --optimize-autoloader --no-interaction
    npm ci --production
    npm run build
    
    # Clear and rebuild caches
    php artisan config:clear
    php artisan cache:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Restart services
    restart_services
    
    print_error "Rolled back to previous version"
    exit 1
}

# Main deployment process
main() {
    echo "========================================="
    echo "Examination Management System Deployment"
    echo "========================================="
    echo ""
    
    # Change to application directory
    cd $APP_DIR || exit 1
    
    # Load environment variables
    if [ -f .env ]; then
        export $(cat .env | grep -v '^#' | xargs)
    fi
    
    # Check requirements
    check_requirements
    
    # Create backup
    create_backup
    
    # Enable maintenance mode
    enable_maintenance_mode
    
    # Deployment steps with error handling
    {
        pull_latest_code &&
        install_dependencies &&
        build_assets &&
        run_migrations &&
        clear_caches &&
        optimize_application &&
        set_permissions &&
        restart_services &&
        verify_deployment
    } || {
        rollback
    }
    
    # Disable maintenance mode
    disable_maintenance_mode
    
    echo ""
    echo "========================================="
    print_success "Deployment completed successfully!"
    echo "========================================="
    echo ""
    echo "Application URL: $APP_URL"
    echo "Deployment time: $(date)"
    echo ""
}

# Run main function
main

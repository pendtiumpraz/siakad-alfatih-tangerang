#!/bin/bash

##############################################################################
# SIAKAD Deployment Script
# Untuk manual deployment atau troubleshooting di server DomaiNesia
##############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_header() {
    echo -e "\n${BLUE}============================================${NC}"
    echo -e "${BLUE}  $1${NC}"
    echo -e "${BLUE}============================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if running from correct directory
if [ ! -f "artisan" ]; then
    print_error "Error: artisan file not found!"
    print_info "Please run this script from Laravel root directory"
    exit 1
fi

# Main deployment process
print_header "SIAKAD Deployment Script"

# Ask for confirmation
echo -e "${YELLOW}This will deploy the latest changes from Git.${NC}"
read -p "Continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_error "Deployment cancelled"
    exit 1
fi

# Enable maintenance mode
print_header "Step 1: Enable Maintenance Mode"
php artisan down --retry=60 || print_warning "Maintenance mode failed (might be already down)"
print_success "Maintenance mode enabled"

# Pull latest changes
print_header "Step 2: Pull Latest Changes"
git fetch origin
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
print_info "Current branch: $CURRENT_BRANCH"
git pull origin $CURRENT_BRANCH
print_success "Code updated"

# Install/Update Composer dependencies
print_header "Step 3: Install Composer Dependencies"
if command -v composer &> /dev/null; then
    composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader
    print_success "Composer dependencies installed"
else
    print_warning "Composer not found in PATH"
    if [ -f "$HOME/composer.phar" ]; then
        php $HOME/composer.phar install --no-interaction --no-dev --prefer-dist --optimize-autoloader
        print_success "Composer dependencies installed"
    else
        print_error "Composer not found! Please install composer first"
        php artisan up
        exit 1
    fi
fi

# Clear cache
print_header "Step 4: Clear Cache"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_success "Cache cleared"

# Run migrations
print_header "Step 5: Run Database Migrations"
read -p "Run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    print_success "Migrations completed"
else
    print_warning "Migrations skipped"
fi

# Install NPM dependencies and build (if Node.js available)
print_header "Step 6: Build Frontend Assets"
if command -v npm &> /dev/null; then
    read -p "Build frontend assets? This may take a while. (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        npm ci --production=false
        npm run build
        print_success "Frontend assets built"
    else
        print_warning "Frontend build skipped"
    fi
else
    print_warning "Node.js/NPM not found - skipping frontend build"
    print_info "Frontend assets should be built by CI/CD pipeline"
fi

# Optimize application
print_header "Step 7: Optimize Application"
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Application optimized"

# Set permissions
print_header "Step 8: Set Permissions"
chmod -R 775 storage bootstrap/cache
print_success "Permissions set"

# Restart queue workers (if using queues)
print_header "Step 9: Restart Queue Workers"
php artisan queue:restart || print_warning "Queue restart skipped"

# Disable maintenance mode
print_header "Step 10: Disable Maintenance Mode"
php artisan up
print_success "Application is now live!"

# Summary
print_header "Deployment Summary"
print_success "Deployment completed successfully!"
print_info "Branch deployed: $CURRENT_BRANCH"
print_info "Time: $(date)"

# Show application info
print_header "Application Info"
php artisan --version
echo ""
php artisan env
echo ""

print_header "Next Steps"
print_info "1. Test the application: $(php artisan route:list | grep '/' | head -1 | awk '{print $4}')"
print_info "2. Check logs: tail -f storage/logs/laravel.log"
print_info "3. Monitor server logs for any errors"

echo -e "\n${GREEN}🎉 Deployment completed! 🎉${NC}\n"

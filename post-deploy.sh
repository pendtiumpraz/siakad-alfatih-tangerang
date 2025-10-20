#!/bin/bash

##############################################################################
# SIAKAD Post-Deployment Tasks
# Script untuk maintenance dan troubleshooting
##############################################################################

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_header() {
    echo -e "\n${BLUE}=== $1 ===${NC}\n"
}

# Check if artisan exists
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: Run from Laravel root directory${NC}"
    exit 1
fi

# Menu
print_header "SIAKAD Post-Deployment Tools"
echo "1. Clear All Cache"
echo "2. Optimize Application"
echo "3. Run Migrations"
echo "4. Rollback Migration"
echo "5. Reset Database (DANGER!)"
echo "6. Fix Permissions"
echo "7. Restart Queue Workers"
echo "8. View Recent Logs"
echo "9. Check Application Status"
echo "10. Maintenance Mode ON"
echo "11. Maintenance Mode OFF"
echo "0. Exit"
echo ""
read -p "Select option: " option

case $option in
    1)
        print_header "Clearing All Cache"
        php artisan config:clear
        php artisan cache:clear
        php artisan route:clear
        php artisan view:clear
        echo -e "${GREEN}✅ All cache cleared${NC}"
        ;;

    2)
        print_header "Optimizing Application"
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan optimize
        echo -e "${GREEN}✅ Application optimized${NC}"
        ;;

    3)
        print_header "Running Migrations"
        php artisan migrate --force
        echo -e "${GREEN}✅ Migrations completed${NC}"
        ;;

    4)
        print_header "Rollback Migration"
        read -p "Are you sure? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan migrate:rollback
            echo -e "${GREEN}✅ Migration rolled back${NC}"
        fi
        ;;

    5)
        print_header "Reset Database (DANGER!)"
        echo -e "${YELLOW}⚠️  This will DELETE ALL DATA!${NC}"
        read -p "Are you ABSOLUTELY sure? Type 'yes' to confirm: " confirm
        if [ "$confirm" = "yes" ]; then
            php artisan migrate:fresh --seed
            echo -e "${GREEN}✅ Database reset completed${NC}"
        else
            echo "Cancelled"
        fi
        ;;

    6)
        print_header "Fixing Permissions"
        chmod -R 775 storage
        chmod -R 775 bootstrap/cache
        echo -e "${GREEN}✅ Permissions fixed${NC}"
        ;;

    7)
        print_header "Restarting Queue Workers"
        php artisan queue:restart
        echo -e "${GREEN}✅ Queue workers restarted${NC}"
        ;;

    8)
        print_header "Recent Logs (Last 50 lines)"
        tail -n 50 storage/logs/laravel.log
        ;;

    9)
        print_header "Application Status"
        echo "Laravel Version:"
        php artisan --version
        echo ""
        echo "Environment:"
        php artisan env
        echo ""
        echo "Database Connection:"
        php artisan db:show || echo "Cannot connect to database"
        ;;

    10)
        print_header "Enabling Maintenance Mode"
        read -p "Enter secret bypass key (optional): " secret
        if [ -n "$secret" ]; then
            php artisan down --secret="$secret"
            echo -e "${GREEN}✅ Maintenance mode enabled${NC}"
            echo -e "${BLUE}Bypass URL: https://your-domain.com/$secret${NC}"
        else
            php artisan down
            echo -e "${GREEN}✅ Maintenance mode enabled${NC}"
        fi
        ;;

    11)
        print_header "Disabling Maintenance Mode"
        php artisan up
        echo -e "${GREEN}✅ Application is now live${NC}"
        ;;

    0)
        echo "Goodbye!"
        exit 0
        ;;

    *)
        echo -e "${YELLOW}Invalid option${NC}"
        ;;
esac

echo ""

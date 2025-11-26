@echo off
echo ============================================
echo Installing Composer Dependencies
echo ============================================
echo.
echo This will take 5-10 minutes...
echo Please wait and DO NOT cancel!
echo.

cd /d D:\AI\siakad\siakad-app

echo Step 1: Installing dependencies...
composer install --no-interaction --no-scripts

echo.
echo Step 2: Generating autoload files...
composer dump-autoload -o

echo.
echo Step 3: Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ============================================
echo Installation Complete!
echo ============================================
echo.
echo Now you can run: php artisan serve
echo.
pause

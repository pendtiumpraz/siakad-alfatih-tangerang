@echo off
echo ========================================
echo RESTART APACHE - XAMPP
echo ========================================
echo.

echo Stopping Apache...
"D:\xampp\apache_stop.bat"
timeout /t 2 >nul

echo Starting Apache...
"D:\xampp\apache_start.bat"
timeout /t 2 >nul

echo.
echo ========================================
echo Apache restarted successfully!
echo ========================================
echo.
echo Checking GD extension...
php -m | findstr gd

echo.
pause

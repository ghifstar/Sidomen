@echo off
echo ================================================================
echo Memulai Laravel Development Server Donat Menak (PHP 8.3)...
echo ================================================================
cd /d "%~dp0"
c:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan serve --host 127.0.0.1 --port 8001
pause

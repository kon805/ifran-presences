@echo off
:start
php artisan queue:work --tries=3 --timeout=60 --sleep=5 --max-jobs=50 --max-time=3600
if %ERRORLEVEL% EQU 0 goto start
timeout /t 60
goto start

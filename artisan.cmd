@echo off
REM Always run Artisan with PHP 8.0 (Laravel 7 incompatible with PHP 8.2)
"C:\php80\php.exe" "%~dp0artisan" %*

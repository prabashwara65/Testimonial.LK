@echo off
REM Prefer PHP 8.0 for Composer on this project
set "PATH=C:\php80;%PATH%"
composer %*

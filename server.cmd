@echo off
REM Script para iniciar servidor PHP embutido

start c:\xampp\php\php -S 0.0.0.0:8000 -t public
timeout /t 2 >nul

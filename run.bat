@echo off

REM Obtener los parámetros pasados al archivo por lotes
set serverPort=%1

REM
start cmd /k "cd mandu-division-backend && php artisan serve --port=%serverPort%"

REM
start cmd /k "cd mandu-division-frontend && npm run dev"

REM
exit
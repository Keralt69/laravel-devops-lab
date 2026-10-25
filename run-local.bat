@echo off
setlocal
echo ==============================================================================
echo  Starting DevOps Lab Laravel Environment (Local PHP Web Server)
echo ==============================================================================

set "BASE_DIR=%~dp0"
set "PATH=%BASE_DIR%tools\php;%BASE_DIR%tools\git\cmd;%PATH%"

echo [1/3] Checking environment file...
if not exist "%BASE_DIR%.env" (
    copy "%BASE_DIR%.env.example" "%BASE_DIR%.env" >nul
)

echo [2/3] Checking SQLite database...
if not exist "%BASE_DIR%database\database.sqlite" (
    type nul > "%BASE_DIR%database\database.sqlite"
)

echo [3/3] Starting PHP Development Server on http://127.0.0.1:8000 ...
echo Press Ctrl+C to stop the server.
echo.
"%BASE_DIR%tools\php\php.exe" -S 127.0.0.1:8000 -t "%BASE_DIR%public"
pause

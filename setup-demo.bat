@echo off
REM OneHelp Video Demo Setup Script for Windows
REM This script prepares the application for video recording

echo ==================================================
echo OneHelp Video Demo Setup
echo ==================================================
echo.

REM Step 1: Check if we're in the right directory
echo [1/8] Checking directory...
if not exist "artisan" (
    echo Error: artisan file not found. Please run this script from the project root directory.
    pause
    exit /b 1
)
echo OK - In correct directory
echo.

REM Step 2: Install Composer dependencies
echo [2/8] Installing Composer dependencies...
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: Composer is not installed. Please install Composer first.
    pause
    exit /b 1
)
call composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorlevel% neq 0 (
    echo Error installing Composer dependencies
    pause
    exit /b 1
)
echo OK - Composer dependencies installed
echo.

REM Step 3: Install NPM dependencies
echo [3/8] Installing NPM dependencies...
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: NPM is not installed. Please install Node.js and NPM first.
    pause
    exit /b 1
)
call npm install
if %errorlevel% neq 0 (
    echo Error installing NPM dependencies
    pause
    exit /b 1
)
echo OK - NPM dependencies installed
echo.

REM Step 4: Setup environment file
echo [4/8] Setting up environment file...
if not exist ".env" (
    copy .env.example .env
    echo OK - .env file created
) else (
    echo OK - .env file already exists
)

REM Configure for SQLite
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=database/database.sqlite' | Set-Content .env"
echo OK - Configured for SQLite
echo.

REM Step 5: Generate application key
echo [5/8] Generating application key...
php artisan key:generate --force
if %errorlevel% neq 0 (
    echo Error generating application key
    pause
    exit /b 1
)
echo OK - Application key generated
echo.

REM Step 6: Setup database
echo [6/8] Setting up database...
if not exist "database\database.sqlite" (
    type nul > database\database.sqlite
    echo OK - SQLite database file created
) else (
    echo OK - SQLite database file already exists
)

echo     Running migrations...
php artisan migrate:fresh --force
if %errorlevel% neq 0 (
    echo Error running migrations
    pause
    exit /b 1
)
echo OK - Database migrated

echo     Seeding demo data...
php artisan db:seed --class=DemoDataSeeder --force
if %errorlevel% neq 0 (
    echo Error seeding demo data
    pause
    exit /b 1
)
echo OK - Demo data seeded
echo.

REM Step 7: Build frontend assets
echo [7/8] Building frontend assets...
call npm run build
if %errorlevel% neq 0 (
    echo Error building frontend assets
    pause
    exit /b 1
)
echo OK - Frontend assets built
echo.

REM Step 8: Clear caches
echo [8/8] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo OK - Caches cleared
echo.

echo ==================================================
echo Setup Complete!
echo ==================================================
echo.
echo Demo Credentials:
echo ----------------------------------------
echo Admin:
echo   Email: admin@onehelp.com
echo   Password: password123
echo.
echo Volunteer:
echo   Email: john.volunteer@example.com
echo   Password: password123
echo.
echo Organization:
echo   Email: contact@helpinghands.org
echo   Password: password123
echo ----------------------------------------
echo.
echo To start the development server, run:
echo php artisan serve
echo.
echo Then open your browser to:
echo http://localhost:8000
echo.
echo For the video presentation guides, see:
echo   - VIDEO_PRESENTATION_GUIDE.md (comprehensive guide)
echo   - VIDEO_QUICK_REFERENCE.md (quick reference)
echo   - APP_FLOW_DIAGRAMS.md (visual diagrams)
echo.
echo Happy recording!
echo.
pause

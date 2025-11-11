#!/bin/bash

# OneHelp Video Demo Setup Script
# This script prepares the application for video recording

set -e  # Exit on error

echo "=================================================="
echo "OneHelp Video Demo Setup"
echo "=================================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Check if we're in the right directory
echo -e "${BLUE}[1/8] Checking directory...${NC}"
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from the project root directory.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ In correct directory${NC}"
echo ""

# Step 2: Install Composer dependencies
echo -e "${BLUE}[2/8] Installing Composer dependencies...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer is not installed. Please install Composer first.${NC}"
    exit 1
fi
composer install --no-interaction --prefer-dist --optimize-autoloader
echo -e "${GREEN}✓ Composer dependencies installed${NC}"
echo ""

# Step 3: Install NPM dependencies
echo -e "${BLUE}[3/8] Installing NPM dependencies...${NC}"
if ! command -v npm &> /dev/null; then
    echo -e "${RED}Error: NPM is not installed. Please install Node.js and NPM first.${NC}"
    exit 1
fi
npm install
echo -e "${GREEN}✓ NPM dependencies installed${NC}"
echo ""

# Step 4: Setup environment file
echo -e "${BLUE}[4/8] Setting up environment file...${NC}"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${GREEN}✓ .env file already exists${NC}"
fi

# Configure for SQLite
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/' .env
echo -e "${GREEN}✓ Configured for SQLite${NC}"
echo ""

# Step 5: Generate application key
echo -e "${BLUE}[5/8] Generating application key...${NC}"
php artisan key:generate --force
echo -e "${GREEN}✓ Application key generated${NC}"
echo ""

# Step 6: Setup database
echo -e "${BLUE}[6/8] Setting up database...${NC}"
# Create SQLite database file if it doesn't exist
if [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    echo -e "${GREEN}✓ SQLite database file created${NC}"
else
    echo -e "${GREEN}✓ SQLite database file already exists${NC}"
fi

# Run migrations
echo -e "${BLUE}    Running migrations...${NC}"
php artisan migrate:fresh --force
echo -e "${GREEN}✓ Database migrated${NC}"

# Seed demo data
echo -e "${BLUE}    Seeding demo data...${NC}"
php artisan db:seed --class=DemoDataSeeder --force
echo -e "${GREEN}✓ Demo data seeded${NC}"
echo ""

# Step 7: Build frontend assets
echo -e "${BLUE}[7/8] Building frontend assets...${NC}"
npm run build
echo -e "${GREEN}✓ Frontend assets built${NC}"
echo ""

# Step 8: Clear caches
echo -e "${BLUE}[8/8] Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

echo "=================================================="
echo -e "${GREEN}Setup Complete!${NC}"
echo "=================================================="
echo ""
echo "Demo Credentials:"
echo "----------------------------------------"
echo "Admin:"
echo "  Email: admin@onehelp.com"
echo "  Password: password123"
echo ""
echo "Volunteer:"
echo "  Email: john.volunteer@example.com"
echo "  Password: password123"
echo ""
echo "Organization:"
echo "  Email: contact@helpinghands.org"
echo "  Password: password123"
echo "----------------------------------------"
echo ""
echo "To start the development server, run:"
echo -e "${BLUE}php artisan serve${NC}"
echo ""
echo "Then open your browser to:"
echo -e "${BLUE}http://localhost:8000${NC}"
echo ""
echo "For the video presentation guides, see:"
echo "  - VIDEO_PRESENTATION_GUIDE.md (comprehensive guide)"
echo "  - VIDEO_QUICK_REFERENCE.md (quick reference)"
echo "  - APP_FLOW_DIAGRAMS.md (visual diagrams)"
echo ""
echo -e "${GREEN}Happy recording! 🎬${NC}"
echo ""

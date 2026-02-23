#!/bin/bash

# Tokesi Laravel Application - Local Setup Script
# This script helps automate the local development setup

set -e

echo "=========================================="
echo "Tokesi Laravel Application Setup"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    echo -e "${RED}Error: Please run this script from the Tokesi directory${NC}"
    exit 1
fi

echo "Step 1: Checking system requirements..."
echo "----------------------------------------"

# Check PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1)
    echo -e "${GREEN}✓${NC} PHP: $PHP_VERSION"
else
    echo -e "${RED}✗${NC} PHP not found. Please install PHP 8.2 or higher."
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version | head -n 1)
    echo -e "${GREEN}✓${NC} Composer: $COMPOSER_VERSION"
else
    echo -e "${RED}✗${NC} Composer not found. Please install Composer."
    exit 1
fi

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✓${NC} Node.js: $NODE_VERSION"
else
    echo -e "${RED}✗${NC} Node.js not found. Please install Node.js."
    exit 1
fi

# Check npm
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    echo -e "${GREEN}✓${NC} npm: $NPM_VERSION"
else
    echo -e "${RED}✗${NC} npm not found. Please install npm."
    exit 1
fi

# Check MySQL
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version)
    echo -e "${GREEN}✓${NC} MySQL: $MYSQL_VERSION"
else
    echo -e "${YELLOW}⚠${NC} MySQL not found."
    echo "  Install with: brew install mysql"
    echo "  Then run: brew services start mysql"
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo ""
echo "Step 2: Environment configuration..."
echo "----------------------------------------"

# Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${YELLOW}⚠${NC} .env file not found."
    if [ -f ".env.local.example" ]; then
        read -p "Copy from .env.local.example? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            cp .env.local.example .env
            echo -e "${GREEN}✓${NC} Created .env from .env.local.example"
        fi
    elif [ -f ".env.example" ]; then
        read -p "Copy from .env.example? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            cp .env.example .env
            echo -e "${GREEN}✓${NC} Created .env from .env.example"
        fi
    fi
else
    echo -e "${GREEN}✓${NC} .env file exists"
fi

echo ""
echo "Step 3: Installing PHP dependencies..."
echo "----------------------------------------"
if [ -d "vendor" ]; then
    echo -e "${GREEN}✓${NC} Vendor directory exists (skipping composer install)"
else
    composer install
fi

echo ""
echo "Step 4: Installing Node.js dependencies..."
echo "----------------------------------------"
if [ -d "node_modules" ]; then
    echo -e "${GREEN}✓${NC} node_modules directory exists (skipping npm install)"
else
    npm install
fi

echo ""
echo "Step 5: Application setup..."
echo "----------------------------------------"

# Generate application key if needed
if grep -q "APP_KEY=base64:" .env; then
    echo -e "${GREEN}✓${NC} Application key already set"
else
    php artisan key:generate
    echo -e "${GREEN}✓${NC} Generated application key"
fi

# Clear caches
echo "Clearing caches..."
php artisan cache:clear > /dev/null 2>&1
php artisan config:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
echo -e "${GREEN}✓${NC} Caches cleared"

echo ""
echo "Step 6: Database setup..."
echo "----------------------------------------"

# Check database connection
if php artisan db:show > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} Database connection successful"
    
    # Check if tables exist
    TABLE_COUNT=$(php artisan tinker --execute="echo DB::select('SHOW TABLES') ? count(DB::select('SHOW TABLES')) : 0;" 2>/dev/null || echo "0")
    
    if [ "$TABLE_COUNT" -gt "0" ]; then
        echo -e "${GREEN}✓${NC} Database has $TABLE_COUNT tables"
    else
        echo -e "${YELLOW}⚠${NC} Database is empty"
        read -p "Run migrations? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan migrate --force
            echo -e "${GREEN}✓${NC} Migrations completed"
        fi
    fi
else
    echo -e "${YELLOW}⚠${NC} Cannot connect to database"
    echo "  Please check your .env database configuration"
    echo "  DB_DATABASE, DB_USERNAME, DB_PASSWORD"
fi

echo ""
echo "Step 7: Storage setup..."
echo "----------------------------------------"

# Create storage link
if [ -L "public/storage" ]; then
    echo -e "${GREEN}✓${NC} Storage link already exists"
else
    php artisan storage:link
    echo -e "${GREEN}✓${NC} Created storage link"
fi

# Set permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
echo -e "${GREEN}✓${NC} Set storage permissions"

echo ""
echo "Step 8: Building frontend assets..."
echo "----------------------------------------"

read -p "Build assets now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    npm run build
    echo -e "${GREEN}✓${NC} Assets built successfully"
else
    echo -e "${YELLOW}⚠${NC} Skipped asset build"
    echo "  Run 'npm run build' or 'npm run dev' later"
fi

echo ""
echo "=========================================="
echo -e "${GREEN}Setup Complete!${NC}"
echo "=========================================="
echo ""
echo "Next steps:"
echo ""
echo "1. Update .env with your local database credentials"
echo "2. Import production database:"
echo "   mysql -u tokesi_user -p tokesi_local < ../tokesi_production.sql"
echo ""
echo "3. Copy production storage files to:"
echo "   storage/app/public/"
echo ""
echo "4. Start development server:"
echo "   php artisan serve"
echo "   OR"
echo "   composer dev (runs server + queue + logs + vite)"
echo ""
echo "5. Access application:"
echo "   Frontend: http://localhost:8000"
echo "   Admin: http://localhost:8000/admin"
echo ""
echo "For detailed instructions, see SETUP_GUIDE.md"
echo ""

#!/bin/bash

# NewsCMS Automated Setup Script
# This script helps you set up your NewsCMS installation

set -e

# Colors for terminal output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Progress indicators
SUCCESS="✓"
ERROR="✗"
INFO="ℹ"
ARROW="→"

echo -e "${BOLD}${CYAN}"
echo "╔════════════════════════════════════════════════════════════╗"
echo "║           NewsCMS - Automated Setup Script               ║"
echo "║         WordPress-like Publishing Platform                ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Check if .env.local exists
echo -e "${BOLD}Step 1: Checking Environment Configuration${NC}"
echo "─────────────────────────────────────────────────"

if [ -f ".env.local" ]; then
    echo -e "${GREEN}${SUCCESS}${NC} .env.local file found"
    
    # Check if it's properly configured
    if grep -q "your-project-id.supabase.co" .env.local 2>/dev/null || \
       grep -q "your-anon-key-here" .env.local 2>/dev/null; then
        echo -e "${YELLOW}${INFO}${NC} .env.local exists but contains placeholder values"
        CONFIGURED=false
    else
        echo -e "${GREEN}${SUCCESS}${NC} .env.local appears to be configured"
        CONFIGURED=true
    fi
else
    echo -e "${YELLOW}${INFO}${NC} .env.local not found"
    CONFIGURED=false
fi

if [ "$CONFIGURED" = false ]; then
    echo ""
    echo -e "${BOLD}Would you like to configure Supabase now? (y/n)${NC}"
    read -r response
    
    if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
        echo ""
        echo -e "${CYAN}${ARROW}${NC} Please enter your Supabase credentials:"
        echo "  (You can find these in your Supabase project settings → API)"
        echo ""
        
        read -p "Supabase Project URL: " SUPABASE_URL
        read -p "Supabase Anon Key: " SUPABASE_KEY
        
        # Create .env.local
        cat > .env.local << EOF
# Supabase Configuration
NEXT_PUBLIC_SUPABASE_URL=$SUPABASE_URL
NEXT_PUBLIC_SUPABASE_ANON_KEY=$SUPABASE_KEY

# Optional: For development
NODE_ENV=development
EOF
        
        echo -e "${GREEN}${SUCCESS}${NC} .env.local created successfully"
        CONFIGURED=true
    else
        echo -e "${YELLOW}${INFO}${NC} Skipping configuration. You can manually edit .env.local later"
    fi
fi

echo ""

# Check dependencies
echo -e "${BOLD}Step 2: Checking Dependencies${NC}"
echo "─────────────────────────────────────────────────"

if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo -e "${GREEN}${SUCCESS}${NC} Node.js is installed: $NODE_VERSION"
else
    echo -e "${RED}${ERROR}${NC} Node.js is not installed"
    echo -e "${YELLOW}${ARROW}${NC} Please install Node.js from https://nodejs.org/"
    exit 1
fi

if command -v pnpm &> /dev/null; then
    PNPM_VERSION=$(pnpm --version)
    echo -e "${GREEN}${SUCCESS}${NC} pnpm is installed: $PNPM_VERSION"
else
    echo -e "${YELLOW}${INFO}${NC} pnpm is not installed"
    echo -e "${CYAN}${ARROW}${NC} Installing pnpm..."
    npm install -g pnpm
fi

echo ""

# Install dependencies
echo -e "${BOLD}Step 3: Installing Project Dependencies${NC}"
echo "─────────────────────────────────────────────────"

if [ -d "node_modules" ]; then
    echo -e "${GREEN}${SUCCESS}${NC} node_modules directory exists"
    echo -e "${YELLOW}${INFO}${NC} Would you like to reinstall dependencies? (y/n)"
    read -r response
    
    if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
        echo -e "${CYAN}${ARROW}${NC} Removing old dependencies..."
        rm -rf node_modules
        echo -e "${CYAN}${ARROW}${NC} Installing dependencies..."
        pnpm install
    else
        echo -e "${GREEN}${SUCCESS}${NC} Using existing dependencies"
    fi
else
    echo -e "${CYAN}${ARROW}${NC} Installing dependencies..."
    pnpm install
fi

echo ""

# Database setup instructions
echo -e "${BOLD}Step 4: Database Setup${NC}"
echo "─────────────────────────────────────────────────"

echo -e "${CYAN}${ARROW}${NC} You need to run SQL scripts in your Supabase project"
echo ""
echo "  1. Go to your Supabase project dashboard"
echo "  2. Click 'SQL Editor' in the left menu"
echo "  3. Create a new query and paste the contents of:"
echo ""
echo -e "     ${BOLD}scripts/01-init-database.sql${NC}"
echo ""
echo "  4. Click 'Run' to create tables and default admin user"
echo "  5. Then create another query with:"
echo ""
echo -e "     ${BOLD}scripts/02-rls-policies.sql${NC}"
echo ""
echo "  6. Click 'Run' to set up Row Level Security policies"
echo ""

if [ -f "scripts/01-init-database.sql" ] && [ -f "scripts/02-rls-policies.sql" ]; then
    echo -e "${GREEN}${SUCCESS}${NC} SQL scripts are available in the scripts/ directory"
else
    echo -e "${RED}${ERROR}${NC} SQL scripts not found in scripts/ directory"
fi

echo ""
echo -e "${YELLOW}${INFO}${NC} Have you completed the database setup? (y/n)"
read -r db_response

if [[ ! "$db_response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
    echo -e "${YELLOW}${INFO}${NC} Please complete the database setup and run this script again"
fi

echo ""

# Summary
echo -e "${BOLD}${GREEN}═══════════════════════════════════════════════════════════${NC}"
echo -e "${BOLD}${GREEN}                    Setup Complete!                         ${NC}"
echo -e "${BOLD}${GREEN}═══════════════════════════════════════════════════════════${NC}"
echo ""
echo -e "${BOLD}Next Steps:${NC}"
echo ""
echo -e "${CYAN}1.${NC} Start the development server:"
echo -e "   ${BOLD}pnpm dev${NC}"
echo ""
echo -e "${CYAN}2.${NC} Visit http://localhost:3000/setup to verify configuration"
echo ""
echo -e "${CYAN}3.${NC} Login with default credentials:"
echo -e "   Email:    ${BOLD}admin@example.com${NC}"
echo -e "   Password: ${BOLD}admin123${NC}"
echo ""
echo -e "${CYAN}4.${NC} Access the admin dashboard at:"
echo -e "   ${BOLD}http://localhost:3000/admin${NC}"
echo ""
echo -e "${YELLOW}${INFO}${NC} Important: Change the default admin password after first login!"
echo ""
echo -e "${BOLD}Testing:${NC}"
echo -e "Run ${BOLD}node scripts/test-all-features.js${NC} to test all features"
echo ""
echo -e "${BOLD}Documentation:${NC}"
echo -e "• QUICKSTART.md    - Quick start guide"
echo -e "• SETUP_GUIDE.md   - Detailed setup instructions"
echo -e "• PROJECT_SUMMARY.md - Project overview"
echo ""
echo -e "${GREEN}Happy publishing! 📰${NC}"
echo ""

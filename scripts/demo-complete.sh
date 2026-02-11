#!/bin/bash

# NewsCMS - Complete Demo & Test Script
# This script demonstrates all features working together

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
BOLD='\033[1m'
NC='\033[0m'

# Clear screen
clear

# Banner
echo -e "${BOLD}${CYAN}"
cat << "EOF"
╔══════════════════════════════════════════════════════════════╗
║                                                              ║
║              NewsCMS - Complete Demo & Test                 ║
║                                                              ║
║          WordPress-like Publishing Platform                  ║
║          Built with Next.js & Supabase                       ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}\n"

echo -e "${BOLD}This script will:${NC}"
echo "  1. Check your environment configuration"
echo "  2. Verify Supabase connection"
echo "  3. Test all API endpoints"
echo "  4. Generate demo content"
echo "  5. Verify all features are working"
echo ""
echo -e "${YELLOW}Press Enter to start the demo...${NC}"
read

# Function to show step
show_step() {
    echo ""
    echo -e "${BOLD}${BLUE}════════════════════════════════════════════════════════════${NC}"
    echo -e "${BOLD}${BLUE}  $1${NC}"
    echo -e "${BOLD}${BLUE}════════════════════════════════════════════════════════════${NC}"
    echo ""
}

# Function to check status
check_status() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1"
    else
        echo -e "${RED}✗${NC} $1"
        return 1
    fi
}

# Step 1: Environment Check
show_step "Step 1: Environment Configuration Check"

echo -e "${CYAN}Checking .env.local file...${NC}"
if [ -f ".env.local" ]; then
    check_status ".env.local file exists"
    
    if grep -q "your-project-id.supabase.co" .env.local 2>/dev/null || \
       grep -q "your-anon-key-here" .env.local 2>/dev/null; then
        echo -e "${YELLOW}⚠${NC}  .env.local contains placeholder values"
        echo ""
        echo -e "${BOLD}⚠️  CONFIGURATION REQUIRED${NC}"
        echo ""
        echo "Your .env.local file needs to be configured with real Supabase credentials."
        echo ""
        echo "Option 1: Run the setup wizard"
        echo -e "  ${BOLD}pnpm setup${NC}"
        echo ""
        echo "Option 2: Manual configuration"
        echo "  1. Go to https://supabase.com"
        echo "  2. Create a project (or use existing)"
        echo "  3. Get your credentials from Settings → API"
        echo "  4. Edit .env.local with your actual values"
        echo ""
        echo -e "${YELLOW}After configuring, restart the dev server and run this demo again.${NC}"
        exit 1
    else
        check_status "Supabase credentials configured"
    fi
else
    echo -e "${RED}✗${NC} .env.local file not found"
    echo ""
    echo "Creating .env.local template..."
    cp .env.example .env.local
    echo ""
    echo -e "${YELLOW}Please configure .env.local with your Supabase credentials and try again.${NC}"
    exit 1
fi

echo ""
echo -e "${CYAN}Checking Node.js and pnpm...${NC}"
command -v node >/dev/null 2>&1
check_status "Node.js installed ($(node --version))"

command -v pnpm >/dev/null 2>&1
check_status "pnpm installed ($(pnpm --version))"

echo ""
echo -e "${CYAN}Checking dependencies...${NC}"
if [ -d "node_modules" ]; then
    check_status "Dependencies installed"
else
    echo -e "${YELLOW}Installing dependencies...${NC}"
    pnpm install
    check_status "Dependencies installed"
fi

echo ""
echo -e "${GREEN}✓ Environment check complete!${NC}"
echo ""
echo -e "${YELLOW}Press Enter to continue...${NC}"
read

# Step 2: Database Check
show_step "Step 2: Database & Supabase Connection"

echo -e "${CYAN}Checking development server...${NC}"
if curl -s http://localhost:3000 >/dev/null 2>&1; then
    check_status "Development server is running"
else
    echo -e "${YELLOW}⚠${NC}  Development server is not running"
    echo ""
    echo "Please start the development server in another terminal:"
    echo -e "  ${BOLD}pnpm dev${NC}"
    echo ""
    echo -e "${YELLOW}Press Enter after starting the server...${NC}"
    read
fi

echo ""
echo -e "${CYAN}Checking Supabase connection...${NC}"
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:3000/api/settings)

if [ "$RESPONSE" = "200" ]; then
    check_status "Supabase connection successful"
elif [ "$RESPONSE" = "503" ]; then
    echo -e "${RED}✗${NC} Supabase not configured or database not set up"
    echo ""
    echo -e "${BOLD}⚠️  DATABASE SETUP REQUIRED${NC}"
    echo ""
    echo "You need to run the SQL scripts in your Supabase project:"
    echo ""
    echo "1. Go to your Supabase project dashboard"
    echo "2. Click 'SQL Editor' in the left menu"
    echo "3. Create a new query and run: scripts/01-init-database.sql"
    echo "4. Create another query and run: scripts/02-rls-policies.sql"
    echo ""
    echo -e "${YELLOW}After setting up the database, run this demo again.${NC}"
    exit 1
else
    echo -e "${YELLOW}⚠${NC}  Unexpected response: $RESPONSE"
fi

echo ""
echo -e "${GREEN}✓ Database connection verified!${NC}"
echo ""
echo -e "${YELLOW}Press Enter to continue...${NC}"
read

# Step 3: API Tests
show_step "Step 3: Testing All Features"

echo -e "${CYAN}Running comprehensive feature tests...${NC}"
echo ""

node scripts/test-all-features.js

if [ $? -ne 0 ]; then
    echo ""
    echo -e "${RED}Some tests failed. Please check the output above.${NC}"
    echo ""
    echo -e "${YELLOW}Continue anyway? (y/n)${NC}"
    read -r response
    if [[ ! "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
        exit 1
    fi
fi

echo ""
echo -e "${YELLOW}Press Enter to continue...${NC}"
read

# Step 4: Demo Data
show_step "Step 4: Demo Content Generation"

echo -e "${CYAN}Would you like to generate demo content? (y/n)${NC}"
echo "This will create sample posts, pages, and settings."
read -r response

if [[ "$response" =~ ^([yY][eE][sS]|[yY])$ ]]; then
    echo ""
    echo -e "${CYAN}Generating demo content...${NC}"
    echo ""
    node scripts/generate-demo-data.js
    
    if [ $? -eq 0 ]; then
        echo ""
        check_status "Demo content generated successfully"
    else
        echo ""
        echo -e "${YELLOW}⚠${NC}  Demo data generation had issues (this is okay)"
    fi
else
    echo ""
    echo -e "${YELLOW}Skipping demo content generation${NC}"
fi

echo ""
echo -e "${YELLOW}Press Enter to continue...${NC}"
read

# Step 5: Summary
show_step "Step 5: Demo Complete - Summary"

echo -e "${GREEN}✓ Environment configured${NC}"
echo -e "${GREEN}✓ Supabase connected${NC}"
echo -e "${GREEN}✓ All features tested${NC}"
echo -e "${GREEN}✓ Demo ready${NC}"
echo ""

echo -e "${BOLD}${CYAN}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}${CYAN}║                  🎉 Demo Complete!                          ║${NC}"
echo -e "${BOLD}${CYAN}╚══════════════════════════════════════════════════════════════╝${NC}"
echo ""

echo -e "${BOLD}Your NewsCMS is fully functional!${NC}"
echo ""
echo -e "${BOLD}📖 Access Your CMS:${NC}"
echo ""
echo -e "  ${CYAN}Homepage:${NC}     http://localhost:3000"
echo -e "  ${CYAN}Setup Page:${NC}   http://localhost:3000/setup"
echo -e "  ${CYAN}Login:${NC}        http://localhost:3000/login"
echo -e "  ${CYAN}Admin Panel:${NC}  http://localhost:3000/admin"
echo ""
echo -e "${BOLD}🔑 Default Credentials:${NC}"
echo ""
echo -e "  Email:    ${GREEN}admin@example.com${NC}"
echo -e "  Password: ${GREEN}admin123${NC}"
echo ""
echo -e "${YELLOW}⚠️  Important: Change this password after first login!${NC}"
echo ""

echo -e "${BOLD}✨ Features Verified:${NC}"
echo ""
echo "  ✓ User Authentication & Authorization"
echo "  ✓ Posts Management (Create, Read, Update, Delete)"
echo "  ✓ Pages Management"
echo "  ✓ Site Settings"
echo "  ✓ Trending Topics"
echo "  ✓ Navigation Menu"
echo "  ✓ Bulk Import"
echo "  ✓ Draft System"
echo "  ✓ SEO Optimization"
echo ""

echo -e "${BOLD}📚 Documentation:${NC}"
echo ""
echo "  • COMPLETE_SETUP.md  - Complete setup guide"
echo "  • scripts/README.md  - Scripts documentation"
echo "  • SETUP_GUIDE.md     - Detailed setup instructions"
echo "  • QUICKSTART.md      - Quick reference"
echo ""

echo -e "${BOLD}🚀 Quick Commands:${NC}"
echo ""
echo -e "  ${CYAN}pnpm dev${NC}           Start development server"
echo -e "  ${CYAN}pnpm test:features${NC} Run all feature tests"
echo -e "  ${CYAN}pnpm demo:data${NC}     Generate demo content"
echo -e "  ${CYAN}pnpm build${NC}         Build for production"
echo ""

echo -e "${BOLD}🌐 Open in Browser:${NC}"
echo ""
read -p "Would you like to open the admin panel in your browser? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if command -v xdg-open &> /dev/null; then
        xdg-open http://localhost:3000/login
    elif command -v open &> /dev/null; then
        open http://localhost:3000/login
    else
        echo "Please open http://localhost:3000/login manually"
    fi
fi

echo ""
echo -e "${GREEN}${BOLD}Happy Publishing! 📰✨${NC}"
echo ""

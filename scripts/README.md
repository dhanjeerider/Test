# NewsCMS Scripts Documentation

This directory contains utility scripts for setting up, testing, and managing your NewsCMS installation.

## Available Scripts

### 1. Setup Script (`setup.sh`)

**Purpose**: Automated setup and configuration helper

**Usage**:
```bash
bash scripts/setup.sh
# or
pnpm setup
```

**What it does**:
- Checks for `.env.local` configuration
- Offers to configure Supabase credentials interactively
- Verifies Node.js and pnpm installation
- Installs project dependencies
- Provides step-by-step database setup instructions
- Shows next steps for getting started

**When to use**: 
- First time setting up the project
- After cloning the repository
- When helping others set up the project

---

### 2. Database Initialization (`01-init-database.sql`)

**Purpose**: Creates all database tables and default data

**Usage**:
1. Open your Supabase project dashboard
2. Go to SQL Editor
3. Create a new query
4. Copy and paste the entire contents of this file
5. Click "Run"

**What it creates**:
- `users` table (with default admin user)
- `posts` table
- `pages` table
- `settings` table (with default settings)
- `menu_items` table
- `import_queue` table
- `trending_topics` table
- `meta_tags` table
- Database indexes for performance
- Default admin user: `admin@example.com` / `admin123`

**Important**: Run this before the RLS policies script

---

### 3. Row Level Security Policies (`02-rls-policies.sql`)

**Purpose**: Sets up security policies for database tables

**Usage**:
1. Open your Supabase project dashboard
2. Go to SQL Editor
3. Create a new query
4. Copy and paste the entire contents of this file
5. Click "Run"

**What it sets up**:
- Enables RLS on all tables
- Creates policies for public access to published content
- Restricts admin operations to admin users
- Ensures users can only modify their own content

**Important**: Run this AFTER the database initialization script

---

### 4. Feature Test Script (`test-all-features.js`)

**Purpose**: Comprehensive testing of all API endpoints and features

**Usage**:
```bash
node scripts/test-all-features.js
# or
pnpm test:features
```

**What it tests**:
- ✓ Environment configuration (Supabase URL and keys)
- ✓ Authentication (login with valid/invalid credentials)
- ✓ Posts API (create, read, update, delete)
- ✓ Pages API (CRUD operations)
- ✓ Settings API (read site settings)
- ✓ Trending API (fetch trending topics)
- ✓ Menu API (fetch menu items)
- ✓ Bulk Import API (import multiple posts)

**Output**: Color-coded test results with success/failure counts

**When to use**:
- After initial setup to verify everything works
- After making changes to API endpoints
- Before deploying to production
- For debugging configuration issues

---

### 5. Demo Data Generator (`generate-demo-data.js`)

**Purpose**: Populates database with sample content for testing and demonstration

**Usage**:
```bash
node scripts/generate-demo-data.js
# or
pnpm demo:data
```

**What it creates**:
- **8 sample posts** including:
  - Published articles on various topics (Technology, Environment, Business, etc.)
  - Draft posts
  - Trending posts
  - Posts with different categories and tags
- **3 sample pages**:
  - About Us
  - Privacy Policy
  - Contact
- **Updated settings** with demo site information

**When to use**:
- After setting up the database (for demo purposes)
- When you need sample content for testing
- For showcasing the CMS features
- During development to have realistic data

**Note**: Requires successful login (database must be set up first)

---

## Setup Workflow

Follow these steps for a complete setup:

### 1. Configure Environment
```bash
# Copy example file
cp .env.example .env.local

# Edit .env.local with your Supabase credentials
# or run the setup script which will prompt you:
pnpm setup
```

### 2. Set Up Database

In your Supabase project:
1. Run `scripts/01-init-database.sql`
2. Run `scripts/02-rls-policies.sql`

### 3. Install Dependencies
```bash
pnpm install
```

### 4. Start Development Server
```bash
pnpm dev
```

### 5. Verify Setup
```bash
# Run tests
pnpm test:features

# Visit setup page
open http://localhost:3000/setup
```

### 6. Add Demo Data (Optional)
```bash
pnpm demo:data
```

### 7. Access Admin Panel
```bash
# Login at
open http://localhost:3000/login

# Credentials
Email: admin@example.com
Password: admin123
```

---

## Environment Variables

Required variables in `.env.local`:

```bash
NEXT_PUBLIC_SUPABASE_URL=https://your-project-id.supabase.co
NEXT_PUBLIC_SUPABASE_ANON_KEY=your-anon-key-here
```

Get these from: Supabase Dashboard → Settings → API

---

## Troubleshooting

### "Supabase not configured" error
- Check that `.env.local` exists and has correct values
- Restart the development server after adding environment variables
- Verify Supabase project is active and credentials are correct

### Database connection errors
- Ensure both SQL scripts have been run in Supabase
- Check that RLS policies are enabled
- Verify your Supabase project URL and key are correct

### Login fails with correct credentials
- Confirm `01-init-database.sql` script created the admin user
- Check browser console for error messages
- Verify database tables exist in Supabase

### Tests fail
- Make sure development server is running (`pnpm dev`)
- Check that database is properly set up
- Verify you're logged in (some tests require authentication)

---

## Script Exit Codes

All scripts follow standard exit code conventions:
- `0` - Success
- `1` - Error occurred

This makes them useful in CI/CD pipelines and automated workflows.

---

## Contributing

When adding new scripts:
1. Make shell scripts executable: `chmod +x scripts/your-script.sh`
2. Add comprehensive comments
3. Include error handling
4. Update this README
5. Add npm script shortcut in `package.json` if appropriate

---

## Security Notes

- Never commit `.env.local` to version control
- Change default admin password immediately after setup
- Keep Supabase credentials secure
- Regularly update dependencies for security patches

---

## Support

For issues or questions:
- Check SETUP_GUIDE.md for detailed setup instructions
- Check QUICKSTART.md for quick start guide
- Check PROJECT_SUMMARY.md for project overview
- Review error messages in browser console and terminal

---

**Last Updated**: February 2026

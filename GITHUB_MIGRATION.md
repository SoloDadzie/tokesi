# GitHub Migration Guide

## Pre-Migration Checklist

### 1. Review .gitignore

The project already has a `.gitignore` file. Verify it excludes:

```
/vendor/
/node_modules/
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
```

### 2. Remove Sensitive Data from Repository

**Files to NEVER commit:**
- `.env` (already in .gitignore)
- Database exports (*.sql files)
- Production credentials
- API keys and secrets
- Email passwords

**Files already committed that contain sensitive data:**
- `/.env` - Contains production database credentials, Stripe LIVE keys, email passwords

### 3. Clean Up Before Migration

```bash
cd /Users/solob/dev/tokesi/Tokesi

# Remove any database dumps
rm -f *.sql

# Remove any backup files
rm -f .env.backup .env.production

# Remove IDE helper files (optional - they're large)
# These can be regenerated with: php artisan ide-helper:generate
rm -f .phpstorm.meta.php _ide_helper.php _ide_helper_models.php
```

---

## Migration Steps

### Step 1: Initialize Git Repository

```bash
cd /Users/solob/dev/tokesi/Tokesi

# Initialize git
git init

# Check status
git status
```

### Step 2: Review What Will Be Committed

```bash
# See what files will be added
git status

# Make sure .env is NOT listed (should be ignored)
# Make sure vendor/ and node_modules/ are NOT listed
```

### Step 3: Create Initial Commit

```bash
# Add all files
git add .

# Create initial commit
git commit -m "feat: initial commit - Laravel 12 e-commerce application

- E-commerce platform for author Tokesi Akinola
- Product catalog with shopping cart
- Stripe and PayPal payment integration
- Blog system with categories, tags, and comments
- Filament 4.0 admin panel
- Email notifications for orders and contacts
- Media library with image management
- Reviews and testimonials system
- Location-based pages (Wigan, Manchester)
- Sitemap generation for SEO
- Responsive design with Tailwind CSS 4"
```

### Step 4: Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `tokesi-ecommerce` (or your preferred name)
3. Description: "E-commerce platform for author Tokesi Akinola - Laravel 12"
4. Visibility: **Private** (recommended for production code)
5. **DO NOT** check "Initialize with README" (we have code already)
6. **DO NOT** add .gitignore (we have one already)
7. Click **Create repository**

### Step 5: Connect and Push to GitHub

```bash
# Add GitHub remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/tokesi-ecommerce.git

# Verify remote
git remote -v

# Rename branch to main (if needed)
git branch -M main

# Push to GitHub
git push -u origin main
```

### Step 6: Set Up GitHub Secrets (for CI/CD later)

In your GitHub repository:
1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Add these secrets (when you set up deployment):
   - `APP_KEY`
   - `DB_PASSWORD`
   - `STRIPE_SECRET`
   - `STRIPE_WEBHOOK_SECRET`
   - `MAIL_PASSWORD`
   - Any other sensitive credentials

---

## Post-Migration Setup

### Create README.md

```bash
cd /Users/solob/dev/tokesi/Tokesi
```

Create a `README.md` file with:

```markdown
# Tokesi Akinola E-Commerce Platform

A Laravel 12 e-commerce application for author Tokesi Akinola, featuring book sales, blog, and event management.

## Features

- 📚 Product catalog with shopping cart
- 💳 Stripe & PayPal payment integration
- 📝 Blog system with comments
- 👨‍💼 Filament admin panel
- 📧 Email notifications
- 🖼️ Media library
- ⭐ Reviews & testimonials
- 📍 Location pages
- 🗺️ SEO sitemap generation

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Tailwind CSS 4, Alpine.js, Vite
- **Admin**: Filament 4.0
- **Database**: MySQL 8.0+
- **Payments**: Stripe, PayPal
- **Email**: SMTP

## Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8.0+

## Installation

See [SETUP_GUIDE.md](SETUP_GUIDE.md) for detailed setup instructions.

Quick start:

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/tokesi-ecommerce.git
cd tokesi-ecommerce

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate

# Build assets
npm run build

# Start server
php artisan serve
```

## Development

```bash
# Run development server with hot reload
composer dev
```

This starts:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log viewer
- Vite dev server

## Admin Access

Access admin panel at: `/admin`

## License

Proprietary - All rights reserved
```

### Add to Git

```bash
git add README.md
git commit -m "docs: add README with setup instructions"
git push
```

---

## Branch Strategy

### Recommended Workflow

```bash
# Create development branch
git checkout -b develop

# For new features
git checkout -b feature/feature-name

# For bug fixes
git checkout -b fix/bug-description

# For hotfixes
git checkout -b hotfix/critical-fix
```

### Branch Protection (Optional)

In GitHub repository settings:
1. Go to **Settings** → **Branches**
2. Add rule for `main` branch:
   - Require pull request reviews
   - Require status checks to pass
   - Require branches to be up to date

---

## Deployment Considerations

### Environment Variables

Create separate `.env` files for each environment:
- `.env.local` - Local development
- `.env.staging` - Staging server
- `.env.production` - Production server

**Never commit these files to Git!**

### Production Deployment Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Use production database
- [ ] Use live payment gateway keys
- [ ] Configure proper email settings
- [ ] Set up SSL certificate
- [ ] Configure queue worker daemon
- [ ] Set up cron jobs for scheduled tasks
- [ ] Enable caching (`php artisan config:cache`)
- [ ] Run `npm run build` for optimized assets
- [ ] Set proper file permissions (755/644)
- [ ] Configure backup strategy
- [ ] Set up monitoring and error tracking

### Continuous Deployment (Optional)

Consider using:
- **GitHub Actions** for CI/CD
- **Laravel Forge** for server management
- **Envoyer** for zero-downtime deployment
- **Laravel Vapor** for serverless deployment

---

## Security Notes

### Sensitive Files Already in .gitignore

✅ `.env` - Environment configuration
✅ `vendor/` - Composer dependencies
✅ `node_modules/` - npm dependencies
✅ `storage/*.key` - Encryption keys
✅ `public/storage` - Symlinked storage

### Additional Security Measures

1. **Rotate all production credentials** after migration
2. **Use different API keys** for development/production
3. **Enable 2FA** on GitHub account
4. **Review commit history** for accidentally committed secrets
5. **Use GitHub secret scanning** (automatic in private repos)

### If You Accidentally Commit Secrets

```bash
# Remove file from Git history
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch path/to/sensitive/file" \
  --prune-empty --tag-name-filter cat -- --all

# Force push (WARNING: rewrites history)
git push origin --force --all
```

**Then immediately:**
1. Rotate all exposed credentials
2. Invalidate compromised API keys
3. Change database passwords

---

## Collaboration

### For Team Members

```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/tokesi-ecommerce.git
cd tokesi-ecommerce

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
# Update .env with local database credentials

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start development
composer dev
```

### Code Review Process

1. Create feature branch
2. Make changes and commit
3. Push branch to GitHub
4. Create Pull Request
5. Request review
6. Address feedback
7. Merge to main/develop

---

## Maintenance

### Regular Updates

```bash
# Update PHP dependencies
composer update

# Update Node dependencies
npm update

# Update Laravel
composer update laravel/framework

# Update Filament
composer update filament/filament
```

### Database Backups

```bash
# Export database
php artisan db:backup

# Or manually
mysqldump -u username -p database_name > backup.sql
```

---

## Support

For issues or questions:
- Check `storage/logs/laravel.log`
- Run `php artisan pail` for real-time logs
- Check failed queue jobs: `php artisan queue:failed`

---

**Last Updated:** February 23, 2026

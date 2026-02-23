# Tokesi Laravel Application - Local Setup Guide

## Overview

This is a **Laravel 12** e-commerce application for author Tokesi Akinola featuring:
- Product catalog with shopping cart
- Stripe & PayPal payment integration
- Blog system with comments
- Filament admin panel
- Email notifications
- Media library
- Reviews & testimonials

## Current Status

✅ **Completed:**
- PHP 8.5.2 installed
- Composer 2.9.5 installed
- Node.js 23.11.0 installed
- npm 10.9.2 installed
- PHP dependencies installed (vendor/)
- Node dependencies installed (node_modules/)

❌ **Missing (Required):**
1. MySQL/MariaDB database server
2. Database export from production
3. Uploaded files/images from production

---

## Step 1: Install MySQL

```bash
# Install MySQL via Homebrew
brew install mysql

# Start MySQL service
brew services start mysql

# Secure MySQL installation (set root password)
mysql_secure_installation
```

**Recommended settings during secure installation:**
- Set root password: Yes (choose a strong password)
- Remove anonymous users: Yes
- Disallow root login remotely: Yes
- Remove test database: Yes
- Reload privilege tables: Yes

---

## Step 2: Create Local Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE tokesi_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create database user
CREATE USER 'tokesi_user'@'localhost' IDENTIFIED BY 'your_secure_password';

# Grant privileges
GRANT ALL PRIVILEGES ON tokesi_local.* TO 'tokesi_user'@'localhost';

# Flush privileges
FLUSH PRIVILEGES;

# Exit MySQL
EXIT;
```

---

## Step 3: Get Production Database Export

### From cPanel:

1. **Login to cPanel** at your hosting provider
2. Navigate to **phpMyAdmin**
3. Select database: `tokeywvs_tokesiinspired`
4. Click **Export** tab
5. Choose:
   - Method: **Quick**
   - Format: **SQL**
6. Click **Export** button
7. Save file as `tokesi_production.sql`
8. Place in: `/Users/solob/dev/tokesi/`

### Import to Local Database:

```bash
# Navigate to tokesi folder
cd /Users/solob/dev/tokesi

# Import database (replace with your MySQL password)
mysql -u tokesi_user -p tokesi_local < tokesi_production.sql
```

---

## Step 4: Get Production Files

### Download Storage Files from cPanel:

1. **Login to cPanel File Manager**
2. Navigate to: `/home/tokeywvs/Tokesi/storage/app/public/`
3. Select all folders/files
4. Click **Compress** → Choose **ZIP Archive**
5. Download the ZIP file
6. Extract contents to: `/Users/solob/dev/tokesi/Tokesi/storage/app/public/`

**Important folders to get:**
- `products/` - Product images
- `blog/` - Blog post images
- `media/` - Media library files
- Any other uploaded content

---

## Step 5: Configure Environment

Update the `.env` file for local development:

```bash
cd /Users/solob/dev/tokesi/Tokesi
```

Edit `.env` and update these values:

```env
# Application
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokesi_local
DB_USERNAME=tokesi_user
DB_PASSWORD=your_secure_password

# Mail (for local testing - use log driver)
MAIL_MAILER=log
# OR use Mailtrap for testing
# MAIL_MAILER=smtp
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=your_mailtrap_username
# MAIL_PASSWORD=your_mailtrap_password

# Stripe (use TEST keys for local)
STRIPE_KEY=pk_test_YOUR_TEST_KEY
STRIPE_SECRET=sk_test_YOUR_TEST_SECRET
STRIPE_WEBHOOK_SECRET=whsec_YOUR_TEST_WEBHOOK_SECRET

# PayPal (already in sandbox mode - good for local)
PAYPAL_MODE=sandbox
```

---

## Step 6: Generate Application Key & Storage Link

```bash
cd /Users/solob/dev/tokesi/Tokesi

# Generate application key (if not already set)
php artisan key:generate

# Create storage symlink
php artisan storage:link

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Step 7: Build Frontend Assets

```bash
cd /Users/solob/dev/tokesi/Tokesi

# Build for production
npm run build

# OR run in development mode with hot reload
npm run dev
```

---

## Step 8: Start Development Server

### Option A: Simple Server (Single Process)

```bash
cd /Users/solob/dev/tokesi/Tokesi
php artisan serve
```

Visit: http://localhost:8000

### Option B: Full Development Environment (Recommended)

This runs server, queue worker, logs, and Vite simultaneously:

```bash
cd /Users/solob/dev/tokesi/Tokesi
composer dev
```

This starts:
- **Server**: http://localhost:8000
- **Queue Worker**: Processes background jobs
- **Logs**: Real-time log monitoring
- **Vite**: Hot module replacement for frontend

---

## Step 9: Access Admin Panel

Visit: http://localhost:8000/admin

**Create Admin User:**

```bash
cd /Users/solob/dev/tokesi/Tokesi
php artisan tinker
```

Then in tinker:

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@tokesi.local';
$user->password = bcrypt('your_admin_password');
$user->is_admin = true;
$user->save();
exit
```

---

## Step 10: Test Application

### Frontend Tests:
- ✅ Homepage: http://localhost:8000
- ✅ Shop: http://localhost:8000/shop
- ✅ Blog: http://localhost:8000/blog
- ✅ Location pages: http://localhost:8000/wigan
- ✅ Cart functionality
- ✅ Checkout process

### Admin Panel Tests:
- ✅ Login: http://localhost:8000/admin
- ✅ Products management
- ✅ Orders management
- ✅ Blog management
- ✅ Media library

---

## Troubleshooting

### Permission Issues

```bash
cd /Users/solob/dev/tokesi/Tokesi
chmod -R 775 storage bootstrap/cache
```

### Database Connection Errors

Check `.env` credentials match your MySQL setup:
```bash
php artisan config:clear
php artisan tinker
# Then: DB::connection()->getPdo();
```

### Missing Tables

If migrations haven't run:
```bash
php artisan migrate
```

### Queue Jobs Not Processing

Start queue worker:
```bash
php artisan queue:work
```

---

## GitHub Migration Preparation

### Step 1: Initialize Git Repository

```bash
cd /Users/solob/dev/tokesi/Tokesi

# Initialize git
git init

# Add all files
git add .

# Initial commit
git commit -m "Initial commit: Laravel 12 e-commerce application"
```

### Step 2: Create .gitignore

The project already has `.gitignore` which excludes:
- `/vendor/`
- `/node_modules/`
- `.env`
- `/storage/*.key`
- `/public/hot`
- `/public/storage`

### Step 3: Create GitHub Repository

1. Go to https://github.com/new
2. Create new repository (e.g., `tokesi-ecommerce`)
3. **DO NOT** initialize with README (we already have code)

### Step 4: Push to GitHub

```bash
# Add remote
git remote add origin https://github.com/YOUR_USERNAME/tokesi-ecommerce.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 5: Secure Sensitive Data

**IMPORTANT:** Never commit:
- `.env` file (already in .gitignore)
- Production database credentials
- Live API keys (Stripe, PayPal)
- Email passwords

Use GitHub Secrets or environment variables for production deployment.

---

## Production Deployment Checklist

When deploying to production:

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Use production database credentials
- [ ] Use live Stripe keys
- [ ] Configure proper email settings
- [ ] Set up SSL certificate
- [ ] Configure queue worker as daemon
- [ ] Set up scheduled tasks (cron)
- [ ] Enable caching (`php artisan config:cache`)
- [ ] Run `npm run build` for optimized assets
- [ ] Set proper file permissions
- [ ] Configure backup strategy

---

## Additional Resources

- **Laravel Documentation**: https://laravel.com/docs/12.x
- **Filament Documentation**: https://filamentphp.com/docs
- **Stripe Testing**: https://stripe.com/docs/testing
- **PayPal Sandbox**: https://developer.paypal.com/

---

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Run `php artisan pail` for real-time logs
- Check queue jobs: `php artisan queue:failed`

---

**Last Updated:** February 23, 2026

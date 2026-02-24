# Tokesi Akinola - cPanel Deployment Guide

## Complete Step-by-Step Procedure to Deploy Laravel 12 E-Commerce Platform to cPanel

**Last Updated:** February 24, 2026  
**Framework:** Laravel 12  
**Database:** MySQL  
**Server:** cPanel/WHM

---

## Table of Contents

1. [Pre-Deployment Requirements](#pre-deployment-requirements)
2. [Step 1: cPanel Account Setup](#step-1-cpanel-account-setup)
3. [Step 2: Database Configuration](#step-2-database-configuration)
4. [Step 3: Domain & Addon Domain Setup](#step-3-domain--addon-domain-setup)
5. [Step 4: SSH Access Configuration](#step-4-ssh-access-configuration)
6. [Step 5: Upload Project Files](#step-5-upload-project-files)
7. [Step 6: Composer Installation](#step-6-composer-installation)
8. [Step 7: Environment Configuration](#step-7-environment-configuration)
9. [Step 8: Laravel Setup](#step-8-laravel-setup)
10. [Step 9: File Permissions](#step-9-file-permissions)
11. [Step 10: Web Server Configuration](#step-10-web-server-configuration)
12. [Step 11: SSL Certificate Setup](#step-11-ssl-certificate-setup)
13. [Step 12: Stripe Configuration](#step-12-stripe-configuration)
14. [Step 13: Email Configuration](#step-13-email-configuration)
15. [Step 14: Post-Deployment Verification](#step-14-post-deployment-verification)
16. [Troubleshooting](#troubleshooting)

---

## Pre-Deployment Requirements

### What You Need

- ✅ cPanel hosting account with SSH access enabled
- ✅ PHP 8.2 or higher installed on server
- ✅ MySQL 5.7+ or MariaDB 10.3+
- ✅ Composer installed on server
- ✅ Git installed on server (optional but recommended)
- ✅ SSL certificate (free Let's Encrypt available)
- ✅ Stripe API keys (live or test)
- ✅ SMTP email credentials (for notifications)
- ✅ Domain name pointing to cPanel server

### Check Server Specifications

**Via cPanel:**
1. Log in to cPanel
2. Go to **Home > Software > PHP Configuration**
3. Verify PHP version is 8.2+
4. Check that required extensions are enabled:
   - OpenSSL
   - PDO
   - Mbstring
   - Tokenizer
   - XML
   - Ctype
   - JSON

---

## Step 1: cPanel Account Setup

### 1.1 Access cPanel

```
URL: https://yourdomain.com:2083
OR
URL: https://your-server-ip:2083
```

**Login with:**
- Username: Your cPanel username
- Password: Your cPanel password

### 1.2 Verify Account Features

1. Navigate to **Home**
2. Check that you have:
   - ✅ Unlimited disk space (or sufficient space for project)
   - ✅ Unlimited bandwidth
   - ✅ SSH access enabled
   - ✅ MySQL databases available
   - ✅ Email accounts available

### 1.3 Create Backup

Before starting, create a backup:

1. Go to **Home > Files > Backups**
2. Click **Backup** (if available)
3. Wait for backup to complete

---

## Step 2: Database Configuration

### 2.1 Create MySQL Database

1. In cPanel, go to **Home > Databases > MySQL Databases**
2. Under "Create New Database":
   - **Database Name:** `tokesi_db` (will be prefixed with cPanel username)
   - Click **Create Database**

### 2.2 Create Database User

1. Under "MySQL Users", fill in:
   - **Username:** `tokesi_user` (will be prefixed with cPanel username)
   - **Password:** Generate strong password (save this!)
   - Click **Create User**

### 2.3 Assign Privileges

1. Under "Add User to Database":
   - **User:** Select `tokesi_user`
   - **Database:** Select `tokesi_db`
   - Click **Add**

2. Check all privileges:
   - ✅ ALL PRIVILEGES
   - Click **Make Changes**

### 2.4 Note Database Credentials

Save these for later:
```
Database Host: localhost
Database Name: cpaneluser_tokesi_db
Database User: cpaneluser_tokesi_user
Database Password: [your-strong-password]
```

---

## Step 3: Domain & Addon Domain Setup

### 3.1 Setup Main Domain (if new)

1. Go to **Home > Domains > Addon Domains**
2. If deploying to main domain, skip to Step 3.2
3. If deploying to subdomain:
   - **Domain Name:** `shop.yourdomain.com`
   - **Document Root:** `public_html/shop` (or custom path)
   - Click **Add Domain**

### 3.2 Verify Domain Points to Server

1. Go to **Home > Domains > Zone Editor**
2. Select your domain
3. Verify A record points to your server IP:
   ```
   yourdomain.com    A    123.45.67.89
   ```

### 3.3 Create Document Root Directory

1. Go to **Home > Files > File Manager**
2. Navigate to `public_html`
3. Create folder for your project (if using subdomain):
   - Right-click > **Create New Folder**
   - Name: `shop` (or your preferred name)
4. If using main domain, use existing `public_html` folder

---

## Step 4: SSH Access Configuration

### 4.1 Enable SSH Access

1. Go to **Home > Advanced > SSH Access**
2. Click **Manage SSH Keys**
3. If no keys exist:
   - Click **Generate a New Key**
   - **Key Name:** `tokesi-deploy`
   - **Key Type:** RSA
   - **Key Size:** 2048 or 4096
   - Click **Generate**

### 4.2 Download SSH Key

1. Click **Manage Authorization**
2. Find your key and click **Download**
3. Save the `.key` file securely on your local machine
4. Set permissions: `chmod 600 ~/.ssh/tokesi-deploy.key`

### 4.3 Test SSH Connection

From your local machine:

```bash
ssh -i ~/.ssh/tokesi-deploy.key cpaneluser@your-server-ip
```

You should see the cPanel shell prompt.

---

## Step 5: Upload Project Files

### Option A: Using Git (Recommended)

**Via SSH:**

```bash
# SSH into server
ssh -i ~/.ssh/tokesi-deploy.key cpaneluser@your-server-ip

# Navigate to document root
cd public_html/shop  # or your folder

# Clone repository
git clone https://github.com/yourusername/tokesi.git .

# Or if using private repo with SSH key
git clone git@github.com:yourusername/tokesi.git .
```

### Option B: Using File Manager

1. In cPanel, go to **Home > Files > File Manager**
2. Navigate to `public_html/shop`
3. Click **Upload**
4. Upload project files (or ZIP and extract)

### Option C: Using FTP/SFTP

1. In cPanel, go to **Home > Files > FTP Accounts**
2. Create FTP account or use existing
3. Use FileZilla or similar FTP client:
   - **Host:** your-server-ip or yourdomain.com
   - **Username:** cpaneluser_ftpuser
   - **Password:** [FTP password]
   - **Port:** 21 (or 22 for SFTP)
4. Upload files to `public_html/shop`

### Verify Upload

```bash
# SSH into server
ssh -i ~/.ssh/tokesi-deploy.key cpaneluser@your-server-ip

# Check files
ls -la public_html/shop/

# Should see: app, bootstrap, config, database, public, resources, routes, storage, etc.
```

---

## Step 6: Composer Installation

### 6.1 Check Composer Installation

```bash
# SSH into server
ssh -i ~/.ssh/tokesi-deploy.key cpaneluser@your-server-ip

# Check if Composer is installed
composer --version

# If not installed, install it:
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### 6.2 Install Project Dependencies

```bash
# Navigate to project
cd public_html/shop

# Install dependencies
composer install --optimize-autoloader --no-dev

# This will:
# - Download all PHP packages from composer.json
# - Generate autoloader
# - Create vendor/ directory
# - May take 2-5 minutes
```

### 6.3 Verify Installation

```bash
# Check vendor directory was created
ls -la vendor/ | head -20

# Should show many package directories
```

---

## Step 7: Environment Configuration

### 7.1 Create .env File

```bash
# SSH into server
cd public_html/shop

# Copy example file
cp .env.example .env

# Edit .env file
nano .env
```

### 7.2 Configure .env File

Update the following values:

```env
# Application
APP_NAME="Tokesi Akinola"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxx  # Will generate in Step 8
APP_DEBUG=false
APP_URL=https://yourdomain.com  # IMPORTANT: Use HTTPS

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpaneluser_tokesi_db
DB_USERNAME=cpaneluser_tokesi_user
DB_PASSWORD=your-database-password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your email provider
MAIL_PORT=2525
MAIL_USERNAME=your-email-username
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Tokesi Akinola"

# Stripe Configuration (from Step 12)
STRIPE_KEY=pk_live_xxxxxxxxxxxx
STRIPE_SECRET=sk_live_xxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxx

# Filament Configuration
FILAMENT_AUTHENTICATION_GUARD=web
```

### 7.3 Save File

Press `Ctrl+X`, then `Y`, then `Enter` to save in nano.

---

## Step 8: Laravel Setup

### 8.1 Generate Application Key

```bash
cd public_html/shop

# Generate APP_KEY
php artisan key:generate

# Output will show: Application key set successfully.
# The key is automatically added to .env file
```

### 8.2 Run Database Migrations

```bash
# Run all migrations
php artisan migrate --force

# This will:
# - Create all database tables
# - Set up relationships
# - Seed initial data (if seeders exist)
```

### 8.3 Create Storage Link

```bash
# Create symlink for storage
php artisan storage:link

# This creates: public/storage -> storage/app/public
# Needed for uploaded images to be accessible
```

### 8.4 Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 9: File Permissions

### 9.1 Set Directory Permissions

```bash
cd public_html/shop

# Set storage directory permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Set public directory permissions
chmod -R 755 public

# Set database directory permissions (if using SQLite)
chmod -R 755 database
```

### 9.2 Set Owner Permissions

```bash
# Check current user
whoami

# Set ownership (replace 'cpaneluser' with your cPanel username)
chown -R cpaneluser:cpaneluser storage
chown -R cpaneluser:cpaneluser bootstrap/cache
chown -R cpaneluser:cpaneluser public
```

### 9.3 Verify Permissions

```bash
# Check storage permissions
ls -ld storage
# Should show: drwxr-xr-x

# Check bootstrap/cache permissions
ls -ld bootstrap/cache
# Should show: drwxr-xr-x
```

---

## Step 10: Web Server Configuration

### 10.1 Configure .htaccess (Apache)

The project includes `.htaccess` in the `public/` directory. Verify it exists:

```bash
cat public/.htaccess
```

Should contain:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 10.2 Configure Document Root (cPanel)

1. Go to **Home > Domains > Addon Domains**
2. Find your domain
3. Click **Manage**
4. Set **Document Root** to: `public_html/shop/public`
5. Click **Save**

### 10.3 Enable mod_rewrite

1. Go to **Home > Software > Apache Handlers**
2. Verify `.htaccess` files are allowed:
   - Check **Allow .htaccess overrides** is enabled
3. If not, contact hosting provider

### 10.4 Test Web Server

```bash
# Check if Apache is running
sudo systemctl status apache2

# If not running, start it
sudo systemctl start apache2

# Restart Apache to apply changes
sudo systemctl restart apache2
```

---

## Step 11: SSL Certificate Setup

### 11.1 Install Free SSL Certificate (Let's Encrypt)

1. In cPanel, go to **Home > Security > AutoSSL**
2. Click **Manage**
3. Find your domain
4. Click **Install** or **Reissue**
5. Wait for installation (usually 5-10 minutes)

### 11.2 Force HTTPS

Edit `.env` file:

```env
APP_URL=https://yourdomain.com  # Must be HTTPS
```

Edit `public/.htaccess`, add after `RewriteEngine On`:

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 11.3 Verify SSL

Visit your domain in browser:
```
https://yourdomain.com
```

Should show:
- ✅ Green padlock icon
- ✅ No SSL warnings
- ✅ Site loads without errors

---

## Step 12: Stripe Configuration

### 12.1 Get Stripe API Keys

1. Log in to [Stripe Dashboard](https://dashboard.stripe.com)
2. Go to **Developers > API Keys**
3. Copy:
   - **Publishable Key** (starts with `pk_live_`)
   - **Secret Key** (starts with `sk_live_`)
   - **Webhook Secret** (if setting up webhooks)

### 12.2 Update .env File

```bash
cd public_html/shop
nano .env
```

Add/update:
```env
STRIPE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxx
```

### 12.3 Configure Webhook (Optional)

1. In Stripe Dashboard, go to **Developers > Webhooks**
2. Click **Add Endpoint**
3. **Endpoint URL:** `https://yourdomain.com/stripe/webhook`
4. **Events:** Select:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Click **Add Endpoint**
6. Copy **Signing Secret** to `STRIPE_WEBHOOK_SECRET` in `.env`

### 12.4 Test Stripe Connection

```bash
# SSH into server
cd public_html/shop

# Test Stripe keys
php artisan tinker

# In tinker:
>>> config('services.stripe.secret')
# Should return your secret key

>>> exit()
```

---

## Step 13: Email Configuration

### 13.1 Configure SMTP

**Option A: Using Mailtrap (for testing)**

1. Sign up at [Mailtrap.io](https://mailtrap.io)
2. Create new inbox
3. Copy SMTP credentials
4. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Tokesi Akinola"
```

**Option B: Using cPanel Email**

1. In cPanel, go to **Home > Email > Email Accounts**
2. Create email account: `noreply@yourdomain.com`
3. Get SMTP details from hosting provider
4. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Tokesi Akinola"
```

### 13.2 Test Email

```bash
# SSH into server
cd public_html/shop

# Test email configuration
php artisan tinker

# In tinker:
>>> Mail::raw('Test email', function($message) { $message->to('your-email@example.com'); })
# Should send test email

>>> exit()
```

---

## Step 14: Post-Deployment Verification

### 14.1 Test Homepage

Visit your domain:
```
https://yourdomain.com
```

Should see:
- ✅ Homepage loads without errors
- ✅ All images display correctly
- ✅ Navigation works
- ✅ No 500 errors

### 14.2 Test Shop Functionality

1. **Browse Products**
   - Go to `/shop`
   - Verify products load
   - Test filters and search

2. **Add to Cart**
   - Click "Add to Cart" on a product
   - Verify cart updates
   - Check cart summary

3. **Checkout Flow**
   - Click "Checkout"
   - Fill shipping information
   - Select shipping method
   - Review order
   - Test Stripe payment (use test card: `4242 4242 4242 4242`)

### 14.3 Test Admin Panel

1. Visit `/admin`
2. Log in with admin credentials
3. Verify all resources load:
   - Products
   - Orders
   - Blog Articles
   - Testimonials
   - Shipping Methods
   - Coupons

### 14.4 Check Error Logs

```bash
# View Laravel error logs
cd public_html/shop
tail -f storage/logs/laravel.log

# Should show no errors
```

### 14.5 Monitor Performance

```bash
# Check server resources
top

# Check disk usage
df -h

# Check memory usage
free -h
```

---

## Troubleshooting

### Issue 1: 500 Internal Server Error

**Symptoms:** White screen or 500 error

**Solutions:**

```bash
cd public_html/shop

# Check error logs
tail -50 storage/logs/laravel.log

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check file permissions
chmod -R 755 storage bootstrap/cache

# Verify .env file exists
ls -la .env

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo()
# Should connect without error
```

### Issue 2: Database Connection Error

**Symptoms:** "SQLSTATE[HY000]: General error"

**Solutions:**

```bash
# Verify database credentials in .env
cat .env | grep DB_

# Test database connection
php artisan tinker
>>> config('database.connections.mysql')
# Should show correct credentials

# Check if database exists
mysql -h localhost -u cpaneluser_tokesi_user -p
mysql> SHOW DATABASES;
# Should list your database

# Run migrations again
php artisan migrate --force
```

### Issue 3: Stripe Payment Not Working

**Symptoms:** Payment fails or Stripe element doesn't load

**Solutions:**

```bash
# Verify Stripe keys in .env
cat .env | grep STRIPE_

# Check if keys are correct
php artisan tinker
>>> config('services.stripe.secret')
# Should return your secret key

# Clear config cache
php artisan config:clear
php artisan config:cache

# Check browser console for JavaScript errors
# (Open browser DevTools > Console tab)
```

### Issue 4: Images Not Displaying

**Symptoms:** Product images show broken image icon

**Solutions:**

```bash
# Verify storage link exists
ls -la public/storage

# If not, create it
php artisan storage:link

# Check file permissions
chmod -R 755 storage/app/public

# Verify files exist
ls -la storage/app/public/
```

### Issue 5: Slow Performance

**Symptoms:** Site loads slowly

**Solutions:**

```bash
# Run optimization commands
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check if caching is working
php artisan tinker
>>> Cache::put('test', 'value', 60)
>>> Cache::get('test')
# Should return 'value'

# Monitor server resources
top
df -h
```

### Issue 6: Email Not Sending

**Symptoms:** Emails not received

**Solutions:**

```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); })

# Check SMTP credentials in .env
cat .env | grep MAIL_

# Verify firewall allows SMTP port
# (Contact hosting provider if needed)

# Check spam folder
# (Emails might be marked as spam)
```

### Issue 7: SSL Certificate Not Working

**Symptoms:** "Not Secure" warning in browser

**Solutions:**

```bash
# Force HTTPS in .env
APP_URL=https://yourdomain.com

# Clear config cache
php artisan config:clear
php artisan config:cache

# Restart web server
sudo systemctl restart apache2

# Check certificate validity
openssl s_client -connect yourdomain.com:443
# Should show certificate details
```

---

## Post-Deployment Maintenance

### Daily Tasks

- Monitor error logs: `tail -f storage/logs/laravel.log`
- Check disk space: `df -h`
- Monitor server resources: `top`

### Weekly Tasks

- Backup database
- Review admin panel for new orders
- Check Stripe transactions
- Review contact form submissions

### Monthly Tasks

- Update dependencies: `composer update`
- Review security logs
- Optimize database
- Clean up old logs

### Quarterly Tasks

- Full system backup
- Security audit
- Performance optimization
- Update documentation

---

## Security Checklist

Before going live, verify:

- ✅ `APP_DEBUG=false` in `.env`
- ✅ `APP_ENV=production` in `.env`
- ✅ SSL certificate installed and working
- ✅ Strong database password set
- ✅ Strong admin password set
- ✅ Stripe keys are live (not test)
- ✅ Email configuration working
- ✅ File permissions set correctly
- ✅ `.env` file not accessible via web
- ✅ Backups configured
- ✅ Error logs not publicly accessible
- ✅ Admin panel protected with strong password

---

## Final Checklist

Before declaring deployment complete:

- ✅ Homepage loads without errors
- ✅ All pages accessible
- ✅ Product catalog working
- ✅ Shopping cart functional
- ✅ Checkout flow complete
- ✅ Stripe payments working
- ✅ Admin panel accessible
- ✅ Email notifications sending
- ✅ SSL certificate active
- ✅ Database backups configured
- ✅ Error logs monitored
- ✅ Performance acceptable
- ✅ Mobile responsive
- ✅ SEO configured
- ✅ Analytics configured (if needed)

---

## Support & Troubleshooting

If you encounter issues:

1. **Check error logs:** `storage/logs/laravel.log`
2. **Check browser console:** DevTools > Console
3. **Check server logs:** cPanel > Metrics > Error Log
4. **Test connectivity:** `ping yourdomain.com`
5. **Contact hosting provider:** For server-level issues

---

## Quick Reference Commands

```bash
# SSH Connection
ssh -i ~/.ssh/tokesi-deploy.key cpaneluser@your-server-ip

# Navigate to project
cd public_html/shop

# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Optimize for production
php artisan optimize && php artisan config:cache && php artisan route:cache

# Run migrations
php artisan migrate --force

# View logs
tail -f storage/logs/laravel.log

# Test database
php artisan tinker

# Restart web server
sudo systemctl restart apache2
```

---

**Deployment Status:** ✅ Ready to Deploy

**Questions?** Refer to Laravel documentation: https://laravel.com/docs/12

---

**Document Version:** 1.0  
**Last Updated:** February 24, 2026  
**Status:** Complete & Ready for Use

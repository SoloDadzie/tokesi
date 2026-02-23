# Tokesi Akinola E-Commerce Platform

A Laravel 12 e-commerce application for author Tokesi Akinola, featuring book sales, blog, and event management.

## 🎯 Project Overview

This application was migrated from cPanel hosting to local development for GitHub version control and modern deployment workflows.

### Features

- 📚 **E-Commerce**: Product catalog, shopping cart, checkout
- 💳 **Payments**: Stripe & PayPal integration
- 📝 **Blog System**: Articles, categories, tags, comments
- 👨‍💼 **Admin Panel**: Filament 4.0 for content management
- 📧 **Email**: Order confirmations, contact forms, notifications
- 🖼️ **Media Library**: Image management with tags
- ⭐ **Reviews**: Customer reviews and testimonials
- 📍 **Locations**: Dedicated pages for Wigan and Manchester
- 🗺️ **SEO**: Automatic sitemap generation

## 🛠️ Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Tailwind CSS 4, Alpine.js, Vite 7
- **Admin**: Filament 4.0
- **Database**: MySQL 8.0+
- **Payments**: Stripe, PayPal
- **Email**: SMTP/Mailtrap
- **Assets**: Vite with hot module replacement

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8.0+ or MariaDB

## 🚀 Quick Start

### 1. Install MySQL (if not installed)

```bash
brew install mysql
brew services start mysql
```

### 2. Run Setup Script

```bash
cd /Users/solob/dev/tokesi/Tokesi
../setup-local.sh
```

This automated script will:
- ✅ Check system requirements
- ✅ Configure environment
- ✅ Install dependencies
- ✅ Set up application
- ✅ Build frontend assets

### 3. Get Production Data

Follow the checklist in `CPANEL_DATA_CHECKLIST.md` to:
- Export database from cPanel
- Download uploaded files/images
- Import to local environment

### 4. Start Development

```bash
# Option A: Simple server
php artisan serve

# Option B: Full dev environment (recommended)
composer dev
```

Access at: http://localhost:8000

## 📚 Documentation

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Detailed setup instructions
- **[CPANEL_DATA_CHECKLIST.md](CPANEL_DATA_CHECKLIST.md)** - Data export guide
- **[GITHUB_MIGRATION.md](GITHUB_MIGRATION.md)** - GitHub migration steps

## 🔧 Development Commands

```bash
# Start dev server with hot reload
composer dev

# Run migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear

# Generate sitemap
php artisan sitemap:generate

# Run tests
php artisan test

# Build assets for production
npm run build
```

## 👤 Admin Access

**URL**: http://localhost:8000/admin

**Create admin user:**
```bash
php artisan tinker
```

```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@tokesi.local';
$user->password = bcrypt('your_password');
$user->is_admin = true;
$user->save();
```

## 🗂️ Project Structure

```
Tokesi/
├── app/
│   ├── Filament/         # Admin panel resources
│   ├── Http/             # Controllers
│   ├── Mail/             # Email templates
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic
├── database/
│   └── migrations/       # Database schema
├── public/               # Web root
├── resources/
│   └── views/            # Blade templates
├── routes/
│   └── web.php           # Application routes
└── storage/
    └── app/public/       # Uploaded files
```

## 🔐 Security Notes

- Never commit `.env` file
- Use test API keys for local development
- Rotate production credentials after migration
- Keep dependencies updated

## 📦 Deployment

See `GITHUB_MIGRATION.md` for:
- GitHub repository setup
- Branch strategy
- CI/CD configuration
- Production deployment checklist

## 🐛 Troubleshooting

### Database Connection Issues
```bash
php artisan config:clear
php artisan db:show
```

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Missing Images
```bash
php artisan storage:link
```

### View Logs
```bash
php artisan pail
# or
tail -f storage/logs/laravel.log
```

## 📄 License

Proprietary - All rights reserved

---

**Last Updated:** February 23, 2026

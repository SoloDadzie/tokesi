# cPanel Data Export Checklist

## What You Need to Download from cPanel

This checklist helps you gather all necessary data from your cPanel hosting to run the application locally.

---

## ✅ 1. Database Export (CRITICAL - Required)

### Location in cPanel:
**phpMyAdmin** → Database: `tokeywvs_tokesiinspired`

### Steps:
1. Login to cPanel
2. Click **phpMyAdmin** icon
3. Select database: `tokeywvs_tokesiinspired` (left sidebar)
4. Click **Export** tab (top menu)
5. Export method: **Quick**
6. Format: **SQL**
7. Click **Export** button
8. Save file as: `tokesi_production.sql`

### Where to Place:
```
/Users/solob/dev/tokesi/tokesi_production.sql
```

### What This Contains:
- All products and product images metadata
- Blog articles, categories, tags, comments
- Orders and order history
- User accounts and admin users
- Reviews and testimonials
- Shopping cart data
- Coupons and discounts
- Contact form submissions
- Media library metadata
- All application data

### File Size:
Expect 5-50 MB depending on content volume

---

## ✅ 2. Uploaded Files/Storage (CRITICAL - Required)

### Location in cPanel:
**File Manager** → `/home/tokeywvs/Tokesi/storage/app/public/`

### What to Download:

#### Required Folders:
- **`products/`** - Product images
- **`blog/`** - Blog post featured images
- **`media/`** - Media library files
- **`categories/`** - Category images (if exists)
- **`testimonials/`** - Testimonial images (if exists)

### Steps:
1. Login to cPanel
2. Click **File Manager**
3. Navigate to: `/home/tokeywvs/Tokesi/storage/app/public/`
4. Select all folders inside `public/`
5. Click **Compress** button
6. Choose **ZIP Archive**
7. Name it: `storage_public.zip`
8. Click **Compress Files**
9. Download `storage_public.zip`
10. Extract to: `/Users/solob/dev/tokesi/Tokesi/storage/app/public/`

### Alternative Method (if File Manager is slow):
Use FTP/SFTP client like FileZilla:
- Host: Your cPanel domain
- Username: Your cPanel username
- Password: Your cPanel password
- Port: 21 (FTP) or 22 (SFTP)
- Download: `/home/tokeywvs/Tokesi/storage/app/public/`

### File Size:
Expect 50-500 MB depending on number of images

---

## ⚠️ 3. Environment Configuration (Optional - For Reference)

### Location in cPanel:
**File Manager** → `/home/tokeywvs/Tokesi/.env`

### Steps:
1. Navigate to `/home/tokeywvs/Tokesi/`
2. Right-click `.env` file
3. Click **View** or **Edit**
4. Copy the contents for reference

### Important Values to Note:
- Database name: `DB_DATABASE`
- Database user: `DB_USERNAME`
- Mail configuration: `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`
- Any custom configuration values

**Note:** You already have this file locally, but production may have different values.

---

## 📋 4. Additional Files (Optional but Recommended)

### Public Uploads (if any):
**File Manager** → `/home/tokeywvs/public_html/uploads/` (if exists)

### Custom Configuration:
- Any custom `.htaccess` rules
- Custom error pages
- Favicon files
- robots.txt (already have this)
- sitemap.xml (can be regenerated)

---

## 🔍 Verification Checklist

After downloading, verify you have:

- [ ] Database SQL file (`tokesi_production.sql`)
- [ ] Storage folder with product images
- [ ] Storage folder with blog images
- [ ] Storage folder with media library files
- [ ] Any custom uploaded files

### Quick Size Check:
```bash
# Check database file
ls -lh /Users/solob/dev/tokesi/tokesi_production.sql

# Check storage folder
du -sh /Users/solob/dev/tokesi/Tokesi/storage/app/public/*
```

---

## 📥 Import Instructions

### 1. Import Database:

```bash
# Navigate to tokesi folder
cd /Users/solob/dev/tokesi

# Import to local database
mysql -u tokesi_user -p tokesi_local < tokesi_production.sql
```

### 2. Verify Import:

```bash
# Login to MySQL
mysql -u tokesi_user -p tokesi_local

# Check tables
SHOW TABLES;

# Check product count
SELECT COUNT(*) FROM products;

# Check blog articles
SELECT COUNT(*) FROM blog_articles;

# Exit
EXIT;
```

### 3. Verify Storage Files:

```bash
# Check if images exist
ls -la /Users/solob/dev/tokesi/Tokesi/storage/app/public/products/
ls -la /Users/solob/dev/tokesi/Tokesi/storage/app/public/blog/
```

---

## 🚨 Common Issues

### Issue: Database Import Fails

**Error:** "Access denied for user"
**Solution:** Check database credentials in command

**Error:** "Unknown database"
**Solution:** Create database first:
```bash
mysql -u root -p
CREATE DATABASE tokesi_local;
EXIT;
```

### Issue: Images Not Showing

**Cause:** Storage link not created
**Solution:**
```bash
cd /Users/solob/dev/tokesi/Tokesi
php artisan storage:link
```

### Issue: Permission Denied

**Solution:**
```bash
chmod -R 775 storage/app/public
```

---

## 📊 Expected Data Volumes

Based on your application structure:

### Database Tables (21 migrations):
- users
- products
- product_images
- categories
- tags
- cart_items
- coupons
- reviews
- orders
- order_items
- shipping_methods
- blog_articles
- blog_categories
- blog_tags
- blog_comments
- testimonials
- media (media library)
- contacts
- sessions
- cache
- jobs

### Storage Folders:
- `products/` - Product images
- `blog/` - Blog images
- `media/` - General media files

---

## 🎯 Next Steps After Import

1. ✅ Verify database connection
2. ✅ Check storage files are accessible
3. ✅ Test image display on frontend
4. ✅ Login to admin panel
5. ✅ Verify products are visible
6. ✅ Check blog posts display correctly
7. ✅ Test cart functionality
8. ✅ Review order history

---

## 📞 Need Help?

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check database connection: `php artisan db:show`
3. Clear caches: `php artisan cache:clear`
4. Check file permissions: `ls -la storage/`

---

**Estimated Time:** 15-30 minutes depending on file sizes and internet speed

**Last Updated:** February 23, 2026

# Sitemap Generation Setup

## Overview
This document provides instructions on how to generate and manage the sitemap for the Tokesi Akinola website.

## What's Included

### Files Created/Modified:
1. **app/Http/Controllers/SitemapController.php** - Controller that generates the sitemap XML
2. **app/Console/Commands/GenerateSitemap.php** - Artisan command to generate sitemap
3. **config/sitemap.php** - Sitemap configuration file
4. **routes/web.php** - Added sitemap routes
5. **public/robots.txt** - Updated with sitemap reference

## Sitemap Features

### Included URLs:
- **Static Pages**: Home, About, Contact, Shop, Blogs
- **Location Pages**: Wigan, Manchester (with weekly changefreq)
- **Featured Products**: All products marked as featured (0.9 priority)
- **All Products**: All active products (0.7 priority, monthly changefreq)
- **Blog Articles**: All published blog posts (0.8 priority, weekly changefreq)

### Priority Levels:
- Homepage: 1.0 (highest)
- Featured Products: 0.9
- Shop: 0.9
- Locations: 0.85
- Blog Articles: 0.8
- About: 0.8
- Blogs Index: 0.8
- Contact: 0.7
- Regular Products: 0.7

### Change Frequencies:
- Static pages: weekly
- Products: weekly or monthly
- Blog posts: weekly

## Usage

### Generate Sitemap via Artisan Command
```bash
php artisan sitemap:generate
```

This command will:
1. Clear any cached sitemap
2. Generate a fresh sitemap with all current products, blog posts, and pages
3. Display a success message with the sitemap URL

### Access Sitemap
The sitemap is available at:
```
https://yourdomain.com/sitemap.xml
```

### Clear Sitemap Cache
If you want to force a regeneration, clear the cache:
```bash
php artisan cache:forget laravel-sitemap.sitemap
```

### Clear Cache via API
POST to `/admin/sitemap/clear-cache` (requires authentication in production)

## Configuration

Edit `config/sitemap.php` to customize:
- Cache duration (default: 3600 seconds = 1 hour)
- Cache key name
- Base URL for sitemap

## How It Works

1. **Automatic Caching**: The sitemap is generated once and cached for 1 hour
2. **Dynamic Updates**: When you add/update products or blog posts, the sitemap automatically updates after the cache expires
3. **Manual Refresh**: Run the artisan command to force an immediate update
4. **SEO Friendly**: All URLs include lastmod dates for better search engine indexing

## Location Route Integration

The sitemap automatically includes location-based routes:
- `/wigan` - Books by Tokesi Akinola in Wigan
- `/manchester` - Books by Tokesi Akinola in Manchester

These routes are dynamically included in the sitemap with weekly change frequency.

## Search Engine Integration

The `robots.txt` file has been updated to include:
```
Sitemap: /sitemap.xml
```

This ensures search engines like Google, Bing, etc., can discover your sitemap.

## Monitoring

You can check the sitemap generation in a few ways:

1. Visit `/sitemap.xml` in your browser
2. Check if the sitemap follows the standard XML format
3. Submit the sitemap to Google Search Console
4. Monitor sitemap generation logs

## Notes

- The sitemap is cached for performance reasons
- Only active (is_active = true) products are included
- Only published blog articles are included
- Lastmod dates are automatically tracked from model timestamps
- The sitemap respects your model relationships and scopes

## Troubleshooting

If the sitemap doesn't work:

1. Clear the cache:
   ```bash
   php artisan cache:clear
   ```

2. Check that all routes are properly registered:
   ```bash
   php artisan route:list | grep sitemap
   ```

3. Verify the configuration:
   ```bash
   php artisan tinker
   >>> config('sitemap.url')
   ```

4. Test in a browser to see if there are any errors in the XML

## Future Enhancements

Consider adding:
- Sitemap index for large number of URLs (>50,000)
- Schedule automatic sitemap regeneration
- Sitemap statistics and monitoring
- Multiple sitemaps for different content types

# Deploying TikTok Fame to Plesk with Laravel Extension

This guide explains how to deploy your Laravel application to a Plesk server using the Laravel extension.

## Prerequisites

1. A Plesk server with the Laravel extension installed
2. PHP 8.2 or higher
3. SSH access to your server (recommended but not required)
4. Composer installed on the server

## Preparation Steps

Before deploying, let's prepare the optimized production files:

### 1. Create a Production Build

```bash
# Create production directory
mkdir -p production

# Install dependencies
composer install --no-dev --optimize-autoloader

# Compile frontend assets
npm install
npm run build

# Copy files to production directory
cp -r app bootstrap config database public resources routes storage vendor production/
cp artisan composer.json composer.lock package.json vite.config.js production/
cp .env.production production/.env
```

### 2. Verify Your .htaccess

The `.htaccess` file in your public directory has been updated to:
- Redirect from www to non-www
- Force HTTPS
- Redirect index.php to root
- Handle sitemap XML files correctly
- Set proper security headers

### 3. Verify Your .env File

Your `.env.production` file has been updated to use:
- Domain: tiktokfame.net
- Production settings (APP_DEBUG=false)
- Proper database path

## Deployment Steps in Plesk

1. **Log in to your Plesk Control Panel**

2. **Navigate to Your Domain**
   - Find and click on your domain (tiktokfame.net)

3. **Access the Laravel Extension**
   - In the domain dashboard, find and click on "Laravel"
   - If you don't see it, you may need to install the extension first

4. **Configure Laravel Application**
   - Document Root: `/httpdocs` (default public directory in Plesk)
   - Application Path: `/` (root of your domain)
   - Laravel Version: Select your Laravel version

5. **Upload Files**
   - Upload all files from your local `production` directory to the server
   - Use FTP, SFTP, or Plesk's File Manager
   - Ensure the directory structure matches what Laravel expects

6. **Configure PHP Version**
   - In Plesk, go to "PHP Settings" for your domain
   - Select PHP 8.2 or higher
   - Enable required PHP extensions:
     - BCMath
     - Ctype
     - JSON
     - Mbstring
     - OpenSSL
     - PDO
     - SQLite3
     - Tokenizer
     - XML

7. **Set Permissions**
   In Plesk terminal or via SSH:
   ```bash
   cd /var/www/vhosts/tiktokfame.net
   
   # Set ownership
   chown -R psaserv:psacln .
   
   # Set base permissions
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   
   # Set special permissions for writable directories
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   
   # Make artisan executable
   chmod +x artisan
   ```

8. **Setup Storage Link**
   ```bash
   # Run this from your domain root
   php artisan storage:link --force
   ```

9. **Optimize Laravel for Production**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   
   # Generate production caches
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Post-Deployment Checks

1. **Test Website**
   - Visit https://tiktokfame.net
   - Verify that all pages load correctly
   - Test features like user profiles and videos

2. **Check for Errors**
   - Monitor Laravel logs: `storage/logs/laravel.log`
   - Check PHP error logs in Plesk

3. **Verify Redirects**
   - Test that www.tiktokfame.net redirects to tiktokfame.net
   - Verify that index.php in URLs is removed automatically

4. **Sitemap Access**
   - Confirm your XML sitemap is accessible with the correct MIME type

## Troubleshooting

If you encounter issues:

1. **Application Error**
   - Temporarily set `APP_DEBUG=true` in .env
   - Check Laravel logs for detailed error messages

2. **Permission Issues**
   - Make sure storage and bootstrap/cache directories are writable
   - Verify proper ownership for files (should be Plesk's web user)

3. **URL Rewriting Problems**
   - Ensure mod_rewrite is enabled in Apache
   - Check that .htaccess file is properly uploaded and readable

4. **Database Connection Errors**
   - Verify the correct database path in the .env file
   - Ensure the database file is writable

5. **Laravel Extension Not Working**
   - Try manually configuring your application without the extension
   - Use standard PHP application settings in Plesk 
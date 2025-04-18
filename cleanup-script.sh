#!/bin/bash

# File Cleanup Script for TikTok Fame Laravel Application
# This script will remove unused, temporary, and redundant files

echo "Starting cleanup process..."

# 1. Remove temporary deployment files
echo "Removing temporary deployment files..."
rm -f fix-redirect-loop.htaccess
rm -f fix-plesk-env
rm -f plesk-troubleshooting.md
rm -f plesk-deployment-guide.md
rm -f plesk-build.sh
rm -f hostgator-*.sh
rm -f hostgator-*.php
rm -f build-production.sh
rm -f improved-hostgator-build.sh
rm -f hostgator-direct-deployment.sh

# 2. Remove macOS specific files
echo "Removing macOS specific files..."
find . -name ".DS_Store" -delete

# 3. Remove unnecessary documentation files
echo "Removing unnecessary documentation files..."
rm -f CACHING.md
rm -f README_SITEMAP.md

# 4. Clean up Laravel specific caches
echo "Cleaning Laravel caches..."
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# 5. Remove unused middleware (commented ones in Kernel.php)
echo "TrustHosts middleware is commented out in Kernel.php. You can safely remove it if not needed."

# 6. Remove production directory if no longer needed
echo "Do you want to remove the 'production' directory? It contains a deployment build."
echo "Only remove it if you've already deployed the application. (y/n)"
read -p "> " remove_production
if [ "$remove_production" = "y" ]; then
  rm -rf production
  echo "Production directory removed."
else
  echo "Production directory kept."
fi

# 7. Remove node_modules if no longer needed for development
echo "Do you want to remove the 'node_modules' directory to save space? (y/n)"
echo "You'll need to run 'npm install' again if you want to rebuild frontend assets."
read -p "> " remove_node_modules
if [ "$remove_node_modules" = "y" ]; then
  rm -rf node_modules
  echo "Node modules removed."
else
  echo "Node modules kept."
fi

# 8. Remove the tests directory if not using tests
echo "Do you want to remove the 'tests' directory if you're not using tests? (y/n)"
read -p "> " remove_tests
if [ "$remove_tests" = "y" ]; then
  rm -rf tests
  rm -f phpunit.xml
  echo "Tests directory and phpunit.xml removed."
else
  echo "Tests directory kept."
fi

# 9. Update TrustProxies middleware with improved configuration
echo "Updating TrustProxies middleware with improved configuration for Plesk..."
cat > app/Http/Middleware/TrustProxies.php << 'EOL'
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
EOL
echo "TrustProxies middleware updated."

# 10. Optimize the public/.htaccess file
echo "Optimizing .htaccess file..."
cat > public/.htaccess << 'EOL'
# Basic rewrite rules for Laravel on Plesk
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect index.php to root
    RewriteCond %{THE_REQUEST} ^[A-Z]{3,}\s/+index\.php/?(\S*) [NC]
    RewriteRule ^index\.php/?(.*)$ /$1 [L,R=301]
    
    # Handle Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Sitemap XML Handling
<FilesMatch "sitemap\.xml$">
    ForceType application/xml
</FilesMatch>

# PHP settings
<IfModule mod_php8.c>
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    php_value max_execution_time 300
    php_value memory_limit 256M
</IfModule>
EOL
echo ".htaccess file optimized."

# 11. Optimize .env file
echo "Optimizing .env file..."
cat > .env.optimized << 'EOL'
APP_NAME="TikTok Fame"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tiktokfame.net
APP_KEY=base64:y2gyIWmSrQ4SmFKRIPIQkynkLIZ8+GgTcgUiCyB2vE8=

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/vhosts/tiktokfame.net/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="info@tiktokfame.net"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
VITE_APP_URL="${APP_URL}"
EOL
echo "Optimized .env file created as .env.optimized"
echo "Review it and rename to .env if you want to use it."

echo "Cleanup complete!"
echo "Note: Some files require manual review before deletion."
echo "The script has created optimized versions of critical files." 
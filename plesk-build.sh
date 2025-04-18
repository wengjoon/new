#!/bin/bash

# Exit on error
set -e

echo "Starting Plesk Laravel deployment build..."

# Clean up existing production directory
echo "Cleaning up..."
rm -rf production
mkdir -p production

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install and compile frontend assets
echo "Building frontend assets..."
npm install
npm run build

# Copy project files to production directory
echo "Copying project files..."
cp -r app bootstrap config database public resources routes storage vendor production/
cp artisan composer.json composer.lock package.json vite.config.js production/
cp .env.production production/.env

# Create necessary directories
echo "Creating storage directories..."
mkdir -p production/storage/framework/{sessions,views,cache}
mkdir -p production/storage/logs
mkdir -p production/storage/app/public

# Set proper permissions for local production folder
echo "Setting permissions..."
find production -type f -exec chmod 644 {} \;
find production -type d -exec chmod 755 {} \;
chmod -R 775 production/storage
chmod -R 775 production/bootstrap/cache
chmod +x production/artisan

# Add robots.txt if it doesn't exist
if [ ! -f production/public/robots.txt ]; then
  echo "Creating robots.txt..."
  cat > production/public/robots.txt << 'EOL'
User-agent: *
Allow: /
Sitemap: https://tiktokfame.net/sitemap.xml
EOL
fi

# Create a basic sitemap.xml if it doesn't exist
if [ ! -f production/public/sitemap.xml ]; then
  echo "Creating basic sitemap.xml..."
  cat > production/public/sitemap.xml << 'EOL'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://tiktokfame.net/</loc>
    <lastmod>2023-06-01</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
</urlset>
EOL
fi

# Create helpful README with deployment instructions
echo "Creating README file..."
cat > production/README.md << 'EOL'
# TikTok Fame - Plesk Deployment

## Deployment Steps

1. Upload all files to your Plesk server using FTP or Plesk File Manager
2. In Plesk, go to your domain and click on the Laravel extension
3. Configure the Laravel application with the correct paths
4. Set proper permissions:
   ```bash
   # Run these commands on your server
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   chmod +x artisan
   ```
5. Create storage symbolic link:
   ```bash
   php artisan storage:link --force
   ```
6. Clear and optimize Laravel caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Troubleshooting

If you encounter issues:
- Check Laravel logs in storage/logs/laravel.log
- Verify PHP version is 8.2 or higher
- Ensure all required extensions are enabled
- Check that .htaccess is properly uploaded and working
EOL

# Create an additional file with post-deployment commands
echo "Creating post-deployment commands file..."
cat > production/post-deployment.txt << 'EOL'
# Run these commands after deployment on your Plesk server

# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod +x artisan

# Create storage symbolic link
php artisan storage:link --force

# Clear and rebuild Laravel caches for production
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
EOL

echo "Build completed successfully!"
echo ""
echo "Your production-ready files are in the 'production' directory."
echo "Upload these files to your Plesk server and follow the instructions in README.md."
echo ""
echo "Don't forget to run the commands in post-deployment.txt after uploading." 
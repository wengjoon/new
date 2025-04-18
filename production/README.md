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

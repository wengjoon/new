# Fixing Redirect Loop on TikTok Fame in Plesk

The "page isn't redirecting properly" error in your browser indicates a redirect loop. Here are step-by-step solutions to resolve this issue:

## Immediate Solutions

### 1. Fix the .htaccess File

Plesk often has its own SSL/HTTPS handling, so your .htaccess rules may be causing conflicts. Replace your public/.htaccess file with this simplified version:

```apache
# Basic rewrite rules for Laravel
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
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
```

### 2. Update Your .env File

Modify your .env file to debug the issue:

```
APP_DEBUG=true
LOG_LEVEL=debug

# Comment out these lines (may be causing issues)
# SESSION_DOMAIN=tiktokfame.net
# SESSION_SECURE_COOKIE=true
```

### 3. Clear All Caches

Run these commands on your server:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## If Issues Persist

If the above steps don't resolve the redirect loop:

### 1. Check Plesk's SSL Settings

1. Log in to Plesk Control Panel
2. Go to your domain settings
3. Click on "SSL/TLS Settings"
4. Make sure SSL is properly set up
5. Check if "HSTS" is enabled - try disabling it temporarily

### 2. Check Laravel's TrustProxies Middleware

Edit `app/Http/Middleware/TrustProxies.php`:

```php
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
    protected $proxies = '*'; // Trust all proxies

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
```

### 3. Check Your Web Server Configuration in Plesk

1. Go to your domain in Plesk
2. Go to "Apache & nginx Settings"
3. Make sure "Proxy mode" is set correctly (if using nginx)
4. Verify that "Additional nginx directives" doesn't have conflicting redirects

### 4. Test with a Simple PHP File

Create a file `public/test.php` with:

```php
<?php
phpinfo();
```

Try accessing `https://tiktokfame.net/test.php` to see if basic PHP is working.

### 5. Check Application Routes

If you're still having issues, it might be related to Laravel's routes:

```bash
php artisan route:list
```

Look for any routes that might be causing redirect loops.

## Advanced Troubleshooting

If none of the above solutions work:

1. **Temporarily disable route caching**:
   ```bash
   php artisan route:clear
   # Edit your .env file
   APP_ROUTES_CACHE=false
   ```

2. **Check Middleware Stack**: Temporarily remove any custom middleware from your `app/Http/Kernel.php` file.

3. **Check Session Configuration**: Try a different session driver:
   ```
   SESSION_DRIVER=file
   ```

4. **Contact Plesk Support**: If all else fails, contact Plesk support and explain:
   - You're running a Laravel application
   - You're experiencing redirect loops
   - You've already tried fixing .htaccess and env settings 
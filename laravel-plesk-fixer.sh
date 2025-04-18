#!/bin/bash
# Laravel Structure Fix Script for Plesk on AlmaLinux 9
# This script creates missing Laravel directories and files with proper permissions

# Exit on error
set -e

# Define color codes for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Starting Laravel Structure Fix Script for Plesk...${NC}"

# Identify the current directory - should be run from the httpdocs or public_html folder
CURRENT_DIR=$(pwd)
echo -e "Current directory: ${YELLOW}$CURRENT_DIR${NC}"

# Ask user to confirm we're in the right directory
echo
echo "This script should be run from your Plesk httpdocs directory."
echo "It will create Laravel's directory structure, assuming httpdocs is the 'public' folder."
echo -e "${RED}Are you sure you want to continue? (y/n)${NC}"
read -p "> " proceed

if [[ "$proceed" != "y" ]]; then
  echo "Aborting script execution."
  exit 1
fi

# Set the Laravel root directory (parent of httpdocs)
LARAVEL_ROOT=$(dirname "$CURRENT_DIR")
echo -e "Laravel root will be: ${YELLOW}$LARAVEL_ROOT${NC}"

# Create the directory structure
echo
echo -e "${GREEN}Creating Laravel directory structure...${NC}"

# 1. Create bootstrap directory and cache
echo "Creating bootstrap directory..."
mkdir -p "$LARAVEL_ROOT/bootstrap/cache"
touch "$LARAVEL_ROOT/bootstrap/cache/.gitignore"
echo "*
!.gitignore" > "$LARAVEL_ROOT/bootstrap/cache/.gitignore"

# 2. Create bootstrap/app.php
echo "Creating bootstrap/app.php..."
cat > "$LARAVEL_ROOT/bootstrap/app.php" << 'EOL'
<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Configure Storage Path
|--------------------------------------------------------------------------
|
| For Plesk deployment, ensure storage path is correctly set
|
*/

$app->useStoragePath(dirname(__DIR__).'/storage');

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
EOL

# 3. Create storage directory with subdirectories
echo "Creating storage directory structure..."
mkdir -p "$LARAVEL_ROOT/storage/app/public"
mkdir -p "$LARAVEL_ROOT/storage/framework/cache/data"
mkdir -p "$LARAVEL_ROOT/storage/framework/sessions"
mkdir -p "$LARAVEL_ROOT/storage/framework/testing"
mkdir -p "$LARAVEL_ROOT/storage/framework/views"
mkdir -p "$LARAVEL_ROOT/storage/logs"

# 4. Create .gitignore files for storage directories
echo "Creating .gitignore files for storage..."
cat > "$LARAVEL_ROOT/storage/framework/.gitignore" << 'EOL'
compiled.php
config.php
down
events.scanned.php
maintenance.php
routes.php
routes.scanned.php
schedule-*
services.json
EOL

cat > "$LARAVEL_ROOT/storage/framework/sessions/.gitignore" << 'EOL'
*
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/framework/views/.gitignore" << 'EOL'
*
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/framework/cache/.gitignore" << 'EOL'
*
!data/
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/framework/cache/data/.gitignore" << 'EOL'
*
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/logs/.gitignore" << 'EOL'
*
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/app/.gitignore" << 'EOL'
*
!public/
!.gitignore
EOL

cat > "$LARAVEL_ROOT/storage/app/public/.gitignore" << 'EOL'
*
!.gitignore
EOL

# 5. Create artisan file if it doesn't exist
echo "Checking for artisan file..."
if [ ! -f "$LARAVEL_ROOT/artisan" ]; then
  echo "Creating artisan file..."
  cat > "$LARAVEL_ROOT/artisan" << 'EOL'
#!/usr/bin/env php
<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any of our classes manually. It's great to relax.
|
*/

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Artisan Application
|--------------------------------------------------------------------------
|
| When we run the console application, the current CLI command will be
| executed in this console and the response sent back to a terminal
| or another output device for the developers. Here goes nothing!
|
*/

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

/*
|--------------------------------------------------------------------------
| Shutdown The Application
|--------------------------------------------------------------------------
|
| Once Artisan has finished running, we will fire off the shutdown events
| so that any final work may be done by the application before we shut
| down the process. This is the last thing to happen to the request.
|
*/

$kernel->terminate($input, $status);

exit($status);
EOL
  chmod +x "$LARAVEL_ROOT/artisan"
fi

# 6. Create or update .env file
echo "Creating .env file..."
cat > "$LARAVEL_ROOT/.env" << 'EOL'
APP_NAME="TikTok Fame"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:y2gyIWmSrQ4SmFKRIPIQkynkLIZ8+GgTcgUiCyB2vE8=

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/vhosts/yourdomain.com/database/database.sqlite

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
MAIL_FROM_ADDRESS="info@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
VITE_APP_URL="${APP_URL}"
EOL

echo "Please edit $LARAVEL_ROOT/.env to update with your domain and database path!"

# 7. Create or update .htaccess file in httpdocs
echo "Creating optimized .htaccess file..."
cat > "$CURRENT_DIR/.htaccess" << 'EOL'
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

# 8. Update index.php to point to the correct location
echo "Updating index.php to use the correct paths..."
cat > "$CURRENT_DIR/index.php" << 'EOL'
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
EOL

# 9. Create database directory if missing
echo "Creating database directory..."
mkdir -p "$LARAVEL_ROOT/database"

# 10. Create config directory if missing
if [ ! -d "$LARAVEL_ROOT/config" ]; then
  echo "Creating basic config directory (needs to be populated properly)..."
  mkdir -p "$LARAVEL_ROOT/config"
  
  # Create a basic app.php config file as minimum
  cat > "$LARAVEL_ROOT/config/app.php" << 'EOL'
<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
EOL
fi

# 11. Set proper permissions
echo -e "${GREEN}Setting proper permissions...${NC}"
find "$LARAVEL_ROOT" -type f -exec chmod 644 {} \;
find "$LARAVEL_ROOT" -type d -exec chmod 755 {} \;
chmod -R 775 "$LARAVEL_ROOT/storage"
chmod -R 775 "$LARAVEL_ROOT/bootstrap/cache"
chmod +x "$LARAVEL_ROOT/artisan"

echo -e "${GREEN}Laravel directory structure has been created!${NC}"
echo
echo -e "${YELLOW}IMPORTANT: You still need to:${NC}"
echo "1. Update the .env file with your actual domain and database path"
echo "2. Make sure your app/Http directory and controllers are present"
echo "3. Install Laravel dependencies if not already done:"
echo "   cd $LARAVEL_ROOT && composer install --no-dev --optimize-autoloader"
echo
echo -e "${GREEN}Once you've done that, run these commands to clear caches:${NC}"
echo "cd $LARAVEL_ROOT"
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo "php artisan route:clear"
echo "php artisan view:clear"
echo
echo -e "${YELLOW}For Plesk, make sure the Laravel PHP extension is installed and configured.${NC}" 
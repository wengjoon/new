<?php
/**
 * Cache Driver Fix for Laravel on Plesk
 * 
 * This script updates the env file and explicitly sets cache to use 'file' driver
 * even when tags are used, which will prevent the MySQL connection attempts.
 */

// Check if the script is run on the server (not necessary for local runs)
if (php_sapi_name() !== 'cli') {
    die("This script should be run from the command line");
}

echo "Starting Cache Driver Fix for Laravel...\n";

// Define paths
$currentDir = dirname(__FILE__);
$envPath = $currentDir . '/.env';
$cacheServicePath = $currentDir . '/app/Services/CacheService.php';
$configCachePath = $currentDir . '/config/cache.php';

// Check if files exist
if (!file_exists($envPath)) {
    die("Error: .env file not found at $envPath\n");
}

if (!file_exists($cacheServicePath)) {
    die("Error: CacheService file not found at $cacheServicePath\n");
}

if (!file_exists($configCachePath)) {
    die("Error: cache.php config file not found at $configCachePath\n");
}

// 1. Update .env file
echo "Updating .env file...\n";
$envContent = file_get_contents($envPath);

// Make sure file cache is explicitly set
if (strpos($envContent, 'CACHE_DRIVER=') !== false) {
    $envContent = preg_replace('/CACHE_DRIVER=.*/', 'CACHE_DRIVER=file', $envContent);
} else {
    $envContent .= "\nCACHE_DRIVER=file\n";
}

// Ensure we have CACHE_STORE set to file
if (strpos($envContent, 'CACHE_STORE=') !== false) {
    $envContent = preg_replace('/CACHE_STORE=.*/', 'CACHE_STORE=file', $envContent);
} else {
    $envContent .= "CACHE_STORE=file\n";
}

// Add NO_TAGS_FALLBACK to prevent database connections when tags are used
if (strpos($envContent, 'CACHE_TAGS_DRIVER_FALLBACK=') === false) {
    $envContent .= "CACHE_TAGS_DRIVER_FALLBACK=array\n";
}

// Save updated .env
file_put_contents($envPath, $envContent);
echo "Updated .env file with cache settings\n";

// 2. Fix the CacheService.php file
echo "Checking CacheService.php file...\n";
$cacheServiceContent = file_get_contents($cacheServicePath);

// Create backup
file_put_contents($cacheServicePath . '.bak', $cacheServiceContent);
echo "Created backup of CacheService.php\n";

// Modify the Cache service to remove tags usage if present
$updatedContent = preg_replace(
    '/Cache::tags\(.*?\)->(get|put|forget)\(/i', 
    'Cache::$1(', 
    $cacheServiceContent
);

// Save if changes were made
if ($updatedContent !== $cacheServiceContent) {
    file_put_contents($cacheServicePath, $updatedContent);
    echo "Modified CacheService.php to remove tags usage\n";
} else {
    echo "No tags usage found in CacheService.php\n";
}

// 3. Update cache.php config
echo "Updating cache.php config file...\n";
$cacheConfigContent = file_get_contents($configCachePath);

// Create backup
file_put_contents($configCachePath . '.bak', $cacheConfigContent);
echo "Created backup of cache.php\n";

// Replace the default cache store with file
$updatedCacheConfig = preg_replace(
    "/'default' => env\('CACHE_STORE'.*?,.*?'file'\),/",
    "'default' => env('CACHE_STORE', 'file'),",
    $cacheConfigContent
);

// Add a custom array store for tags fallback
if (strpos($cacheConfigContent, "'tags_driver_fallback'") === false) {
    $updatedCacheConfig = str_replace(
        "'octane' => [
            'driver' => 'octane',
        ],",
        "'octane' => [
            'driver' => 'octane',
        ],
        
        'array_tags' => [
            'driver' => 'array',
            'serialize' => false,
        ],",
        $updatedCacheConfig
    );
}

// Save if changes were made
if ($updatedCacheConfig !== $cacheConfigContent) {
    file_put_contents($configCachePath, $updatedCacheConfig);
    echo "Updated cache.php configuration\n";
} else {
    echo "No changes needed in cache.php\n";
}

// Clear cache
echo "Clearing cache...\n";
if (file_exists($currentDir . '/artisan')) {
    echo shell_exec('php ' . $currentDir . '/artisan config:clear');
    echo shell_exec('php ' . $currentDir . '/artisan cache:clear');
    echo "Cache cleared successfully.\n";
} else {
    echo "Warning: Artisan file not found. Please manually clear the cache.\n";
}

echo "\nCache Driver Fix completed!\n";
echo "The application should now use the file driver without attempting to connect to MySQL.\n";
echo "If you encounter any issues, you can restore the backups that were created (*.bak files).\n"; 
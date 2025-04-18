<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class WarmTrendingCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-trending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm the cache for trending profiles';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService)
    {
        $this->info('Starting cache warming for trending profiles...');
        
        // In a real application, you would fetch this list from an analytics service
        // Here we're just using a hardcoded list of example trending profiles
        $trendingProfiles = [
            'tiktok',
            'charlidamelio',
            'addisonre',
            'khaby.lame',
            'bellapoarch'
        ];
        
        $cacheService->warmTrendingProfilesCache($trendingProfiles);
        
        $this->info('Cache warming scheduled for ' . count($trendingProfiles) . ' trending profiles');
        
        return 0;
    }
} 
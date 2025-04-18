<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CacheService
{
    /**
     * Cache durations in seconds
     */
    const PROFILE_CACHE_DURATION = 21600; // 6 hours
    const VIDEOS_CACHE_DURATION = 7200;   // 2 hours
    const VIDEO_CACHE_DURATION = 86400;   // 24 hours
    
    /**
     * Fetch profile from cache or source
     *
     * @param string $username
     * @param callable $fetchCallback
     * @param bool $forceRefresh
     * @return array
     */
    public function getProfile(string $username, callable $fetchCallback, bool $forceRefresh = false)
    {
        $cacheKey = "profile:{$username}";
        
        // Force refresh requested (admin function)
        if ($forceRefresh) {
            return $this->refreshProfileCache($username, $fetchCallback, $cacheKey);
        }
        
        // Attempt to get from cache
        $cachedProfile = Cache::get($cacheKey);
        
        if ($cachedProfile) {
            // Check if we need to log serving stale data
            if (isset($cachedProfile['is_stale']) && $cachedProfile['is_stale']) {
                Log::info("Serving stale profile data for user: {$username}");
            }
            return $cachedProfile;
        }
        
        // Cache miss, fetch fresh data
        return $this->refreshProfileCache($username, $fetchCallback, $cacheKey);
    }
    
    /**
     * Fetch videos from cache or source
     *
     * @param string $username
     * @param callable $fetchCallback
     * @param string|null $cursor
     * @param bool $forceRefresh
     * @return array
     */
    public function getVideos(string $username, callable $fetchCallback, $cursor = null, bool $forceRefresh = false)
    {
        $cacheKey = "videos:{$username}:" . ($cursor ?: 'initial');
        
        // Force refresh requested (admin function)
        if ($forceRefresh) {
            return $this->refreshVideosCache($username, $fetchCallback, $cacheKey, $cursor);
        }
        
        // Attempt to get from cache
        $cachedVideos = Cache::get($cacheKey);
        
        if ($cachedVideos) {
            // Check if we need to log serving stale data
            if (isset($cachedVideos['is_stale']) && $cachedVideos['is_stale']) {
                Log::info("Serving stale videos data for user: {$username}, cursor: {$cursor}");
            }
            return $cachedVideos;
        }
        
        // Cache miss, fetch fresh data
        return $this->refreshVideosCache($username, $fetchCallback, $cacheKey, $cursor);
    }
    
    /**
     * Fetch single video from cache or source
     *
     * @param string $username
     * @param string $videoId
     * @param callable $fetchCallback
     * @param bool $forceRefresh
     * @return array
     */
    public function getVideo(string $username, string $videoId, callable $fetchCallback, bool $forceRefresh = false)
    {
        $cacheKey = "video:{$username}:{$videoId}";
        
        // Force refresh requested (admin function)
        if ($forceRefresh) {
            return $this->refreshVideoCache($username, $videoId, $fetchCallback, $cacheKey);
        }
        
        // Attempt to get from cache
        $cachedVideo = Cache::get($cacheKey);
        
        if ($cachedVideo) {
            // Check if we need to log serving stale data
            if (isset($cachedVideo['is_stale']) && $cachedVideo['is_stale']) {
                Log::info("Serving stale video data for video ID: {$videoId}");
            }
            return $cachedVideo;
        }
        
        // Cache miss, fetch fresh data
        return $this->refreshVideoCache($username, $videoId, $fetchCallback, $cacheKey);
    }
    
    /**
     * Refresh profile cache with fresh data
     *
     * @param string $username
     * @param callable $fetchCallback
     * @param string $cacheKey
     * @return array
     */
    protected function refreshProfileCache(string $username, callable $fetchCallback, string $cacheKey)
    {
        try {
            $freshData = $fetchCallback();
            
            if (!$freshData || isset($freshData['code']) && $freshData['code'] !== 0) {
                return $this->handleFetchFailure($cacheKey, 'profile');
            }
            
            // Add cache metadata
            $freshData['cached_at'] = Carbon::now()->toIso8601String();
            $freshData['is_stale'] = false;
            
            // Store in cache
            Cache::put($cacheKey, $freshData, self::PROFILE_CACHE_DURATION);
            
            return $freshData;
        } catch (\Exception $e) {
            Log::error("Error refreshing profile cache: " . $e->getMessage(), [
                'username' => $username,
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->handleFetchFailure($cacheKey, 'profile');
        }
    }
    
    /**
     * Refresh videos cache with fresh data
     *
     * @param string $username
     * @param callable $fetchCallback
     * @param string $cacheKey
     * @param string|null $cursor
     * @return array
     */
    protected function refreshVideosCache(string $username, callable $fetchCallback, string $cacheKey, $cursor = null)
    {
        try {
            $freshData = $fetchCallback();
            
            if (!$freshData || isset($freshData['code']) && $freshData['code'] !== 0) {
                return $this->handleFetchFailure($cacheKey, 'videos');
            }
            
            // Add cache metadata
            $freshData['cached_at'] = Carbon::now()->toIso8601String();
            $freshData['is_stale'] = false;
            
            // Store in cache
            Cache::put($cacheKey, $freshData, self::VIDEOS_CACHE_DURATION);
            
            return $freshData;
        } catch (\Exception $e) {
            Log::error("Error refreshing videos cache: " . $e->getMessage(), [
                'username' => $username,
                'cursor' => $cursor,
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->handleFetchFailure($cacheKey, 'videos');
        }
    }
    
    /**
     * Refresh video cache with fresh data
     *
     * @param string $username
     * @param string $videoId
     * @param callable $fetchCallback
     * @param string $cacheKey
     * @return array
     */
    protected function refreshVideoCache(string $username, string $videoId, callable $fetchCallback, string $cacheKey)
    {
        try {
            $freshData = $fetchCallback();
            
            if (!$freshData || isset($freshData['code']) && $freshData['code'] !== 0) {
                return $this->handleFetchFailure($cacheKey, 'video');
            }
            
            // Add cache metadata
            $freshData['cached_at'] = Carbon::now()->toIso8601String();
            $freshData['is_stale'] = false;
            
            // Store in cache
            Cache::put($cacheKey, $freshData, self::VIDEO_CACHE_DURATION);
            
            return $freshData;
        } catch (\Exception $e) {
            Log::error("Error refreshing video cache: " . $e->getMessage(), [
                'username' => $username,
                'video_id' => $videoId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->handleFetchFailure($cacheKey, 'video');
        }
    }
    
    /**
     * Handle fetch failure by returning stale data if available
     *
     * @param string $cacheKey
     * @param string $type
     * @return array
     */
    protected function handleFetchFailure(string $cacheKey, string $type)
    {
        // Try to get stale data from cache
        $staleData = Cache::get("stale:{$cacheKey}");
        
        if ($staleData) {
            // Mark data as stale for UI indication
            $staleData['is_stale'] = true;
            Log::info("Serving stale {$type} data for key: {$cacheKey}");
            return $staleData;
        }
        
        return ['code' => -1, 'msg' => 'Failed to fetch data and no stale data available'];
    }
    
    /**
     * Schedule a background refresh for a cached resource
     * This is a stub method and would need a proper queue worker in production
     *
     * @param string $type
     * @param array $params
     */
    public function scheduleBackgroundRefresh(string $type, array $params)
    {
        // Log the refresh request for now
        Log::info("Background refresh scheduled for {$type}", $params);
        
        // In a real app, you would dispatch a job to a queue worker
        // e.g. RefreshCacheJob::dispatch($type, $params);
    }
    
    /**
     * Invalidate all cache for a user
     *
     * @param string $username
     */
    public function invalidateUserCache(string $username)
    {
        // Since we're using the file driver without tags, 
        // we need to manually clear the specific cache keys
        // This is less efficient than using tags for invalidation
        $profileKey = "profile:{$username}";
        $staleProfileKey = "stale:{$profileKey}";
        
        Cache::forget($profileKey);
        Cache::forget($staleProfileKey);
        
        Log::info("Invalidated cache for user: {$username}");
    }
    
    /**
     * Invalidate cache for a specific video
     *
     * @param string $username
     * @param string $videoId
     */
    public function invalidateVideoCache(string $username, string $videoId)
    {
        $videoKey = "video:{$username}:{$videoId}";
        $staleVideoKey = "stale:{$videoKey}";
        
        Cache::forget($videoKey);
        Cache::forget($staleVideoKey);
        
        Log::info("Invalidated cache for video: {$videoId}");
    }
    
    /**
     * Warm cache for trending profiles
     *
     * @param array $usernames
     */
    public function warmTrendingProfilesCache(array $usernames)
    {
        // This is a placeholder method that would prefetch and cache profiles
        Log::info("Warming cache for trending profiles", ['usernames' => $usernames]);
    }
} 
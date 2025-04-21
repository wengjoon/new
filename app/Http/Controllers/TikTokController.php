<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;

class TikTokController extends Controller
{
    protected $apiKey;
    protected $baseUrl;
    protected $cacheService;
    protected $maxRetries = 2; // Number of API retries to attempt

    public function __construct(CacheService $cacheService)
    {
        $this->apiKey = '4388edfedemsh1289c398bba84a2p133c6ajsn23ac51a4963b';
        $this->baseUrl = 'https://tiktok-scraper7.p.rapidapi.com';
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        return view('home');
    }

    public function search(Request $request)
    {
        $username = $request->input('username');
        
        if (empty($username)) {
            return redirect()->route('home')->with('error', 'Please enter a username');
        }

        // Clean the username (remove @ if present)
        $username = ltrim($username, '@');

        return redirect()->route('user.profile', ['username' => $username]);
    }

    public function userProfile(Request $request, $username = null)
    {
        // If username is not provided in URL, try to get it from the query string
        if (empty($username) && $request->has('username')) {
            $username = $request->input('username');
            // Remove @ if present
            $username = ltrim($username, '@');
            // Redirect to the canonical URL
            return redirect()->route('user.profile', ['username' => $username]);
        }
        
        if (empty($username)) {
            return redirect()->route('home')->with('error', 'Please enter a username');
        }
        
        try {
            $forceRefresh = $request->attributes->get('force_refresh', false);
            
            // Fetch user info with caching
            $userInfo = $this->cacheService->getProfile($username, function() use ($username) {
                return $this->getUserInfo($username);
            }, $forceRefresh);
            
            if (!$userInfo || isset($userInfo['code']) && $userInfo['code'] !== 0) {
                $errorMessage = isset($userInfo['msg']) ? $userInfo['msg'] : 'User not found or API error occurred';
                return redirect()->route('home')->with('error', $errorMessage);
            }

            // Is the profile data stale?
            $isStaleProfile = isset($userInfo['is_stale']) && $userInfo['is_stale'];
            
            // Fetch user posts with caching
            $userPosts = $this->cacheService->getVideos($username, function() use ($username) {
                return $this->getUserPosts($username);
            }, null, $forceRefresh);
            
            // Is the videos data stale?
            $isStaleVideos = isset($userPosts['is_stale']) && $userPosts['is_stale'];
            
            // Schedule background refresh if we served stale data
            if ($isStaleProfile) {
                $this->cacheService->scheduleBackgroundRefresh('profile', ['username' => $username]);
            }
            
            if ($isStaleVideos) {
                $this->cacheService->scheduleBackgroundRefresh('videos', ['username' => $username]);
            }
            
            if (!$userPosts || isset($userPosts['code']) && $userPosts['code'] !== 0) {
                $errorMessage = isset($userPosts['msg']) ? $userPosts['msg'] : 'Could not fetch user videos';
                Log::warning('Failed to fetch videos for user: ' . $username, ['response' => $userPosts]);
                
                // Still show profile but with empty videos
                return view('profile', [
                    'user' => $userInfo['data']['user'],
                    'stats' => $userInfo['data']['stats'],
                    'videos' => [],
                    'cursor' => null,
                    'hasMore' => false,
                    'username' => $username,
                    'error' => $errorMessage,
                    'isStale' => $isStaleProfile || $isStaleVideos, // Indicate if any data is stale
                    'cachedAt' => $userInfo['cached_at'] ?? null
                ]);
            }
            
            return view('profile', [
                'user' => $userInfo['data']['user'],
                'stats' => $userInfo['data']['stats'],
                'videos' => $userPosts['data']['videos'] ?? [],
                'cursor' => $userPosts['data']['cursor'] ?? null,
                'hasMore' => $userPosts['data']['hasMore'] ?? false,
                'username' => $username,
                'isStale' => $isStaleProfile || $isStaleVideos, // Indicate if any data is stale
                'cachedAt' => $userInfo['cached_at'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user profile: ' . $e->getMessage(), [
                'username' => $username,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function loadMorePosts(Request $request)
    {
        try {
            $username = $request->input('username');
            $cursor = $request->input('cursor');
            $forceRefresh = $request->attributes->get('force_refresh', false);
            
            // Fetch more posts with caching
            $userPosts = $this->cacheService->getVideos($username, function() use ($username, $cursor) {
                return $this->getUserPosts($username, $cursor);
            }, $cursor, $forceRefresh);
            
            // Schedule background refresh if we served stale data
            if (isset($userPosts['is_stale']) && $userPosts['is_stale']) {
                $this->cacheService->scheduleBackgroundRefresh('videos', [
                    'username' => $username,
                    'cursor' => $cursor
                ]);
            }
            
            if (!$userPosts || isset($userPosts['code']) && $userPosts['code'] !== 0) {
                return response()->json([
                    'error' => isset($userPosts['msg']) ? $userPosts['msg'] : 'Failed to load more videos',
                    'videos' => [],
                    'cursor' => null,
                    'hasMore' => false,
                    'isStale' => isset($userPosts['is_stale']) ? $userPosts['is_stale'] : false,
                    'cachedAt' => $userPosts['cached_at'] ?? null
                ], 200);
            }
            
            return response()->json([
                'videos' => $userPosts['data']['videos'] ?? [],
                'cursor' => $userPosts['data']['cursor'] ?? null,
                'hasMore' => $userPosts['data']['hasMore'] ?? false,
                'isStale' => isset($userPosts['is_stale']) ? $userPosts['is_stale'] : false,
                'cachedAt' => $userPosts['cached_at'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading more posts: ' . $e->getMessage(), [
                'username' => $request->input('username'),
                'cursor' => $request->input('cursor'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading more videos: ' . $e->getMessage(),
                'videos' => [],
                'cursor' => null,
                'hasMore' => false
            ], 200);
        }
    }
    
    /**
     * Get video details - new method to support direct video access
     * 
     * @param string $videoId
     * @return array
     */
    public function getVideoDetails($videoId)
    {
        $attempts = 0;
        $lastException = null;
        
        while ($attempts <= $this->maxRetries) {
            try {
                $response = Http::timeout(15)->withHeaders([
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => 'tiktok-scraper7.p.rapidapi.com'
                ])->get($this->baseUrl . '/video/info', [
                    'video_id' => $videoId
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::warning('Failed API call to get video details', [
                    'video_id' => $videoId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'attempt' => $attempts + 1
                ]);
                
                // If we get a 429 (too many requests) or 500+ error, retry
                if ($response->status() == 429 || $response->status() >= 500) {
                    $attempts++;
                    // Exponential backoff
                    if ($attempts <= $this->maxRetries) {
                        sleep(pow(2, $attempts));
                        continue;
                    }
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'API request failed: ' . $response->status() . ' - ' . $response->body()
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('Exception when getting video details: ' . $e->getMessage(), [
                    'video_id' => $videoId,
                    'trace' => $e->getTraceAsString(),
                    'attempt' => $attempts + 1
                ]);
                
                $attempts++;
                // Only retry for network-related exceptions
                if ($attempts <= $this->maxRetries && (
                    $e instanceof \Illuminate\Http\Client\ConnectionException ||
                    $e instanceof \GuzzleHttp\Exception\ConnectException ||
                    $e instanceof \GuzzleHttp\Exception\RequestException
                )) {
                    sleep(pow(2, $attempts));
                    continue;
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'Exception: ' . $e->getMessage() . ' (' . get_class($e) . ')'
                ];
            }
        }
        
        return [
            'code' => -1, 
            'msg' => 'Max retries exceeded. Last error: ' . ($lastException ? $lastException->getMessage() : 'Unknown error')
        ];
    }

    public function getUserInfo($username)
    {
        $attempts = 0;
        $lastException = null;
        
        while ($attempts <= $this->maxRetries) {
            try {
                $response = Http::timeout(15)->withHeaders([
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => 'tiktok-scraper7.p.rapidapi.com'
                ])->get($this->baseUrl . '/user/info', [
                    'unique_id' => $username
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::warning('Failed API call to get user info', [
                    'username' => $username,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'attempt' => $attempts + 1
                ]);
                
                // If we get a 429 (too many requests) or 500+ error, retry
                if ($response->status() == 429 || $response->status() >= 500) {
                    $attempts++;
                    // Exponential backoff
                    if ($attempts <= $this->maxRetries) {
                        sleep(pow(2, $attempts));
                        continue;
                    }
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'API request failed: ' . $response->status() . ' - ' . $response->body()
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('Exception when getting user info: ' . $e->getMessage(), [
                    'username' => $username,
                    'trace' => $e->getTraceAsString(),
                    'attempt' => $attempts + 1
                ]);
                
                $attempts++;
                // Only retry for network-related exceptions
                if ($attempts <= $this->maxRetries && (
                    $e instanceof \Illuminate\Http\Client\ConnectionException ||
                    $e instanceof \GuzzleHttp\Exception\ConnectException ||
                    $e instanceof \GuzzleHttp\Exception\RequestException
                )) {
                    sleep(pow(2, $attempts));
                    continue;
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'Exception: ' . $e->getMessage() . ' (' . get_class($e) . ')'
                ];
            }
        }
        
        return [
            'code' => -1, 
            'msg' => 'Max retries exceeded. Last error: ' . ($lastException ? $lastException->getMessage() : 'Unknown error')
        ];
    }

    public function getUserPosts($username, $cursor = 0)
    {
        $attempts = 0;
        $lastException = null;
        
        while ($attempts <= $this->maxRetries) {
            try {
                $response = Http::timeout(15)->withHeaders([
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => 'tiktok-scraper7.p.rapidapi.com'
                ])->get($this->baseUrl . '/user/posts', [
                    'unique_id' => $username,
                    'count' => 10,
                    'cursor' => $cursor
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::warning('Failed API call to get user posts', [
                    'username' => $username,
                    'cursor' => $cursor,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'attempt' => $attempts + 1
                ]);
                
                // If we get a 429 (too many requests) or 500+ error, retry
                if ($response->status() == 429 || $response->status() >= 500) {
                    $attempts++;
                    // Exponential backoff
                    if ($attempts <= $this->maxRetries) {
                        sleep(pow(2, $attempts));
                        continue;
                    }
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'API request failed: ' . $response->status() . ' - ' . $response->body()
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('Exception when getting user posts: ' . $e->getMessage(), [
                    'username' => $username,
                    'cursor' => $cursor,
                    'trace' => $e->getTraceAsString(),
                    'attempt' => $attempts + 1
                ]);
                
                $attempts++;
                // Only retry for network-related exceptions
                if ($attempts <= $this->maxRetries && (
                    $e instanceof \Illuminate\Http\Client\ConnectionException ||
                    $e instanceof \GuzzleHttp\Exception\ConnectException ||
                    $e instanceof \GuzzleHttp\Exception\RequestException
                )) {
                    sleep(pow(2, $attempts));
                    continue;
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'Exception: ' . $e->getMessage() . ' (' . get_class($e) . ')'
                ];
            }
        }
        
        return [
            'code' => -1, 
            'msg' => 'Max retries exceeded. Last error: ' . ($lastException ? $lastException->getMessage() : 'Unknown error')
        ];
    }
    
    /**
     * Method to manually warm the cache for trending profiles
     * This could be triggered by a scheduled task/cron job
     */
    public function warmTrendingCache()
    {
        // In a real application, you would fetch this list from an analytics service
        // Here we're just using a hardcoded list of example trending profiles
        $trendingProfiles = [
            'tiktok',
            'charlidamelio',
            'addisonre',
            'khaby.lame',
            'bellapoarch'
        ];
        
        $this->cacheService->warmTrendingProfilesCache($trendingProfiles);
        
        return response()->json([
            'success' => true,
            'message' => 'Cache warming scheduled for ' . count($trendingProfiles) . ' trending profiles'
        ]);
    }

    /**
     * Show the "How It Works" page with detailed explanation of anonymous viewing
     */
    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    /**
     * Show the "Popular TikTok Profiles" page
     */
    public function popularProfiles()
    {
        // These could be retrieved from a database, but for now, we'll hardcode some popular profiles
        $popularProfiles = [
            [
                'username' => 'charlidamelio',
                'name' => 'Charli D\'Amelio',
                'followers' => '149.5M',
                'description' => 'One of the most-followed creators on TikTok known for her dance videos',
                'image' => '/images/profile-placeholder.svg',
            ],
            [
                'username' => 'khaby.lame',
                'name' => 'Khaby Lame',
                'followers' => '160.3M',
                'description' => 'Known for his silent comedy videos where he mocks overly complicated life hacks',
                'image' => '/images/profile-placeholder.svg',
            ],
            [
                'username' => 'addisonre',
                'name' => 'Addison Rae',
                'followers' => '88.9M',
                'description' => 'Dancer and actress known for her dance videos and collaborations',
                'image' => '/images/profile-placeholder.svg',
            ],
            [
                'username' => 'zachking',
                'name' => 'Zach King',
                'followers' => '78.6M',
                'description' => 'Digital illusionist known for his "magic" videos with creative editing',
                'image' => '/images/profile-placeholder.svg',
            ],
            [
                'username' => 'bellapoarch',
                'name' => 'Bella Poarch',
                'followers' => '92.7M',
                'description' => 'Singer and content creator known initially for her lip-syncing and facial expressions',
                'image' => '/images/profile-placeholder.svg',
            ],
            [
                'username' => 'willsmith',
                'name' => 'Will Smith',
                'followers' => '72.2M',
                'description' => 'Actor and entertainer sharing comedy and behind-the-scenes content',
                'image' => '/images/profile-placeholder.svg',
            ],
        ];
        
        return view('pages.popular-profiles', compact('popularProfiles'));
    }

    /**
     * Show the "TikTok Tips" page
     */
    public function tikTokTips()
    {
        return view('pages.tiktok-tips');
    }
    
    /**
     * Test cache functionality
     * This is useful for debugging cache issues
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function testCache()
    {
        try {
            // Test cache connection
            $cacheTest = $this->cacheService->testCacheConnection();
            
            // Check cache directory
            $cachePath = config('cache.stores.file.path');
            $cachePathExists = file_exists($cachePath);
            $cachePathWritable = is_writable($cachePath);
            
            return response()->json([
                'cache_test' => $cacheTest,
                'cache_path_exists' => $cachePathExists,
                'cache_path_writable' => $cachePathWritable,
                'storage_path' => storage_path(),
                'base_path' => base_path(),
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'memory_limit' => ini_get('memory_limit'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Cache test failed',
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
} 
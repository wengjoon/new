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
        $this->apiKey = '5f6f67de6bmsh1e6aafcb72084e4p107e58jsncf5e09d03c5a';
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
            // Enable debug logging
            Log::debug("Attempting to fetch profile for username: {$username}");
            
            $forceRefresh = $request->has('refresh') || $request->attributes->get('force_refresh', false);
            
            // Fetch user info with caching
            $userInfo = $this->cacheService->getProfile($username, function() use ($username) {
                return $this->getUserInfo($username);
            }, $forceRefresh);
            
            // Log the full response for debugging
            Log::debug('API response for user profile', [
                'username' => $username,
                'response' => $userInfo,
            ]);
            
            if (!$userInfo) {
                Log::error("User info is null for username: {$username}");
                return redirect()->route('home')->with('error', 'Failed to retrieve user information. Please try again later.');
            }
            
            if (isset($userInfo['code']) && $userInfo['code'] !== 0) {
                $errorMessage = isset($userInfo['msg']) ? $userInfo['msg'] : 'User not found or API error occurred';
                Log::warning("API error for username: {$username}, code: {$userInfo['code']}, message: {$errorMessage}");
                
                // Instead of redirecting immediately, show error details in dev environment
                if (config('app.debug')) {
                    return view('error', [
                        'title' => 'API Error',
                        'message' => $errorMessage,
                        'details' => $userInfo,
                    ]);
                }
                
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
            
            // Return more detailed error in debug mode
            if (config('app.debug')) {
                return view('error', [
                    'title' => 'Error Fetching Profile',
                    'message' => $e->getMessage(),
                    'details' => [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],
                ]);
            }
            
            return redirect()->route('home')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function loadMorePosts(Request $request)
    {
        try {
            // Log request data for debugging
            Log::debug('Load More Posts Request', [
                'username' => $request->input('username'),
                'cursor' => $request->input('cursor'),
                'input' => $request->all()
            ]);
            
            // Validate inputs - removed _token requirement
            $validator = validator($request->all(), [
                'username' => 'required|string',
                'cursor' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                Log::warning('Validation failed for load more request', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'error' => 'Invalid request parameters: ' . implode(', ', $validator->errors()->all()),
                    'videos' => [],
                    'cursor' => null,
                    'hasMore' => false
                ], 400);
            }
            
            $username = $request->input('username');
            $cursor = $request->input('cursor', 0); // Default to 0 if not provided
            
            // Handle empty cursor string
            if ($cursor === '') {
                $cursor = 0;
            }
            
            // Initialize parameters for API request
            $params = [
                'unique_id' => $username,
                'count' => 10
            ];
            
            // Only add cursor if it's not 0 or empty
            if (!empty($cursor) && $cursor !== 0 && $cursor !== '0') {
                $params['cursor'] = $cursor;
            }
            
            Log::debug('Processed request parameters', [
                'username' => $username,
                'cursor' => $cursor,
                'cursor_type' => gettype($cursor),
                'api_params' => $params
            ]);
            
            // Make direct API request instead of using getUserPosts method
            try {
                $response = Http::timeout(30)->withHeaders([
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => 'tiktok-scraper7.p.rapidapi.com'
                ])->get($this->baseUrl . '/user/posts', $params);
                
                Log::debug('Raw API response for load more', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body_length' => strlen($response->body()),
                    'body_preview' => substr($response->body(), 0, 200),
                ]);
                
                if (!$response->successful()) {
                    return response()->json([
                        'error' => 'API request failed with status: ' . $response->status(),
                        'videos' => [],
                        'cursor' => null,
                        'hasMore' => false
                    ], 200);
                }
                
                $userPostsResponse = $response->json();
                
                if (!$userPostsResponse) {
                    return response()->json([
                        'error' => 'Failed to decode JSON response from API',
                        'videos' => [],
                        'cursor' => null,
                        'hasMore' => false
                    ], 200);
                }
                
                if (isset($userPostsResponse['code']) && $userPostsResponse['code'] !== 0) {
                    $errorMessage = $userPostsResponse['msg'] ?? 'Unknown API error';
                    
                    Log::error('TikTok API error', [
                        'username' => $username,
                        'cursor' => $cursor,
                        'code' => $userPostsResponse['code'],
                        'message' => $errorMessage
                    ]);
                    
                    return response()->json([
                        'error' => $errorMessage,
                        'debug_info' => $userPostsResponse,
                        'videos' => [],
                        'cursor' => null,
                        'hasMore' => false
                    ], 200);
                }
                
                // Extract videos data
                $videos = $userPostsResponse['data']['videos'] ?? [];
                $nextCursor = $userPostsResponse['data']['cursor'] ?? null;
                $hasMore = $userPostsResponse['data']['hasMore'] ?? false;
                
                Log::debug('Successfully processed videos', [
                    'username' => $username,
                    'video_count' => count($videos),
                    'next_cursor' => $nextCursor,
                    'has_more' => $hasMore
                ]);
                
                return response()->json([
                    'videos' => $videos,
                    'cursor' => $nextCursor,
                    'hasMore' => $hasMore,
                    'cachedAt' => date('Y-m-d H:i:s')
                ]);
                
            } catch (\Exception $apiException) {
                Log::error('API request exception: ' . $apiException->getMessage(), [
                    'username' => $username,
                    'cursor' => $cursor,
                    'exception_type' => get_class($apiException),
                    'trace' => $apiException->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => 'API error: ' . $apiException->getMessage(),
                    'exception_type' => get_class($apiException),
                    'videos' => [],
                    'cursor' => null,
                    'hasMore' => false
                ], 200);
            }
            
        } catch (\Exception $e) {
            Log::error('Error in loadMorePosts: ' . $e->getMessage(), [
                'username' => $request->input('username'),
                'cursor' => $request->input('cursor'),
                'exception_type' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage(),
                'exception_type' => get_class($e),
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
        
        Log::debug('Fetching user posts', [
            'username' => $username,
            'cursor' => $cursor,
            'api_key_length' => strlen($this->apiKey),
            'max_retries' => $this->maxRetries
        ]);
        
        while ($attempts <= $this->maxRetries) {
            try {
                // Build query parameters
                $params = [
                    'unique_id' => $username,
                    'count' => 10
                ];
                
                // Only add cursor if it's not 0
                if ($cursor !== 0 && !empty($cursor)) {
                    $params['cursor'] = $cursor;
                }
                
                Log::debug('Making TikTok API request for user posts', [
                    'endpoint' => $this->baseUrl . '/user/posts',
                    'params' => $params,
                    'attempt' => $attempts + 1
                ]);
                
                // Make the API request
                $response = Http::timeout(15)->withHeaders([
                    'X-RapidAPI-Key' => $this->apiKey,
                    'X-RapidAPI-Host' => 'tiktok-scraper7.p.rapidapi.com'
                ])->get($this->baseUrl . '/user/posts', $params);
                
                // Log the raw response for debugging
                Log::debug('Raw API response', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body_length' => strlen($response->body()),
                    'body_preview' => substr($response->body(), 0, 500),
                ]);
                
                if ($response->successful()) {
                    $jsonResponse = $response->json();
                    Log::debug('Successful response', [
                        'code' => $jsonResponse['code'] ?? 'not set',
                        'has_data' => isset($jsonResponse['data']),
                        'has_videos' => isset($jsonResponse['data']['videos']),
                        'video_count' => isset($jsonResponse['data']['videos']) ? count($jsonResponse['data']['videos']) : 0
                    ]);
                    
                    return $jsonResponse;
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
                        $sleepTime = pow(2, $attempts);
                        Log::info("Retrying after {$sleepTime} seconds (attempt {$attempts})");
                        sleep($sleepTime);
                        continue;
                    }
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'API request failed: ' . $response->status() . ' - ' . $response->body(),
                    'status_code' => $response->status()
                ];
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('Exception when getting user posts: ' . $e->getMessage(), [
                    'username' => $username,
                    'cursor' => $cursor,
                    'exception_type' => get_class($e),
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
                    $sleepTime = pow(2, $attempts);
                    Log::info("Retrying after {$sleepTime} seconds (attempt {$attempts})");
                    sleep($sleepTime);
                    continue;
                }
                
                return [
                    'code' => -1, 
                    'msg' => 'Exception: ' . $e->getMessage() . ' (' . get_class($e) . ')',
                    'exception_type' => get_class($e)
                ];
            }
        }
        
        return [
            'code' => -1, 
            'msg' => 'Max retries exceeded. Last error: ' . ($lastException ? $lastException->getMessage() : 'Unknown error'),
            'retry_count' => $attempts
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
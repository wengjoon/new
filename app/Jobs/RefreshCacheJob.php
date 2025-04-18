<?php

namespace App\Jobs;

use App\Services\CacheService;
use App\Http\Controllers\TikTokController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The type of cache to refresh.
     *
     * @var string
     */
    protected $type;

    /**
     * The parameters for the refresh.
     *
     * @var array
     */
    protected $params;

    /**
     * The number of attempts for this job.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [30, 60, 120];

    /**
     * Create a new job instance.
     *
     * @param string $type
     * @param array $params
     * @return void
     */
    public function __construct(string $type, array $params)
    {
        $this->type = $type;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param CacheService $cacheService
     * @param TikTokController $tikTokController
     * @return void
     */
    public function handle(CacheService $cacheService, TikTokController $tikTokController)
    {
        Log::info("Starting background cache refresh", [
            'type' => $this->type,
            'params' => $this->params
        ]);

        try {
            switch ($this->type) {
                case 'profile':
                    $this->refreshProfileCache($cacheService, $tikTokController);
                    break;
                
                case 'videos':
                    $this->refreshVideosCache($cacheService, $tikTokController);
                    break;
                
                case 'video':
                    $this->refreshVideoCache($cacheService, $tikTokController);
                    break;
                
                default:
                    Log::warning("Unknown cache refresh type: {$this->type}");
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Error in cache refresh job: " . $e->getMessage(), [
                'type' => $this->type,
                'params' => $this->params,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Refresh profile cache
     *
     * @param CacheService $cacheService
     * @param TikTokController $tikTokController
     * @return void
     */
    protected function refreshProfileCache(CacheService $cacheService, TikTokController $tikTokController)
    {
        $username = $this->params['username'];
        
        $cacheService->getProfile($username, function() use ($tikTokController, $username) {
            return $tikTokController->getUserInfo($username);
        }, true);
        
        Log::info("Completed background profile cache refresh", ['username' => $username]);
    }

    /**
     * Refresh videos cache
     *
     * @param CacheService $cacheService
     * @param TikTokController $tikTokController
     * @return void
     */
    protected function refreshVideosCache(CacheService $cacheService, TikTokController $tikTokController)
    {
        $username = $this->params['username'];
        $cursor = $this->params['cursor'] ?? null;
        
        $cacheService->getVideos($username, function() use ($tikTokController, $username, $cursor) {
            return $tikTokController->getUserPosts($username, $cursor);
        }, $cursor, true);
        
        Log::info("Completed background videos cache refresh", [
            'username' => $username,
            'cursor' => $cursor
        ]);
    }

    /**
     * Refresh single video cache
     *
     * @param CacheService $cacheService
     * @param TikTokController $tikTokController
     * @return void
     */
    protected function refreshVideoCache(CacheService $cacheService, TikTokController $tikTokController)
    {
        $username = $this->params['username'];
        $videoId = $this->params['video_id'];
        
        $cacheService->getVideo($username, $videoId, function() use ($tikTokController, $videoId) {
            return $tikTokController->getVideoDetails($videoId);
        }, true);
        
        Log::info("Completed background video cache refresh", [
            'username' => $username,
            'video_id' => $videoId
        ]);
    }
} 
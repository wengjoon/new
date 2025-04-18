<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for admin secret - this is a simple approach
        // In a real app, you'd use proper authentication
        if ($request->has('admin_key') && $request->admin_key === config('app.admin_key')) {
            Log::info("Admin access granted", [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Set cache refresh flag in request for use in controllers
            $request->attributes->set('force_refresh', true);
            
            return $next($request);
        }
        
        // If requesting a force refresh without proper credentials
        if ($request->has('refresh') && $request->refresh === 'true') {
            Log::warning("Unauthorized attempt to force-refresh cache", [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Silently ignore the refresh parameter for non-admins
            $request->attributes->set('force_refresh', false);
            
            return $next($request);
        }
        
        return $next($request);
    }
} 
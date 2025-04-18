<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ETagMiddleware
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
        // Get the response
        $response = $next($request);
        
        // Skip if not a GET request or if response isn't successful
        if (!$request->isMethod('GET') || !$response->isSuccessful()) {
            return $response;
        }
        
        // Get the response content
        $content = $response->getContent();
        
        // Generate ETag from the content
        $etag = md5($content);
        
        // Set ETag header
        $response->header('ETag', '"' . $etag . '"');
        
        // Check if If-None-Match header exists
        if ($request->header('If-None-Match') === '"' . $etag . '"') {
            // Return 304 Not Modified
            $response->setStatusCode(Response::HTTP_NOT_MODIFIED);
            $response->setContent(null); // Remove content to save bandwidth
        }
        
        // Set cache control headers
        $response->header('Cache-Control', 'private, must-revalidate');
        
        return $response;
    }
} 
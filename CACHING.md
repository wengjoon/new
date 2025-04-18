# TikTok Viewer Caching System

This document explains the comprehensive caching strategy implemented in the TikTok Viewer application to optimize performance, reduce API calls, and provide a seamless user experience.

## Caching Strategy Overview

The application implements a multi-layered caching approach:

1. **User Profile Caching**: Complete user profiles are cached for 6 hours
2. **Video Listings Caching**: Video lists on profile pages are cached for 2 hours
3. **Individual Video Caching**: Detailed video information is cached for 24 hours
4. **Stale Content Delivery**: Always serves content, even if stale, with visual indicators
5. **Background Refresh**: Queue-based cache refreshing that doesn't impact user experience
6. **Cache Tags**: Intelligent cache invalidation using tagging
7. **ETags**: Bandwidth optimization with browser-level caching
8. **Redis Storage**: High-performance caching using Redis

## Cache Durations

| Content Type | Duration | Configuration Constant |
|--------------|----------|------------------------|
| User Profiles | 6 hours | `PROFILE_CACHE_DURATION` |
| Video Listings | 2 hours | `VIDEOS_CACHE_DURATION` |
| Individual Videos | 24 hours | `VIDEO_CACHE_DURATION` |

## Key Components

### 1. CacheService

The `CacheService` class handles all caching operations and provides a consistent interface for accessing cached data. It includes:

- Methods for fetching profiles, videos, and individual video details
- Logic for handling stale data when API requests fail
- Background refresh scheduling through queued jobs
- Cache invalidation using tags

### 2. Redis Configuration

The application uses Redis for caching with proper serialization of complex objects. This is configured in:

- `config/cache.php` - Sets Redis as the default cache driver
- `.env` file - Contains Redis connection settings

### 3. Background Jobs

The `RefreshCacheJob` handles asynchronous cache refreshing to prevent slow page loads:

- Runs in a separate queue named "cache"
- Includes retry logic with backoff periods
- Updates cache entries without impacting user experience

### 4. Stale Content Handling

When API calls fail, the system:

1. Attempts to return cached data, even if expired
2. Marks the data as "stale" with a flag
3. Shows visual indicators to users when content is stale
4. Schedules a background refresh of the stale content

### 5. ETag Middleware

The `ETagMiddleware` implements HTTP ETag support for bandwidth optimization:

- Generates ETags based on response content
- Returns 304 Not Modified responses when content hasn't changed
- Reduces bandwidth usage for repeat visitors

### 6. Cache Warming

The system includes proactive cache warming for trending profiles:

- Scheduled via Laravel's task scheduler to run hourly
- Accessible via admin-only API endpoint
- Focuses on high-traffic profiles to minimize API rate limiting

## Cache Tags Structure

The application uses a hierarchical tagging system:

- `profile`: Tag for all profile caches
- `videos`: Tag for all video listing caches
- `video`: Tag for individual video caches
- `user:{username}`: Tag for all caches related to a specific user
- `video:{videoId}`: Tag for all caches related to a specific video

This structure allows for targeted cache invalidation when new content is detected.

## Admin Tools

The application includes admin-only tools for cache management:

- Force refresh parameter (`?admin_key=XXX&refresh=true`)
- Cache warming endpoint (`/admin/warm-cache?admin_key=XXX`)
- Admin middleware for controlling access

## Implementation in Controllers

The `TikTokController` has been updated to:

1. Inject the `CacheService` for dependency injection
2. Use callbacks for providing fresh data
3. Check for admin force-refresh parameters
4. Pass cache metadata to views for stale indicators
5. Schedule background refreshes when serving stale content

## Redis Requirements

To use this caching system, Redis must be installed and configured:

```bash
# Install Redis (Ubuntu example)
sudo apt update
sudo apt install redis-server

# Configure Redis to start on boot
sudo systemctl enable redis-server
```

Also ensure the PHP Redis extension is installed:

```bash
# PHP Redis extension (Ubuntu example)
sudo apt install php-redis
```

## Maintenance and Monitoring

For optimal performance:

1. Monitor Redis memory usage
2. Watch for changes in the TikTok API that might require cache invalidation
3. Adjust cache durations based on API rate limits and application traffic
4. Use Laravel's built-in cache statistics for troubleshooting 
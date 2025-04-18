<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user['nickname'] }} ({{"@" . $user['uniqueId']}}) videos - TikTok Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #000000;
            padding: 1rem;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fe2c55;
        }
        .logo i {
            margin-right: 5px;
        }
        .header-search {
            max-width: 300px;
        }
        .profile-header {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .stat-item {
            text-align: center;
            min-width: 80px;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .video-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: transform 0.3s;
        }
        .video-card:hover {
            transform: translateY(-5px);
        }
        .video-thumbnail {
            position: relative;
            padding-top: 177.78%; /* 16:9 aspect ratio */
            overflow: hidden;
            background-color: #eee;
        }
        .video-thumbnail img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .video-duration {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .video-info {
            padding: 1rem;
        }
        .video-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .video-stats {
            display: flex;
            gap: 1rem;
            color: #666;
            font-size: 0.9rem;
        }
        .load-more {
            display: block;
            width: 200px;
            margin: 0 auto 2rem;
            padding: 0.7rem 0;
            background-color: #fe2c55;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .load-more:hover {
            background-color: #e6254d;
        }
        .load-more:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .verified-badge {
            color: #20d5ec;
            margin-left: 5px;
        }
        footer {
            background-color: #f1f1f1;
            padding: 2rem 0;
            text-align: center;
            margin-top: 2rem;
        }
        /* Video Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background-color: #121212;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            background-color: #121212;
            border-bottom: 1px solid #303030;
            padding: 1rem 1.5rem;
        }
        .modal-title {
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .modal-body {
            padding: 0;
            background-color: #000;
            position: relative;
        }
        .btn-close {
            background-color: #fff;
            opacity: 0.8;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
        }
        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        #videoPlayer {
            width: 100%;
            height: auto;
            max-height: 80vh;
            background-color: #000;
        }
        .video-container {
            position: relative;
            max-width: 450px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            height: auto;
        }
        .video-info-modal {
            padding: 15px;
            background-color: #121212;
            color: #fff;
        }
        .video-stats-modal {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            color: #aaa;
        }
        .video-stats-modal i {
            color: #fe2c55;
            margin-right: 5px;
        }
        .overlay-buttons {
            position: absolute;
            bottom: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
        }
        .overlay-btn {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .overlay-btn:hover {
            background-color: #fe2c55;
            transform: scale(1.1);
        }
        /* Make the modal more mobile-friendly */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            .video-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand logo" href="{{ route('home') }}">
                <i class="fab fa-tiktok"></i> TikTok Viewer
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                </ul>
                <form class="d-flex header-search" action="{{ route('search') }}" method="POST">
                    @csrf
                    <input class="form-control me-2" type="search" name="username" placeholder="TikTok Username (e.g., @tiktok)">
                    <button class="btn btn-danger" type="submit">Go</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @if(isset($error))
            <div class="alert alert-warning my-3">
                {{ $error }}
            </div>
        @endif
        
        @if(isset($isStale) && $isStale)
            <div class="alert alert-info my-3">
                <i class="fas fa-clock me-2"></i> You are viewing cached data from {{ \Carbon\Carbon::parse($cachedAt)->diffForHumans() }}. 
                <a href="{{ request()->url() }}?refresh=true" class="alert-link">Refresh</a>
            </div>
        @endif
        
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row">
                <div class="col-md-auto text-center mb-3 mb-md-0">
                    <img src="{{ $user['avatarLarger'] }}" alt="{{ $user['nickname'] }}" class="profile-img">
                </div>
                <div class="col">
                    <h1>
                        {{ $user['nickname'] }}
                        @if($user['verified'])
                            <i class="fas fa-check-circle verified-badge"></i>
                        @endif
                    </h1>
                    <p class="text-muted">{{"@" . $user['uniqueId'] }}</p>
                    
                    @if(!empty($user['signature']))
                        <p>{{ $user['signature'] }}</p>
                    @endif
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($stats['followingCount']) }}</div>
                            <div class="stat-label">Following</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($stats['followerCount']) }}</div>
                            <div class="stat-label">Followers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($stats['heartCount']) }}</div>
                            <div class="stat-label">Likes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($stats['videoCount']) }}</div>
                            <div class="stat-label">Videos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Videos Section -->
        <h2 class="mb-4">Videos</h2>
        <div class="video-grid" id="video-container">
            @foreach($videos as $video)
                <div class="video-card">
                    <a href="javascript:void(0);" class="video-link" data-video-id="{{ $video['video_id'] }}" data-video-url="{{ $video['play'] }}">
                        <div class="video-thumbnail">
                            <img src="{{ $video['cover'] }}" alt="{{ $video['title'] }}">
                            <div class="video-duration">{{ gmdate("i:s", $video['duration']) }}</div>
                        </div>
                    </a>
                    <div class="video-info">
                        <h3 class="video-title">{{ $video['title'] }}</h3>
                        <div class="video-stats">
                            <span><i class="fas fa-eye"></i> {{ number_format($video['play_count']) }}</span>
                            <span><i class="fas fa-heart"></i> {{ number_format($video['digg_count']) }}</span>
                            <span><i class="fas fa-comment"></i> {{ number_format($video['comment_count']) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($hasMore)
            <button id="load-more-btn" class="load-more" data-cursor="{{ $cursor }}" data-username="{{ $username }}">
                Load More
            </button>
        @endif
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">{{$user['nickname']}}'s Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="video-container">
                        <video id="videoPlayer" controls autoplay playsinline>
                            <source src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="overlay-buttons">
                            <button class="overlay-btn" id="fullscreenBtn"><i class="fas fa-expand"></i></button>
                            <button class="overlay-btn" id="shareBtn"><i class="fas fa-share-alt"></i></button>
                        </div>
                    </div>
                    <div class="video-info-modal">
                        <h6 id="modal-video-title"></h6>
                        <div class="video-stats-modal">
                            <span id="modal-views"><i class="fas fa-eye"></i> <span id="view-count">0</span></span>
                            <span id="modal-likes"><i class="fas fa-heart"></i> <span id="like-count">0</span></span>
                            <span id="modal-comments"><i class="fas fa-comment"></i> <span id="comment-count">0</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>TikTok Viewer is not affiliated with TikTok. This is a third-party application.</p>
            <p>&copy; {{ date('Y') }} TikTok Viewer. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize video modal
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            const videoPlayer = document.getElementById('videoPlayer');
            // Store original document title
            const originalTitle = document.title;
            
            // Check URL for video ID on page load
            const currentUrl = window.location.pathname;
            const videoIdMatch = currentUrl.match(/\/video\/([^\/]+)$/);
            
            if (videoIdMatch && videoIdMatch[1]) {
                const videoId = videoIdMatch[1];
                // Find the video link with this ID
                const videoLink = $(`.video-link[data-video-id="${videoId}"]`);
                if (videoLink.length > 0) {
                    // Simulate a click on this video
                    setTimeout(() => videoLink.click(), 500);
                }
            }
            
            // Handle video click events
            $(document).on('click', '.video-link', function(e) {
                e.preventDefault();
                const videoUrl = $(this).data('video-url');
                const videoId = $(this).data('video-id');
                const videoTitle = $(this).closest('.video-card').find('.video-title').text();
                
                // Get video stats
                const viewCount = $(this).closest('.video-card').find('.video-stats span:nth-child(1)').text().trim();
                const likeCount = $(this).closest('.video-card').find('.video-stats span:nth-child(2)').text().trim();
                const commentCount = $(this).closest('.video-card').find('.video-stats span:nth-child(3)').text().trim();
                
                // Update video source
                $('#videoPlayer source').attr('src', videoUrl);
                videoPlayer.load();
                
                // Update modal title and stats
                $('#videoModalLabel').text(videoTitle);
                $('#modal-video-title').text(videoTitle);
                $('#view-count').text(viewCount.replace(/[^0-9,]/g, ''));
                $('#like-count').text(likeCount.replace(/[^0-9,]/g, ''));
                $('#comment-count').text(commentCount.replace(/[^0-9,]/g, ''));
                
                // Update browser URL without reloading the page
                const newUrl = `{{ url('/user/' . $username) }}/video/${videoId}`;
                history.pushState({}, '', newUrl);
                
                // Update document title
                document.title = `${videoTitle} - {{ $user['uniqueId'] }} TikTok videos`;
                
                // Show modal
                videoModal.show();
            });
            
            // Handle fullscreen button
            $('#fullscreenBtn').click(function() {
                if (videoPlayer.requestFullscreen) {
                    videoPlayer.requestFullscreen();
                } else if (videoPlayer.webkitRequestFullscreen) { /* Safari */
                    videoPlayer.webkitRequestFullscreen();
                } else if (videoPlayer.msRequestFullscreen) { /* IE11 */
                    videoPlayer.msRequestFullscreen();
                }
            });
            
            // Handle share button
            $('#shareBtn').click(function() {
                if (navigator.share) {
                    navigator.share({
                        title: $('#videoModalLabel').text(),
                        url: window.location.href
                    }).catch(console.error);
                } else {
                    // Fallback - copy to clipboard
                    const dummy = document.createElement('input');
                    document.body.appendChild(dummy);
                    dummy.value = window.location.href;
                    dummy.select();
                    document.execCommand('copy');
                    document.body.removeChild(dummy);
                    
                    alert('Link copied to clipboard!');
                }
            });
            
            // Reset URL when modal is closed
            $('#videoModal').on('hidden.bs.modal', function () {
                history.pushState({}, '', `{{ url('/user/' . $username) }}`);
                videoPlayer.pause();
                $('#videoPlayer source').attr('src', '');
                videoPlayer.load();
                
                // Restore original document title
                document.title = originalTitle;
            });
            
            // Show stale indicator for dynamically loaded content if needed
            function showStaleIndicator(response) {
                if (response.isStale) {
                    const staleIndicator = $('<div class="alert alert-info my-3">' +
                        '<i class="fas fa-clock me-2"></i> ' +
                        'This content was cached ' + moment(response.cachedAt).fromNow() + '.' +
                        '</div>');
                    
                    // Show indicator only if it doesn't already exist
                    if ($('#stale-indicator-load-more').length === 0) {
                        staleIndicator.attr('id', 'stale-indicator-load-more')
                            .insertBefore('#load-more-btn');
                    }
                }
            }
        
            $('#load-more-btn').click(function() {
                const btn = $(this);
                const cursor = btn.data('cursor');
                const username = btn.data('username');
                
                btn.prop('disabled', true).text('Loading...');
                
                $.ajax({
                    url: '{{ route("load.more") }}',
                    type: 'POST',
                    data: {
                        username: username,
                        cursor: cursor,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.videos && response.videos.length > 0) {
                            let html = '';
                            
                            response.videos.forEach(function(video) {
                                const duration = formatDuration(video.duration);
                                
                                html += `
                                <div class="video-card">
                                    <a href="javascript:void(0);" class="video-link" data-video-id="${video.video_id}" data-video-url="${video.play}">
                                        <div class="video-thumbnail">
                                            <img src="${video.cover}" alt="${video.title}">
                                            <div class="video-duration">${duration}</div>
                                        </div>
                                    </a>
                                    <div class="video-info">
                                        <h3 class="video-title">${video.title}</h3>
                                        <div class="video-stats">
                                            <span><i class="fas fa-eye"></i> ${formatNumber(video.play_count)}</span>
                                            <span><i class="fas fa-heart"></i> ${formatNumber(video.digg_count)}</span>
                                            <span><i class="fas fa-comment"></i> ${formatNumber(video.comment_count)}</span>
                                        </div>
                                    </div>
                                </div>
                                `;
                            });
                            
                            $('#video-container').append(html);
                            
                            // Show stale indicator if applicable
                            showStaleIndicator(response);
                            
                            if (response.hasMore) {
                                btn.data('cursor', response.cursor);
                                btn.prop('disabled', false).text('Load More');
                            } else {
                                btn.remove();
                            }
                        } else {
                            if (response.error) {
                                $('<div class="alert alert-warning my-3">' + response.error + '</div>').insertBefore(btn);
                            }
                            btn.remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('<div class="alert alert-danger my-3">Failed to load videos: ' + error + '</div>').insertBefore(btn);
                        btn.prop('disabled', false).text('Try again');
                    }
                });
            });
            
            function formatDuration(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }
            
            function formatNumber(num) {
                return new Intl.NumberFormat().format(num);
            }
        });
    </script>
</body>
</html> 
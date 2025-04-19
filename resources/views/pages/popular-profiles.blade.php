@extends('layouts.app')

@section('meta-tags')
    <title>Popular TikTok Profiles to Watch Anonymously | TikTok Viewer</title>
    <meta name="description" content="Discover and view popular TikTok accounts anonymously. Browse trending profiles and videos without logging in or revealing your identity to creators.">
    <meta name="keywords" content="popular tiktok profiles, top tiktok creators, trending tiktok accounts, famous tiktok users, best tiktok profiles, anonymous tiktok viewing, tiktok stars">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Popular TikTok Profiles to Watch Anonymously | TikTok Viewer">
    <meta property="og:description" content="Discover and view popular TikTok accounts anonymously. Browse trending profiles and videos without logging in.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Popular TikTok Profiles to Watch Anonymously">
    <meta name="twitter:description" content="Discover and view popular TikTok accounts anonymously without logging in.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
@endsection

@section('additional-styles')
    <style>
        .profile-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: transform 0.3s;
            height: 100%;
        }
        .profile-card:hover {
            transform: translateY(-5px);
        }
        .profile-header {
            position: relative;
            background: linear-gradient(45deg, #fe2c55, #25F4EE);
            padding: 2rem 1rem;
            text-align: center;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIj48Y2lyY2xlIGN4PSIxMDAiIGN5PSIxMDAiIHI9IjEwMCIgZmlsbD0iI2ZlMmM1NSIvPjxjaXJjbGUgY3g9IjEwMCIgY3k9IjgwIiByPSI0MCIgZmlsbD0iI2ZmZiIvPjxwYXRoIGQ9Ik0xNjAgMTcwYzAtMzMtNDAtNjAtOTAtNjBzLTkwIDI3LTkwIDYweiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==");
            background-size: cover;
            background-position: center;
        }
        .profile-info {
            padding: 1.5rem;
        }
        .profile-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .profile-username {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .profile-stats {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            margin-bottom: 1rem;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 0.8rem;
            color: #777;
        }
        .view-profile-btn {
            background-color: #fe2c55;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            width: 100%;
            transition: background-color 0.3s;
        }
        .view-profile-btn:hover {
            background-color: #e6254d;
            color: white;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Popular TikTok Profiles</h1>
            <p class="lead">Discover trending creators and view their content anonymously</p>
        </div>
    </div>

    <div class="container">
        <!-- Introduction Section -->
        <div class="content-section">
            <h2 class="mb-4">Trending TikTok Creators</h2>
            <p>Explore the most popular and influential TikTok creators on the platform. Browse their profiles and watch their content completely anonymously without signing in or revealing your identity. Simply click on any profile to view their videos and content without creating a TikTok account.</p>
        </div>
        
        <!-- Popular Profiles Grid -->
        <div class="row g-4 mb-5">
            @foreach($popularProfiles as $profile)
                <div class="col-md-6 col-lg-4">
                    <div class="profile-card">
                        <div class="profile-header">
                            <img src="{{ $profile['image'] }}" alt="{{ $profile['name'] }}" class="profile-img" onerror="this.onerror=null;">
                        </div>
                        <div class="profile-info">
                            <h3 class="profile-name">{{ $profile['name'] }}</h3>
                            
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <div class="stat-value">{{ $profile['followers'] }}</div>
                                    <div class="stat-label">Followers</div>
                                </div>
                            </div>
                            
                            <p class="mb-4">{{ $profile['description'] }}</p>
                            
                            <form action="{{ route('user.profile', ['username' => $profile['username']]) }}" method="GET">
                                <button type="submit" class="btn view-profile-btn">
                                    View Profile <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Why Follow These Creators Section -->
        <div class="content-section">
            <h2 class="mb-4">Why These Creators Are Trending</h2>
            <p>TikTok's algorithm promotes creators based on engagement, creativity, and consistency. The profiles featured above have mastered the art of creating viral content that resonates with millions of viewers worldwide. Here's what makes them stand out:</p>
            
            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <h4><i class="fas fa-star text-warning me-2"></i> Content Innovation</h4>
                        <p>These creators consistently push the boundaries with new trends, creative formats, and innovative approaches to short-form video content.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4><i class="fas fa-users text-primary me-2"></i> Community Engagement</h4>
                        <p>Top TikTok creators actively engage with their audiences through comments, duets, and trends, building loyal communities around their content.</p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="mb-4">
                        <h4><i class="fas fa-chart-line text-success me-2"></i> Consistency</h4>
                        <p>Regular posting schedules and adaptation to platform changes help these creators maintain visibility and grow their audiences over time.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4><i class="fas fa-magic text-danger me-2"></i> Authenticity</h4>
                        <p>Whether showcasing dance moves, comedy skits, or lifestyle content, these creators maintain authentic voices that resonate with viewers.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Categories Section -->
        <div class="content-section">
            <h2 class="mb-4">Popular TikTok Content Categories</h2>
            <p class="mb-4">TikTok features diverse content across many niches. The most popular categories include:</p>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-music text-danger me-2"></i> Dance & Music</h5>
                            <p class="card-text">Choreographed routines to trending songs, original dances, and music-driven content.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-laugh text-warning me-2"></i> Comedy & Skits</h5>
                            <p class="card-text">Humor-focused content including pranks, jokes, impersonations, and situational comedy.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-lightbulb text-info me-2"></i> Life Hacks & DIY</h5>
                            <p class="card-text">Quick tips, tutorials, and creative solutions for everyday problems and projects.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-utensils text-success me-2"></i> Food & Cooking</h5>
                            <p class="card-text">Recipes, cooking tutorials, food challenges, and culinary creations.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-dumbbell text-primary me-2"></i> Fitness & Health</h5>
                            <p class="card-text">Workout routines, health tips, transformation journeys, and wellness content.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-graduation-cap text-secondary me-2"></i> Educational</h5>
                            <p class="card-text">Quick facts, history lessons, science experiments, and informative content.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('schema-markup')
<!-- BreadcrumbList Schema.org Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Popular TikTok Profiles",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>

<!-- ItemList Schema.org Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "itemListElement": [
        @foreach($popularProfiles as $index => $profile)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "item": {
                "@type": "Person",
                "name": "{{ $profile['name'] }}",
                "alternateName": "@{{ $profile['username'] }}",
                "identifier": "{{ $profile['username'] }}",
                "description": "{{ $profile['description'] }}",
                "image": "{{ $profile['image'] }}",
                "url": "https://www.tiktok.com/@{{ $profile['username'] }}"
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>

<!-- WebPage Schema.org Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "headline": "Popular TikTok Profiles to Watch Anonymously",
    "description": "Discover and view popular TikTok accounts anonymously. Browse trending profiles and videos without logging in or revealing your identity to creators.",
    "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
            @foreach($popularProfiles as $index => $profile)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
                "url": "https://www.tiktok.com/@{{ $profile['username'] }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
}
</script>
@endsection 
@extends('layouts.app')

@section('meta-tags')
    <title>How TikTok Viewer Works - Anonymous TikTok Viewing Explained</title>
    <meta name="description" content="Learn how our TikTok Viewer allows you to browse TikTok profiles and videos anonymously without logging in or revealing your identity to content creators.">
    <meta name="keywords" content="how tiktok viewer works, anonymous tiktok viewing, watch tiktok without account, tiktok profile viewer tutorial, tiktok anonymously, private tiktok browsing">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="How TikTok Viewer Works - Anonymous TikTok Viewing Explained">
    <meta property="og:description" content="Learn how our TikTok Viewer allows you to browse TikTok profiles and videos anonymously without logging in or revealing your identity.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="How TikTok Viewer Works - Anonymous TikTok Viewing Explained">
    <meta name="twitter:description" content="Learn how our TikTok Viewer allows you to browse TikTok profiles and videos anonymously without logging in.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">How TikTok Viewer Works</h1>
            <p class="lead">The ultimate guide to anonymous TikTok viewing</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- How It Works Content -->
                <div class="content-section">
                    <h2 class="mb-4">Understanding Anonymous TikTok Viewing</h2>
                    <p>TikTok Viewer provides a seamless, private way to browse TikTok content without the limitations and privacy concerns of the official app. Here's everything you need to know about how our anonymous viewing service works.</p>
                    
                    <div class="mt-5">
                        <h3><i class="fas fa-shield-alt text-danger me-2"></i> Complete Privacy Protection</h3>
                        <p>When you use TikTok's official app or website, your views, interactions, and browsing behavior are tracked. Content creators can see metrics about who's viewing their content, and TikTok collects extensive data about your viewing habits.</p>
                        <p>TikTok Viewer acts as an intermediary, fetching TikTok content through our secure servers. This means:</p>
                        <ul>
                            <li class="mb-2">Your identity is never revealed to TikTok or content creators</li>
                            <li class="mb-2">No record of your profile visits appears in TikTok's analytics</li>
                            <li class="mb-2">Your viewing habits remain completely private</li>
                            <li>No TikTok account is required to access content</li>
                        </ul>
                    </div>
                    
                    <div class="mt-5">
                        <h3><i class="fas fa-cogs text-danger me-2"></i> How Our Technology Works</h3>
                        <p>Our service utilizes advanced proxy technology to securely fetch content from TikTok:</p>
                        <ol>
                            <li class="mb-2">You enter a TikTok username in our search box</li>
                            <li class="mb-2">Our secure server makes the request to TikTok's API</li>
                            <li class="mb-2">We retrieve the public profile data and video content</li>
                            <li class="mb-2">The content is displayed in our clean, ad-free interface</li>
                            <li>You browse videos, view profiles, and explore content completely anonymously</li>
                        </ol>
                        <p>This process happens in real-time, ensuring you always see the most up-to-date content from your favorite creators.</p>
                    </div>
                    
                    <div class="mt-5">
                        <h3><i class="fas fa-film text-danger me-2"></i> Features & Capabilities</h3>
                        <p>TikTok Viewer offers comprehensive access to public TikTok content:</p>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <ul>
                                    <li class="mb-2">View user profiles anonymously</li>
                                    <li class="mb-2">Watch videos without views being tracked</li>
                                    <li class="mb-2">See follower and following counts</li>
                                    <li>Browse user's video history</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li class="mb-2">View video stats (likes, comments, shares)</li>
                                    <li class="mb-2">Read profile descriptions</li>
                                    <li class="mb-2">See trending content</li>
                                    <li>All without creating an account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <h3><i class="fas fa-lock text-danger me-2"></i> Limitations & Legal Compliance</h3>
                        <p>While TikTok Viewer provides anonymous access to public content, there are some important limitations:</p>
                        <ul>
                            <li class="mb-2">We can only display <strong>publicly available</strong> content - private accounts remain private</li>
                            <li class="mb-2">We respect TikTok's Terms of Service by only accessing public API endpoints</li>
                            <li class="mb-2">We don't store or cache TikTok videos on our servers</li>
                            <li>Our service is for viewing only - you cannot interact with content (like, comment, share)</li>
                        </ul>
                        <p>These limitations ensure we provide a legal service that respects both TikTok's platform rules and user privacy.</p>
                    </div>
                </div>
                
                <!-- Getting Started Guide -->
                <div class="content-section">
                    <h2 class="mb-4">Getting Started: Step-by-Step Guide</h2>
                    <div class="row align-items-center mb-5">
                        <div class="col-md-6">
                            <h4>1. Enter a TikTok Username</h4>
                            <p>On our homepage, enter any public TikTok username in the search box. You can enter it with or without the "@" symbol (e.g., "charlidamelio" or "@charlidamelio").</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-search fa-3x text-danger mb-3"></i>
                                <p class="mb-0 text-muted">Example: "charlidamelio"</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row align-items-center mb-5">
                        <div class="col-md-6 order-md-2">
                            <h4>2. Browse the User Profile</h4>
                            <p>After searching, you'll see the user's profile information, including their profile picture, bio, follower count, and other public statistics.</p>
                        </div>
                        <div class="col-md-6 text-center order-md-1">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-user-circle fa-3x text-danger mb-3"></i>
                                <p class="mb-0 text-muted">Profile details & stats</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4>3. Watch Videos Anonymously</h4>
                            <p>Browse through the user's videos, which are displayed in a grid format. Click on any video thumbnail to play the video anonymously, with no record of your view being sent to TikTok.</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="p-3 bg-light rounded">
                                <i class="fas fa-play-circle fa-3x text-danger mb-3"></i>
                                <p class="mb-0 text-muted">Watch without being tracked</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Sidebar -->
                <div class="content-section">
                    <h3 class="mb-4">Why Choose TikTok Viewer?</h3>
                    <div class="mb-4">
                        <div class="d-flex mb-2">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <h5 class="mb-1">Complete Anonymity</h5>
                                <p class="text-muted small mb-0">View without revealing your identity</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex mb-2">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <h5 class="mb-1">No Account Required</h5>
                                <p class="text-muted small mb-0">Access content without signing up</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex mb-2">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <h5 class="mb-1">Ad-Free Experience</h5>
                                <p class="text-muted small mb-0">No distracting advertisements</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex mb-2">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <h5 class="mb-1">Clean Interface</h5>
                                <p class="text-muted small mb-0">Simple, easy-to-use design</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex mb-2">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <h5 class="mb-1">Fast & Reliable</h5>
                                <p class="text-muted small mb-0">Quick access to TikTok content</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Sidebar -->
                <div class="content-section">
                    <h3 class="mb-4">Frequently Asked Questions</h3>
                    
                    <div class="mb-4">
                        <h5>Is this service completely free?</h5>
                        <p class="text-muted small">Yes, TikTok Viewer is 100% free to use with no hidden charges.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Can I view private accounts?</h5>
                        <p class="text-muted small">No, our service can only display publicly available content.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Is this service legal?</h5>
                        <p class="text-muted small">Yes, we only access publicly available content through permitted API endpoints.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Do you store my searches?</h5>
                        <p class="text-muted small">We maintain minimal usage logs for service improvement but don't store personal data.</p>
                    </div>
                    
                    <div>
                        <h5>Does this work on mobile?</h5>
                        <p class="text-muted small mb-0">Yes, our service is fully responsive and works on all devices.</p>
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
            "name": "How It Works",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>

<!-- Article Schema.org Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "How TikTok Viewer Works - Anonymous TikTok Viewing Explained",
    "description": "Learn how our TikTok Viewer allows you to browse TikTok profiles and videos anonymously without logging in or revealing your identity to content creators.",
    "image": "{{ asset('images/tiktok-viewer-og.jpg') }}",
    "author": {
        "@type": "Organization",
        "name": "TikTok Viewer"
    },
    "publisher": {
        "@type": "Organization",
        "name": "TikTok Viewer",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "datePublished": "{{ date('Y-m-d') }}",
    "dateModified": "{{ date('Y-m-d') }}"
}
</script>
@endsection 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>TikTok Viewer - Anonymous TikTok Profile & Video Viewer | No Login Required</title>
    <meta name="description" content="Browse TikTok profiles and videos anonymously without logging in. View any TikTok creator's content privately with our free anonymous viewer - no account required.">
    <meta name="keywords" content="tiktok viewer, anonymous tiktok, tiktok profile viewer, tiktok without account, private tiktok viewing, tiktok video downloader, watch tiktok anonymously">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="TikTok Viewer - Anonymous TikTok Profile & Video Viewer">
    <meta property="og:description" content="Browse TikTok profiles and videos anonymously without logging in. View any TikTok creator's content privately with our free anonymous viewer.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="TikTok Viewer - Anonymous TikTok Profile & Video Viewer">
    <meta name="twitter:description" content="Browse TikTok profiles and videos anonymously without logging in. View any TikTok creator's content privately.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <meta name="google-analytics-id" content="{{ config('analytics.measurement_id') }}">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PTZLTK0KFQ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-PTZLTK0KFQ');
    </script>
    
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
        .search-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 3rem 1rem;
            text-align: center;
        }
        .search-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #333;
        }
        .search-subtitle {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            color: #555;
        }
        .search-box {
            position: relative;
            margin: 0 auto;
            max-width: 600px;
        }
        .search-input {
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: 2px solid #ddd;
            width: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .search-input:focus {
            border-color: #fe2c55;
            box-shadow: 0 4px 20px rgba(254, 44, 85, 0.2);
        }
        .search-button {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            padding: 0 1.5rem;
            border-radius: 0 50px 50px 0;
            border: none;
            background-color: #fe2c55;
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        .search-button:hover {
            background-color: #e6254d;
        }
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 4rem auto;
            max-width: 900px;
        }
        .feature {
            text-align: center;
            max-width: 250px;
            margin: 1rem;
        }
        .feature i {
            font-size: 3rem;
            color: #fe2c55;
            margin-bottom: 1rem;
        }
        .feature h3 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .feature p {
            color: #666;
        }
        footer {
            background-color: #f1f1f1;
            padding: 2rem 0;
            text-align: center;
            margin-top: 2rem;
        }
        .faq-section {
            margin: 3rem auto;
            max-width: 800px;
        }
        .faq-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
        }
        .accordion-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .accordion-header {
            background-color: #f8f9fa;
        }
        .accordion-button {
            font-weight: 600;
            color: #333;
        }
        .accordion-button:not(.collapsed) {
            background-color: #f0f0f0;
            color: #fe2c55;
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
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('how.it.works') }}">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('popular.profiles') }}">Popular Profiles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tiktok.tips') }}">TikTok Tips</a>
                    </li>
                </ul>
                <form class="d-flex header-search" action="{{ url('/user') }}" method="GET">
                    <input class="form-control me-2" type="search" name="username" placeholder="TikTok Username (e.g., @tiktok)">
                    <button class="btn btn-danger" type="submit">Go</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger my-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="search-container">
            <h1 class="search-title">Anonymous TikTok Profile Viewer</h1>
            <p class="search-subtitle">View any TikTok profile, stats, and videos without logging in or showing that you viewed them</p>
            
            <div class="search-box">
                <form action="{{ url('/user') }}" method="GET">
                    <input type="text" class="search-input" name="username" placeholder="Enter TikTok username (e.g., tiktok or @tiktok)" required>
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>
        </div>

        <div class="features">
            <div class="feature">
                <i class="fas fa-eye-slash"></i>
                <h3>100% Anonymous</h3>
                <p>View profiles without notifications being sent to the user.</p>
            </div>
            <div class="feature">
                <i class="fas fa-bolt"></i>
                <h3>Fast & Reliable</h3>
                <p>Get quick access to profile details and videos.</p>
            </div>
            <div class="feature">
                <i class="fas fa-lock"></i>
                <h3>No Login Required</h3>
                <p>No need to create an account or log in to view profiles.</p>
            </div>
        </div>
    </div>

    <!-- FAQ Section with Schema.org FAQ Markup -->
    <div class="container faq-section">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        How can I view TikTok profiles anonymously?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Simply enter the TikTok username in the search box above. Our service will fetch the profile information and videos without requiring you to log in to TikTok or leave any trace that you viewed the profile.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Is TikTok Viewer completely free to use?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, TikTok Viewer is 100% free to use. You don't need to create an account, provide any personal information, or pay for any services. We believe in providing open access to public content.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Can TikTok users see that I viewed their profile?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No, TikTok users cannot see that you viewed their profile when using our service. Our platform acts as an intermediary, so your identity is never revealed to the TikTok user whose profile you're viewing.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Can I view private TikTok profiles?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No, our service can only display content that is publicly available on TikTok. Private profiles and content restricted by the creator cannot be viewed through our platform or any other third-party service.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Why use TikTok Viewer instead of the TikTok app?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        TikTok Viewer offers several advantages: complete anonymity when viewing profiles, no account required, no data collection about your viewing habits, faster browsing experience without ads, and the ability to view content without installing the TikTok app.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schema.org FAQPage Markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "How can I view TikTok profiles anonymously?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Simply enter the TikTok username in the search box above. Our service will fetch the profile information and videos without requiring you to log in to TikTok or leave any trace that you viewed the profile."
                }
            },
            {
                "@type": "Question",
                "name": "Is TikTok Viewer completely free to use?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, TikTok Viewer is 100% free to use. You don't need to create an account, provide any personal information, or pay for any services. We believe in providing open access to public content."
                }
            },
            {
                "@type": "Question",
                "name": "Can TikTok users see that I viewed their profile?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No, TikTok users cannot see that you viewed their profile when using our service. Our platform acts as an intermediary, so your identity is never revealed to the TikTok user whose profile you're viewing."
                }
            },
            {
                "@type": "Question",
                "name": "Can I view private TikTok profiles?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No, our service can only display content that is publicly available on TikTok. Private profiles and content restricted by the creator cannot be viewed through our platform or any other third-party service."
                }
            },
            {
                "@type": "Question",
                "name": "Why use TikTok Viewer instead of the TikTok app?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "TikTok Viewer offers several advantages: complete anonymity when viewing profiles, no account required, no data collection about your viewing habits, faster browsing experience without ads, and the ability to view content without installing the TikTok app."
                }
            }
        ]
    }
    </script>

    <!-- WebSite Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "TikTok Viewer - Anonymous TikTok Profile & Video Viewer",
        "url": "{{ url('/') }}",
        "description": "Browse TikTok profiles and videos anonymously without logging in. View any TikTok creator's content privately with our free anonymous viewer - no account required.",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/search') }}?username={search_term}",
            "query-input": "required name=search_term"
        }
    }
    </script>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>TikTok Viewer is not affiliated with TikTok. This is a third-party application.</p>
            <p>&copy; {{ date('Y') }} TikTok Viewer. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
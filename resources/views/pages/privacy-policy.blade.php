@extends('layouts.app')

@section('meta-tags')
    <title>Privacy Policy | TikTok Viewer</title>
    <meta name="description" content="Our privacy policy outlines how we collect, use, and protect your information when using our TikTok Viewer service.">
    <meta name="robots" content="noindex, follow">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Privacy Policy | TikTok Viewer">
    <meta property="og:description" content="Our privacy policy outlines how we collect, use, and protect your information when using our TikTok Viewer service.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Privacy Policy | TikTok Viewer">
    <meta name="twitter:description" content="Our privacy policy outlines how we collect, use, and protect your information when using our TikTok Viewer service.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Privacy Policy</h1>
            <p class="lead">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <div class="container">
        <div class="content-section">
            <h2>Introduction</h2>
            <p>Welcome to TikTok Viewer. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>
            <p>This privacy policy applies to all information collected through our website, as well as any related services, sales, marketing, or events.</p>
            
            <h2>Information We Collect</h2>
            <p>When you use our service, we may collect the following types of information:</p>
            
            <h3>Information You Provide</h3>
            <ul>
                <li>TikTok usernames that you enter into our search functionality</li>
                <li>Contact information if you choose to communicate with us</li>
                <li>Feedback and correspondence, such as if you contact us</li>
            </ul>
            
            <h3>Information Automatically Collected</h3>
            <ul>
                <li><strong>Log and Usage Data:</strong> We collect information that your browser sends whenever you visit our website. This may include your computer's Internet Protocol address, browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, and other statistics.</li>
                <li><strong>Cookies and Similar Technologies:</strong> We use cookies and similar tracking technologies to track activity on our Service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier.</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <p>We use the information we collect for various purposes, including:</p>
            <ul>
                <li>To provide and maintain our Service</li>
                <li>To notify you about changes to our Service</li>
                <li>To allow you to participate in interactive features of our Service when you choose to do so</li>
                <li>To provide customer support</li>
                <li>To gather analysis or valuable information so that we can improve our Service</li>
                <li>To monitor the usage of our Service</li>
                <li>To detect, prevent and address technical issues</li>
            </ul>
            
            <h2>Third-Party Services</h2>
            <p>Our service accesses publicly available TikTok data through third-party APIs. We do not share your personal information with TikTok or other third parties except as described in this privacy policy.</p>
            <p>We use Google Analytics to help us understand how our users use the Site. You can read more about how Google uses your Personal Information <a href="https://www.google.com/intl/en/policies/privacy/" target="_blank" rel="noopener noreferrer">here</a>.</p>
            
            <h2>Ezoic Services</h2>
            <p>We use Ezoic to provide personalization and analytics services on this website. This privacy policy includes information about how Ezoic and its partners use information collected from visitors to our site.</p>
            
            <!-- Ezoic Privacy Policy Embed -->
            <span id="ezoic-privacy-policy-embed"></span>
            <!-- End Ezoic Privacy Policy Embed -->
            
            <h2>Data Security</h2>
            <p>The security of your data is important to us, but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security.</p>
            
            <h2>Your Rights</h2>
            <p>Depending on your location, you may have certain rights regarding your personal information, such as:</p>
            <ul>
                <li>The right to access the personal information we have about you</li>
                <li>The right to request that we correct or update your personal information</li>
                <li>The right to request that we delete your personal information</li>
                <li>The right to object to processing of your personal information</li>
                <li>The right to data portability</li>
                <li>The right to withdraw consent</li>
            </ul>
            
            <h2>Children's Privacy</h2>
            <p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us.</p>
            
            <h2>Changes to This Privacy Policy</h2>
            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date at the top of this Privacy Policy.</p>
            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
            
            <h2>Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us:</p>
            <ul>
                <li>By email: privacy@tiktokviewer.com</li>
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Ezoic Privacy Policy Loader Script -->
    <script>
        (function() {
            // Create script element
            var script = document.createElement('script');
            script.async = true;
            script.src = 'https://g.ezoic.net/ezoic/privacypolicyembedcode.js';
            
            // Append script to document
            document.body.appendChild(script);
        })();
    </script>
@endsection 
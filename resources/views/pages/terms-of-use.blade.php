@extends('layouts.app')

@section('meta-tags')
    <title>Terms of Use | TikTok Viewer</title>
    <meta name="description" content="The terms and conditions governing your use of the TikTok Viewer service. By using our site, you agree to these terms.">
    <meta name="robots" content="noindex, follow">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Terms of Use | TikTok Viewer">
    <meta property="og:description" content="The terms and conditions governing your use of the TikTok Viewer service. By using our site, you agree to these terms.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Terms of Use | TikTok Viewer">
    <meta name="twitter:description" content="The terms and conditions governing your use of the TikTok Viewer service. By using our site, you agree to these terms.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Terms of Use</h1>
            <p class="lead">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <div class="container">
        <div class="content-section">
            <h2>Introduction</h2>
            <p>Welcome to TikTok Viewer. These Terms of Use govern your use of our website and services. By accessing or using TikTok Viewer, you agree to be bound by these Terms of Use. If you disagree with any part of these terms, you may not access our service.</p>
            
            <h2>Definitions</h2>
            <ul>
                <li><strong>"We", "Us", "Our"</strong> refers to TikTok Viewer.</li>
                <li><strong>"You", "Your"</strong> refers to the individual accessing or using our Service, or the company, or other legal entity on behalf of which such individual is accessing or using the Service.</li>
                <li><strong>"Service"</strong> refers to the TikTok Viewer website, features, and functionality.</li>
                <li><strong>"TikTok"</strong> refers to the social media platform owned by ByteDance Ltd.</li>
            </ul>
            
            <h2>Use of Our Service</h2>
            <p>TikTok Viewer provides a service that allows users to view TikTok content without requiring a TikTok account. We are not affiliated with, endorsed by, or sponsored by TikTok/ByteDance Ltd.</p>
            
            <h3>User Obligations</h3>
            <p>By using our Service, you agree to:</p>
            <ul>
                <li>Use our Service only for lawful purposes and in accordance with these Terms</li>
                <li>Not attempt to probe, scan, or test the vulnerability of our system or network or breach any security or authentication measures</li>
                <li>Not use our Service in any way that could damage, disable, overburden, or impair it</li>
                <li>Not attempt to gain unauthorized access to any parts of the Service</li>
                <li>Not use any robot, spider, or other automated device to access our Service</li>
                <li>Not collect or harvest any information from other users of the Service</li>
            </ul>
            
            <h2>Intellectual Property</h2>
            <p>The Service and its original content (excluding content accessed through the TikTok API), features, and functionality are and will remain the exclusive property of TikTok Viewer. Our Service is protected by copyright, trademark, and other laws of both the United States and foreign countries.</p>
            <p>All content displayed through our Service using the TikTok API belongs to its respective owners. We do not claim ownership of any TikTok content displayed through our Service.</p>
            
            <h2>Disclaimers</h2>
            <p>Your use of the Service is at your sole risk. The Service is provided on an "AS IS" and "AS AVAILABLE" basis. The Service is provided without warranties of any kind, whether express or implied, including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, non-infringement, or course of performance.</p>
            <p>TikTok Viewer does not warrant that:</p>
            <ul>
                <li>The Service will function uninterrupted, secure, or available at any particular time or location</li>
                <li>Any errors or defects will be corrected</li>
                <li>The Service is free of viruses or other harmful components</li>
                <li>The results of using the Service will meet your requirements</li>
            </ul>
            
            <h2>Limitation of Liability</h2>
            <p>In no event shall TikTok Viewer, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
            <ul>
                <li>Your access to or use of or inability to access or use the Service</li>
                <li>Any conduct or content of any third party on the Service</li>
                <li>Any content obtained from the Service</li>
                <li>Unauthorized access, use, or alteration of your transmissions or content</li>
            </ul>
            
            <h2>Third-Party Services</h2>
            <p>Our Service may contain links to third-party websites or services that are not owned or controlled by TikTok Viewer. We have no control over, and assume no responsibility for, the content, privacy policies, or practices of any third-party websites or services. You further acknowledge and agree that TikTok Viewer shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with the use of or reliance on any such content, goods, or services available on or through any such websites or services.</p>
            
            <h2>Changes to Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
            
            <h2>Termination</h2>
            <p>We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
            
            <h2>Governing Law</h2>
            <p>These Terms shall be governed and construed in accordance with the laws of the United States, without regard to its conflict of law provisions.</p>
            
            <h2>Contact Us</h2>
            <p>If you have any questions about these Terms, please contact us:</p>
            <ul>
                <li>By email: terms@tiktokviewer.com</li>
            </ul>
        </div>
    </div>
@endsection 
@extends('layouts.app')

@section('meta-tags')
    <title>Legal Information | TikTok Viewer</title>
    <meta name="description" content="Legal information regarding TikTok Viewer, including copyright notices, trademark information, and legal disclaimers.">
    <meta name="robots" content="noindex, follow">
    
    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Legal Information | TikTok Viewer">
    <meta property="og:description" content="Legal information regarding TikTok Viewer, including copyright notices, trademark information, and legal disclaimers.">
    <meta property="og:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Legal Information | TikTok Viewer">
    <meta name="twitter:description" content="Legal information regarding TikTok Viewer, including copyright notices, trademark information, and legal disclaimers.">
    <meta name="twitter:image" content="{{ asset('images/tiktok-viewer-og.jpg') }}">
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Legal Information</h1>
            <p class="lead">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <div class="container">
        <div class="content-section">
            <h2>Copyright Notice</h2>
            <p>© {{ date('Y') }} TikTok Viewer. All rights reserved.</p>
            <p>All content on this website, including but not limited to text, graphics, logos, icons, images, audio clips, digital downloads, and software, is the property of TikTok Viewer or its content suppliers and is protected by international copyright laws.</p>
            <p>The compilation of all content on this site is the exclusive property of TikTok Viewer and is protected by international copyright laws. All software used on this site is the property of TikTok Viewer or its software suppliers and is protected by international copyright laws.</p>
            
            <h2>Trademark Information</h2>
            <p>TikTok Viewer™ and the TikTok Viewer logo are trademarks of TikTok Viewer.</p>
            <p>TikTok® is a registered trademark of ByteDance Ltd. TikTok Viewer is not affiliated with, endorsed by, or sponsored by TikTok or ByteDance Ltd.</p>
            <p>All other trademarks, service marks, logos, and trade names appearing on this website are the property of their respective owners.</p>
            
            <h2>DMCA Compliance</h2>
            <p>TikTok Viewer respects the intellectual property rights of others and expects users of the Service to do the same. We will respond to notices of alleged copyright infringement that comply with applicable law and are properly provided to us.</p>
            <p>If you believe that your content has been copied in a way that constitutes copyright infringement, please provide us with the following information:</p>
            <ul>
                <li>A physical or electronic signature of the copyright owner or a person authorized to act on their behalf</li>
                <li>Identification of the copyrighted work claimed to have been infringed</li>
                <li>Identification of the material that is claimed to be infringing or to be the subject of infringing activity and that is to be removed</li>
                <li>Information reasonably sufficient to permit us to contact you, such as an address, telephone number, and email address</li>
                <li>A statement by you that you have a good faith belief that use of the material in the manner complained of is not authorized by the copyright owner, its agent, or the law</li>
                <li>A statement that the information in the notification is accurate, and, under penalty of perjury, that you are authorized to act on behalf of the copyright owner</li>
            </ul>
            <p>We may remove content alleged to be infringing without prior notice and at our sole discretion. In appropriate circumstances, we will also terminate a user's account if the user is determined to be a repeat infringer.</p>
            <p>Please send copyright infringement notices to:</p>
            <p>Email: dmca@tiktokviewer.com</p>
            
            <h2>Disclaimer of Affiliation</h2>
            <p>TikTok Viewer is not affiliated with, endorsed by, or in any way officially connected with TikTok or ByteDance Ltd.</p>
            <p>The official TikTok website can be found at <a href="https://www.tiktok.com" target="_blank" rel="noopener noreferrer">https://www.tiktok.com</a>.</p>
            
            <h2>Content Disclaimer</h2>
            <p>TikTok Viewer displays publicly available content from TikTok. We do not create, own, or host any of the TikTok content displayed through our service. All TikTok content remains the property of its original creators or TikTok.</p>
            <p>TikTok Viewer does not screen or filter content from TikTok before displaying it on our service. We are not responsible for the content, accuracy, safety, or reliability of any TikTok content displayed through our service.</p>
            
            <h2>User-Generated Content</h2>
            <p>When you submit ideas, suggestions, documents, or proposals ("Contributions") to TikTok Viewer, you acknowledge and agree that:</p>
            <ul>
                <li>Your Contributions do not contain confidential or proprietary information</li>
                <li>TikTok Viewer is not under any obligation of confidentiality with respect to the Contributions</li>
                <li>TikTok Viewer may have similar ideas under consideration or development</li>
                <li>Your Contributions automatically become the property of TikTok Viewer without any compensation to you</li>
                <li>TikTok Viewer may use or redistribute the Contributions for any purpose without restriction</li>
            </ul>
            
            <h2>Contact Us</h2>
            <p>If you have any questions about these legal notices, please contact us:</p>
            <ul>
                <li>By email: legal@tiktokviewer.com</li>
            </ul>
        </div>
    </div>
@endsection 
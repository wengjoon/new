<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Test Page</h1>
    <p>This is a test page to verify that blade templates are working correctly.</p>
    
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('how.it.works') }}">How It Works</a></li>
        <li><a href="{{ route('popular.profiles') }}">Popular Profiles</a></li>
        <li><a href="{{ route('tiktok.tips') }}">TikTok Tips</a></li>
    </ul>
</body>
</html> 
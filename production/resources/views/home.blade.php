<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TikTok Viewer - Anonymous TikTok Profile Viewer</title>
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
        @if(session('error'))
            <div class="alert alert-danger my-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="search-container">
            <h1 class="search-title">Anonymous TikTok Profile Viewer</h1>
            <p class="search-subtitle">View any TikTok profile, stats, and videos without logging in or showing that you viewed them</p>
            
            <div class="search-box">
                <form action="{{ route('search') }}" method="POST">
                    @csrf
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
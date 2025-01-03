<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} - Sewa Taman</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ env('STISLA_CSS') }}">
    <link rel="stylesheet" href="{{ env('STISLA_CSS_CUSTOM') }}">

    <style>
        .hero {
            background-image: url('https://images.unsplash.com/photo-1585320806297-9794b3e4eeae');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            position: relative;
        }
        .hero::after {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>
    <div class="hero d-flex align-items-center">
        <div class="container hero-content text-center text-white">
            <h1 class="mb-4">Selamat Datang di {{ config('app.name') }}</h1>
            <p class="lead mb-5">Temukan taman yang indah untuk acara spesial Anda</p>
            
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg mr-3">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mr-3">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            @endauth
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ env('STISLA_JS') }}"></script>
</body>
</html>

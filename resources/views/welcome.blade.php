<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Sistem Penyewaan Taman</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .taman-card {
            transition: transform 0.3s;
        }

        .taman-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #6777ef;
            margin-bottom: 1rem;
        }

        .section-title {
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #6777ef;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="display-4 mb-4">Selamat Datang di {{ config('app.name') }}</h1>
            <p class="lead mb-5">Temukan dan sewa taman terbaik untuk berbagai kegiatan Anda</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg mr-3">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Masuk</a>
            @else
                <a href="{{ route('taman.index') }}" class="btn btn-primary btn-lg">Lihat Taman</a>
            @endguest
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title text-center">
                <h2>Mengapa Memilih Kami?</h2>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h4>Lokasi Strategis</h4>
                    <p class="text-muted">Taman-taman kami tersebar di lokasi strategis dan mudah dijangkau</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h4>Harga Terjangkau</h4>
                    <p class="text-muted">Nikmati fasilitas terbaik dengan harga yang terjangkau</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Proses Cepat</h4>
                    <p class="text-muted">Proses pemesanan yang cepat dan mudah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Gardens Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title text-center">
                <h2>Taman Populer</h2>
            </div>
            <div class="row">
                @foreach($tamanPopuler as $taman)
                    <div class="col-md-4 mb-4">
                        <div class="card taman-card">
                            @if($taman->gambar)
                                <img src="{{ asset('storage/' . $taman->gambar) }}" 
                                     class="card-img-top" 
                                     alt="{{ $taman->nama }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $taman->nama }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($taman->deskripsi, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary">
                                        Rp {{ number_format($taman->harga_per_hari, 0, ',', '.') }}/hari
                                    </span>
                                    @auth
                                        <a href="{{ route('taman.show', $taman->id) }}" class="btn btn-primary btn-sm">
                                            Detail
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                            Login untuk Pesan
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-0">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        // Change navbar background on scroll
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('bg-dark');
            } else {
                $('.navbar').removeClass('bg-dark');
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Sistem Penyewaan Taman</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6777ef;
            --secondary-color: #34395e;
            --accent-color: #ffa426;
            --dark-color: #191d3a;
            --light-color: #f4f6fc;
            --success-color: #47c363;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #4d4d4d;
            background-color: #fff;
            overflow-x: hidden;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #5166e7;
            border-color: #5166e7;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(103, 119, 239, 0.3);
        }
        
        .btn-outline-light {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(255, 255, 255, 0.2);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.5)), url('https://1.bp.blogspot.com/-npJlyq37qwQ/XXTOsRkDE1I/AAAAAAAADsE/0EgcFrj_TocnUd-12G7nhyATwkBQOiWYQCLcBGAs/s1600/IMG_20190908_164604.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 180px 0 150px;
            color: white;
            margin-bottom: 80px;
        }
        
        .hero .container {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 3.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 650px;
            line-height: 1.8;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        
        /* Navbar */
        .navbar {
            padding: 20px 0;
            transition: all 0.4s ease;
        }
        
        .navbar.scrolled {
            background-color: var(--dark-color) !important;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: #fff !important;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            font-size: 16px;
            padding: 10px 15px;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
        }
        
        /* Features */
        .features {
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .features::before {
            content: "";
            position: absolute;
            height: 400px;
            width: 400px;
            background: rgba(76, 175, 80, 0.05);
            border-radius: 50%;
            top: -200px;
            left: -200px;
            z-index: -1;
        }
        
        .features::after {
            content: "";
            position: absolute;
            height: 300px;
            width: 300px;
            background: rgba(76, 175, 80, 0.05);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
            z-index: -1;
        }
        
        .feature-box {
            padding: 40px 30px;
            text-align: center;
            border-radius: 15px;
            transition: all 0.4s ease;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
            border-bottom: 5px solid transparent;
        }
        
        .feature-box:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.09);
            border-bottom: 5px solid var(--primary-color);
        }
        
        .feature-icon {
            display: inline-flex;
            width: 90px;
            height: 90px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: var(--primary-color);
            font-size: 36px;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover .feature-icon {
            background: var(--primary-color);
            color: white;
            transform: rotateY(180deg);
        }
        
        .feature-title {
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        /* Taman Section */
        .taman-section {
            padding: 100px 0;
            background-color: #f8fbf8;
            position: relative;
        }
        
        .taman-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom right, #fff 0%, #fff 50%, #f8fbf8 50%, #f8fbf8 100%);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 70px;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: "";
            width: 70px;
            height: 4px;
            background: var(--primary-color);
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .section-title p {
            color: #6c757d;
            max-width: 700px;
            margin: 25px auto 0;
            font-size: 18px;
        }
        
        .taman-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .taman-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .taman-img {
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .location-badge, .capacity-badge {
            position: absolute;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .location-badge {
            top: 15px;
            left: 15px;
        }
        
        .capacity-badge {
            top: 15px;
            right: 15px;
        }
        
        .taman-title {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        
        .taman-price {
            font-weight: 700;
            font-size: 18px;
            color: var(--primary-color);
        }
        
        /* Footer */
        .footer {
            background-color: var(--dark-color);
            color: rgba(255, 255, 255, 0.7);
            padding: 50px 0 20px;
        }
        
        .footer-title {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer-links {
            list-style: none;
            padding-left: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: none;
            padding-left: 5px;
        }
        
        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
        }
        
        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--dark-color);
            color: white;
            text-decoration: none;
            transform: translateY(-3px);
        }
        
        /* Animation */
        .animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s ease;
        }
        
        .fadeIn {
            opacity: 1;
            transform: translateY(0);
        }
        
        .taman-detail {
            display: none;
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            margin-top: 10px;
        }
        
        .taman-detail.show {
            display: block;
        }
        
        .detail-item {
            margin-bottom: 10px;
        }
        
        .detail-item strong {
            display: inline-block;
            min-width: 120px;
            color: var(--dark-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo DLHKP" height="40" class="d-inline-block align-middle mr-2">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#taman">Taman</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary ml-lg-3">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero d-flex align-items-center" id="beranda">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 hero-content animate">
                    <h1 class="mb-4">Temukan Taman Ideal untuk Setiap Acara Anda</h1>
                    <p class="mb-5">Kami menawarkan beragam pilihan taman yang indah dan terawat untuk berbagai kebutuhan acara Anda. Dari pernikahan hingga gathering perusahaan, semua dalam satu platform yang mudah digunakan.</p>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg mr-3">Daftar Sekarang</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Masuk</a>
                    @else
                        <a href="{{ route('taman.index') }}" class="btn btn-primary btn-lg mr-3">Lihat Taman</a>
                        <a href="#taman" class="btn btn-outline-light btn-lg">Taman Populer</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="fitur">
        <div class="container">
            <div class="section-title">
                <h2>Kenapa Memilih Kami?</h2>
                <p>Kami menyediakan layanan terbaik untuk kebutuhan penyewaan taman Anda dengan fitur-fitur unggulan</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="feature-title">Lokasi Strategis</h3>
                        <p>Taman-taman kami terletak di lokasi strategis yang mudah diakses dari berbagai titik kota dengan area parkir yang luas.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3 class="feature-title">Harga Terjangkau</h3>
                        <p>Kami menawarkan harga yang kompetitif dan terjangkau untuk berbagai jenis taman dengan paket yang fleksibel.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">Proses Cepat</h3>
                        <p>Proses pemesanan yang cepat dan mudah dengan konfirmasi instan untuk menghemat waktu berharga Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Taman Section -->
    <section class="taman-section" id="taman">
        <div class="container">
            <div class="section-title">
                <h2>Taman Populer</h2>
                <p>Temukan taman yang paling diminati oleh pelanggan kami</p>
            </div>
            
            <div class="row">
                @foreach($tamanPopuler as $taman)
                <div class="col-md-4">
                    <div class="card taman-card">
                        @if($taman->gambar)
                            <div class="taman-img" style="background-image: url('{{ asset('storage/' . $taman->gambar) }}')">
                                <div class="capacity-badge"><i class="fas fa-users mr-1"></i>{{ $taman->kapasitas ?? '200' }} Orang</div>
                            </div>
                        @else
                            <div class="taman-img bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="taman-title">{{ $taman->nama }}</h5>
                            <p class="card-text">{{ Str::limit($taman->deskripsi, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="taman-price">{{ 'Rp ' . number_format($taman->harga_per_hari, 0, ',', '.') }}/hari</div>
                                <button class="btn btn-primary detail-btn" data-id="{{ $taman->id }}">Detail</button>
                            </div>
                            
                            <div class="taman-detail" id="detail-{{ $taman->id }}">
                                <div class="detail-item">
                                    <strong>Nama Taman:</strong> {{ $taman->nama }}
                                </div>
                                <div class="detail-item">
                                    <strong>Lokasi:</strong> {{ $taman->lokasi ?? 'Jakarta' }}
                                </div>
                                <div class="detail-item">
                                    <strong>Kapasitas:</strong> {{ $taman->kapasitas ?? '200' }} Orang
                                </div>
                                <div class="detail-item">
                                    <strong>Ukuran:</strong> {{ $taman->ukuran ?? '500' }} m²
                                </div>
                                <div class="detail-item">
                                    <strong>Harga:</strong> {{ 'Rp ' . number_format($taman->harga_per_hari, 0, ',', '.') }}/hari
                                </div>
                                <div class="detail-item">
                                    <strong>Fasilitas:</strong> 
                                    @if(is_array($taman->fasilitas))
                                        {{ implode(', ', $taman->fasilitas) }}
                                    @else
                                        {{ $taman->fasilitas ?? 'Free WiFi, Parkir, Toilet' }}
                                    @endif
                                </div>
                                <div class="detail-item mt-3">
                                    <strong>Deskripsi:</strong>
                                    <p>{{ $taman->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('taman.index') }}" class="btn btn-primary btn-lg">Lihat Semua Taman</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h3 class="footer-title">{{ config('app.name') }}</h3>
                    <p>Tempat terbaik untuk menemukan dan menyewa taman untuk berbagai acara dan kegiatan Anda.</p>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                    <h5 class="footer-title">Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#taman">Taman</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Kontak</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Jalan Basuki Rahmad No.15, Kelurahan Pocanan, Kota Kediri</li>
                        <!-- <li><i class="fas fa-phone mr-2"></i> +628883866931</li> -->
                        <li><i class="fas fa-envelope mr-2"></i> https://www.kedirikota.go.id/</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>Created with love by <a href="https://www.instagram.com/steinliejoki/" target="_blank">Owner</a></p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
                $('.back-to-top').addClass('show');
            } else {
                $('.navbar').removeClass('scrolled');
                $('.back-to-top').removeClass('show');
            }
        });
        
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate(
                {
                    scrollTop: $($(this).attr('href')).offset().top - 70,
                },
                500,
                'linear'
            );
        });
        
        // Back to top
        $('.back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 500);
            return false;
        });
        
        // Animation on scroll
        function animateOnScroll() {
            $('.animate').each(function() {
                var elementPos = $(this).offset().top;
                var topOfWindow = $(window).scrollTop();
                var windowHeight = $(window).height();
                
                if (elementPos < topOfWindow + windowHeight - 50) {
                    $(this).addClass('fadeIn');
                }
            });
        }
        
        $(window).on('scroll', animateOnScroll);
        $(window).on('load', animateOnScroll);
        
        // Toggle detail taman
        $('.detail-btn').on('click', function() {
            var tamanId = $(this).data('id');
            $('#detail-' + tamanId).toggleClass('show');
            
            // Ganti teks tombol
            if ($('#detail-' + tamanId).hasClass('show')) {
                $(this).text('Tutup');
            } else {
                $(this).text('Detail');
            }
        });
    </script>
</body>
</html>

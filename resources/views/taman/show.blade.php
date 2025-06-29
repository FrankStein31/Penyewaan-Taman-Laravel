@extends('layouts.app')

@section('title', 'Detail Taman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Detail Taman') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div id="carouselTamanFotos" class="carousel slide" data-bs-ride="carousel">
                                <!-- Indikator -->
                                @if($taman->fotos->count() > 1)
                                    <div class="carousel-indicators">
                                        @foreach($taman->fotos as $index => $foto)
                                            <button type="button" data-bs-target="#carouselTamanFotos" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="carousel-inner">
                                    @forelse($taman->fotos as $index => $foto)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $foto->foto) }}" class="d-block w-100" alt="Foto Taman" style="height: 400px; object-fit: cover;">
                                        </div>
                                    @empty
                                        <div class="carousel-item active">
                                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                                                <p class="text-muted">Tidak ada foto</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                @if($taman->fotos->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselTamanFotos" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselTamanFotos" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8">
                            <h2>{{ $taman->nama }}</h2>
                            <p class="text-muted mb-4">{{ $taman->lokasi }}</p>

                            <h5>Deskripsi</h5>
                            <p>{{ $taman->deskripsi }}</p>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Kapasitas</h5>
                                    <p>{{ number_format($taman->kapasitas) }} orang</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Harga per Hari</h5>
                                    <p>Rp {{ number_format($taman->harga_per_hari, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <h5>Fasilitas</h5>
                            <div class="row mb-4">
                                @foreach($fasilitas as $f)
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-fasilitas h-100">
                                            @if($f->foto)
                                                <img src="{{ asset('storage/' . $f->foto) }}" class="card-img-top" alt="{{ $f->nama_fasilitas }}">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    <h6 class="card-title mb-0">{{ $f->nama_fasilitas }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                @if(auth()->check())
                                    <a href="{{ route('pemesanan.create', ['taman' => $taman->id]) }}" class="btn btn-primary">
                                        {{ __('Pesan Sekarang') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                        {{ __('Login untuk Memesan') }}
                                    </a>
                                @endif
                                <a href="{{ route('welcome') }}" class="btn btn-secondary">
                                    {{ __('Kembali') }}
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Thumbnail Foto</h5>
                                    <div class="row">
                                        @forelse($taman->fotos as $index => $foto)
                                            <div class="col-6 mb-3">
                                                <a href="javascript:void(0)" class="thumbnail-link" data-bs-target="#carouselTamanFotos" data-bs-slide-to="{{ $index }}">
                                                    <img src="{{ asset('storage/' . $foto->foto) }}" class="img-thumbnail" alt="Thumbnail" style="height: 100px; object-fit: cover;">
                                                </a>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-muted">Tidak ada foto</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.carousel {
    position: relative;
}
.carousel-item img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}
.carousel-indicators {
    margin-bottom: 0;
}
.carousel-indicators [data-bs-target] {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
}
.carousel-indicators .active {
    background-color: #fff;
}
.carousel-control-prev,
.carousel-control-next {
    width: 10%;
    opacity: 0.8;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 1.5rem;
}
.thumbnail-link {
    cursor: pointer;
    display: block;
}
.thumbnail-link img {
    transition: opacity 0.3s;
    width: 100%;
    height: 100px;
    object-fit: cover;
}
.thumbnail-link:hover img {
    opacity: 0.8;
}

/* Style untuk card fasilitas */
.card-fasilitas {
    transition: transform 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.card-fasilitas:hover {
    transform: translateY(-5px);
}
.card-fasilitas .card-img-top {
    height: 150px;
    object-fit: cover;
}
.card-fasilitas .card-body {
    padding: 1rem;
}
.card-fasilitas .fas {
    color: #28a745;
}
.card-fasilitas .card-title {
    font-size: 1rem;
    margin-bottom: 0;
    color: #333;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi carousel
    var myCarousel = document.getElementById('carouselTamanFotos');
    var carousel = new bootstrap.Carousel(myCarousel, {
        interval: 5000,
        wrap: true,
        touch: true
    });

    // Event listener untuk thumbnail
    document.querySelectorAll('.thumbnail-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var slideIndex = this.getAttribute('data-bs-slide-to');
            carousel.to(parseInt(slideIndex));
        });
    });
});
</script>
@endpush
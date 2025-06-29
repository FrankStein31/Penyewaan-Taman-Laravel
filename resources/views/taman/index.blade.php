@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Manajemen Taman' : 'Daftar Taman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Taman') }}</span>
                    @if(auth()->user()->isAdmin())
                            <a href="{{ route('taman.create') }}" class="btn btn-primary">
                            {{ __('Tambah Taman') }}
                            </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        @forelse($taman as $item)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div id="carousel{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                                        @if($item->fotos->count() > 1)
                                            <div class="carousel-indicators">
                                                @foreach($item->fotos as $index => $foto)
                                                    <button type="button" data-bs-target="#carousel{{ $item->id }}" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                            </div>
                                    @endif

                                        <div class="carousel-inner">
                                            @forelse($item->fotos as $index => $foto)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $foto->foto) }}" class="d-block w-100" alt="Foto Taman" style="height: 200px; object-fit: cover;">
                                                </div>
                                            @empty
                                                <div class="carousel-item active">
                                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                        <p class="text-muted">Tidak ada foto</p>
                        </div>
                    </div>
                                            @endforelse
                            </div>

                                        @if($item->fotos->count() > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $item->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $item->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->nama }}</h5>
                                        <p class="card-text text-muted mb-2">{{ $item->lokasi }}</p>
                                        <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Fasilitas:</small>
                                            <div class="mt-1">
                                                @foreach($item->fasilitas as $fasilitas)
                                                    <span class="badge bg-info me-1">{{ $fasilitas }}</span>
                                                @endforeach
                                </div>
                            </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Tanggal Sudah Dipesan:</small>
                                            <ul class="mb-0" style="font-size: 0.95em;">
                                                @forelse(($bookedDates[$item->id] ?? []) as $b)
                                                    <li>
                                                        @if($b->tanggal_mulai == $b->tanggal_selesai)
                                                            {{ date('d-m-Y', strtotime($b->tanggal_mulai)) }}
                                                        @else
                                                            {{ date('d-m-Y', strtotime($b->tanggal_mulai)) }} s/d {{ date('d-m-Y', strtotime($b->tanggal_selesai)) }}
                                                        @endif
                                                    </li>
                                                @empty
                                                    <li class="text-success">Belum ada yang dipesan</li>
                                                @endforelse
                                            </ul>
                            </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Kapasitas:</small>
                                                <br>
                                                <strong>{{ number_format($item->kapasitas) }} orang</strong>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">Harga per Hari:</small>
                                                <br>
                                                <strong>Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>

                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('taman.show', $item->id) }}" class="btn btn-info btn-sm">
                                                    {{ __('Detail') }}
                                                </a>
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('taman.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        {{ __('Edit') }}
                                                </a>
                                                    <form action="{{ route('taman.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus taman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                            {{ __('Hapus') }}
                                                    </button>
                                                </form>
                    @else
                                                    <a href="{{ route('pemesanan.create', ['taman' => $item->id]) }}" class="btn btn-primary btn-sm">
                                                        {{ __('Pesan') }}
                                                    </a>
                                            @endif
                                            </div>
                                            <span class="badge {{ $item->status ? 'bg-success' : 'bg-warning' }}">
                                                {{ $item->status ? 'Tersedia' : 'Tersedia dan Sedang Dipesan' }}
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    {{ __('Tidak ada data taman') }}
                                    </div>
                                </div>
                            @endforelse
                        </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $taman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6777ef;
        border-color: #6777ef;
        color: #fff;
        padding: 0 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }
    .card-taman {
        transition: transform .2s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,.1);
    }
    .card-taman:hover {
        transform: translateY(-5px);
    }
    .img-cover {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        color: white;
        font-size: 12px;
    }
    .card-info {
        margin: 15px 0;
    }
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .info-item i {
        width: 20px;
        margin-right: 8px;
    }
    .gallery {
        position: relative;
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 4px;
    }
    .page-item {
        margin: 0 2px;
    }
    .page-item.active .page-link {
        background-color: #6777ef;
        border-color: #6777ef;
        color: #fff;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #6777ef;
        background-color: #fff;
        border: 1px solid #dee2e6;
        min-width: 35px;
        text-align: center;
    }
    .page-link:hover {
        z-index: 2;
        color: #fff;
        text-decoration: none;
        background-color: #6777ef;
        border-color: #6777ef;
    }
    .page-link:focus {
        z-index: 3;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
    }
    @media (max-width: 768px) {
        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .text-muted {
            text-align: center;
            margin-bottom: 1rem;
        }
    }
    .carousel {
        position: relative;
    }
    .carousel-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .carousel-indicators {
        margin-bottom: 0;
    }
    .carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        border: none;
    }
    .carousel-indicators .active {
        background-color: #fff;
    }
    .carousel-control-prev,
    .carousel-control-next {
        width: 15%;
        opacity: 0.8;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi semua carousel
    document.querySelectorAll('.carousel').forEach(function(carouselEl) {
        new bootstrap.Carousel(carouselEl, {
            interval: 5000,
            wrap: true,
            touch: true
        });
    });
});
</script>
@endpush
@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Manajemen Taman' : 'Daftar Taman')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ auth()->user()->isAdmin() ? 'Manajemen Taman' : 'Daftar Taman Tersedia' }}</h4>
                    @if(auth()->user()->isAdmin())
                        <div class="card-header-action">
                            <a href="{{ route('taman.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Taman
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form id="perPageForm" action="{{ route('taman.index') }}" method="GET" class="form-inline">
                                @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $arrayValue)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                
                                <label class="mr-2">Tampilkan</label>
                                <select class="form-control form-control-sm" name="per_page" onchange="document.getElementById('perPageForm').submit()">
                                    @foreach([5, 10, 25, 50] as $perPage)
                                        <option value="{{ $perPage }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                                            {{ $perPage }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="ml-2">data per halaman</label>
                            </form>
                        </div>
                    </div>

                    @if(!auth()->user()->isAdmin())
                    <form action="{{ route('taman.index') }}" method="GET" id="filterForm" style="background: #ffffff; padding: 25px; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); margin-bottom: 30px;">
                        <div class="row">
                            <!-- Search Name/Location -->
                            <div class="col-md-4 mb-4">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #2c3e50;">
                                        <i class="fas fa-search" style="margin-right: 8px; color: #3498db;"></i>Cari Nama/Lokasi
                                    </label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="search" 
                                        placeholder="Cari nama atau lokasi taman..." 
                                        value="{{ request('search') }}"
                                        style="padding: 12px; border: 1px solid #e1e8ee; border-radius: 8px; width: 100%; transition: all 0.3s; font-size: 14px;">
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="col-md-4 mb-4">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #2c3e50;">
                                        <i class="fas fa-tag" style="margin-right: 8px; color: #3498db;"></i>Range Harga per Hari
                                    </label>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <input type="number" 
                                            class="form-control" 
                                            name="harga_min" 
                                            placeholder="Min" 
                                            value="{{ request('harga_min') }}"
                                            style="padding: 12px; border: 1px solid #e1e8ee; border-radius: 8px; width: 100%; transition: all 0.3s; font-size: 14px;">
                                        <span style="color: #7f8c8d; font-weight: bold;">-</span>
                                        <input type="number" 
                                            class="form-control" 
                                            name="harga_max" 
                                            placeholder="Max" 
                                            value="{{ request('harga_max') }}"
                                            style="padding: 12px; border: 1px solid #e1e8ee; border-radius: 8px; width: 100%; transition: all 0.3s; font-size: 14px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Minimum Capacity -->
                            <div class="col-md-4 mb-4">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #2c3e50;">
                                        <i class="fas fa-users" style="margin-right: 8px; color: #3498db;"></i>Kapasitas Minimal
                                    </label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="kapasitas_min" 
                                        placeholder="Minimal kapasitas..." 
                                        value="{{ request('kapasitas_min') }}"
                                        style="padding: 12px; border: 1px solid #e1e8ee; border-radius: 8px; width: 100%; transition: all 0.3s; font-size: 14px;">
                                </div>
                            </div>

                            <!-- Facilities -->
                            <div class="col-md-12 mb-4">
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 15px; font-weight: 500; color: #2c3e50;">
                                        <i class="fas fa-box" style="margin-right: 8px; color: #3498db;"></i>Fasilitas
                                    </label>
                                    <div class="row" style="margin: 0 -10px;">
                                        @foreach($allFasilitas as $fasilitas)
                                            <div class="col-md-3 mb-3">
                                                <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; transition: all 0.3s;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" 
                                                            class="custom-control-input" 
                                                            name="fasilitas[]"
                                                            id="fasilitas-{{ $loop->index }}"
                                                            value="{{ $fasilitas }}"
                                                            {{ in_array($fasilitas, (array)request('fasilitas')) ? 'checked' : '' }}
                                                            style="margin-right: 8px;">
                                                        <label class="custom-control-label" 
                                                            for="fasilitas-{{ $loop->index }}"
                                                            style="color: #2c3e50; font-size: 14px;">
                                                            {{ $fasilitas }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-md-12">
                                <div style="display: flex; gap: 15px;">
                                    <button type="submit" 
                                            style="padding: 12px 25px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ route('taman.index') }}" 
                                    style="padding: 12px 25px; background: #95a5a6; color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Taman</th>
                                        <th>Lokasi</th>
                                        <th>Kapasitas</th>
                                        <th>Biaya Retribusi Fasilitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taman as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($t->gambar)
                                                    <img src="{{ Storage::url($t->gambar) }}" 
                                                         alt="{{ $t->nama }}"
                                                         width="100"
                                                         class="img-thumbnail">
                                                @else
                                                    <span class="badge badge-secondary">Tidak ada gambar</span>
                                                @endif
                                            </td>
                                            <td>{{ $t->nama }}</td>
                                            <td>{{ $t->lokasi }}</td>
                                            <td>{{ number_format($t->kapasitas) }} orang</td>
                                            <td>Rp {{ number_format($t->harga_per_hari, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $t->status ? 'success' : 'danger' }}">
                                                    {{ $t->status ? 'Tersedia' : 'Tidak Tersedia' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('taman.show', $t->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('taman.edit', $t->id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('taman.destroy', $t->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus taman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            @forelse($taman as $t)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                                    <div class="card h-100 card-taman">
                                        <div class="gallery">
                                            @if($t->gambar)
                                                <div class="gallery-item" data-title="{{ $t->nama }}">
                                                    <img src="{{ Storage::url($t->gambar) }}" 
                                                         alt="{{ $t->nama }}"
                                                         class="img-cover">
                                                </div>
                                            @else
                                                <div class="gallery-item">
                                                    <img src="{{ asset('assets/img/no-image.jpg') }}" 
                                                         alt="No Image"
                                                         class="img-cover">
                                                </div>
                                            @endif
                                            @if($t->status)
                                                <div class="status-badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Tersedia
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-dark">{{ $t->nama }}</h5>
                                            <div class="card-info">
                                                <div class="info-item">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                    <span>{{ $t->lokasi }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-users text-info"></i>
                                                    <span>{{ number_format($t->kapasitas) }} orang</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-money-bill text-success"></i>
                                                    <span>Rp {{ number_format($t->harga_per_hari, 0, ',', '.') }}/hari</span>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-list-ul text-warning"></i>
                                                    <span>{{ is_array($t->fasilitas) ? implode(', ', $t->fasilitas) : $t->fasilitas }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 text-center">
                                                <a href="{{ route('taman.show', $t->id) }}" 
                                                   class="btn btn-light btn-icon icon-left mr-2">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('pemesanan.create', ['taman' => $t->id]) }}" 
                                                   class="btn btn-primary btn-icon icon-left">
                                                    <i class="fas fa-calendar-plus"></i> Pesan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        Tidak ada taman yang tersedia saat ini
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-6">
                            <p class="text-muted">Menampilkan {{ $taman->firstItem() ?? 0 }} sampai {{ $taman->lastItem() ?? 0 }} dari {{ $taman->total() }} data</p>
                        </div>
                        <div class="col-lg-6">
                            <nav aria-label="Page navigation" class="float-right">
                                @if ($taman->hasPages())
                                    <ul class="pagination mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($taman->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $taman->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        @foreach ($taman->getUrlRange(1, $taman->lastPage()) as $page => $url)
                                            @if ($page == $taman->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($taman->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $taman->nextPageUrl() }}" rel="next">›</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">›</span>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </nav>
                        </div>
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
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush
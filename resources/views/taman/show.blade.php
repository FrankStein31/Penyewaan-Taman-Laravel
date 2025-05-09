@extends('layouts.app')

@section('title', 'Detail Taman')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Taman</h4>
                    <div class="card-header-action">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('taman.edit', $taman->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @else
                            <a href="{{ route('pemesanan.create', ['taman' => $taman->id]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Pesan Sekarang
                            </a>
                        @endif
                        <a href="{{ route('taman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($taman->gambar)
                                <div class="chocolat-parent mb-4">
                                    <a href="{{ Storage::url($taman->gambar) }}" 
                                       class="chocolat-image" 
                                       title="{{ $taman->nama }}">
                                        <img src="{{ Storage::url($taman->gambar) }}" 
                                             alt="{{ $taman->nama }}"
                                             class="img-fluid rounded">
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    Tidak ada gambar tersedia
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th style="width: 200px">Nama Taman</th>
                                    <td>{{ $taman->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>{{ $taman->lokasi }}</td>
                                </tr>
                                <tr>
                                    <th>Kapasitas</th>
                                    <td>{{ number_format($taman->kapasitas) }} orang</td>
                                </tr>
                                <tr>
                                    <th>Biaya Retribusi Fasilitas</th>
                                    <td>Rp {{ number_format($taman->harga_per_hari, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $taman->status ? 'success' : 'danger' }}">
                                            {{ $taman->status ? 'Tersedia' : 'Tidak Tersedia' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fasilitas</th>
                                    <td>
                                        @foreach($taman->fasilitas as $fasilitas)
                                            <span class="badge badge-info mr-1">{{ $fasilitas }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">Deskripsi:</h6>
                            <p class="text-justify">{{ $taman->deskripsi }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="mb-3">Riwayat Pemesanan:</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pemesan</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status</th>
                                                <th>Total Bayar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($taman->pemesanan()->latest()->get() as $pemesanan)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $pemesanan->user->name }}</td>
                                                    <td>{{ $pemesanan->tanggal_mulai->format('d/m/Y') }}</td>
                                                    <td>{{ $pemesanan->tanggal_selesai->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ 
                                                            $pemesanan->status === 'pending' ? 'warning' :
                                                            ($pemesanan->status === 'disetujui' ? 'success' :
                                                            ($pemesanan->status === 'ditolak' ? 'danger' : 'info'))
                                                        }}">
                                                            {{ ucfirst($pemesanan->status) }}
                                                        </span>
                                                    </td>
                                                    <td>Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                                                    <td>
                                                        <a href="{{ route('pemesanan.show', $pemesanan->id) }}" 
                                                           class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Belum ada pemesanan</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chocolat@1.0.4/dist/css/chocolat.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chocolat@1.0.4/dist/js/chocolat.min.js"></script>
<script>
$(document).ready(function() {
    Chocolat(document.querySelectorAll('.chocolat-parent .chocolat-image'))
});
</script>
@endpush 
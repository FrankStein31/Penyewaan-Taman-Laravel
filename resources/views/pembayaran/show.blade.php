@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-money-bill-wave text-primary mr-2"></i>
                            Detail Pembayaran
                        </h4>
                        <div class="card-header-action">
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge badge-lg badge-{{ 
                            $pembayaran->status == 'pending' ? 'warning' :
                            ($pembayaran->status == 'diverifikasi' ? 'success' : 'danger')
                        }} px-4 py-2" style="font-size: 1rem;">
                            <i class="fas fa-{{ 
                                $pembayaran->status == 'pending' ? 'clock' :
                                ($pembayaran->status == 'diverifikasi' ? 'check-circle' : 'times-circle')
                            }} mr-2"></i>
                            {{ $pembayaran->status == 'pending' ? 'Menunggu Verifikasi' :
                               ($pembayaran->status == 'diverifikasi' ? 'Terverifikasi' : 'Ditolak') }}
                        </span>
                    </div>
                    
                    <div class="row">
                        <!-- Informasi Pembayaran -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-receipt text-primary mr-2"></i>
                                        Informasi Pembayaran
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0" width="40%">ID Pembayaran</th>
                                            <td class="text-right">{{ $pembayaran->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Jumlah Dibayar</th>
                                            <td class="text-right font-weight-bold text-primary">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Pembayaran</th>
                                            <td class="text-right">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Metode Pembayaran</th>
                                            <td class="text-right">
                                                @if($pembayaran->payment_type)
                                                    {{ ucfirst(str_replace('_', ' ', $pembayaran->payment_type)) }}
                                                @else
                                                    Transfer Manual
                                                @endif
                                            </td>
                                        </tr>
                                        @if($pembayaran->status == 'ditolak' && $pembayaran->catatan)
                                        <tr>
                                            <th class="pl-0">Catatan</th>
                                            <td class="text-right text-danger">{{ $pembayaran->catatan }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bukti Pembayaran -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-image text-primary mr-2"></i>
                                        Bukti Pembayaran
                                    </h5>
                                    <div class="text-center chocolat-parent">
                                        @if($pembayaran->bukti_pembayaran)
                                            <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" class="chocolat-image">
                                                <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                                    class="img-fluid rounded shadow-sm" 
                                                    style="max-height: 200px" alt="Bukti Pembayaran">
                                            </a>
                                            <small class="d-block mt-2 text-muted">Klik gambar untuk memperbesar</small>
                                        @elseif($pembayaran->payment_type)
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Pembayaran via {{ ucfirst(str_replace('_', ' ', $pembayaran->payment_type)) }} (Midtrans)
                                            </div>
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-image mr-2"></i>
                                                Tidak ada bukti pembayaran
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Pemesanan -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-clipboard-list text-primary mr-2"></i>
                                Detail Pemesanan
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0" width="40%">Kode Pemesanan</th>
                                            <td class="text-right font-weight-bold">{{ $pembayaran->pemesanan->kode }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Taman</th>
                                            <td class="text-right">{{ $pembayaran->pemesanan->taman->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Mulai</th>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($pembayaran->pemesanan->waktu_mulai)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Selesai</th>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($pembayaran->pemesanan->waktu_selesai)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0" width="40%">Keperluan</th>
                                            <td class="text-right">{{ $pembayaran->pemesanan->keperluan }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Jumlah Orang</th>
                                            <td class="text-right">{{ $pembayaran->pemesanan->jumlah_orang }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Total Harga</th>
                                            <td class="text-right font-weight-bold text-primary">Rp {{ number_format($pembayaran->pemesanan->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Status Pemesanan</th>
                                            <td class="text-right">
                                                <span class="badge badge-{{ 
                                                    $pembayaran->pemesanan->status == 'pending' ? 'warning' :
                                                    ($pembayaran->pemesanan->status == 'disetujui' ? 'success' :
                                                    ($pembayaran->pemesanan->status == 'ditolak' ? 'danger' : 
                                                    ($pembayaran->pemesanan->status == 'dibayar' ? 'info' : 'secondary')))
                                                }}">
                                                    {{ ucfirst($pembayaran->pemesanan->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Verifikasi Pembayaran (Admin) -->
                    @if(auth()->user()->isAdmin() && $pembayaran->status == 'pending')
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-check-circle text-primary mr-2"></i>
                                Verifikasi Pembayaran
                            </h5>
                            <form action="{{ route('pembayaran.verifikasi', $pembayaran) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" required>
                                                <option value="diverifikasi">Terima Pembayaran</option>
                                                <option value="ditolak">Tolak Pembayaran</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Catatan (wajib jika ditolak)</label>
                                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i> Proses Verifikasi
                                    </button>
                                </div>
                            </form>
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
    Chocolat(document.querySelectorAll('.chocolat-parent .chocolat-image'));
});
</script>
@endpush 


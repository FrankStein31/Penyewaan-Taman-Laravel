@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pembayaran</h4>
                    <div class="card-header-action">
                        <a href="{{ route('pemesanan.show', $pembayaran->pemesanan_id) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Pemesanan
                        </a>
                    </div>
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Informasi Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th style="width: 200px">Status</th>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $pembayaran->status === 'pending' ? 'warning' :
                                                    ($pembayaran->status === 'diverifikasi' ? 'success' : 'danger')
                                                }}">
                                                    {{ ucfirst($pembayaran->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>ID Pemesanan</th>
                                            <td>#{{ $pembayaran->pemesanan_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Upload</th>
                                            <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah</th>
                                            <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        @if($pembayaran->catatan)
                                            <tr>
                                                <th>Catatan</th>
                                                <td>{{ $pembayaran->catatan }}</td>
                                            </tr>
                                        @endif
                                    </table>

                                    @if(auth()->user()->isAdmin() && $pembayaran->status === 'pending')
                                        <hr>
                                        <form action="{{ route('pembayaran.verifikasi', $pembayaran->id) }}" 
                                              method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Update Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="diverifikasi">Verifikasi</option>
                                                    <option value="ditolak">Tolak</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan (wajib jika ditolak)</label>
                                                <textarea name="catatan" class="form-control"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                Update Status
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Bukti Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chocolat-parent">
                                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                           class="chocolat-image" 
                                           title="Bukti Pembayaran">
                                            <img alt="Bukti Pembayaran" 
                                                 src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                                 class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                           class="btn btn-primary" 
                                           download>
                                            <i class="fas fa-download"></i> Download Bukti Pembayaran
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Detail Pemesanan</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th style="width: 150px">Taman</th>
                                            <td>{{ $pembayaran->pemesanan->taman->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pemesan</th>
                                            <td>{{ $pembayaran->pemesanan->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Sewa</th>
                                            <td>
                                                {{ $pembayaran->pemesanan->tanggal_mulai->format('d/m/Y') }} - 
                                                {{ $pembayaran->pemesanan->tanggal_selesai->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status Pemesanan</th>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $pembayaran->pemesanan->status === 'pending' ? 'warning' :
                                                    ($pembayaran->pemesanan->status === 'disetujui' ? 'success' :
                                                    ($pembayaran->pemesanan->status === 'ditolak' ? 'danger' : 'info'))
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
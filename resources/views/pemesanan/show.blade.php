@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pemesanan</h4>
                    <div class="card-header-action">
                        @if(auth()->user()->isAdmin() && $pemesanan->status === 'pending')
                            <form action="{{ route('pemesanan.approve', $pemesanan->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pemesanan ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('pemesanan.reject', $pemesanan->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menolak pemesanan ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
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
                            <table class="table">
                                <tr>
                                    <th style="width: 200px">Kode Pemesanan</th>
                                    <td>{{ $pemesanan->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $pemesanan->status === 'pending' ? 'warning' :
                                            ($pemesanan->status === 'disetujui' ? 'success' :
                                            ($pemesanan->status === 'ditolak' ? 'danger' : 'info'))
                                        }}">
                                            {{ ucfirst($pemesanan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pemesanan</th>
                                    <td>{{ $pemesanan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Pemesan</th>
                                    <td>{{ $pemesanan->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Taman</th>
                                    <td>{{ $pemesanan->taman->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>{{ $pemesanan->taman->lokasi }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th style="width: 200px">Tanggal Mulai</th>
                                    <td>{{ $pemesanan->tanggal_mulai->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ $pemesanan->tanggal_selesai->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Hari</th>
                                    <td>{{ $pemesanan->total_hari }} hari</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Orang</th>
                                    <td>{{ number_format($pemesanan->jumlah_orang) }} orang</td>
                                </tr>
                                <tr>
                                    <th>Harga per Hari</th>
                                    <td>Rp {{ number_format($pemesanan->taman->harga_per_hari, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Bayar</th>
                                    <td>Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-dark">Keperluan:</h6>
                            <p class="text-muted">{{ $pemesanan->keperluan }}</p>
                        </div>
                    </div>

                    @if($pemesanan->catatan)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-dark">Catatan Admin:</h6>
                                <p class="text-muted">{{ $pemesanan->catatan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
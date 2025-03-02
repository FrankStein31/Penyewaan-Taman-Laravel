@extends('layouts.app')

@section('title', 'Daftar Pemesanan')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Pemesanan</h4>
                    @if(!auth()->user()->isAdmin())
                        <div class="card-header-action">
                            <a href="{{ route('taman.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Pemesanan
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    @if(auth()->user()->isAdmin())
                                        <th>Pemesan</th>
                                    @endif
                                    <th>Taman</th>
                                    <th>Tanggal</th>
                                    <th>Total Hari</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemesanan as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->kode }}</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{ $p->user->name }}</td>
                                        @endif
                                        <td>{{ $p->taman->nama }}</td>
                                        <td>
                                            {{ $p->waktu_mulai->format('d/m/Y H:i') }} - 
                                            {{ $p->waktu_selesai->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            @if($p->total_jam >= 24)
                                                {{ $p->total_hari }} hari
                                            @else
                                                {{ $p->total_jam }} jam
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $p->status === 'pending' ? 'warning' :
                                                ($p->status === 'disetujui' ? 'success' :
                                                ($p->status === 'selesai' ? 'success' :
                                                ($p->status === 'ditolak' ? 'danger' :
                                                ($p->status === 'dibayar' ? 'info' : 'secondary'))))
                                            }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $pembayaran = $p->pembayaran()->latest()->first();
                                            @endphp
                                            
                                            @if(auth()->user()->isAdmin())
                                                @if(!$pembayaran && $p->status == 'disetujui')
                                                    <span class="badge bg-danger text-white">Belum Dibayar</span>
                                                @elseif($pembayaran)
                                                    @if($pembayaran->status == 'pending')
                                                        <span class="badge bg-warning text-white">Menunggu Verifikasi</span>
                                                    @elseif($pembayaran->status == 'diverifikasi')
                                                        <span class="badge bg-success text-white">Lunas</span>
                                                    @elseif($pembayaran->status == 'ditolak')
                                                        <span class="badge bg-danger text-white">Pembayaran Ditolak</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary text-white">-</span>
                                                @endif
                                            @else
                                                @if(!$pembayaran && $p->status == 'disetujui')
                                                    <a href="{{ route('pembayaran.create', $p) }}" class="btn btn-sm btn-primary">Bayar Sekarang</a>
                                                @elseif($pembayaran)
                                                    @if($pembayaran->status == 'pending')
                                                        <span class="badge bg-warning text-white">Menunggu Verifikasi</span>
                                                    @elseif($pembayaran->status == 'diverifikasi')
                                                        <span class="badge bg-success text-white">Terverifikasi</span>
                                                    @elseif($pembayaran->status == 'ditolak')
                                                        <span class="badge bg-danger text-white">Ditolak</span>
                                                        <a href="{{ route('pembayaran.create', $p) }}" class="btn btn-sm btn-primary">Bayar Ulang</a>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary text-white">-</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pemesanan.show', $p->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($p->status === 'pending' && !auth()->user()->isAdmin())
                                                <form action="{{ route('pemesanan.destroy', $p->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isAdmin() ? '9' : '8' }}" class="text-center">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="float-right">
                        {{ $pemesanan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
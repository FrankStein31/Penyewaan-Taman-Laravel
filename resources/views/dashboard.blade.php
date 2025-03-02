@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
    <div class="row">
        @if(auth()->user()->isAdmin())
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total User</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalUser }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Taman</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalTaman }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pemesanan</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalPemesanan }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pemesanan Pending</h4>
                    </div>
                    <div class="card-body">
                        {{ $pemesananPending }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Pemesanan Terbaru</h4>
                    <div class="card-header-action">
                        <a href="{{ route('pemesanan.index') }}" class="btn btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    @if(auth()->user()->isAdmin())
                                        <th>Pemesan</th>
                                    @endif
                                    <th>Taman</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemesananTerbaru as $pemesanan)
                                    <tr>
                                        <td>{{ $pemesanan->kode }}</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{ $pemesanan->user->name }}</td>
                                        @endif
                                        <td>{{ $pemesanan->taman->nama }}</td>
                                        <td>
                                            {{ $pemesanan->tanggal_mulai->format('d/m/Y') }} - 
                                            {{ $pemesanan->tanggal_selesai->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $pemesanan->status === 'pending' ? 'warning' :
                                                ($pemesanan->status === 'disetujui' ? 'success' :
                                                ($pemesanan->status === 'ditolak' ? 'danger' : 'info'))
                                            }}">
                                                {{ ucfirst($pemesanan->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pemesanan.show', $pemesanan->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isAdmin() ? '6' : '5' }}" class="text-center">
                                            Tidak ada pemesanan terbaru
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(!auth()->user()->isAdmin())
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Pemesanan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center mb-4">
                                    <div class="text-warning h2">{{ $pemesananPending }}</div>
                                    <div class="text-muted">Pending</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center mb-4">
                                    <div class="text-success h2">{{ $pemesananDisetujui }}</div>
                                    <div class="text-muted">Disetujui</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection 
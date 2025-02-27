@extends('layouts.app')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-money-bill-wave text-primary mr-2"></i> Daftar Pembayaran</h4>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Taman</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaran as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>
                                        <span class="font-weight-bold">{{ $p->pemesanan->kode }}</span>
                                    </td>
                                    <td>{{ $p->pemesanan->taman->nama }}</td>
                                    <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->status == 'pending')
                                            <span class="badge bg-warning text-white"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi</span>
                                        @elseif($p->status == 'diverifikasi')
                                            <span class="badge bg-success text-white"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                                        @elseif($p->status == 'ditolak')
                                            <span class="badge bg-danger text-white"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('pembayaran.show', $p) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="float-right">
                        {{ $pembayaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

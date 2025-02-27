@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-list text-primary mr-2"></i>
                            Detail Pemesanan
                        </h4>
                        <div class="card-header-action">
                            @if(auth()->user()->isAdmin() && $pemesanan->status === 'pending')
                                <form action="{{ route('pemesanan.approve', $pemesanan->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pemesanan ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-lg px-4 ml-2" id="btnTolak">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            @endif
                            
                            @if(auth()->user()->isAdmin() && $pemesanan->status === 'dibayar')
                                <form action="{{ route('pemesanan.selesai', $pemesanan->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan pemesanan ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-info btn-lg px-4">
                                        <i class="fas fa-check-circle mr-1"></i> Selesaikan Pemesanan
                                    </button>
                                </form>
                            @endif
                            
                            @if(!auth()->user()->isAdmin() && $pemesanan->status === 'disetujui' && !$pembayaran)
                                <a href="{{ route('pembayaran.create', $pemesanan) }}" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-money-bill mr-1"></i> Bayar Sekarang
                                </a>
                            @endif
                            
                            <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary btn-lg px-4 ml-2">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Penolakan (Hidden by default) -->
                <div id="formPenolakan" class="card-body bg-light border-top" style="display: none;">
                    <form action="{{ route('pemesanan.reject', $pemesanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="catatan_admin" class="font-weight-bold">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="catatan_admin" 
                                    id="catatan_admin" 
                                    rows="3" 
                                    class="form-control @error('catatan_admin') is-invalid @enderror" 
                                    required>{{ old('catatan_admin') }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" id="btnBatalTolak">
                                <i class="fas fa-times mr-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger ml-2">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim
                            </button>
                        </div>
                    </form>
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

                    @if($pemesanan->status == 'disetujui' && !$pembayaran && !auth()->user()->isAdmin())
                    <div class="alert alert-warning alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <i class="fas fa-exclamation-triangle mr-2"></i> Silakan lakukan pembayaran untuk menyelesaikan pemesanan Anda.
                        </div>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge badge-lg badge-{{ 
                            $pemesanan->status === 'pending' ? 'warning' :
                            ($pemesanan->status === 'disetujui' ? 'success' :
                            ($pemesanan->status === 'ditolak' ? 'danger' : 
                            ($pemesanan->status === 'dibayar' ? 'info' : 'secondary')))
                        }} px-4 py-2" style="font-size: 1rem;">
                            <i class="fas fa-{{ 
                                $pemesanan->status === 'pending' ? 'clock' :
                                ($pemesanan->status === 'disetujui' ? 'check-circle' :
                                ($pemesanan->status === 'ditolak' ? 'times-circle' : 
                                ($pemesanan->status === 'dibayar' ? 'money-bill-wave' : 'flag-checkered')))
                            }} mr-2"></i>
                            {{ ucfirst($pemesanan->status) }}
                        </span>
                    </div>

                    <!-- Booking Code -->
                    <div class="text-center mb-4">
                        <h6 class="text-muted mb-2">Kode Pemesanan</h6>
                        <h4 class="font-weight-bold">{{ $pemesanan->kode }}</h4>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-info-circle text-primary mr-2"></i>
                                        Informasi Taman
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0">Nama Taman</th>
                                            <td class="text-right">{{ $pemesanan->taman->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Lokasi</th>
                                            <td class="text-right">{{ $pemesanan->taman->lokasi }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Kapasitas</th>
                                            <td class="text-right">{{ number_format($pemesanan->taman->kapasitas) }} orang</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Fasilitas</th>
                                            <td class="text-right">
                                                @if(is_array($pemesanan->taman->fasilitas))
                                                    {{ implode(', ', $pemesanan->taman->fasilitas) }}
                                                @else
                                                    {{ $pemesanan->taman->fasilitas }}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                        Detail Pemesanan
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0">Pemesan</th>
                                            <td class="text-right">{{ $pemesanan->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Mulai</th>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($pemesanan->waktu_mulai)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Selesai</th>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($pemesanan->waktu_selesai)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Durasi</th>
                                            <td class="text-right">
                                                @if($pemesanan->total_jam >= 24)
                                                    {{ $pemesanan->total_hari }} hari
                                                @else
                                                    {{ $pemesanan->total_jam }} jam
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Jumlah Orang</th>
                                            <td class="text-right">{{ number_format($pemesanan->jumlah_orang) }} orang</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-money-bill-wave text-primary mr-2"></i>
                                Rincian Pembayaran
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0">Harga per Hari</th>
                                            <td class="text-right">Rp {{ number_format($pemesanan->taman->harga_per_hari, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Harga per Jam</th>
                                            <td class="text-right">Rp {{ number_format($pemesanan->taman->harga_per_hari / 24, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Total Durasi</th>
                                            <td class="text-right">
                                                @if($pemesanan->total_jam >= 24)
                                                    {{ $pemesanan->total_hari }} hari ({{ $pemesanan->total_jam }} jam)
                                                @else
                                                    {{ $pemesanan->total_jam }} jam
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white p-4 rounded shadow-sm">
                                        <h6 class="text-muted mb-2">Total Pembayaran</h6>
                                        <h3 class="text-primary mb-0">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pembayaran)
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-receipt text-primary mr-2"></i>
                                Status Pembayaran
                                @if($pembayaran->status == 'pending')
                                    <span class="badge badge-warning ml-2">Menunggu Verifikasi</span>
                                @elseif($pembayaran->status == 'diverifikasi')
                                    <span class="badge badge-success ml-2">Terverifikasi</span>
                                @elseif($pembayaran->status == 'ditolak')
                                    <span class="badge badge-danger ml-2">Ditolak</span>
                                @endif
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th class="pl-0">Jumlah Dibayar</th>
                                            <td class="text-right">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Pembayaran</th>
                                            <td class="text-right">{{ \Carbon\Carbon::parse($pembayaran->created_at)->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @if($pembayaran->status == 'ditolak' && $pembayaran->catatan)
                                        <tr>
                                            <th class="pl-0">Catatan</th>
                                            <td class="text-right text-danger">{{ $pembayaran->catatan }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                    
                                    @if($pembayaran->status == 'ditolak' && !auth()->user()->isAdmin())
                                    <div class="mt-3">
                                        <a href="{{ route('pembayaran.create', $pemesanan) }}" class="btn btn-primary">
                                            <i class="fas fa-redo mr-1"></i> Bayar Ulang
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <h6 class="text-muted mb-2">Bukti Pembayaran</h6>
                                        <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                            class="img-fluid rounded shadow-sm" 
                                            style="max-height: 200px;" alt="Bukti Pembayaran">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->isAdmin() && $pembayaran->status == 'pending')
                    <div class="card bg-light mt-4">
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
                                            <label class="font-weight-bold">Status</label>
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
                    @endif

                    @if($pemesanan->taman->gambar)
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-image text-primary mr-2"></i>
                                Foto Taman
                            </h5>
                            <img src="{{ asset('storage/' . $pemesanan->taman->gambar) }}" 
                                 alt="Foto {{ $pemesanan->taman->nama }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    @endif

                    <!-- Additional Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">
                                        <i class="fas fa-clipboard-list text-primary mr-2"></i>
                                        Informasi Tambahan
                                    </h5>
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Keperluan:</h6>
                                        <p class="mb-0">{{ $pemesanan->keperluan }}</p>
                                    </div>

                                    @if($pemesanan->catatan_admin)
                                        <div>
                                            <h6 class="text-muted mb-2">Catatan Admin:</h6>
                                            <p class="mb-0">{{ $pemesanan->catatan_admin }}</p>
                                        </div>
                                    @endif
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

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle form penolakan
    $('#btnTolak').click(function() {
        $('#formPenolakan').slideDown();
        $(this).hide();
    });

    $('#btnBatalTolak').click(function() {
        $('#formPenolakan').slideUp();
        $('#btnTolak').show();
    });

    // Show form if there are errors
    @if($errors->any() && old('_method') === 'PUT')
        $('#btnTolak').hide();
        $('#formPenolakan').show();
    @endif
});
</script>
@endpush

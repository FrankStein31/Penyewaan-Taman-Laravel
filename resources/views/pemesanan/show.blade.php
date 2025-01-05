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

                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span class="badge badge-lg badge-{{ 
                            $pemesanan->status === 'pending' ? 'warning' :
                            ($pemesanan->status === 'disetujui' ? 'success' :
                            ($pemesanan->status === 'ditolak' ? 'danger' : 'info'))
                        }} px-4 py-2" style="font-size: 1rem;">
                            <i class="fas fa-{{ 
                                $pemesanan->status === 'pending' ? 'clock' :
                                ($pemesanan->status === 'disetujui' ? 'check-circle' :
                                ($pemesanan->status === 'ditolak' ? 'times-circle' : 'info-circle'))
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
                                            <td class="text-right">{{ $pemesanan->waktu_mulai->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0">Tanggal Selesai</th>
                                            <td class="text-right">{{ $pemesanan->waktu_selesai->format('d/m/Y H:i') }}</td>
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
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white p-4 rounded">
                                        <h6 class="text-muted mb-2">Total Pembayaran</h6>
                                        <h3 class="text-primary mb-0">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
@extends('layouts.app')

@section('title', 'Buat Pemesanan')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Buat Pemesanan Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemesanan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="taman_id" value="{{ $taman->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Taman</label>
                                    <input type="text" class="form-control" value="{{ $taman->nama }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Harga per Hari</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp</div>
                                        </div>
                                        <input type="text" class="form-control" 
                                               value="{{ number_format($taman->harga_per_hari, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal & Waktu Mulai</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" name="tanggal_mulai" 
                                                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                                   value="{{ old('tanggal_mulai') }}" 
                                                   min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="time" name="waktu_mulai" 
                                                   class="form-control @error('waktu_mulai') is-invalid @enderror"
                                                   value="{{ old('waktu_mulai') }}" required>
                                        </div>
                                    </div>
                                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal & Waktu Selesai</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" name="tanggal_selesai" 
                                                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                                   value="{{ old('tanggal_selesai') }}" 
                                                   min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="time" name="waktu_selesai" 
                                                   class="form-control @error('waktu_selesai') is-invalid @enderror"
                                                   value="{{ old('waktu_selesai') }}" required>
                                        </div>
                                    </div>
                                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Orang</label>
                                    <input type="number" name="jumlah_orang" 
                                           class="form-control @error('jumlah_orang') is-invalid @enderror"
                                           value="{{ old('jumlah_orang') }}" 
                                           min="1" max="{{ $taman->kapasitas }}" required>
                                    @error('jumlah_orang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Maksimal {{ number_format($taman->kapasitas) }} orang
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keperluan</label>
                                    <textarea name="keperluan" 
                                              class="form-control @error('keperluan') is-invalid @enderror" 
                                              style="height: 100px" required>{{ old('keperluan') }}</textarea>
                                    @error('keperluan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('taman.show', $taman->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('input[type="date"], input[type="time"]').change(function() {
        let tanggalMulai = $('input[name="tanggal_mulai"]').val();
        let waktuMulai = $('input[name="waktu_mulai"]').val();
        let tanggalSelesai = $('input[name="tanggal_selesai"]').val();
        let waktuSelesai = $('input[name="waktu_selesai"]').val();
        
        if (tanggalMulai && waktuMulai && tanggalSelesai && waktuSelesai) {
            let start = new Date(tanggalMulai + ' ' + waktuMulai);
            let end = new Date(tanggalSelesai + ' ' + waktuSelesai);
            
            if (end <= start) {
                alert('Waktu selesai harus lebih besar dari waktu mulai');
                $('input[name="waktu_selesai"]').val('');
                return;
            }
            
            let diffHours = Math.abs(end - start) / 36e5; // Convert to hours
            let hargaPerJam = {{ $taman->harga_per_hari }} / 24;
            let totalBayar = diffHours * hargaPerJam;
            
            $('#total_waktu').text(diffHours.toFixed(1) + ' jam');
            $('#total_bayar').text('Rp ' + number_format(totalBayar));
        }
    });
});
</script>
@endpush 
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
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" 
                                           class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" 
                                           class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
    // Hitung total hari dan total bayar saat tanggal berubah
    $('input[name="tanggal_mulai"], input[name="tanggal_selesai"]').change(function() {
        let tanggalMulai = $('input[name="tanggal_mulai"]').val();
        let tanggalSelesai = $('input[name="tanggal_selesai"]').val();
        
        if (tanggalMulai && tanggalSelesai) {
            let start = new Date(tanggalMulai);
            let end = new Date(tanggalSelesai);
            
            // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
            if (end < start) {
                alert('Tanggal selesai tidak boleh kurang dari tanggal mulai');
                $('input[name="tanggal_selesai"]').val('');
                return;
            }
            
            // Hitung selisih hari
            let diffTime = Math.abs(end - start);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Hitung total bayar
            let hargaPerHari = {{ $taman->harga_per_hari }};
            let totalBayar = diffDays * hargaPerHari;
            
            // Tampilkan informasi
            $('#total_hari').text(diffDays + ' hari');
            $('#total_bayar').text('Rp ' + number_format(totalBayar));
        }
    });
    
    // Format number to currency
    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>
@endpush 
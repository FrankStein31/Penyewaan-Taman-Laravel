@extends('layouts.app')

@section('title', 'Upload Pembayaran')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Upload Bukti Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Informasi Pembayaran</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th style="width: 200px">ID Pemesanan</th>
                                            <td>#{{ $pemesanan->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Taman</th>
                                            <td>{{ $pemesanan->taman->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Sewa</th>
                                            <td>
                                                {{ $pemesanan->tanggal_mulai->format('d/m/Y') }} - 
                                                {{ $pemesanan->tanggal_selesai->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total yang Harus Dibayar</th>
                                            <td>
                                                <h4 class="text-primary">
                                                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                                </h4>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="alert alert-info mt-4">
                                        <h6>Informasi Pembayaran:</h6>
                                        <p class="mb-2">Silahkan transfer ke rekening berikut:</p>
                                        <table class="mb-0">
                                            <tr>
                                                <td style="width: 100px">Bank</td>
                                                <td>: BRI</td>
                                            </tr>
                                            <tr>
                                                <td>No. Rekening</td>
                                                <td>: 1234-5678-9012-3456</td>
                                            </tr>
                                            <tr>
                                                <td>Atas Nama</td>
                                                <td>: DLHKP KOTA KEDIRI</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('pembayaran.store', $pemesanan->id) }}" 
                                  method="POST" 
                                  enctype="multipart/form-data">
                                @csrf
                                
                                <div class="form-group">
                                    <label>Upload Bukti Transfer</label>
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label">Pilih File</label>
                                        <input type="file" name="bukti_pembayaran" 
                                               id="image-upload" 
                                               class="@error('bukti_pembayaran') is-invalid @enderror"
                                               accept="image/*" 
                                               required>
                                    </div>
                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG (Max. 2MB)
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Catatan (Opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
                                </div>

                                <div class="alert alert-warning">
                                    <h6>Perhatian!</h6>
                                    <ul class="mb-0">
                                        <li>Pastikan nominal transfer sesuai dengan total yang harus dibayar</li>
                                        <li>Upload bukti pembayaran yang jelas dan dapat dibaca</li>
                                        <li>Admin akan memverifikasi pembayaran Anda dalam 1x24 jam</li>
                                    </ul>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                                    </button>
                                    <a href="{{ route('pemesanan.show', $pemesanan->id) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.image-preview {
    width: 100%;
    min-height: 200px;
    border: 2px dashed #ddd;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
    background-color: #ffffff;
    color: #ecf0f1;
}

.image-preview input {
    line-height: 200px;
    font-size: 200px;
    position: absolute;
    opacity: 0;
    z-index: 10;
}

.image-preview label {
    position: absolute;
    z-index: 5;
    opacity: 0.8;
    cursor: pointer;
    background-color: #bdc3c7;
    width: 150px;
    height: 50px;
    font-size: 12px;
    line-height: 50px;
    text-transform: uppercase;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/jquery.uploadPreview.min.js') }}"></script>
<script>
$(document).ready(function() {
    $.uploadPreview({
        input_field: "#image-upload",
        preview_box: "#image-preview",
        label_field: "#image-label",
        label_default: "Pilih File",
        label_selected: "Ganti File",
        no_label: false
    });
});
</script>
@endpush 
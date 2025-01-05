@extends('layouts.app')

@section('title', 'Edit Taman')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Taman</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('taman.update', $taman->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Taman</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="nama" 
                                       class="form-control @error('nama') is-invalid @enderror"
                                       value="{{ old('nama', $taman->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea name="deskripsi" 
                                          class="form-control @error('deskripsi') is-invalid @enderror" 
                                          style="height: 150px" required>{{ old('deskripsi', $taman->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Lokasi</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="lokasi" 
                                       class="form-control @error('lokasi') is-invalid @enderror"
                                       value="{{ old('lokasi', $taman->lokasi) }}" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Kapasitas (Orang)</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" name="kapasitas" 
                                       class="form-control @error('kapasitas') is-invalid @enderror"
                                       value="{{ old('kapasitas', $taman->kapasitas) }}" required min="1">
                                @error('kapasitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Harga per Hari</label>
                            <div class="col-sm-12 col-md-7">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="number" name="harga_per_hari" 
                                           class="form-control currency @error('harga_per_hari') is-invalid @enderror"
                                           value="{{ old('harga_per_hari', $taman->harga_per_hari) }}" required min="0" step="0.01">
                                </div>
                                @error('harga_per_hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Fasilitas</label>
                            <div class="col-sm-12 col-md-7">
                                <div class="row">
                                    @foreach($fasilitas as $f)
                                        <div class="col-md-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       name="fasilitas[]" 
                                                       class="custom-control-input" 
                                                       id="fasilitas-{{ $f->id_fasilitas }}"
                                                       value="{{ $f->nama_fasilitas }}"
                                                       {{ in_array($f->nama_fasilitas, old('fasilitas', $taman->fasilitas ?? [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="fasilitas-{{ $f->id_fasilitas }}">
                                                    {{ $f->nama_fasilitas }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('fasilitas')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Gambar</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview">
                                    <label for="image-upload" id="image-label">Pilih File</label>
                                    <input type="file" name="gambar" id="image-upload" 
                                           class="@error('gambar') is-invalid @enderror"
                                           accept="image/*">
                                </div>
                                @if($taman->gambar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $taman->gambar) }}" 
                                             alt="{{ $taman->nama }}"
                                             class="img-thumbnail"
                                             width="200">
                                    </div>
                                @endif
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                            <div class="col-sm-12 col-md-7">
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', $taman->status) == '1' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="0" {{ old('status', $taman->status) == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('taman.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/jquery.uploadPreview.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
    
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
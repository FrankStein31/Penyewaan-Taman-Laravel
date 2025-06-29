@extends('layouts.app')

@section('title', 'Edit Taman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Taman') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('taman.update', $taman->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="nama" class="col-md-2 col-form-label text-md-end">{{ __('Nama Taman') }}</label>
                            <div class="col-md-10">
                                <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $taman->nama) }}" required>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="deskripsi" class="col-md-2 col-form-label text-md-end">{{ __('Deskripsi') }}</label>
                            <div class="col-md-10">
                                <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" required rows="4">{{ old('deskripsi', $taman->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="lokasi" class="col-md-2 col-form-label text-md-end">{{ __('Lokasi') }}</label>
                            <div class="col-md-10">
                                <input id="lokasi" type="text" class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" value="{{ old('lokasi', $taman->lokasi) }}" required>
                                @error('lokasi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="kapasitas" class="col-md-2 col-form-label text-md-end">{{ __('Kapasitas') }}</label>
                            <div class="col-md-10">
                                <input id="kapasitas" type="number" class="form-control @error('kapasitas') is-invalid @enderror" name="kapasitas" value="{{ old('kapasitas', $taman->kapasitas) }}" required min="1">
                                @error('kapasitas')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_per_hari" class="col-md-2 col-form-label text-md-end">{{ __('Harga per Hari') }}</label>
                            <div class="col-md-10">
                                <input id="harga_per_hari" type="number" class="form-control @error('harga_per_hari') is-invalid @enderror" name="harga_per_hari" value="{{ old('harga_per_hari', $taman->harga_per_hari) }}" required min="0">
                                @error('harga_per_hari')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label text-md-end">{{ __('Status') }}</label>
                            <div class="col-md-10">
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', $taman->status) == 1 ? 'selected' : '' }}>Tersedia</option>
                                    <option value="0" {{ old('status', $taman->status) == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label text-md-end">{{ __('Fasilitas') }}</label>
                            <div class="col-md-10">
                                <div class="row">
                                    @foreach(\App\Models\Fasilitas::orderBy('nama_fasilitas')->get() as $fasilitas)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fasilitas->nama_fasilitas }}" id="fasilitas_{{ $fasilitas->id_fasilitas }}" {{ (is_array(old('fasilitas', $taman->fasilitas)) && in_array($fasilitas->nama_fasilitas, old('fasilitas', $taman->fasilitas))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fasilitas_{{ $fasilitas->id_fasilitas }}">
                                                {{ $fasilitas->nama_fasilitas }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('fasilitas')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label text-md-end">{{ __('Gambar Utama') }}</label>
                            <div class="col-md-10">
                                @if($taman->gambar)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $taman->gambar) }}" alt="Gambar Utama" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept="image/*">
                                <small class="text-muted">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</small>
                                @error('gambar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-2 col-form-label text-md-end">{{ __('Foto-foto Saat Ini') }}</label>
                            <div class="col-md-10">
                                <div class="row">
                                    @forelse($taman->fotos as $foto)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $foto->foto) }}" class="card-img-top" alt="Foto Taman" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="delete_fotos[]" value="{{ $foto->id }}" id="delete_foto_{{ $foto->id }}">
                                                        <label class="form-check-label text-danger" for="delete_foto_{{ $foto->id }}">
                                                            Hapus foto ini
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted">Belum ada foto</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="fotos" class="col-md-2 col-form-label text-md-end">{{ __('Tambah Foto Baru') }}</label>
                            <div class="col-md-10">
                                <input type="file" class="form-control @error('fotos.*') is-invalid @enderror" name="fotos[]" accept="image/*" multiple>
                                <small class="text-muted">Anda dapat memilih beberapa foto sekaligus. Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB per file.</small>
                                @error('fotos.*')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-10 offset-md-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Simpan Perubahan') }}
                                </button>
                                <a href="{{ route('taman.index') }}" class="btn btn-secondary">
                                    {{ __('Kembali') }}
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
<style>
.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
</style>
@endpush 
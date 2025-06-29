@extends('layouts.app')

@section('title', 'Edit Fasilitas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Fasilitas') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('fasilitas.update', $fasilitas->id_fasilitas) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="nama_fasilitas" class="col-md-4 col-form-label text-md-end">{{ __('Nama Fasilitas') }}</label>

                            <div class="col-md-6">
                                <input id="nama_fasilitas" type="text" class="form-control @error('nama_fasilitas') is-invalid @enderror" name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required autocomplete="nama_fasilitas" autofocus>

                                @error('nama_fasilitas')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="foto" class="col-md-4 col-form-label text-md-end">{{ __('Foto') }}</label>

                            <div class="col-md-6">
                                @if($fasilitas->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="Foto Fasilitas" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input id="foto" type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*">

                                @error('foto')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                                <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">
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
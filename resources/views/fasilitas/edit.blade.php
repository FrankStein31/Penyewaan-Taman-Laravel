@extends('layouts.app')

@section('title', 'Edit Fasilitas')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Fasilitas</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('fasilitas.update', $fasilitas->id_fasilitas) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Fasilitas</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="nama_fasilitas" 
                                       class="form-control @error('nama_fasilitas') is-invalid @enderror"
                                       value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                                @error('nama_fasilitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
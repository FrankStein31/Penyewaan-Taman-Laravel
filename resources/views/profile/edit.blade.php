@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profil</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="name" 
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" 
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', auth()->user()->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Foto Profil</label>
                                    <div class="custom-file">
                                        <input type="file" name="profile_photo" 
                                              class="custom-file-input @error('profile_photo') is-invalid @enderror" 
                                              id="profile_photo">
                                        <label class="custom-file-label" for="profile_photo">Pilih foto...</label>
                                        @error('profile_photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(auth()->user()->profile_photo)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                                alt="Foto Profil" class="img-thumbnail" width="150">
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <form action="{{ route('profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label>Password Lama</label>
                                    <input type="password" name="current_password" 
                                           class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" name="password" 
                                           class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" 
                                           class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key"></i> Ubah Password
                                    </button>
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
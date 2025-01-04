@extends('layouts.app')

@section('title', 'Manajemen Taman')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Taman</h4>
                    @if(auth()->user()->isAdmin())
                        <div class="card-header-action">
                            <a href="{{ route('taman.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Taman
                            </a>
                        </div>
                    @endif
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

                    <form action="{{ route('taman.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>Cari Nama/Lokasi</label>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari nama atau lokasi taman..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>Range Harga per Hari</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="harga_min" 
                                               placeholder="Min" value="{{ request('harga_min') }}">
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text">-</span>
                                        </div>
                                        <input type="number" class="form-control" name="harga_max" 
                                               placeholder="Max" value="{{ request('harga_max') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>Kapasitas Minimal</label>
                                    <input type="number" class="form-control" name="kapasitas_min" 
                                           placeholder="Minimal kapasitas..." 
                                           value="{{ request('kapasitas_min') }}">
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <select class="form-control select2" name="fasilitas[]" 
                                            multiple="multiple" data-placeholder="Pilih fasilitas...">
                                        @foreach($allFasilitas as $fasilitas)
                                            <option value="{{ $fasilitas }}" 
                                                {{ in_array($fasilitas, (array)request('fasilitas')) ? 'selected' : '' }}>
                                                {{ $fasilitas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('taman.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Taman</th>
                                    <th>Lokasi</th>
                                    <th>Kapasitas</th>
                                    <th>Harga/Hari</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taman as $t)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($t->gambar)
                                                <img src="{{ Storage::url($t->gambar) }}" 
                                                     alt="{{ $t->nama }}"
                                                     width="100"
                                                     class="img-thumbnail">
                                            @else
                                                <span class="badge badge-secondary">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td>{{ $t->nama }}</td>
                                        <td>{{ $t->lokasi }}</td>
                                        <td>{{ number_format($t->kapasitas) }} orang</td>
                                        <td>Rp {{ number_format($t->harga_per_hari, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $t->status ? 'success' : 'danger' }}">
                                                {{ $t->status ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('taman.show', $t->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('taman.edit', $t->id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('taman.destroy', $t->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus taman ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('pemesanan.create', ['taman' => $t->id]) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-calendar-plus"></i> Pesan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="float-right">
                        {{ $taman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #6777ef;
        border-color: #6777ef;
        color: #fff;
        padding: 0 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush 
@extends('layouts.app')

@section('title', 'Manajemen Fasilitas')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Fasilitas</h4>
                    <div class="card-header-action">
                        <a href="{{ route('fasilitas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Fasilitas
                        </a>
                    </div>
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fasilitas as $f)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $f->nama_fasilitas }}</td>
                                        <td>
                                            <a href="{{ route('fasilitas.edit', $f->id_fasilitas) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fasilitas.destroy', $f->id_fasilitas) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="float-right">
                        {{ $fasilitas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
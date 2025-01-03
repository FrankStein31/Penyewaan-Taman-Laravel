@extends('layouts.app')

@section('title', 'Manajemen Taman')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Taman</h4>
                    <div class="card-header-action">
                        <a href="{{ route('taman.create') }}" class="btn btn-primary">
                            Tambah Taman
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
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taman as $t)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $t->nama }}</td>
                                        <td>Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $t->status ? 'success' : 'danger' }}">
                                                {{ $t->status ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($t->gambar)
                                                <img src="{{ asset('storage/' . $t->gambar) }}" 
                                                     alt="{{ $t->nama }}" 
                                                     width="50">
                                            @else
                                                Tidak ada gambar
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('taman.edit', $t->id) }}" 
                                               class="btn btn-warning btn-sm">Edit</a>
                                            
                                            <form action="{{ route('taman.destroy', $t->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus taman ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $taman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
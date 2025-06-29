@extends('layouts.app')

@section('title', 'Manajemen Fasilitas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Fasilitas') }}</span>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('fasilitas.create') }}" class="btn btn-primary btn-sm">
                            {{ __('Tambah Fasilitas') }}
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama Fasilitas</th>
                                    @if(auth()->user()->isAdmin())
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fasilitas as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->nama_fasilitas }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                            @else
                                                <span class="text-muted">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama_fasilitas }}</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>
                                                <a href="{{ route('fasilitas.edit', $item->id_fasilitas) }}" class="btn btn-warning btn-sm">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('fasilitas.destroy', $item->id_fasilitas) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isAdmin() ? 4 : 3 }}" class="text-center">Tidak ada data fasilitas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $fasilitas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
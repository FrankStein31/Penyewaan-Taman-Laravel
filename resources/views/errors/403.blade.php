@extends('layouts.error')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="page-error">
    <div class="page-inner">
        <h1>403</h1>
        <div class="page-description">
            Maaf, Anda tidak memiliki akses ke halaman ini.
        </div>
        <div class="page-search">
            <div class="mt-3">
                <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 
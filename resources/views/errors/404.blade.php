@extends('layouts.error')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="page-error">
    <div class="page-inner">
        <h1>404</h1>
        <div class="page-description">
            Maaf, halaman yang Anda cari tidak ditemukan.
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
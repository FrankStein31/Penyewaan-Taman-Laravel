@extends('layouts.error')

@section('title', '500 - Server Error')

@section('content')
<div class="page-error">
    <div class="page-inner">
        <h1>500</h1>
        <div class="page-description">
            Whoops! Terjadi kesalahan pada server kami.
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
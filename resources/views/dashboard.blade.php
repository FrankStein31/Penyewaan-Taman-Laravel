@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5>Selamat datang, {{ Auth::user()->name }}!</h5>
                    @if(Auth::user()->isAdmin())
                        <p>Anda login sebagai Admin</p>
                    @else
                        <p>Anda login sebagai User</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
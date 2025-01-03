@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="card card-primary">
    <div class="card-header"><h4>Login</h4></div>

    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Login
                </button>
            </div>
        </form>
        <div class="mt-5 text-center">
            Don't have an account? <a href="{{ route('register') }}">Create One</a>
        </div>
    </div>
</div>
@endsection 
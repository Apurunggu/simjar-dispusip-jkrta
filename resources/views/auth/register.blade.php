@extends('layout-login')


@section('title', 'Register')

@push('styles')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #236080 60%, #fff 100%);
        overflow: hidden;
        width: 100vw;
    }
</style>
@endpush

@section('content')
<div class="login-split">
    <!-- Kiri: Form Register -->
    <div class="login-left" style="flex:1;background:#fff;display:flex;align-items:center;justify-content:center;">
        <div class="login-card" style="margin:0;box-shadow:none;width:370px;max-width:90vw;margin-top:-40px;">
            <div class="card-header" style="background:none;color:#222;font-size:2rem;font-weight:700;box-shadow:none;text-shadow:none;text-align:center;">
                Register hire.
            </div>
            <div class="card-body" style="padding:0 0 0 0;">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('register') }}" style="padding: 0 32px 0 32px;">
                    @csrf
                    <div class="mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2" style="background:#4ecdc4;border-radius:24px;font-size:1.1rem;font-weight:600;border:none;">
                        Register
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2" style="margin-top:8px;border-radius:24px;font-size:1.1rem;font-weight:600;border:2px solid #4ecdc4;color:#1976d2;background:#fff;transition:background 0.2s, color 0.2s;">Login</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Kanan: Panel gambar saja -->
    <div class="login-right" style="flex:1.1;background:url('/images/login.jpeg') center/cover no-repeat;display:flex;align-items:center;justify-content:center;"></div>
</div>
@endsection

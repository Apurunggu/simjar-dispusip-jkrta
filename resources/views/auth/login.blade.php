@extends('layout-login')

@section('title', 'Login')

@push('styles')
<style>
    *, *::before, *::after { box-sizing: border-box; }
    html, body {
        height: 100%; margin: 0; padding: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #236080 60%, #fff 100%);
        overflow: hidden; width: 100vw;
    }
    body {
        min-height: 100vh; height: 100vh; width: 100vw;
        display: flex; align-items: stretch; justify-content: stretch; overflow: hidden;
    }
    .login-split {
        display: flex; width: 100vw; height: 100vh; overflow: hidden; position: relative; z-index: 1;
    }
    .login-left {
        flex: 1; background: transparent; display: flex; align-items: center; justify-content: flex-end; padding-right: 0; height: 100vh;
    }
    .login-card {
        margin: 0 auto 24px auto;
        margin-top: -40px;
    }
    .card-header {
        text-align: center !important;
    }
    .login-right {
        flex: 1.1; background: url('/images/login.jpeg') center/cover no-repeat; display: flex; align-items: center; justify-content: center;
    }
    .login-right-panel {
        background: rgba(0,0,0,0.28); border-radius: 24px; padding: 48px 36px; max-width: 420px; text-align: center; box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    }
    .login-right-panel-title {
        font-size: 2.2rem; font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.1;
    }
    .login-right-panel-desc {
        font-size: 1.1rem; color: #e3e8ee; margin-bottom: 32px;
    }
    .login-right-panel-link {
        display: inline-block; padding: 12px 38px; border: 2px solid #fff; border-radius: 32px; color: #fff; font-size: 1.15rem; font-weight: 600; text-decoration: none; background: rgba(255,255,255,0.08); transition: background 0.2s, color 0.2s; letter-spacing: 1px;
    }
    @media (max-width: 900px) {
        .login-split { flex-direction: column; height: 100vh; }
        .login-left, .login-right {
            /* width: 100vw; min-height: 0; justify-content: center; height: 50vh; */
            justify-content: center; height: 50vh;
        }
        .login-card { margin: 0 auto 24px auto; }
    }
    @media (max-width: 600px) {
        .login-card { padding: 8px 2px; max-width: 98vw; }
    }
</style>
@endpush

    @section('content')
    <div class="login-split">
        <!-- Kiri: Form Login -->
        <div class="login-left">
            <div class="login-card">
                <div class="card-header" style="background:none;color:#222;font-size:2rem;font-weight:700;box-shadow:none;text-shadow:none;justify-content:flex-start;text-align:left;">
                    Login SIMJAR.
                </div>
                <div class="card-body" style="padding:0 0 0 0;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}" style="padding: 0 32px 0 32px;">
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
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                            <div>
                                <input type="checkbox" id="remember" name="remember" style="margin-right:4px;">
                                <label for="remember" style="font-size:0.98rem;color:#555;">Remember me</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2" style="background:#4ecdc4;border-radius:24px;font-size:1.1rem;font-weight:600;border:none;">
                            Login
                        </button>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mb-2" style="margin-top:8px;border-radius:24px;font-size:1.1rem;font-weight:600;border:2px solid #4ecdc4;color:#1976d2;background:#fff;transition:background 0.2s, color 0.2s;">Register</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- Kanan: Panel gambar saja -->
        <div class="login-right"></div>
    </div>
    @endsection

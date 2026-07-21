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
                Form Pendaftaran
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
                    <div class="mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @csrf
                    <div class="mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div style="position:relative;">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required placeholder="Password" style="padding-right:38px;">
                            <span onclick="togglePassword('password', this)" style="position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <div style="position:relative;">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password" style="padding-right:38px;">
                            <span onclick="togglePassword('password_confirmation', this)" style="position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </span>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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

@push('scripts')
<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        el.querySelector('svg').style.opacity = 0.5;
    } else {
        input.type = 'password';
        el.querySelector('svg').style.opacity = 1;
    }
}
</script>
@endpush

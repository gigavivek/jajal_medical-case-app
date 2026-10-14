@extends('layouts.app')

@section('title', 'Clinical Sign In')

@section('content')
<div class="row justify-content-center align-items-center py-4">
    <div class="col-md-7 col-lg-5 col-xl-4">
        {{-- Login Card Container --}}
        <div class="card jm-card shadow-lg border-0">
            <div class="card-body p-4 p-md-5">
                {{-- Portal Brand Header --}}
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 p-3 mb-3 shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); width: 64px; height: 64px;">
                        <i class="bi bi-hospital fs-2 text-white"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark tracking-tight">Clinical Portal Sign In</h3>
                    <p class="text-muted small mb-0">Patient-Specific Surgical Implant Case Tracking</p>
                </div>

                {{-- Validation Errors Banner --}}
                @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small mb-4 rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Sign In Form --}}
                <form method="POST" action="{{ route('login') }}" novalidate id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="jm-form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope text-primary"></i></span>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="name@jajalmedical.com"
                                   autocomplete="email"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="jm-form-label mb-0">Password</label>
                            <span class="text-muted small">8+ characters</span>
                        </div>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="bi bi-lock text-primary"></i></span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••••••"
                                   autocomplete="current-password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn" title="Toggle password visibility">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small text-muted" for="remember">Remember this workstation</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Authenticate & Enter Portal
                        </button>
                    </div>
                </form>

                {{-- Quick Demo Credentials Card with 1-Click Autofill --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase text-muted" style="letter-spacing: 0.05em;">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Demo Access
                        </span>
                        <span class="badge bg-light text-secondary border">Click to auto-fill</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm text-start p-2 d-flex align-items-center justify-content-between" onclick="fillCredentials('admin@jajalmedical.com', 'Admin@12345')">
                            <div>
                                <div class="fw-bold"><i class="bi bi-person-gear me-1"></i> Administrator Account</div>
                                <div class="text-muted font-monospace small" style="font-size: 0.75rem;">admin@jajalmedical.com</div>
                            </div>
                            <span class="badge bg-primary">Admin</span>
                        </button>

                        <button type="button" class="btn btn-outline-secondary btn-sm text-start p-2 d-flex align-items-center justify-content-between" onclick="fillCredentials('viewer@jajalmedical.com', 'Viewer@12345')">
                            <div>
                                <div class="fw-bold"><i class="bi bi-eye me-1"></i> Clinical Viewer Account</div>
                                <div class="text-muted font-monospace small" style="font-size: 0.75rem;">viewer@jajalmedical.com</div>
                            </div>
                            <span class="badge bg-secondary">Viewer</span>
                        </button>
                    </div>
                </div>

                {{-- Security Compliance Footnote --}}
                <div class="mt-4 text-center">
                    <span class="small text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-check text-success me-1"></i> 256-bit Encrypted Session &bull; ISO 13485 Certified Audit
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1-Click demo credential auto-filler
    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
        // Subtle highlight effect
        document.getElementById('email').classList.add('bg-light');
        document.getElementById('password').classList.add('bg-light');
        setTimeout(() => {
            document.getElementById('email').classList.remove('bg-light');
            document.getElementById('password').classList.remove('bg-light');
        }, 500);
    }

    // Toggle password visibility
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>
@endpush
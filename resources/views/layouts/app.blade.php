<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Case Management') | Jajal Medical &mdash; Patient-Specific Implants</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Jajal Medical Enterprise Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/jajal-medical.css') }}">

    @stack('styles')
</head>
<body>
    {{-- Header & Primary Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-dark jm-navbar d-print-none">
        <div class="container-xl">
            <a class="jm-brand-logo" href="{{ route('dashboard') }}">
                <div class="jm-brand-icon-box">
                    <i class="bi bi-shield-plus"></i>
                </div>
                <div>
                    <span class="jm-brand-title">JAJAL MEDICAL</span>
                    <span class="jm-brand-subtitle">SURGICAL CASE PORTAL</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link jm-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link jm-nav-link {{ request()->routeIs('cases.index') || request()->routeIs('cases.show') ? 'active' : '' }}" href="{{ route('cases.index') }}">
                            <i class="bi bi-folder2-open"></i> Medical Cases
                        </a>
                    </li>
                    @can('create', App\Models\MedicalCase::class)
                    <li class="nav-item">
                        <a class="nav-link jm-nav-link {{ request()->routeIs('cases.create') ? 'active' : '' }}" href="{{ route('cases.create') }}">
                            <i class="bi bi-plus-circle"></i> New Case
                        </a>
                    </li>
                    @endcan
                </ul>

                <div class="d-flex flex-wrap align-items-center gap-3 mt-3 mt-lg-0">
                    {{-- Cleanroom System Status Pill --}}
                    <div class="jm-system-status-pill d-none d-xl-flex">
                        <span class="jm-pulse-dot"></span>
                        <span>Cleanroom Portal Online</span>
                    </div>

                    {{-- User Profile & Role Pill --}}
                    <div class="jm-user-pill">
                        <div class="jm-avatar-badge">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="d-flex flex-column lh-1 pe-1">
                            <span class="small fw-semibold text-white">{{ Auth::user()->name }}</span>
                            <span class="text-white-50" style="font-size: 0.6875rem;">{{ Auth::user()->email }}</span>
                        </div>
                        <span class="jm-role-badge-{{ Auth::user()->isAdmin() ? 'admin' : 'viewer' }} ms-1">
                            {{ Auth::user()->role }}
                        </span>
                    </div>

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm px-3 rounded-pill" title="Sign out of system">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
                @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link jm-nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </a>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main App View Container --}}
    <main class="py-4 flex-grow-1">
        <div class="container-xl">
            {{-- Toast-Style Flash Notifications --}}
            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center rounded-3 mb-4 p-3 alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle me-3" style="width: 34px; height: 34px; flex-shrink: 0;">
                    <i class="bi bi-check-lg fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Success</div>
                    <div class="small">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center rounded-3 mb-4 p-3 alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center justify-content-center bg-info text-white rounded-circle me-3" style="width: 34px; height: 34px; flex-shrink: 0;">
                    <i class="bi bi-info fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Notice</div>
                    <div class="small">{{ session('info') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center rounded-3 mb-4 p-3 alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle me-3" style="width: 34px; height: 34px; flex-shrink: 0;">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Action Required</div>
                    <div class="small">{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Enterprise Footer --}}
    <footer class="jm-footer d-print-none">
        <div class="container-xl">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">Jajal Medical Inc.</span>
                    <span>&mdash; Patient-Specific Surgical Implant Case Management</span>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-3 small text-muted">
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-patch-check text-success"></i> ISO 13485:2016 Certified
                    </span>
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-shield-lock text-primary"></i> FDA 21 CFR Part 11 Compliant
                    </span>
                    <span class="badge bg-light text-secondary border">v2.4.0-prod</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Utilities -->
    <script>
        // Copy to clipboard helper with tooltip feedback
        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text).then(function() {
                const originalHtml = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="bi bi-check2 text-success"></i>';
                btnElement.classList.add('btn-light');
                setTimeout(() => {
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('btn-light');
                }, 1500);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
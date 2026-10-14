<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jajal Medical &mdash; Patient-Specific Implants & Surgical Case Management</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/jajal-medical.css') }}">

    <style>
        .jm-hero-bg {
            background: radial-gradient(circle at 80% 20%, rgba(2, 132, 199, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 20% 80%, rgba(6, 182, 212, 0.12) 0%, transparent 40%),
                        #0f172a;
            color: #ffffff;
            min-height: 85vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .jm-hero-bg::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to top, #f8fafc, transparent);
        }
    </style>
</head>
<body class="bg-light">
    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container-xl">
            <a class="jm-brand-logo" href="{{ url('/') }}">
                <div class="jm-brand-icon-box">
                    <i class="bi bi-shield-plus"></i>
                </div>
                <div>
                    <span class="jm-brand-title">JAJAL MEDICAL</span>
                    <span class="jm-brand-subtitle">SURGICAL CASE PORTAL</span>
                </div>
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Open Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Portal Sign In
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="jm-hero-bg py-5">
        <div class="container-xl position-relative" style="z-index: 2;">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-dark border border-secondary text-info mb-3">
                        <span class="jm-pulse-dot"></span>
                        <span class="small fw-semibold">Next-Gen Patient-Specific Implant Lifecycle</span>
                    </div>
                    <h1 class="display-4 fw-extrabold text-white mb-3 tracking-tight">
                        Precision Surgical Engineering &amp; Implant Dossier Tracking
                    </h1>
                    <p class="lead text-light opacity-75 mb-4">
                        From 3D CT scan intake to cleanroom manufacturing clearance: track patient-specific cranial plates, spinal cages, and orthognathic guides across strict clinical review checkpoints.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 fw-semibold">
                                <i class="bi bi-speedometer2 me-2"></i> Access Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Portal
                            </a>
                        @endauth
                        <a href="{{ route('cases.index') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-folder2-open me-2"></i> Browse Cases
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card jm-card shadow-lg border-0 bg-white text-dark p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div>
                                <span class="badge bg-primary">DEMO WORKFLOW</span>
                                <h5 class="fw-bold mb-0 mt-1">Role-Based Access</h5>
                            </div>
                            <i class="bi bi-shield-check fs-2 text-success"></i>
                        </div>

                        <div class="mb-3">
                            <div class="p-3 bg-light rounded-3 border mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark"><i class="bi bi-person-gear text-primary me-1"></i> Administrator</strong>
                                    <span class="badge bg-primary">Full CRUD</span>
                                </div>
                                <div class="small font-mono text-muted">admin@jajalmedical.com</div>
                            </div>

                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark"><i class="bi bi-eye text-secondary me-1"></i> Clinical Viewer</strong>
                                    <span class="badge bg-secondary">Read Only</span>
                                </div>
                                <div class="small font-mono text-muted">viewer@jajalmedical.com</div>
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 fw-semibold">
                                Enter With Demo Credentials &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Feature Highlights --}}
    <section class="py-5 bg-white">
        <div class="container-xl py-4">
            <div class="text-center mb-5">
                <span class="badge bg-light text-primary border px-3 py-1 rounded-pill fw-semibold text-uppercase">Standards &amp; Capabilities</span>
                <h2 class="fw-bold mt-2 text-dark">Built for Rigorous Surgical Standards</h2>
                <p class="text-muted small">Quality system alignment for custom medical device manufacturers.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="d-inline-flex p-3 rounded-3 bg-primary text-white mb-3 shadow-xs">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark">5-Stage Surgical Lifecycle</h5>
                        <p class="text-muted small mb-0">
                            Rigorous transitions: Draft intake, 3D CAD planning, surgical lead review, cleanroom manufacturing approval, and completed surgery archiving.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="d-inline-flex p-3 rounded-3 bg-success text-white mb-3 shadow-xs">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark">HIPAA &amp; Audit Traceability</h5>
                        <p class="text-muted small mb-0">
                            Strict de-identified patient references, permanent user attribution, and server-side authorization policies preventing unauthorized alterations.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="d-inline-flex p-3 rounded-3 bg-info text-white mb-3 shadow-xs">
                            <i class="bi bi-file-earmark-medical-fill fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Clinical Dossier &amp; Print Sheets</h5>
                        <p class="text-muted small mb-0">
                            Generates printer-optimized surgical checklists, material specifications (Titanium, PEEK), and physical signature verification lines.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="jm-footer">
        <div class="container-xl">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">Jajal Medical Inc.</span>
                    <span>&mdash; Medical Case Management System</span>
                </div>
                <div class="text-muted small">
                    ISO 13485:2016 Certified &bull; FDA 21 CFR Part 11 Compliant
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

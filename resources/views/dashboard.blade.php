@extends('layouts.app')

@section('title', 'Clinical Operations Dashboard')

@section('content')
{{-- Hero Command Banner --}}
<div class="card jm-card border-0 mb-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="card-body p-4 text-white">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary px-3 py-1 rounded-pill text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Clinical Operations</span>
                    <span class="text-white-50 small">&bull; {{ now()->format('l, F d, Y') }}</span>
                </div>
                <h2 class="fw-bold mb-1 text-white">Welcome back, {{ Auth::user()->name }}</h2>
                <p class="text-light opacity-75 mb-0 small">
                    Tracking patient-specific implant lifecycle &mdash; 3D surgical planning, biomechanical review, cleanroom manufacturing, and surgical logistics.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('cases.index') }}" class="btn btn-outline-light px-3">
                    <i class="bi bi-list-task me-1"></i> Directory
                </a>
                @can('create', App\Models\MedicalCase::class)
                <a href="{{ route('cases.create') }}" class="btn btn-primary px-3">
                    <i class="bi bi-plus-lg me-1"></i> New Case
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

{{-- Metric / KPI Stat Cards Grid --}}
<div class="row g-3 mb-4">
    {{-- Total Cases --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index') }}" class="jm-stat-card" style="--stat-accent: #0f172a; --stat-bg: #f1f5f9;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">Total Cases</span>
                <div class="jm-stat-icon-wrap">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
            <div class="jm-stat-value">{{ $counts['total'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-folder-check text-primary"></i> <span>All active dossiers</span>
            </div>
        </a>
    </div>

    {{-- Planning --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index', ['status' => 'Planning']) }}" class="jm-stat-card" style="--stat-accent: #0284c7; --stat-bg: #e0f2fe;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">Planning</span>
                <div class="jm-stat-icon-wrap">
                    <i class="bi bi-pencil-square"></i>
                </div>
            </div>
            <div class="jm-stat-value text-primary">{{ $counts['planning'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-bounding-box text-info"></i> <span>3D CAD / Guides</span>
            </div>
        </a>
    </div>

    {{-- In Review --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index', ['status' => 'In Review']) }}" class="jm-stat-card" style="--stat-accent: #d97706; --stat-bg: #fef3c7;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">In Review</span>
                <div class="jm-stat-icon-wrap" style="color: #d97706; background-color: #fef3c7;">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <div class="jm-stat-value" style="color: #d97706;">{{ $counts['in_review'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-clock-history text-warning"></i> <span>Clinical lead sign-off</span>
            </div>
        </a>
    </div>

    {{-- Approved --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index', ['status' => 'Approved']) }}" class="jm-stat-card" style="--stat-accent: #059669; --stat-bg: #dcfce7;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">Approved</span>
                <div class="jm-stat-icon-wrap" style="color: #059669; background-color: #dcfce7;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="jm-stat-value text-success">{{ $counts['approved'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-box-seam text-success"></i> <span>Cleanroom cleared</span>
            </div>
        </a>
    </div>

    {{-- Completed --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index', ['status' => 'Completed']) }}" class="jm-stat-card" style="--stat-accent: #16a34a; --stat-bg: #f0fdf4;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">Completed</span>
                <div class="jm-stat-icon-wrap" style="color: #16a34a; background-color: #f0fdf4;">
                    <i class="bi bi-check2-all"></i>
                </div>
            </div>
            <div class="jm-stat-value" style="color: #16a34a;">{{ $counts['completed'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-award text-success"></i> <span>Surgeries logged</span>
            </div>
        </a>
    </div>

    {{-- Drafts --}}
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('cases.index', ['status' => 'Draft']) }}" class="jm-stat-card" style="--stat-accent: #64748b; --stat-bg: #f1f5f9;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="jm-stat-label">Drafts</span>
                <div class="jm-stat-icon-wrap" style="color: #64748b; background-color: #f1f5f9;">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
            </div>
            <div class="jm-stat-value text-secondary">{{ $counts['draft'] }}</div>
            <div class="small text-muted d-flex align-items-center gap-1">
                <i class="bi bi-pencil text-secondary"></i> <span>Initial intake</span>
            </div>
        </a>
    </div>
</div>

{{-- Surgical Lifecycle Pipeline Tracker Bar --}}
@php
    $total = max(1, $counts['total']);
    $pctPlanning = round(($counts['planning'] / $total) * 100);
    $pctInReview = round(($counts['in_review'] / $total) * 100);
    $pctApproved = round(($counts['approved'] / $total) * 100);
    $pctCompleted = round(($counts['completed'] / $total) * 100);
    $pctDraft = round(($counts['draft'] / $total) * 100);
@endphp
<div class="jm-pipeline-container shadow-sm">
    <div class="jm-pipeline-header">
        <div>
            <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-diagram-3-fill text-primary"></i> Surgical Lifecycle Pipeline Distribution
            </h6>
            <span class="text-muted small">Live case balance across all active production stages</span>
        </div>
        <span class="badge bg-light text-dark border font-mono">{{ $counts['total'] }} Total Active Cases</span>
    </div>

    <div class="jm-pipeline-track">
        @if($counts['planning'] > 0)
        <div class="jm-pipeline-segment bg-info" style="width: {{ $pctPlanning }}%;" title="Planning: {{ $counts['planning'] }} ({{ $pctPlanning }}%)"></div>
        @endif
        @if($counts['in_review'] > 0)
        <div class="jm-pipeline-segment bg-warning" style="width: {{ $pctInReview }}%;" title="In Review: {{ $counts['in_review'] }} ({{ $pctInReview }}%)"></div>
        @endif
        @if($counts['approved'] > 0)
        <div class="jm-pipeline-segment bg-success" style="width: {{ $pctApproved }}%;" title="Approved: {{ $counts['approved'] }} ({{ $pctApproved }}%)"></div>
        @endif
        @if($counts['completed'] > 0)
        <div class="jm-pipeline-segment bg-primary" style="width: {{ $pctCompleted }}%;" title="Completed: {{ $counts['completed'] }} ({{ $pctCompleted }}%)"></div>
        @endif
        @if($counts['draft'] > 0)
        <div class="jm-pipeline-segment bg-secondary" style="width: {{ $pctDraft }}%;" title="Draft: {{ $counts['draft'] }} ({{ $pctDraft }}%)"></div>
        @endif
    </div>

    <div class="jm-pipeline-legend">
        <div class="jm-pipeline-legend-item">
            <span class="jm-pulse-dot" style="background: #0284c7;"></span>
            <span>Planning: <strong>{{ $counts['planning'] }}</strong></span>
        </div>
        <div class="jm-pipeline-legend-item">
            <span class="jm-pulse-dot" style="background: #d97706;"></span>
            <span>In Review: <strong>{{ $counts['in_review'] }}</strong></span>
        </div>
        <div class="jm-pipeline-legend-item">
            <span class="jm-pulse-dot" style="background: #059669;"></span>
            <span>Approved: <strong>{{ $counts['approved'] }}</strong></span>
        </div>
        <div class="jm-pipeline-legend-item">
            <span class="jm-pulse-dot" style="background: #16a34a;"></span>
            <span>Completed: <strong>{{ $counts['completed'] }}</strong></span>
        </div>
        <div class="jm-pipeline-legend-item">
            <span class="jm-pulse-dot" style="background: #64748b;"></span>
            <span>Drafts: <strong>{{ $counts['draft'] }}</strong></span>
        </div>
    </div>
</div>

{{-- Recently Logged Cases Card --}}
<div class="card jm-card shadow-sm mb-4">
    <div class="jm-card-header">
        <div class="jm-card-title">
            <i class="bi bi-clock-history text-primary"></i>
            <span>Recently Logged Medical Cases</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('cases.index') }}" class="btn btn-outline-primary btn-sm">
                View Full Directory <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table jm-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="ps-4">Case Identifier</th>
                        <th scope="col">Patient Ref</th>
                        <th scope="col">Surgeon Lead</th>
                        <th scope="col">Implant Specification</th>
                        <th scope="col">Stage</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Surgery Date</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCases as $case)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('cases.show', $case) }}" class="text-decoration-none fw-bold font-mono text-primary d-inline-flex align-items-center gap-1">
                                <i class="bi bi-file-earmark-medical"></i>
                                <span>{{ $case->case_number }}</span>
                            </a>
                        </td>
                        <td>
                            <span class="jm-patient-code-badge" title="Anonymized Patient Reference">
                                <i class="bi bi-shield-lock-fill text-muted" style="font-size: 0.7rem;"></i>
                                {{ $case->patient_reference }}
                            </span>
                        </td>
                        <td>
                            <div class="jm-doctor-name">
                                <span class="jm-doctor-avatar">
                                    {{ strtoupper(substr($case->surgeon_name, strpos($case->surgeon_name, ' ') !== false ? strpos($case->surgeon_name, ' ') + 1 : 0, 1)) }}
                                </span>
                                <span>{{ $case->surgeon_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block fw-medium text-dark" style="max-width: 240px;" title="{{ $case->implant_type }}">
                                {{ $case->implant_type }}
                            </span>
                        </td>
                        <td>
                            <span class="jm-status-badge jm-status-badge-{{ Str::slug($case->status) }}">
                                <span class="jm-status-dot"></span>
                                {{ $case->status }}
                            </span>
                        </td>
                        <td>
                            <span class="jm-priority-badge jm-priority-{{ Str::slug($case->priority) }}">
                                @if(strtolower($case->priority) === 'high')
                                    <i class="bi bi-exclamation-octagon-fill"></i>
                                @elseif(strtolower($case->priority) === 'medium')
                                    <i class="bi bi-arrow-up-circle-fill"></i>
                                @else
                                    <i class="bi bi-dash-circle"></i>
                                @endif
                                {{ $case->priority }}
                            </span>
                        </td>
                        <td>
                            @if($case->surgery_date)
                                <div class="d-flex align-items-center gap-1 small text-dark fw-medium">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                    <span>{{ $case->surgery_date->format('M d, Y') }}</span>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Unscheduled</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary" title="View Dossier">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $case)
                                <a href="{{ route('cases.edit', $case) }}" class="btn btn-outline-primary" title="Edit Case">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="p-3 bg-light rounded-circle mb-2">
                                    <i class="bi bi-inbox fs-2 text-secondary"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No medical cases recorded yet</h6>
                                <p class="small text-muted mb-3">Begin by creating your first patient-specific implant case file.</p>
                                @can('create', App\Models\MedicalCase::class)
                                <a href="{{ route('cases.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Create First Case
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Enterprise Clinical & Regulatory Info Banner --}}
<div class="card jm-card bg-light border-0">
    <div class="card-body p-3 p-md-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2 bg-white rounded-3 shadow-xs text-primary border">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">ISO 13485:2016 & FDA 21 CFR Part 11 Design Traceability</h6>
                        <p class="small text-muted mb-0">
                            Every patient-specific surgical implant case maintains cryptographic user attribution. Modification of planning files and stage approval creates an immutable audit log.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-white text-dark border px-3 py-2 font-mono">
                    Session User: {{ Auth::user()->name }} ({{ Auth::user()->role }})
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
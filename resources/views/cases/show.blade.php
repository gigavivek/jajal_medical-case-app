@extends('layouts.app')

@section('title', 'Case File: ' . $case->case_number)

@section('content')
{{-- Print-Only Clinical Letterhead Header --}}
<div class="d-none d-print-block jm-print-header">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0">JAJAL MEDICAL INC.</h2>
            <div class="small text-muted">PATIENT-SPECIFIC IMPLANTS & SURGICAL PLANNING DOSSIER</div>
        </div>
        <div class="text-end">
            <div class="fw-bold font-mono">{{ $case->case_number }}</div>
            <div class="small text-muted">Printed: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>

{{-- Top Action & Breadcrumb Bar --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cases.index') }}" class="text-decoration-none">Medical Cases</a></li>
                <li class="breadcrumb-item active font-mono" aria-current="page">{{ $case->case_number }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <h2 class="fw-bold mb-0 text-dark">Case Dossier: <span class="font-mono text-primary">{{ $case->case_number }}</span></h2>
            <button type="button" class="btn btn-sm btn-light border text-muted" title="Copy Case Number" onclick="copyToClipboard('{{ $case->case_number }}', this)">
                <i class="bi bi-copy"></i>
            </button>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Directory
        </a>
        <button type="button" class="btn btn-outline-dark" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Dossier
        </button>
        @can('update', $case)
        <a href="{{ route('cases.edit', $case) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Case
        </a>
        @endcan
        @can('delete', $case)
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCaseModal">
            <i class="bi bi-trash me-1"></i> Delete
        </button>
        @endcan
    </div>
</div>

{{-- Visual Surgical Lifecycle Stepper --}}
@php
    $stages = ['Draft', 'Planning', 'In Review', 'Approved', 'Completed'];
    $currentStageIndex = array_search($case->status, $stages);
    $isCancelled = strtolower($case->status) === 'cancelled';
@endphp
<div class="jm-stepper shadow-sm d-print-none">
    @if($isCancelled)
        <div class="w-100 text-center py-2 text-danger fw-bold d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-x-octagon-fill fs-4"></i>
            <span>Case Has Been Marked as Cancelled / Superseded</span>
        </div>
    @else
        @foreach($stages as $idx => $st)
            @php
                $statusClass = '';
                if ($currentStageIndex !== false) {
                    if ($idx < $currentStageIndex) {
                        $statusClass = 'completed';
                    } elseif ($idx === $currentStageIndex) {
                        $statusClass = 'active';
                    }
                }
            @endphp
            <div class="jm-stepper-item {{ $statusClass }}">
                <div class="jm-stepper-icon">
                    @if($statusClass === 'completed')
                        <i class="bi bi-check-lg"></i>
                    @elseif($statusClass === 'active')
                        <i class="bi bi-record-circle-fill"></i>
                    @else
                        <span>{{ $idx + 1 }}</span>
                    @endif
                </div>
                <div class="jm-stepper-label">{{ $st }}</div>
            </div>
        @endforeach
    @endif
</div>

{{-- 2-Column Clinical Layout --}}
<div class="row g-4">
    {{-- Left / Primary Column: Clinical & Engineering Specifications --}}
    <div class="col-lg-8">
        {{-- Patient & Surgical Lead Reference Card --}}
        <div class="card jm-card shadow-sm mb-4">
            <div class="jm-card-header">
                <div class="jm-card-title">
                    <i class="bi bi-person-vcard text-primary"></i>
                    <span>Patient & Surgical Team Reference</span>
                </div>
                <span class="badge bg-light text-dark border font-mono">ID: #{{ $case->id }}</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">
                                <i class="bi bi-shield-lock-fill text-primary me-1"></i> Patient Reference Code
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-5 fw-bold font-mono text-dark">{{ $case->patient_reference }}</span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted border-0" onclick="copyToClipboard('{{ $case->patient_reference }}', this)" title="Copy Patient Ref">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;">HIPAA De-Identified Medical Reference</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">
                                <i class="bi bi-person-badge-fill text-info me-1"></i> Primary Surgeon Lead
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="jm-doctor-avatar">
                                    {{ strtoupper(substr($case->surgeon_name, strpos($case->surgeon_name, ' ') !== false ? strpos($case->surgeon_name, ' ') + 1 : 0, 1)) }}
                                </span>
                                <span class="fs-5 fw-bold text-dark">{{ $case->surgeon_name }}</span>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;">Lead Surgical Clinical Investigator</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">
                                <i class="bi bi-calendar-check-fill text-success me-1"></i> Scheduled Surgery Date
                            </span>
                            @if($case->surgery_date)
                                <div class="fs-5 fw-bold text-dark d-flex align-items-center gap-2">
                                    <span>{{ $case->surgery_date->format('F d, Y') }}</span>
                                </div>
                                <span class="text-muted small">
                                    ({{ $case->surgery_date->isFuture() ? 'In ' . $case->surgery_date->diffForHumans(['parts' => 1]) : $case->surgery_date->diffForHumans() }})
                                </span>
                            @else
                                <div class="fs-5 text-muted fst-italic">Pending scheduling</div>
                                <span class="text-muted" style="font-size: 0.75rem;">Surgery date to be confirmed by hospital</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">
                                <i class="bi bi-flag-fill text-warning me-1"></i> Clinical Urgency & Priority
                            </span>
                            <div>
                                <span class="jm-priority-badge jm-priority-{{ Str::slug($case->priority) }} fs-6 px-3 py-1">
                                    @if(strtolower($case->priority) === 'high')
                                        <i class="bi bi-exclamation-octagon-fill"></i>
                                    @elseif(strtolower($case->priority) === 'medium')
                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                    @else
                                        <i class="bi bi-dash-circle"></i>
                                    @endif
                                    Priority: {{ $case->priority }}
                                </span>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;">Manufacturing queue priority rank</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Implant Engineering Specifications Card --}}
        <div class="card jm-card shadow-sm mb-4">
            <div class="jm-card-header">
                <div class="jm-card-title">
                    <i class="bi bi-cpu text-primary"></i>
                    <span>Implant Engineering & Manufacturing Specification</span>
                </div>
                <span class="badge bg-light text-secondary border font-mono">Custom Device</span>
            </div>
            <div class="card-body p-4">
                <div class="p-3 rounded-3 mb-3 border-start border-4 border-primary bg-light">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Implant Specification / Description</span>
                    <h5 class="fw-bold text-dark mb-0 font-monospace">{{ $case->implant_type }}</h5>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded border">
                            <span class="text-muted small d-block">Biocompatible Material Class</span>
                            <strong class="text-dark">Medical Ti6Al4V ELI / PEEK Optima</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded border">
                            <span class="text-muted small d-block">Manufacturing Process</span>
                            <strong class="text-dark">Direct Metal Laser Sintering (DMLS 3D)</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded border">
                            <span class="text-muted small d-block">Sterilization Requirement</span>
                            <strong class="text-dark">Cleanroom ISO Class 7 / Gamma 25kGy</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded border">
                            <span class="text-muted small d-block">Regulatory Device Standard</span>
                            <strong class="text-dark">Custom-Made Device (MDR 2017/745)</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right / Sidebar Column: Audit Trail & Quality Governance --}}
    <div class="col-lg-4">
        {{-- Lifecycle Status Card --}}
        <div class="card jm-card shadow-sm mb-4">
            <div class="jm-card-header">
                <div class="jm-card-title">
                    <i class="bi bi-activity text-primary"></i>
                    <span>Lifecycle Status</span>
                </div>
            </div>
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <span class="jm-status-badge jm-status-badge-{{ Str::slug($case->status) }} fs-5 px-3 py-2">
                        <span class="jm-status-dot" style="width: 10px; height: 10px;"></span>
                        {{ $case->status }}
                    </span>
                </div>

                <p class="small text-muted mb-0">
                    @if($case->status === 'Draft')
                        Initial case data collected; waiting for CT/MRI scan DICOM upload.
                    @elseif($case->status === 'Planning')
                        3D virtual surgical planning and CAD/CAM modeling in progress.
                    @elseif($case->status === 'In Review')
                        Under multi-disciplinary review by surgical leads and biomechanical engineers.
                    @elseif($case->status === 'Approved')
                        Approved for sterile cleanroom 3D additive manufacturing.
                    @elseif($case->status === 'Completed')
                        Implant surgery successfully performed and recorded in clinical registry.
                    @elseif($case->status === 'Cancelled')
                        Case superseded or discontinued.
                    @endif
                </p>
            </div>
        </div>

        {{-- Audit Trail & Ownership Card --}}
        <div class="card jm-card shadow-sm mb-4">
            <div class="jm-card-header">
                <div class="jm-card-title">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span>Audit Trail & Provenance</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <div class="jm-avatar-badge" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        {{ strtoupper(substr($case->creator->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-muted small d-block">Case Logged By:</span>
                        <strong class="text-dark">{{ $case->creator->name ?? 'System User' }}</strong>
                        <span class="badge bg-secondary ms-1 text-uppercase" style="font-size: 0.65rem;">
                            {{ $case->creator->role ?? 'viewer' }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block fw-semibold">Record Created:</span>
                    <div class="small font-mono text-dark">{{ $case->created_at->format('Y-m-d H:i:s') }}</div>
                    <div class="text-muted small">{{ $case->created_at->diffForHumans() }}</div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block fw-semibold">Last State Revision:</span>
                    <div class="small font-mono text-dark">{{ $case->updated_at->format('Y-m-d H:i:s') }}</div>
                    <div class="text-muted small">{{ $case->updated_at->diffForHumans() }}</div>
                </div>

                <div class="p-2 bg-light rounded text-muted small font-mono d-flex align-items-center justify-content-between">
                    <span>Audit Hash:</span>
                    <span class="text-dark">{{ substr(md5($case->id . $case->case_number . $case->created_at), 0, 12) }}</span>
                </div>
            </div>
        </div>

        {{-- Quality Assurance Notice --}}
        <div class="card jm-card bg-light border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-patch-check-fill text-success fs-5 mt-1"></i>
                    <div class="small text-muted">
                        <strong>ISO 13485:2016 Compliant:</strong> Case modification and status transitions require authenticated authorization. Author attribution is permanent.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Print-Only Clinical Verification Sign-Off Footer --}}
<div class="d-none d-print-block jm-print-footer">
    <div class="row pt-4">
        <div class="col-6">
            <div class="border-top pt-2">
                <strong>Primary Operating Surgeon Signature:</strong>
                <div class="mt-4 text-muted small">Dr. {{ $case->surgeon_name }} / Date: ____________</div>
            </div>
        </div>
        <div class="col-6">
            <div class="border-top pt-2">
                <strong>Quality Assurance / Cleanroom Lead:</strong>
                <div class="mt-4 text-muted small">Quality Sign-Off / Date: ____________</div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
@can('delete', $case)
<div class="modal fade" id="deleteCaseModal" tabindex="-1" aria-labelledby="deleteCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteCaseModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Permanent Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p>Are you sure you want to permanently delete medical case <strong class="font-mono text-danger">{{ $case->case_number }}</strong>?</p>
                <div class="alert alert-warning small mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation fs-4"></i>
                    <div>
                        <strong>Permanent Action:</strong> This action cannot be undone. All audit and implant tracking references will be removed from the system.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('cases.destroy', $case) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-trash-fill me-1"></i> Yes, Permanently Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
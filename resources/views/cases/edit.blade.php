@extends('layouts.app')

@section('title', 'Edit Case: ' . $case->case_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        {{-- Breadcrumbs & Header --}}
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cases.index') }}" class="text-decoration-none">Medical Cases</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cases.show', $case) }}" class="text-decoration-none font-mono">{{ $case->case_number }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h2 class="fw-bold mb-1 text-dark">
                        Edit Surgical Case: <span class="font-mono text-primary">{{ $case->case_number }}</span>
                    </h2>
                    <p class="text-muted small mb-0">Update surgical details, CAD/CAM implant parameters, or transition lifecycle status.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="jm-status-badge jm-status-badge-{{ Str::slug($case->status) }}">
                        <span class="jm-status-dot"></span>
                        {{ $case->status }}
                    </span>
                    <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x me-1"></i> Close
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card jm-card shadow-sm border-0 mb-4">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('cases.update', $case) }}" novalidate>
                    @csrf
                    @method('PUT')

                    @include('cases._form')

                    <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i>
                            <span>Update Medical Case</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
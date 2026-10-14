@extends('layouts.app')

@section('title', 'Log New Surgical Case')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        {{-- Breadcrumb & Header --}}
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cases.index') }}" class="text-decoration-none">Medical Cases</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Case</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1 text-dark">Log New Medical Case</h2>
                    <p class="text-muted small mb-0">Initialize surgical planning file and implant manufacturing specifications.</p>
                </div>
                <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Directory
                </a>
            </div>
        </div>

        {{-- Main Form Card --}}
        <div class="card jm-card shadow-sm border-0 mb-4">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('cases.store') }}" novalidate>
                    @csrf

                    @include('cases._form')

                    <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i>
                            <span>Save Medical Case</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
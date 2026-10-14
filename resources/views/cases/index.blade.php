@extends('layouts.app')

@section('title', 'Medical Cases Directory')

@section('content')
{{-- Directory Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="fw-bold mb-0 text-dark">Medical Cases Directory</h2>
            <span class="badge bg-light text-primary border font-mono px-2.5 py-1 rounded-pill" style="font-size: 0.8rem;">
                {{ $cases->total() }} Total Cases
            </span>
        </div>
        <p class="text-muted mb-0 small">Browse, inspect, filter, and audit patient-specific surgical implant case files.</p>
    </div>
    @can('create', App\Models\MedicalCase::class)
    <div>
        <a href="{{ route('cases.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>New Surgical Case</span>
        </a>
    </div>
    @endcan
</div>

{{-- Quick Status Filter Tabs / Chips --}}
<div class="jm-filter-pills d-print-none">
    <a href="{{ route('cases.index', array_merge(request()->except(['status', 'page']))) }}"
       class="jm-filter-pill {{ empty($status) ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i> All Cases
    </a>
    @foreach($statuses as $st)
    <a href="{{ route('cases.index', array_merge(request()->except(['page']), ['status' => $st])) }}"
       class="jm-filter-pill {{ $status === $st ? 'active' : '' }}">
        <span class="jm-status-dot" style="background-color: var(--jm-status-{{ Str::slug($st) }}, #0284c7);"></span>
        {{ $st }}
    </a>
    @endforeach
</div>

{{-- Search & Refinement Filter Bar --}}
<div class="card jm-card shadow-sm border-0 mb-4 d-print-none">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('cases.index') }}" class="row g-2 align-items-center">
            {{-- Search Input --}}
            <div class="col-lg-5 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by Case Number, Surgeon, or Patient Ref..."
                           value="{{ $search }}">
                    @if(!empty($search))
                    <a href="{{ route('cases.index', request()->except(['search', 'page'])) }}" class="btn btn-outline-secondary" title="Clear search">
                        <i class="bi bi-x"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Status Dropdown --}}
            <div class="col-lg-3 col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Lifecycle Stages</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                            Stage: {{ $st }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Per Page Dropdown --}}
            <div class="col-lg-2 col-md-2">
                <select name="per_page" class="form-select" title="Items per page" onchange="this.form.submit()">
                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 / page</option>
                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 / page</option>
                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 / page</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="col-lg-2 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-funnel"></i> Apply
                </button>
                @if(!empty($search) || !empty($status) || ($perPage ?? 10) != 10)
                <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary" title="Reset all filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Cases Directory Table --}}
<div class="card jm-card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table jm-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="ps-4">Case Number</th>
                        <th scope="col">Patient Ref</th>
                        <th scope="col">Surgeon Lead</th>
                        <th scope="col">Implant Specification</th>
                        <th scope="col">Status</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Surgery Date</th>
                        <th scope="col">Logged By</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cases as $case)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('cases.show', $case) }}" class="text-decoration-none fw-bold font-mono text-primary">
                                    {{ $case->case_number }}
                                </a>
                                <button type="button" class="btn btn-sm btn-link text-muted p-0 ms-1 border-0" title="Copy case number" onclick="copyToClipboard('{{ $case->case_number }}', this)">
                                    <i class="bi bi-copy" style="font-size: 0.75rem;"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <span class="jm-patient-code-badge" title="Anonymized Patient Reference">
                                    <i class="bi bi-shield-lock-fill text-muted" style="font-size: 0.7rem;"></i>
                                    {{ $case->patient_reference }}
                                </span>
                            </div>
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
                            <span class="text-truncate d-inline-block fw-medium text-dark" style="max-width: 220px;" title="{{ $case->implant_type }}">
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
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    <span>{{ $case->surgery_date->format('M d, Y') }}</span>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Unscheduled</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border small font-mono">
                                {{ $case->creator->name ?? 'System' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary" title="Inspect Case Dossier">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $case)
                                <a href="{{ route('cases.edit', $case) }}" class="btn btn-outline-primary" title="Edit Case">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete', $case)
                                <button type="button"
                                        class="btn btn-outline-danger"
                                        title="Delete Case"
                                        onclick="confirmDelete('{{ $case->case_number }}', '{{ route('cases.destroy', $case) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="p-3 bg-light rounded-circle mb-2">
                                    <i class="bi bi-folder-x fs-1 text-secondary"></i>
                                </div>
                                <h6 class="fw-bold mb-1">No medical cases matched your query</h6>
                                <p class="small text-muted mb-3">Try adjusting your keyword filter or resetting stage criteria.</p>
                                @if(!empty($search) || !empty($status))
                                <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filter Criteria
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Enhanced Pagination Footer --}}
    @if($cases->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3 px-4">
        <div class="small text-muted">
            Displaying <strong class="text-dark">{{ $cases->firstItem() }}</strong> to <strong class="text-dark">{{ $cases->lastItem() }}</strong> of <strong class="text-dark">{{ $cases->total() }}</strong> surgical cases
            <span class="badge bg-light text-secondary border ms-1 font-mono">{{ $perPage ?? 10 }} per page</span>
        </div>
        <div class="pagination-wrapper">
            {{ $cases->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Global Delete Confirmation Modal --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Case Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">
                    Are you sure you want to permanently delete medical case <strong id="deleteCaseNumber" class="font-mono text-danger"></strong>?
                </p>
                <div class="alert alert-warning small mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-exclamation fs-4"></i>
                    <div>
                        <strong>Irreversible Action:</strong> All associated surgical planning references, CAD specifications, and audit traces for this case will be permanently purged.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
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
@endsection

@push('scripts')
<script>
    function confirmDelete(caseNumber, deleteUrl) {
        document.getElementById('deleteCaseNumber').textContent = caseNumber;
        document.getElementById('deleteForm').action = deleteUrl;
        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }
</script>
@endpush
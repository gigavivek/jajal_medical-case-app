<div class="row g-4">
    {{-- Section 1: Identification & Clinical References --}}
    <div class="col-12">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 24px; height: 24px; font-size: 0.75rem; font-weight: 700;">1</span>
            <h6 class="fw-bold mb-0 text-dark">Case Identification & Patient Reference</h6>
        </div>
    </div>

    {{-- Case Number --}}
    <div class="col-md-6">
        <label for="case_number" class="jm-form-label">
            Case Number <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-hash text-primary"></i></span>
            <input type="text"
                   name="case_number"
                   id="case_number"
                   class="form-control font-mono @error('case_number') is-invalid @enderror"
                   value="{{ old('case_number', $case->case_number) }}"
                   placeholder="e.g. CASE-0001"
                   required>
            @error('case_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if(!$errors->has('case_number'))
            <div class="form-text small text-muted">Must be a unique dossier reference across the medical system.</div>
        @endif
    </div>

    {{-- Patient Reference Anonymized Code --}}
    <div class="col-md-6">
        <label for="patient_reference" class="jm-form-label">
            Patient Reference Code <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-lock text-primary"></i></span>
            <input type="text"
                   name="patient_reference"
                   id="patient_reference"
                   class="form-control font-mono @error('patient_reference') is-invalid @enderror"
                   value="{{ old('patient_reference', $case->patient_reference) }}"
                   placeholder="e.g. JM-PT-10023"
                   required>
            @error('patient_reference')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if(!$errors->has('patient_reference'))
            <div class="form-text small text-muted">HIPAA de-identified patient code (no real patient names or PII).</div>
        @endif
    </div>

    {{-- Section 2: Surgical Team & Schedule --}}
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 24px; height: 24px; font-size: 0.75rem; font-weight: 700;">2</span>
            <h6 class="fw-bold mb-0 text-dark">Surgical Lead & Hospital Schedule</h6>
        </div>
    </div>

    {{-- Surgeon Name --}}
    <div class="col-md-6">
        <label for="surgeon_name" class="jm-form-label">
            Surgeon Name <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge text-primary"></i></span>
            <input type="text"
                   name="surgeon_name"
                   id="surgeon_name"
                   class="form-control @error('surgeon_name') is-invalid @enderror"
                   value="{{ old('surgeon_name', $case->surgeon_name) }}"
                   placeholder="e.g. Dr. Rajesh Jajal, MD"
                   required>
            @error('surgeon_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Surgery Date --}}
    <div class="col-md-6">
        <label for="surgery_date" class="jm-form-label">
            Scheduled Surgery Date
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-calendar-event text-primary"></i></span>
            <input type="date"
                   name="surgery_date"
                   id="surgery_date"
                   class="form-control @error('surgery_date') is-invalid @enderror"
                   value="{{ old('surgery_date', $case->surgery_date ? $case->surgery_date->format('Y-m-d') : '') }}">
            @error('surgery_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        @if(!$errors->has('surgery_date'))
            <div class="form-text small text-muted">Optional estimated or confirmed surgery date.</div>
        @endif
    </div>

    {{-- Section 3: Implant Engineering & Material Specifications --}}
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 24px; height: 24px; font-size: 0.75rem; font-weight: 700;">3</span>
            <h6 class="fw-bold mb-0 text-dark">Implant Engineering Specifications</h6>
        </div>
    </div>

    {{-- Implant Type --}}
    <div class="col-12">
        <label for="implant_type" class="jm-form-label">
            Implant Specification / Device Description <span class="text-danger">*</span>
        </label>
        <div class="input-group mb-2">
            <span class="input-group-text"><i class="bi bi-cpu text-primary"></i></span>
            <input type="text"
                   name="implant_type"
                   id="implant_type"
                   class="form-control @error('implant_type') is-invalid @enderror"
                   value="{{ old('implant_type', $case->implant_type) }}"
                   placeholder="e.g. Titanium Cranial Mesh, PEEK Spinal Cage, Mandibular Reconstruction Plate"
                   required>
            @error('implant_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Quick Suggestion Chips --}}
        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
            <span class="small text-muted fw-semibold" style="font-size: 0.75rem;">Common Specs:</span>
            <span class="jm-chip" onclick="setImplantSpec('Titanium 3D Cranial Plate')">
                <i class="bi bi-plus"></i> Titanium Cranial Plate
            </span>
            <span class="jm-chip" onclick="setImplantSpec('PEEK Cervical Spinal Cage')">
                <i class="bi bi-plus"></i> PEEK Spinal Cage
            </span>
            <span class="jm-chip" onclick="setImplantSpec('Mandibular Reconstruction Plate & Mesh')">
                <i class="bi bi-plus"></i> Mandibular Plate
            </span>
            <span class="jm-chip" onclick="setImplantSpec('Patient-Specific Orthognathic Splint')">
                <i class="bi bi-plus"></i> Orthognathic Splint
            </span>
            <span class="jm-chip" onclick="setImplantSpec('Custom Acetabular Reconstruction Cup')">
                <i class="bi bi-plus"></i> Acetabular Cup
            </span>
        </div>
    </div>

    {{-- Section 4: Workflow Stage & Quality Priority --}}
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 24px; height: 24px; font-size: 0.75rem; font-weight: 700;">4</span>
            <h6 class="fw-bold mb-0 text-dark">Production Stage & Quality Priority</h6>
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <label for="status" class="jm-form-label">
            Lifecycle Status <span class="text-danger">*</span>
        </label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="" disabled {{ old('status', $case->status) ? '' : 'selected' }}>Select lifecycle stage...</option>
            @foreach($statuses as $st)
                <option value="{{ $st }}" {{ old('status', $case->status) === $st ? 'selected' : '' }}>
                    {{ $st }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text small text-muted">Controls movement through design, review, and cleanroom clearance.</div>
        @enderror
    </div>

    {{-- Priority --}}
    <div class="col-md-6">
        <label for="priority" class="jm-form-label">
            Clinical Priority <span class="text-danger">*</span>
        </label>
        <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
            <option value="" disabled {{ old('priority', $case->priority) ? '' : 'selected' }}>Select urgency level...</option>
            @foreach($priorities as $pr)
                <option value="{{ $pr }}" {{ old('priority', $case->priority) === $pr ? 'selected' : '' }}>
                    Priority: {{ $pr }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text small text-muted">Determines surgical engineering and 3D printing queue order.</div>
        @enderror
    </div>

    {{-- Audit & Security Notice --}}
    <div class="col-12 mt-4">
        <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
            <div class="p-2 bg-white rounded-circle shadow-xs text-primary border">
                <i class="bi bi-shield-check fs-4"></i>
            </div>
            <div class="small text-muted">
                <strong class="text-dark">Regulatory Audit Integrity:</strong>
                Record author attribution is locked to the authenticated user ({{ Auth::user()->name }}). All modifications and revisions are permanently tracked for ISO 13485:2016 design compliance.
            </div>
        </div>
    </div>
</div>

<script>
    function setImplantSpec(value) {
        const input = document.getElementById('implant_type');
        input.value = value;
        input.focus();
    }
</script>
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MedicalCaseController extends Controller
{
    /**
     * Display a paginated listing of medical cases with search and filter.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MedicalCase::class);

        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $query = MedicalCase::with('creator');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('surgeon_name', 'like', "%{$search}%")
                  ->orWhere('patient_reference', 'like', "%{$search}%");
            });
        }

        if (!empty($status) && in_array($status, MedicalCase::STATUSES)) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 10;
        }

        $cases = $query->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $statuses = MedicalCase::STATUSES;

        return view('cases.index', compact('cases', 'search', 'status', 'statuses', 'perPage'));
    }

    /**
     * Show the form for creating a new medical case.
     */
    public function create(): View
    {
        $this->authorize('create', MedicalCase::class);

        $case = new MedicalCase();
        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.create', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Store a newly created medical case in storage.
     */
    public function store(StoreCaseRequest $request): RedirectResponse
    {
        $this->authorize('create', MedicalCase::class);

        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        $case = MedicalCase::create($validated);

        return redirect()->route('cases.show', $case)
            ->with('success', "Medical Case {$case->case_number} created successfully.");
    }

    /**
     * Display the specified medical case.
     */
    public function show(MedicalCase $case): View
    {
        $this->authorize('view', $case);

        $case->load('creator');

        return view('cases.show', compact('case'));
    }

    /**
     * Show the form for editing the specified medical case.
     */
    public function edit(MedicalCase $case): View
    {
        $this->authorize('update', $case);

        $statuses = MedicalCase::STATUSES;
        $priorities = MedicalCase::PRIORITIES;

        return view('cases.edit', compact('case', 'statuses', 'priorities'));
    }

    /**
     * Update the specified medical case in storage.
     */
    public function update(UpdateCaseRequest $request, MedicalCase $case): RedirectResponse
    {
        $this->authorize('update', $case);

        $validated = $request->validated();
        // Prevent altering the original creator
        unset($validated['created_by']);

        $case->update($validated);

        return redirect()->route('cases.show', $case)
            ->with('success', "Medical Case {$case->case_number} updated successfully.");
    }

    /**
     * Remove the specified medical case from storage.
     */
    public function destroy(MedicalCase $case): RedirectResponse
    {
        $this->authorize('delete', $case);

        $caseNumber = $case->case_number;
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', "Medical Case {$caseNumber} deleted successfully.");
    }
}
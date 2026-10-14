<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiMedicalCaseController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of medical cases.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', MedicalCase::class);

        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $query = MedicalCase::with('creator:id,name,email,role');

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

        $perPage = min((int) $request->input('per_page', 10), 50);
        $cases = $query->latest('id')->paginate($perPage);

        return $this->successResponse($cases, 'Cases retrieved successfully.', 200);
    }

    /**
     * Store a newly created medical case.
     */
    public function store(StoreCaseRequest $request): JsonResponse
    {
        $this->authorize('create', MedicalCase::class);

        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $case = MedicalCase::create($validated);
        $case->load('creator:id,name,email,role');

        return $this->successResponse($case, 'Medical case created successfully.', 201);
    }

    /**
     * Display the specified medical case.
     */
    public function show(string|int $id): JsonResponse
    {
        $case = MedicalCase::with('creator:id,name,email,role')->find($id);

        if (!$case) {
            return $this->errorResponse('Medical case not found.', 404);
        }

        $this->authorize('view', $case);

        return $this->successResponse($case, 'Medical case retrieved successfully.', 200);
    }

    /**
     * Update the specified medical case.
     */
    public function update(UpdateCaseRequest $request, string|int $id): JsonResponse
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return $this->errorResponse('Medical case not found.', 404);
        }

        $this->authorize('update', $case);

        $validated = $request->validated();
        unset($validated['created_by']);

        $case->update($validated);
        $case->load('creator:id,name,email,role');

        return $this->successResponse($case, 'Medical case updated successfully.', 200);
    }

    /**
     * Remove the specified medical case.
     */
    public function destroy(string|int $id): JsonResponse
    {
        $case = MedicalCase::find($id);

        if (!$case) {
            return $this->errorResponse('Medical case not found.', 404);
        }

        $this->authorize('delete', $case);

        $case->delete();

        return $this->successResponse(null, 'Medical case deleted successfully.', 200);
    }
}
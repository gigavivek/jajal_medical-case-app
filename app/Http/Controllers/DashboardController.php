<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display medical cases summary dashboard.
     */
    public function index(): View
    {
        $counts = [
            'total' => MedicalCase::count(),
            'draft' => MedicalCase::where('status', MedicalCase::STATUS_DRAFT)->count(),
            'planning' => MedicalCase::where('status', MedicalCase::STATUS_PLANNING)->count(),
            'in_review' => MedicalCase::where('status', MedicalCase::STATUS_IN_REVIEW)->count(),
            'approved' => MedicalCase::where('status', MedicalCase::STATUS_APPROVED)->count(),
            'completed' => MedicalCase::where('status', MedicalCase::STATUS_COMPLETED)->count(),
            'cancelled' => MedicalCase::where('status', MedicalCase::STATUS_CANCELLED)->count(),
        ];

        $recentCases = MedicalCase::with('creator')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact('counts', 'recentCases'));
    }
}
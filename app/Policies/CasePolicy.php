<?php

namespace App\Policies;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any medical cases.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isViewer();
    }

    /**
     * Determine whether the user can view the specific medical case.
     */
    public function view(User $user, MedicalCase $case): bool
    {
        return $user->isAdmin() || $user->isViewer();
    }

    /**
     * Determine whether the user can create medical cases.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the medical case.
     */
    public function update(User $user, MedicalCase $case): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the medical case.
     */
    public function delete(User $user, MedicalCase $case): bool
    {
        return $user->isAdmin();
    }
}
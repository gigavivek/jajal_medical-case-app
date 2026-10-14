<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiMedicalCaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes (Sanctum Authenticated)
|--------------------------------------------------------------------------
*/

// Public Authentication Endpoints
Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');

// Protected API Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');

    Route::get('/cases', [ApiMedicalCaseController::class, 'index'])->name('api.cases.index');
    Route::post('/cases', [ApiMedicalCaseController::class, 'store'])->name('api.cases.store');
    Route::get('/cases/{id}', [ApiMedicalCaseController::class, 'show'])->name('api.cases.show');
    Route::put('/cases/{id}', [ApiMedicalCaseController::class, 'update'])->name('api.cases.update');
    Route::delete('/cases/{id}', [ApiMedicalCaseController::class, 'destroy'])->name('api.cases.destroy');
});
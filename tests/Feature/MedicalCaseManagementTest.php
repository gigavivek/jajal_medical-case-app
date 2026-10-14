<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalCaseManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Requirement 1: Valid login succeeds (Web & API).
     */
    public function test_valid_login_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'doctor@jajalmedical.com',
            'password' => bcrypt('SecurePassword123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // 1. Web session login test
        $webResponse = $this->post('/login', [
            'email' => 'doctor@jajalmedical.com',
            'password' => 'SecurePassword123',
        ]);

        $webResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // 2. Sanctum API login test
        $apiResponse = $this->postJson('/api/login', [
            'email' => 'doctor@jajalmedical.com',
            'password' => 'SecurePassword123',
        ]);

        $apiResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Authenticated successfully.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'role'],
                ],
            ]);
    }

    /**
     * Requirement 2: Invalid login is rejected (Web & API).
     */
    public function test_invalid_login_is_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'tester@jajalmedical.com',
            'password' => bcrypt('CorrectPassword123'),
        ]);

        // 1. Web login with bad password
        $webResponse = $this->from('/login')->post('/login', [
            'email' => 'tester@jajalmedical.com',
            'password' => 'WrongPassword',
        ]);

        $webResponse->assertRedirect('/login');
        $webResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        // 2. API login with bad password
        $apiResponse = $this->postJson('/api/login', [
            'email' => 'tester@jajalmedical.com',
            'password' => 'WrongPassword',
        ]);

        $apiResponse->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials provided.',
            ]);
    }

    /**
     * Requirement 3: A guest cannot open case pages or APIs (401 / redirect).
     */
    public function test_guest_cannot_open_case_pages_or_apis(): void
    {
        $case = MedicalCase::factory()->create();

        // Guest attempting web pages -> redirected to login
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/cases')->assertRedirect('/login');
        $this->get('/cases/create')->assertRedirect('/login');
        $this->get('/cases/' . $case->id)->assertRedirect('/login');
        $this->get('/cases/' . $case->id . '/edit')->assertRedirect('/login');

        // Guest attempting API endpoints -> 401 Unauthenticated
        $this->getJson('/api/cases')->assertStatus(401);
        $this->getJson('/api/cases/' . $case->id)->assertStatus(401);
        $this->postJson('/api/cases', [])->assertStatus(401);
        $this->putJson('/api/cases/' . $case->id, [])->assertStatus(401);
        $this->deleteJson('/api/cases/' . $case->id)->assertStatus(401);
    }

    /**
     * Requirement 4: Admin can create a medical case (Web & API).
     */
    public function test_admin_can_create_a_case(): void
    {
        $admin = User::factory()->admin()->create();

        $casePayload = [
            'case_number' => 'CASE-TEST-1001',
            'patient_reference' => 'PT-REF-99201',
            'surgeon_name' => 'Dr. Rajesh Jajal',
            'implant_type' => 'Titanium 3D Cranial Plate',
            'status' => MedicalCase::STATUS_PLANNING,
            'priority' => MedicalCase::PRIORITY_HIGH,
            'surgery_date' => '2026-11-15',
        ];

        // 1. Web creation
        $webResponse = $this->actingAs($admin)->post('/cases', $casePayload);
        $webResponse->assertRedirect();
        $this->assertDatabaseHas('medical_cases', [
            'case_number' => 'CASE-TEST-1001',
            'created_by' => $admin->id,
        ]);

        // 2. API creation with Sanctum token
        $token = $admin->createToken('admin-test-token')->plainTextToken;

        $apiPayload = [
            'case_number' => 'CASE-TEST-1002',
            'patient_reference' => 'PT-REF-99202',
            'surgeon_name' => 'Dr. Meera Iyer',
            'implant_type' => 'PEEK Spinal Cage',
            'status' => MedicalCase::STATUS_APPROVED,
            'priority' => MedicalCase::PRIORITY_MEDIUM,
            'surgery_date' => '2026-12-01',
        ];

        $apiResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cases', $apiPayload);

        $apiResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Medical case created successfully.',
            ])
            ->assertJsonPath('data.case_number', 'CASE-TEST-1002')
            ->assertJsonPath('data.created_by', $admin->id);

        $this->assertDatabaseHas('medical_cases', [
            'case_number' => 'CASE-TEST-1002',
            'created_by' => $admin->id,
        ]);
    }

    /**
     * Requirement 5: Creating a case with a duplicate Case Number fails (422).
     */
    public function test_creating_a_case_with_a_duplicate_case_number_fails(): void
    {
        $admin = User::factory()->admin()->create();

        // Create initial case
        MedicalCase::factory()->create([
            'case_number' => 'CASE-DUPLICATE-001',
            'created_by' => $admin->id,
        ]);

        $duplicatePayload = [
            'case_number' => 'CASE-DUPLICATE-001', // Duplicate!
            'patient_reference' => 'PT-REF-88401',
            'surgeon_name' => 'Dr. Vikram Malhotra',
            'implant_type' => 'Ceramic Hip Stem',
            'status' => MedicalCase::STATUS_PLANNING,
            'priority' => MedicalCase::PRIORITY_LOW,
            'surgery_date' => '2026-11-20',
        ];

        // 1. API verification (returns HTTP 422 with field-level errors)
        $token = $admin->createToken('admin-token')->plainTextToken;

        $apiResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cases', $duplicatePayload);

        $apiResponse->assertStatus(422)
            ->assertJsonValidationErrors(['case_number'])
            ->assertJson([
                'success' => false,
            ]);

        // 2. Web form verification
        $webResponse = $this->actingAs($admin)->post('/cases', $duplicatePayload);
        $webResponse->assertSessionHasErrors(['case_number']);
    }

    /**
     * Requirement 6: Viewer cannot delete a case (403).
     */
    public function test_viewer_cannot_delete_a_case(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->viewer()->create();

        $case = MedicalCase::factory()->create([
            'case_number' => 'CASE-PRESERVED-001',
            'created_by' => $admin->id,
        ]);

        // 1. Web attempt by Viewer -> 403 Forbidden
        $webResponse = $this->actingAs($viewer)->delete('/cases/' . $case->id);
        $webResponse->assertStatus(403);

        // Case must still exist in database
        $this->assertDatabaseHas('medical_cases', ['id' => $case->id]);

        // 2. API attempt by Viewer -> 403 Forbidden
        $viewerToken = $viewer->createToken('viewer-token')->plainTextToken;

        $apiResponse = $this->withHeader('Authorization', 'Bearer ' . $viewerToken)
            ->deleteJson('/api/cases/' . $case->id);

        $apiResponse->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ]);

        // Verify case is still untouched
        $this->assertDatabaseHas('medical_cases', ['id' => $case->id]);
    }

    /**
     * Additional Security Test: Viewer can view cases but cannot create or update.
     */
    public function test_viewer_can_view_cases_but_cannot_create_or_update(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->viewer()->create();

        $case = MedicalCase::factory()->create(['created_by' => $admin->id]);

        // Viewer CAN view listing and details
        $this->actingAs($viewer)->get('/cases')->assertStatus(200);
        $this->actingAs($viewer)->get('/cases/' . $case->id)->assertStatus(200);

        // Viewer CANNOT access create form or submit new case
        $this->actingAs($viewer)->get('/cases/create')->assertStatus(403);
        $this->actingAs($viewer)->post('/cases', [
            'case_number' => 'CASE-UNAUTHORIZED',
            'patient_reference' => 'PT-REF-000',
            'surgeon_name' => 'Dr. Nobody',
            'implant_type' => 'None',
            'status' => MedicalCase::STATUS_DRAFT,
            'priority' => MedicalCase::PRIORITY_LOW,
        ])->assertStatus(403);

        // Viewer CANNOT access edit form or update case
        $this->actingAs($viewer)->get('/cases/' . $case->id . '/edit')->assertStatus(403);
        $this->actingAs($viewer)->put('/cases/' . $case->id, [
            'case_number' => $case->case_number,
            'patient_reference' => 'PT-REF-ALTERED',
            'surgeon_name' => 'Dr. Altered',
            'implant_type' => 'Altered',
            'status' => MedicalCase::STATUS_APPROVED,
            'priority' => MedicalCase::PRIORITY_HIGH,
        ])->assertStatus(403);
    }

    /**
     * Requirement: Medical cases display 10 details per page with pagination.
     */
    public function test_cases_are_paginated_at_ten_records_per_page(): void
    {
        $admin = User::factory()->admin()->create();

        // Create 25 medical cases
        MedicalCase::factory()->count(25)->create([
            'created_by' => $admin->id,
        ]);

        // 1. Web test: First page returns exactly 10 cases
        $response = $this->actingAs($admin)->get('/cases');
        $response->assertStatus(200);

        $paginatedCases = $response->viewData('cases');
        $this->assertEquals(10, $paginatedCases->perPage());
        $this->assertEquals(10, $paginatedCases->count());
        $this->assertEquals(25, $paginatedCases->total());
        $this->assertEquals(3, $paginatedCases->lastPage());
        $this->assertEquals(1, $paginatedCases->currentPage());

        // Second page returns 10 items
        $page2Response = $this->actingAs($admin)->get('/cases?page=2');
        $page2Response->assertStatus(200);
        $page2Cases = $page2Response->viewData('cases');
        $this->assertEquals(10, $page2Cases->count());
        $this->assertEquals(2, $page2Cases->currentPage());

        // 2. API test: Returns 10 items with standard pagination metadata
        $token = $admin->createToken('test-token')->plainTextToken;
        $apiResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cases');

        $apiResponse->assertStatus(200)
            ->assertJsonPath('data.per_page', 10)
            ->assertJsonPath('data.total', 25)
            ->assertJsonPath('data.current_page', 1);

        $this->assertCount(10, $apiResponse->json('data.data'));
    }
}
# Requirements Traceability Matrix (RTM) - Jajal Medical Case Management

In medical software and regulated healthcare environments (such as ISO 13485 and FDA 21 CFR Part 11 guidance), **Requirements Traceability** establishes an audit trail demonstrating that every system requirement is directly implemented in verified source code and covered by automated regression tests.

---

## 1. Traceability Matrix

| ID | Requirement (Input) | Implementation (Output) | Verification (Automated Test) | Status |
|---|---|---|---|---|
| **REQ-001** | Admin shall be able to create a medical case | `App\Http\Controllers\MedicalCaseController@store`<br>`App\Http\Requests\StoreCaseRequest`<br>`resources/views/cases/create.blade.php`<br>`resources/views/cases/_form.blade.php`<br>`App\Http\Controllers\Api\ApiMedicalCaseController@store` | `Tests\Feature\MedicalCaseManagementTest::test_admin_can_create_a_case`<br>• Verified Web case creation & DB persistence<br>• Verified Sanctum API `POST /api/cases` (201 Created) | **PASS** |
| **REQ-002** | Case Number shall be unique across the system | `database/migrations/2026_10_14_060100_create_medical_cases_table.php` (`unique('case_number')`)<br>`App\Http\Requests\StoreCaseRequest` (`unique:medical_cases,case_number`)<br>`App\Http\Requests\UpdateCaseRequest` (`Rule::unique()->ignore($caseId)`) | `Tests\Feature\MedicalCaseManagementTest::test_creating_a_case_with_a_duplicate_case_number_fails`<br>• Verified API duplicate fails with HTTP 422 and validation error dictionary<br>• Verified Web form session validation errors | **PASS** |
| **REQ-003** | Only Admin shall delete cases (Viewer blocked with HTTP 403) | `App\Policies\CasePolicy@delete`<br>`App\Http\Controllers\MedicalCaseController@destroy`<br>`App\Http\Controllers\Api\ApiMedicalCaseController@destroy`<br>`resources/views/cases/index.blade.php` (`@can('delete', $case)`) | `Tests\Feature\MedicalCaseManagementTest::test_viewer_cannot_delete_a_case`<br>• Verified Web DELETE by Viewer returns HTTP 403<br>• Verified API DELETE by Viewer returns HTTP 403<br>• Verified database record remains intact | **PASS** |
| **REQ-004** | User Authentication with Role Enforcement | `App\Http\Controllers\AuthController@login`<br>`App\Http\Controllers\Api\ApiAuthController@login`<br>`App\Models\User` (`isAdmin()`, `isViewer()`) | `Tests\Feature\MedicalCaseManagementTest::test_valid_login_succeeds`<br>`Tests\Feature\MedicalCaseManagementTest::test_invalid_login_is_rejected` | **PASS** |
| **REQ-005** | Guest Access Restrictions | `routes/web.php` (`auth` middleware group)<br>`routes/api.php` (`auth:sanctum` middleware group) | `Tests\Feature\MedicalCaseManagementTest::test_guest_cannot_open_case_pages_or_apis`<br>• Verified guests redirected to `/login` for Web<br>• Verified guests receive HTTP 401 for API | **PASS** |
| **REQ-006** | Viewer Read-Only Authorization | `App\Policies\CasePolicy@create`<br>`App\Policies\CasePolicy@update`<br>`App\Http\Controllers\MedicalCaseController`<br>`App\Http\Controllers\Api\ApiMedicalCaseController` | `Tests\Feature\MedicalCaseManagementTest::test_viewer_can_view_cases_but_cannot_create_or_update`<br>• Verified Viewer can view listing & details<br>• Verified Viewer blocked from create/edit forms with 403 | **PASS** |

---

## 2. Implementation Artifact Cross-References

- **Policy**: `app/Policies/CasePolicy.php` registered in `app/Providers/AppServiceProvider.php`
- **Requests**: `app/Http/Requests/StoreCaseRequest.php`, `app/Http/Requests/UpdateCaseRequest.php`
- **Web Controllers**: `app/Http/Controllers/MedicalCaseController.php`, `app/Http/Controllers/DashboardController.php`, `app/Http/Controllers/AuthController.php`
- **API Controllers**: `app/Http/Controllers/Api/ApiMedicalCaseController.php`, `app/Http/Controllers/Api/ApiAuthController.php`
- **Test Suite**: `tests/Feature/MedicalCaseManagementTest.php`
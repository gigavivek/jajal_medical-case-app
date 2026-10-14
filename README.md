# Jajal Medical &mdash; Medical Case Management System

A robust, clean, and maintainable Medical Case Management web application and REST API built with **Laravel**, **MySQL**, **Blade**, and **Bootstrap 5**. Designed for tracking patient-specific surgical implant cases from draft creation through surgical planning, multi-disciplinary review, clinical approval, and surgical completion.

Developed for the **Software Developer Intern Practical Assignment** at **Jajal Medical**.

---

## 1. Project Overview & Scenario

Medical device companies manufacture and customize patient-specific implants (e.g. titanium cranial plates, spinal cages, custom orthognathic guides). Each case progresses through rigorous quality stages:
- **Draft**: Initial case information gathered.
- **Planning**: 3D design and engineering surgical plan in progress.
- **In Review**: Clinical and biomechanical review by surgical leads.
- **Approved**: Cleared for sterile cleanroom manufacturing and surgical logistics.
- **Completed**: Surgery successfully conducted and recorded.
- **Cancelled**: Case discarded or superseded.

This application provides role-based access control (RBAC), preventing unauthorized modifications on both the client and server.

---

## 2. Key Features

- **Authentication & Roles**:
  - Secure session-based authentication for Web UI.
  - Token-based API authentication powered by **Laravel Sanctum**.
  - Two distinct roles: **Admin** (full CRUD, case status updates, deletions) and **Viewer** (read-only access to dashboard, search, filters, and case details).
- **Server-Side Authorization**:
  - Enforced using Laravel Policies (`App\Policies\CasePolicy`). Unauthorized actions are blocked with **HTTP 403 Forbidden** on both web routes and REST APIs.
- **Audit Trail & Ownership**:
  - Medical cases automatically capture `created_by` referencing the authenticated user.
  - Updates strictly preserve the original case creator.
- **Medical Case Management**:
  - Interactive dashboard with real-time dynamic metric counters and recently logged cases.
  - Paginated directory (10 cases/page) with search by Case Number, Surgeon Name, or Patient Reference.
  - Status filtering with query-string parameter preservation across page transitions.
  - Reusable Blade partial form for both Create and Edit operations with inline field-level validation errors.
  - Delete confirmation modals preventing accidental removals.
- **REST API**:
  - Standardized JSON envelope: `{ "success": true/false, "data": ..., "message": "...", "errors": ... }`.
  - HTTP status codes: `200 OK`, `201 Created`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `422 Unprocessable Entity`.
- **Automated Regression Testing**:
  - Comprehensive feature test suite running on an isolated in-memory SQLite database (`:memory:`).

---

## 3. Technology Stack

- **Backend**: PHP 8.2+ / Laravel 12
- **Frontend**: Laravel Blade, HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3, Jajal Medical Enterprise Clinical Design System (Plus Jakarta Sans, JetBrains Mono, Interactive Lifecycle Stepper, Quick Filter Chips, Print Dossiers)
- **Database**: MySQL 8.0 / MariaDB
- **Authentication**: Laravel Session Auth (Web) & Laravel Sanctum (REST API)
- **Authorization**: Laravel Policies & Gates
- **Testing**: PHPUnit 11
- **Version Control**: Git

---

## 4. Default Demo Credentials

The database seeder automatically seeds two verified user accounts:

| Role | Email Address | Password | Permissions |
|---|---|---|---|
| **Admin** | `admin@jajalmedical.com` | `Admin@12345` | Full access: View, Create, Edit, Update Status, Delete cases. |
| **Viewer** | `viewer@jajalmedical.com` | `Viewer@12345` | Read-only access: View dashboard, list cases, search, filter, view details. |

> [!NOTE]
> All patient reference codes (e.g. `JM-PT-88201`) and surgeon names are strictly fictional dummy data.

---

## 5. Prerequisites

Before installing, ensure your development machine has:
- **PHP >= 8.2** with extensions: `pdo_mysql`, `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `xml`, `zip`.
- **Composer** (v2.x)
- **MySQL Server** (8.0 or MariaDB)
- **Git**

---

## 6. Installation & Setup Instructions

### Step 1: Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/jajal-medical-case-management.git
cd jajal-medical-case-management
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Configure Environment Variables
Copy the template configuration file:
```bash
cp .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```

### Step 4: Configure the MySQL Database
Open `.env` in a text editor and update your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306         # Or your custom port (e.g., 3300)
DB_DATABASE=medical_cases_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database in MySQL:
```sql
CREATE DATABASE IF NOT EXISTS medical_cases_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5: Run Database Migrations & Seeders
This will create all schema tables, seed the Admin and Viewer users, and populate **25 realistic medical implant cases**:
```bash
php artisan migrate:fresh --seed
```

### Step 6: Start the Development Server
```bash
php artisan serve
```
Open your browser and navigate to: **`http://localhost:8000`**

---

## 7. Automated Testing

The application includes an automated feature test suite that validates all six mandatory assignment requirements. The tests run against an **isolated in-memory SQLite database**, ensuring your development database is never altered.

### Execute the Test Suite:
```bash
php artisan test
```

### Tested Scenarios:
1. `test_valid_login_succeeds`: Validates web login session and API Sanctum token generation.
2. `test_invalid_login_is_rejected`: Validates rejection of incorrect credentials for both Web (session errors) and API (HTTP 401).
3. `test_guest_cannot_open_case_pages_or_apis`: Verifies unauthenticated users are redirected to `/login` for web views and receive HTTP 401 for APIs.
4. `test_admin_can_create_a_case`: Verifies Admin can create cases via web form and via Sanctum API (HTTP 201).
5. `test_creating_a_case_with_a_duplicate_case_number_fails`: Validates that duplicate `case_number` attempts are rejected with **HTTP 422 Unprocessable Entity** and field-level validation messages.
6. `test_viewer_cannot_delete_a_case`: Verifies Viewer deletion attempts are blocked with **HTTP 403 Forbidden** on both web and API, leaving the database record intact.
7. `test_viewer_can_view_cases_but_cannot_create_or_update`: Verifies Viewer can browse directory but is forbidden from create/edit operations.

---

## 8. REST API Reference

All protected endpoints require the header:
```http
Authorization: Bearer <SANCTUM_TOKEN>
Accept: application/json
```

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| `POST` | `/api/login` | Public | Authenticates user; returns Sanctum bearer token. |
| `POST` | `/api/logout` | Authenticated | Revokes the current access token. |
| `GET` | `/api/cases` | Admin, Viewer | Returns paginated list of cases. Supports `?search=` and `?status=`. |
| `POST` | `/api/cases` | Admin only | Creates a new medical case. |
| `GET` | `/api/cases/{id}` | Admin, Viewer | Retrieves a single medical case by ID. |
| `PUT` | `/api/cases/{id}` | Admin only | Updates an existing medical case. |
| `DELETE` | `/api/cases/{id}` | Admin only | Deletes a medical case. |

### API Response Format

#### Success Response (`200 OK` / `201 Created`):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "case_number": "CASE-1001",
    "patient_reference": "JM-PT-88201",
    "surgeon_name": "Dr. Rajesh Jajal, MD",
    "implant_type": "Titanium 3D-Printed Cranial Mesh",
    "status": "Planning",
    "priority": "High",
    "surgery_date": "2026-11-20",
    "created_by": 1,
    "created_at": "2026-10-14T06:00:00.000000Z",
    "updated_at": "2026-10-14T06:00:00.000000Z"
  },
  "message": "Medical case created successfully."
}
```

#### Validation Error (`422 Unprocessable Entity`):
```json
{
  "success": false,
  "data": null,
  "message": "This Case Number is already registered in the system.",
  "errors": {
    "case_number": [
      "This Case Number is already registered in the system."
    ]
  }
}
```

#### Unauthorized Response (`403 Forbidden`):
```json
{
  "success": false,
  "data": null,
  "message": "This action is unauthorized."
}
```

---

## 9. How to Test with Postman or Thunder Client

A ready-to-import collection is provided in the repository root: **`postman_collection.json`**.

1. In Postman or Thunder Client, click **Import** and select `postman_collection.json`.
2. Run the request **1. Login (Admin)**. Copy the returned `token` from the response.
3. Paste the token into the collection's `token` variable or directly in the `Authorization: Bearer <token>` header of subsequent requests.
4. Test endpoints:
   - Run **3. List Cases** to view paginated records.
   - Run **4. Create Case** as Admin (succeeds with 201).
   - Log in as Viewer, copy Viewer token, and try **7. Delete Case** (fails with 403 Forbidden).

---

## 10. Design & Architectural Decisions (Interview Walkthrough)

- **Why Laravel Form Requests?**
  Moving validation logic out of controllers into dedicated `StoreCaseRequest` and `UpdateCaseRequest` classes adheres to the Single Responsibility Principle (SRP) and ensures validation rules are consistent, reusable, and cleanly testable.
- **Why Laravel Policies instead of just Blade `@can`?**
  Hiding buttons in the frontend is a UX consideration, not security. A malicious client could still send HTTP `POST`, `PUT`, or `DELETE` requests directly. Enforcing checks in `App\Policies\CasePolicy` ensures server-side authorization is applied before any controller execution.
- **Why Bootstrap 5 over Single Page Apps (SPA)?**
  For a hospital intranet or case management portal, server-rendered Blade with Bootstrap 5 provides fast initial page loads, simplicity, minimal dependencies, and native accessibility without the build overhead of React or Vue.
- **Audit Trails**:
  In compliance with medical software standards, `created_by` cannot be tampered with via user inputs and is retained during edits.

---

## 11. Assumptions & Known Limitations

- **Email Verification**: Email verification is pre-verified in seeders for local demo convenience.
- **Soft Deletes**: Currently hard deletion is implemented per specification; for production medical compliance, Laravel SoftDeletes (`deleted_at`) can be enabled.
- **Public Holidays Integration**: Prepared for external HTTP API integrations (e.g. Nager.Date API) to display public holidays next to the surgery date picker.

---

## 12. Documentation Index

- [Database Design & ER Diagram](docs/database.md)
- [Requirements Traceability Matrix (RTM)](docs/traceability.md)
- [AWS Production Deployment Plan](docs/deployment.md)
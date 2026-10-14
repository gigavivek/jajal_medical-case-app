# Database Design Documentation - Jajal Medical Case Management

## 1. Overview
The database schema is designed for tracking patient-specific surgical implant cases from draft creation through surgical planning, review, approval, and completion.

- **DBMS**: MySQL 8.0 / MariaDB
- **Collation**: `utf8mb4_unicode_ci`
- **Engine**: InnoDB (supporting transactions and foreign key constraints)

---

## 2. Entity Relationship (ER) Diagram

```
+-------------------------------------------------------------+
|                           users                             |
+-------------------------------------------------------------+
| PK  | id                 | BIGINT UNSIGNED AUTO_INCREMENT   |
|     | name               | VARCHAR(255)                     |
| UQ  | email              | VARCHAR(255) UNIQUE              |
|     | email_verified_at  | TIMESTAMP NULL                   |
|     | password           | VARCHAR(255)                     |
|     | role               | VARCHAR(255) DEFAULT 'viewer'    |
|     | remember_token     | VARCHAR(100) NULL                |
|     | created_at         | TIMESTAMP NULL                   |
|     | updated_at         | TIMESTAMP NULL                   |
+-------------------------------------------------------------+
                               |
                               | 1
                               |
                               | hasMany (created_by)
                               |
                               | N
                               v
+-------------------------------------------------------------+
|                       medical_cases                         |
+-------------------------------------------------------------+
| PK  | id                 | BIGINT UNSIGNED AUTO_INCREMENT   |
| UQ  | case_number        | VARCHAR(255) UNIQUE (INDEXED)    |
|     | patient_reference  | VARCHAR(255)                     |
|     | surgeon_name       | VARCHAR(255)                     |
|     | implant_type       | VARCHAR(255)                     |
| IDX | status             | VARCHAR(255) (INDEXED)           |
|     | priority           | VARCHAR(255)                     |
|     | surgery_date       | DATE NULL                        |
| FK  | created_by         | BIGINT UNSIGNED -> users(id)     |
|     | created_at         | TIMESTAMP NULL                   |
|     | updated_at         | TIMESTAMP NULL                   |
+-------------------------------------------------------------+

+-------------------------------------------------------------+
|                   personal_access_tokens                    |
+-------------------------------------------------------------+
| PK  | id                 | BIGINT UNSIGNED AUTO_INCREMENT   |
| IDX | tokenable_type     | VARCHAR(255)                     |
| IDX | tokenable_id       | BIGINT UNSIGNED                  |
|     | name               | VARCHAR(255)                     |
| UQ  | token              | VARCHAR(64) UNIQUE               |
|     | abilities          | TEXT NULL                        |
|     | last_used_at       | TIMESTAMP NULL                   |
|     | expires_at         | TIMESTAMP NULL                   |
|     | created_at         | TIMESTAMP NULL                   |
|     | updated_at         | TIMESTAMP NULL                   |
+-------------------------------------------------------------+
```

---

## 3. Tables & Column Specifications

### Table: `users`
Stores user accounts for system access and role-based permissions.

| Column | Type | Nullable | Default | Constraints & Details |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key |
| `name` | `VARCHAR(255)` | No | None | User's full display name |
| `email` | `VARCHAR(255)` | No | None | Unique index, user login identifier |
| `email_verified_at` | `TIMESTAMP` | Yes | `NULL` | Email verification timestamp |
| `password` | `VARCHAR(255)` | No | None | Bcrypt hashed string |
| `role` | `VARCHAR(255)` | No | `'viewer'` | Role check: `'admin'` or `'viewer'` |
| `remember_token` | `VARCHAR(100)` | Yes | `NULL` | Laravel session remember token |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Standard timestamp |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Standard timestamp |

---

### Table: `medical_cases`
Stores patient-specific medical implant case details.

| Column | Type | Nullable | Default | Constraints & Details |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key |
| `case_number` | `VARCHAR(255)` | No | None | **Unique Index**. Identifier (e.g. `CASE-1001`) |
| `patient_reference` | `VARCHAR(255)` | No | None | Anonymized patient dummy code (e.g. `JM-PT-88201`) |
| `surgeon_name` | `VARCHAR(255)` | No | None | Operating surgeon (e.g. `Dr. Rajesh Jajal, MD`) |
| `implant_type` | `VARCHAR(255)` | No | None | Medical device specification (e.g. Cranial Mesh, Spinal Cage) |
| `status` | `VARCHAR(255)` | No | None | **B-Tree Index**. Allowed: `Draft`, `Planning`, `In Review`, `Approved`, `Completed`, `Cancelled` |
| `priority` | `VARCHAR(255)` | No | None | Allowed: `Low`, `Medium`, `High` |
| `surgery_date` | `DATE` | Yes | `NULL` | Scheduled surgery date (format: `YYYY-MM-DD`) |
| `created_by` | `BIGINT UNSIGNED` | No | None | **Foreign Key** referencing `users.id` (`ON DELETE CASCADE`) |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Record creation timestamp |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Record last modification timestamp |

---

## 4. Relationships and Eloquent Implementations

1. **User hasMany MedicalCases**
   - In `App\Models\User.php`:
     ```php
     public function medicalCases(): HasMany
     {
         return $this->hasMany(MedicalCase::class, 'created_by');
     }
     ```
2. **MedicalCase belongsTo User (Creator)**
   - In `App\Models\MedicalCase.php`:
     ```php
     public function creator(): BelongsTo
     {
         return $this->belongsTo(User::class, 'created_by');
     }
     ```

---

## 5. Indexes and Performance Rationale
- `case_number` has a **Unique Index** (`unique('case_number')`) to prevent duplicate case references across surgical teams and guarantee $O(1)$ fast lookups.
- `status` has a **Standard Index** (`index('status')`) to accelerate dashboard counts and case list filtering across high-volume case databases.
- `created_by` is an indexed foreign key to ensure fast cascade operations and user author queries.
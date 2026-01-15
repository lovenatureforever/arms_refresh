# E-Confirmation Module - Technical Implementation

## Overview
Technical specification for implementing the E-Confirmation module in Laravel/Livewire.

## Implementation Status

| Component | Status | Notes |
|-----------|--------|-------|
| Banks Migration | Done | `2025_12_30_000001_create_banks_table.php` |
| Bank Branches Migration | Done | `2025_12_30_000002_create_bank_branches_table.php` + modify migration |
| E-Confirmation Requests Migration | Done | `2025_12_30_000003_create_econfirmation_requests_table.php` |
| E-Confirmation Bank PDFs Migration | Done | `2025_12_30_000004_create_econfirmation_bank_pdfs_table.php` |
| E-Confirmation Signatures Migration | Done | `2025_12_30_000005_create_econfirmation_signatures_table.php` |
| E-Confirmation Logs Migration | Done | `2025_12_30_000006_create_econfirmation_logs_table.php` |
| Additional Request Fields Migration | Done | `2026_01_06/07_*` - account_no, charge_code, approved_by, authorization_letter_path, client_consent_acknowledged |
| Bank Model | Done | `app/Models/Tenant/Bank.php` |
| BankBranch Model | Done | `app/Models/Tenant/BankBranch.php` |
| EConfirmationRequest Model | Done | `app/Models/Tenant/EConfirmationRequest.php` |
| EConfirmationBankPdf Model | Done | `app/Models/Tenant/EConfirmationBankPdf.php` |
| EConfirmationSignature Model | Done | `app/Models/Tenant/EConfirmationSignature.php` |
| EConfirmationLog Model | Done | `app/Models/Tenant/EConfirmationLog.php` |
| BankRegistry Component | Done | CRUD for banks and branches |
| RequestsIndex Component | Done | List requests with filters, stats |
| CreateRequest Component | Done | Create new request form with bank/branch selection, file upload |
| ViewRequest Component | Done | View request details/progress |
| DirectorSigning Component | Done | Director signing interface via token URL |
| Sidebar Menu | Done | E-Confirmation menu with Requests and Bank Registry |
| Routes | Done | index, create, view, banks, sign |
| PDF Service | Done | PDF generation with signature overlay |
| Settings Component | Pending | Company settings |
| Notification Service | Pending | Email notifications |
| Scheduled Jobs | Pending | Reminders and auto-generation |

---

## 1. Database Schema

### 1.1 Banks Table
```sql
CREATE TABLE banks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(255) NOT NULL,
    bank_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Slug lowercase identifier',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    INDEX idx_bank_code (bank_code),
    INDEX idx_is_active (is_active)
);
```

### 1.2 Bank Branches Table
```sql
CREATE TABLE bank_branches (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bank_id BIGINT UNSIGNED NOT NULL,
    branch_name VARCHAR(255) NULL,
    street VARCHAR(255) NULL,
    street_2 VARCHAR(255) NULL,
    street_3 VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    postcode VARCHAR(10) NULL,
    country VARCHAR(100) DEFAULT 'Malaysia',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (bank_id) REFERENCES banks(id) ON DELETE CASCADE,
    INDEX idx_bank_active (bank_id, is_active)
);
```

### 1.3 E-Confirmation Requests Table
```sql
CREATE TABLE econfirmation_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id BIGINT UNSIGNED NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    year_end_date DATE NOT NULL COMMENT 'Company year-end date at time of request',
    year_end_period VARCHAR(10) NOT NULL COMMENT 'e.g., 2026',
    token VARCHAR(64) NOT NULL UNIQUE,
    token_expires_at TIMESTAMP NOT NULL,
    status ENUM('draft', 'pending_signatures', 'completed', 'expired') DEFAULT 'draft',
    total_banks INT UNSIGNED DEFAULT 0,
    banks_completed INT UNSIGNED DEFAULT 0,
    total_signatures_required INT UNSIGNED DEFAULT 0,
    total_signatures_collected INT UNSIGNED DEFAULT 0,
    sent_at TIMESTAMP NULL COMMENT 'When emails were sent to directors',
    completed_at TIMESTAMP NULL,
    validity_days INT UNSIGNED DEFAULT 14,
    account_no VARCHAR(100) NULL COMMENT 'Bank account number',
    charge_code VARCHAR(100) NULL COMMENT 'Charge code for billing',
    approved_by BIGINT UNSIGNED NULL COMMENT 'Approver user ID',
    authorization_letter_path VARCHAR(500) NULL COMMENT 'Path to uploaded authorization letter',
    client_consent_acknowledged BOOLEAN DEFAULT FALSE COMMENT 'Client consent checkbox acknowledgement',
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_company_status (company_id, status),
    INDEX idx_token (token),
    INDEX idx_token_expires (token_expires_at),
    INDEX idx_status_expires (status, token_expires_at)
);
```

### 1.4 E-Confirmation Bank PDFs Table
```sql
CREATE TABLE econfirmation_bank_pdfs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    econfirmation_request_id BIGINT UNSIGNED NOT NULL,
    bank_branch_id BIGINT UNSIGNED NOT NULL,
    unsigned_pdf_path VARCHAR(500) NULL,
    signed_pdf_path VARCHAR(500) NULL,
    status ENUM('pending', 'partial', 'signed') DEFAULT 'pending',
    signatures_required INT UNSIGNED DEFAULT 0,
    signatures_collected INT UNSIGNED DEFAULT 0,
    version INT UNSIGNED DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (econfirmation_request_id) REFERENCES econfirmation_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (bank_branch_id) REFERENCES bank_branches(id) ON DELETE CASCADE,
    UNIQUE KEY econf_bank_pdfs_request_branch_unique (econfirmation_request_id, bank_branch_id),
    INDEX econf_bank_pdfs_request_status_idx (econfirmation_request_id, status)
);
```

### 1.5 E-Confirmation Signatures Table
```sql
CREATE TABLE econfirmation_signatures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    econfirmation_bank_pdf_id BIGINT UNSIGNED NOT NULL,
    director_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'signed', 'waived') DEFAULT 'pending',
    signature_path_used VARCHAR(500) NULL COMMENT 'Snapshot of signature used',
    signed_at TIMESTAMP NULL,
    signed_ip VARCHAR(45) NULL,
    director_name VARCHAR(255) NOT NULL COMMENT 'Director name at time of signing',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (econfirmation_bank_pdf_id) REFERENCES econfirmation_bank_pdfs(id) ON DELETE CASCADE,
    FOREIGN KEY (director_id) REFERENCES company_directors(id) ON DELETE CASCADE,
    UNIQUE KEY econf_sigs_pdf_director_unique (econfirmation_bank_pdf_id, director_id),
    INDEX econf_sigs_director_status_idx (director_id, status)
);
```

### 1.6 E-Confirmation Logs Table
```sql
CREATE TABLE econfirmation_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    econfirmation_request_id BIGINT UNSIGNED NOT NULL,
    log_type VARCHAR(50) NOT NULL COMMENT 'email_sent, reminder_sent, signature_collected, etc.',
    director_id BIGINT UNSIGNED NULL,
    recipient_email VARCHAR(255) NULL,
    success BOOLEAN DEFAULT TRUE,
    error_message TEXT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (econfirmation_request_id) REFERENCES econfirmation_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (director_id) REFERENCES company_directors(id) ON DELETE SET NULL,
    INDEX econf_logs_request_type_idx (econfirmation_request_id, log_type)
);
```

---

## 2. Models

### 2.1 Bank Model (Implemented)
**File:** `app/Models/Tenant/Bank.php`

**Fillable:** bank_name, bank_code, is_active

**Casts:** is_active (boolean)

**Relationships:**
- `branches()` - hasMany BankBranch
- `activeBranches()` - hasMany BankBranch where is_active = true

**Scopes:**
- `scopeActive($query)` - where is_active = true

---

### 2.2 BankBranch Model (Implemented)
**File:** `app/Models/Tenant/BankBranch.php`

**Fillable:** bank_id, branch_name, street, street_2, street_3, city, state, postcode, country, is_active

**Casts:** is_active (boolean)

**Relationships:**
- `bank()` - belongsTo Bank

**Scopes:**
- `scopeActive($query)` - where is_active = true

**Accessors:**
- `getFullAddressAttribute()` - Returns formatted address string (street, street_2, street_3, city, state, postcode, country)
- `getDisplayNameAttribute()` - Returns "Bank Name - Branch Name"

---

### 2.3 EConfirmationRequest Model (Implemented)
**File:** `app/Models/Tenant/EConfirmationRequest.php`

**Constants:**
- STATUS_DRAFT, STATUS_PENDING_SIGNATURES, STATUS_COMPLETED, STATUS_EXPIRED

**Fillable:** company_id, created_by, year_end_date, year_end_period, token, token_expires_at, status, total_banks, banks_completed, total_signatures_required, total_signatures_collected, sent_at, completed_at, validity_days, account_no, charge_code, approved_by, authorization_letter_path, client_consent_acknowledged, metadata

**Casts:** year_end_date (date), token_expires_at (datetime), sent_at (datetime), completed_at (datetime), client_consent_acknowledged (boolean), metadata (array)

**Boot:** Auto-generate token and token_expires_at on creating

**Relationships:**
- `company()` - belongsTo Company
- `creator()` - belongsTo User (created_by)
- `approver()` - belongsTo User (approved_by)
- `bankPdfs()` - hasMany EConfirmationBankPdf
- `logs()` - hasMany EConfirmationLog

**Scopes:**
- `scopePending($query)`
- `scopeExpiringSoon($query, $days)`
- `scopeExpired($query)`

**Methods:**
- `isExpired()`, `isCompleted()`, `canSign()`
- `getProgressPercentage()`
- `daysUntilExpiry()`
- `regenerateToken($validityDays)`
- `recalculateProgress()`

**Accessors:**
- `getStatusBadgeColorAttribute()` - Returns color for status badge
- `getStatusLabelAttribute()` - Returns human-readable status label

---

### 2.4 EConfirmationBankPdf Model (Implemented)
**File:** `app/Models/Tenant/EConfirmationBankPdf.php`

**Constants:**
- STATUS_PENDING, STATUS_PARTIAL, STATUS_SIGNED

**Fillable:** econfirmation_request_id, bank_branch_id, unsigned_pdf_path, signed_pdf_path, status, signatures_required, signatures_collected, version

**Relationships:**
- `request()` - belongsTo EConfirmationRequest
- `bankBranch()` - belongsTo BankBranch
- `signatures()` - hasMany EConfirmationSignature

**Methods:**
- `isFullySigned()`
- `getProgressPercentage()`
- `updateSignatureCount()`
- `getPendingDirectors()`

**Accessors:**
- `getStatusBadgeColorAttribute()` - Returns color for status badge

---

### 2.5 EConfirmationSignature Model (Implemented)
**File:** `app/Models/Tenant/EConfirmationSignature.php`

**Constants:**
- STATUS_PENDING, STATUS_SIGNED, STATUS_WAIVED

**Fillable:** econfirmation_bank_pdf_id, director_id, status, signature_path_used, signed_at, signed_ip, director_name

**Casts:** signed_at (datetime)

**Relationships:**
- `bankPdf()` - belongsTo EConfirmationBankPdf
- `director()` - belongsTo CompanyDirector

**Methods:**
- `isSigned()`
- `sign($ip)` - Records signature and updates bank PDF count

**Accessors:**
- `getStatusBadgeColorAttribute()` - Returns color for status badge

---

### 2.6 EConfirmationLog Model (Implemented)
**File:** `app/Models/Tenant/EConfirmationLog.php`

**Constants:**
- TYPE_REQUEST_CREATED, TYPE_EMAIL_SENT, TYPE_REMINDER_SENT
- TYPE_SIGNATURE_COLLECTED, TYPE_PDF_GENERATED, TYPE_PDF_REGENERATED
- TYPE_TOKEN_ACCESSED, TYPE_REQUEST_EXPIRED, TYPE_REQUEST_COMPLETED

**Fillable:** econfirmation_request_id, log_type, director_id, recipient_email, success, error_message, metadata

**Casts:** success (boolean), metadata (array)

**Relationships:**
- `request()` - belongsTo EConfirmationRequest
- `director()` - belongsTo CompanyDirector

---

## 3. Routes (Implemented)

**File:** `routes/tenant.php`

```php
// E-Confirmation Routes
Route::prefix('/e-confirmation')->name('econfirmation.')->group(function () {
    Route::get('/', \App\Livewire\Tenant\Pages\EConfirmation\RequestsIndex::class)->name('index');
    Route::get('/create', \App\Livewire\Tenant\Pages\EConfirmation\CreateRequest::class)->name('create');
    Route::get('/request/{id}', \App\Livewire\Tenant\Pages\EConfirmation\ViewRequest::class)->name('view');
    Route::get('/banks', \App\Livewire\Tenant\Pages\EConfirmation\BankRegistry::class)->name('banks');
    Route::get('/sign/{token}', \App\Livewire\Tenant\Pages\EConfirmation\DirectorSigning::class)->name('sign');
});
```

**Pending Routes:**
```php
Route::get('/settings', \App\Livewire\Tenant\Pages\EConfirmation\Settings::class)->name('settings');
Route::get('/logs', \App\Livewire\Tenant\Pages\EConfirmation\Logs::class)->name('logs');
```

---

## 4. Livewire Components

| Component | File | Status | Purpose |
|-----------|------|--------|---------|
| BankRegistry | `app/Livewire/Tenant/Pages/EConfirmation/BankRegistry.php` | Done | CRUD for banks and branches |
| RequestsIndex | `app/Livewire/Tenant/Pages/EConfirmation/RequestsIndex.php` | Done | List requests with filters, stats |
| CreateRequest | `app/Livewire/Tenant/Pages/EConfirmation/CreateRequest.php` | Done | Create new request form with bank/branch selection, file upload |
| ViewRequest | `app/Livewire/Tenant/Pages/EConfirmation/ViewRequest.php` | Done | View request details/progress |
| DirectorSigning | `app/Livewire/Tenant/Pages/EConfirmation/DirectorSigning.php` | Done | Director signing interface via token URL |
| Settings | Pending | Pending | Company settings |
| Logs | Pending | Pending | Audit logs viewer |

### 4.1 CreateRequest Component Features
**File:** `app/Livewire/Tenant/Pages/EConfirmation/CreateRequest.php`

**Form Fields (All Mandatory):**
- Company dropdown (auto-selected first)
- Bank dropdown (filters branches)
- Branch search (single selection, searchable)
- Account number (text input)
- Charge code (text input, disabled if "Charge Code Unavailable" checked)
- Approver dropdown (from Auditor model with active users)
- Validity days (7-30 days)
- Authorization letter (file upload: PDF, DOC, DOCX, JPG, PNG, max 10MB)
- Client consent acknowledgement checkbox

**On Submit:**
1. Validates all required fields
2. Stores authorization letter to `econfirmation/authorization-letters`
3. Creates EConfirmationRequest record
4. Creates EConfirmationBankPdf record for selected bank/branch
5. Creates EConfirmationSignature records for each active director
6. Generates unsigned PDF via EConfirmationPdfService

### 4.2 DirectorSigning Component Features
**File:** `app/Livewire/Tenant/Pages/EConfirmation/DirectorSigning.php`

**URL:** `/e-confirmation/sign/{token}`

**Validation Checks:**
- Token exists and is valid
- Token not expired
- Request not already completed
- Current authenticated user is a director of the company

**Features:**
- Displays pending and completed signatures
- Sign individual confirmation
- Sign All button (when multiple pending)
- Verifies director has default e-signature uploaded
- Records IP address on signing
- Auto-updates signature status and triggers PDF regeneration when all signed

---

## 5. Views (Implemented)

### Livewire Views
`resources/views/livewire/tenant/pages/econfirmation/`
- bank-registry.blade.php (Done)
- requests-index.blade.php (Done)
- create-request.blade.php (Done)
- view-request.blade.php (Done)
- director-signing.blade.php (Done)
- settings.blade.php (Pending)
- logs.blade.php (Pending)

### PDF Templates
`resources/views/pdf/econfirmation/`
- bank-confirmation.blade.php (Done) - 2-page PDF template with:
  - Page 1: Standard Request for Information for Audit Purposes
  - Page 2: Agreement to Obtain Bank Confirmation with director signatures

---

## 6. Sidebar Menu (Implemented)

**File:** `resources/views/layouts/components/tenant-sidebar.blade.php`

```blade
{{-- E-Confirmation Menu --}}
<li class="menu-item">
    <a href="javascript:void(0);" data-fc-type="collapse" class="menu-link fc-collapse">
        <span class="menu-icon"><i class="mgc_bank_line"></i></span>
        <span class="menu-text"> E-Confirmation </span>
        <span class="menu-arrow"></span>
    </a>

    <ul class="sub-menu hidden" style="height: 0px;">
        <li class="menu-item">
            <a href="{{ route('econfirmation.index') }}" class="menu-link">
                <span class="menu-text">Requests</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('econfirmation.banks') }}" class="menu-link">
                <span class="menu-text">Bank Registry</span>
            </a>
        </li>
    </ul>
</li>
```

---

## 7. Services

### EConfirmationPdfService (Implemented)
**File:** `app/Services/EConfirmationPdfService.php`

**Methods:**
- `generateUnsignedPdf($bankPdf)` - Creates unsigned PDF with placeholder signatures
- `generateSignedPdf($bankPdf)` - Creates signed PDF with director signatures overlaid
- `isReadyForSignedPdf($bankPdf)` - Checks if all signatures collected
- `streamUnsignedPdf($bankPdf)` - Preview PDF without saving

**Features:**
- Uses barryvdh/laravel-dompdf for PDF generation
- Automatically overlays director signatures from `director_signatures` table
- Auto-triggers signed PDF generation when all directors have signed (via `EConfirmationBankPdf::updateSignatureCount()`)

---

## 8. Pending Implementation

### Services
- `EConfirmationService` - Main service for request handling
- `EConfirmationNotificationService` - Email notifications

### Scheduled Jobs
- `ProcessEConfirmationReminders` - Daily reminders
- `GenerateAutoEConfirmations` - Auto-generation

### Email Mailables
- `EConfirmationSigningRequest`
- `EConfirmationReminder`
- `EConfirmationYearEndAlert`

### Company Settings Migration
- `company_econfirmation_settings` table

---

## 9. Key Timing Rules

| Event | Timing |
|-------|--------|
| User Alert (created_by) | 30 days BEFORE year-end |
| Director Notification | 1 day AFTER year-end |
| Reminder 1 | 3 days before token expiry |
| Reminder 2 (Urgent) | 1 day before token expiry |
| Default Validity | 14 days |

---

## 10. File Structure

```
app/
├── Livewire/Tenant/Pages/EConfirmation/
│   ├── BankRegistry.php         ✓
│   ├── RequestsIndex.php        ✓
│   ├── CreateRequest.php        ✓
│   ├── ViewRequest.php          ✓
│   ├── DirectorSigning.php      ✓
│   ├── Settings.php             (pending)
│   └── Logs.php                 (pending)
├── Models/Tenant/
│   ├── Bank.php                 ✓
│   ├── BankBranch.php           ✓
│   ├── EConfirmationRequest.php ✓
│   ├── EConfirmationBankPdf.php ✓
│   ├── EConfirmationSignature.php ✓
│   └── EConfirmationLog.php     ✓
├── Services/
│   ├── EConfirmationPdfService.php      ✓
│   ├── EConfirmationService.php         (pending)
│   └── EConfirmationNotificationService.php (pending)
├── Jobs/
│   ├── ProcessEConfirmationReminders.php (pending)
│   └── GenerateAutoEConfirmations.php    (pending)
└── Mail/
    ├── EConfirmationSigningRequest.php   (pending)
    ├── EConfirmationReminder.php         (pending)
    └── EConfirmationYearEndAlert.php     (pending)

database/migrations/tenant/
├── 2025_12_30_000001_create_banks_table.php              ✓
├── 2025_12_30_000002_create_bank_branches_table.php      ✓
├── 2025_12_30_000003_create_econfirmation_requests_table.php ✓
├── 2025_12_30_000004_create_econfirmation_bank_pdfs_table.php ✓
├── 2025_12_30_000005_create_econfirmation_signatures_table.php ✓
├── 2025_12_30_000006_create_econfirmation_logs_table.php ✓
├── 2025_12_30_100000_modify_bank_branches_table.php      ✓
├── 2026_01_06_000001_add_account_and_charge_code_to_econfirmation_requests_table.php ✓
├── 2026_01_07_000001_add_approved_by_to_econfirmation_requests_table.php ✓
├── 2026_01_07_000002_add_authorization_letter_to_econfirmation_requests_table.php ✓
└── 2026_01_07_000003_rename_data_consent_to_client_consent_acknowledged.php ✓

resources/views/livewire/tenant/pages/econfirmation/
├── bank-registry.blade.php      ✓
├── requests-index.blade.php     ✓
├── create-request.blade.php     ✓
├── view-request.blade.php       ✓
├── director-signing.blade.php   ✓
├── settings.blade.php           (pending)
└── logs.blade.php               (pending)

resources/views/pdf/econfirmation/
└── bank-confirmation.blade.php  ✓
```

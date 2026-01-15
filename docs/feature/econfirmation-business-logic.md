# E-Confirmation Module - Business Logic & Functional Requirements

## Overview
A bank confirmation audit system where users generate multiple bank confirmation PDFs for their company's year-end audit, send them to all active directors for e-signature collection, track completion across all directors and banks, and export signed PDFs as a combined ZIP file.

---

## Business Requirements

### 1. Purpose
The E-Confirmation module automates the process of obtaining bank confirmations for audit purposes by:
- Generating professional bank confirmation letters
- Collecting director signatures electronically
- Tracking signature completion across multiple banks and directors
- Providing audit-ready signed documents

### 2. User Roles & Access

#### Admin/Subscriber (Tenant User)
- Create bank confirmation requests
- Select multiple banks for confirmation
- View progress and completion status
- Download signed PDFs as ZIP
- Manage bank registry
- Configure settings

#### Director
- Receive email notification with signing request
- View bank confirmation PDFs
- Sign confirmations using pre-uploaded e-signature
- Track their signing progress

---

## Core Business Flows

### Flow 1: Request Creation (Manual)

**Actors:** Admin/Subscriber

**Steps:**
1. Navigate to E-Confirmation → Create Request
2. Select company (auto-populates year-end date from company fiscal year)
3. Multi-select banks from registry (can select multiple banks)
4. Preview shows:
   - Number of PDFs to be generated (one per bank)
   - Number of directors who will receive emails
   - List of active directors
5. Submit request
6. System generates:
   - One unsigned PDF per selected bank
   - Each PDF contains company letterhead, bank details, confirmation text, and signature section
7. System sends parallel emails to ALL active directors with single token link
8. Directors can access all pending bank confirmations via one link

**Business Rules:**
- Company must have active directors
- Year-end date must be set in company profile
- Selected banks must be active in registry
- Minimum 1 bank must be selected

---

### Flow 2: Automated Request Generation

**Trigger:** Scheduled job runs daily at 8:00 AM

**Pre-Year-End Alert (30 days before year-end):**
1. System identifies companies where `year_end - 30 days = today`
2. Sends preparation email to company's `created_by` user
3. Email reminds them to prepare for upcoming bank confirmations
4. Does NOT generate PDFs yet

**Post-Year-End Generation (30 days after year-end):**
1. System identifies companies where `year_end + 30 days = today`
2. Checks if company has `enable_auto_generation = true` in settings
3. Auto-generates bank confirmation request for all banks in company's registry
4. Generates unsigned PDFs for each bank
5. Sends parallel emails to all active directors

**Business Rules:**
- Only companies with auto-generation enabled
- Only sends to active directors
- Uses default validity period from settings (14 days)

---

### Flow 3: Director Signing Process

**Actors:** Director

**Steps:**
1. Director receives email with subject "Bank Confirmation Signature Required - [Company Name]"
2. Email lists all banks requiring signature
3. Clicks "Sign All Confirmations" button (single token for all banks)
4. Arrives at signing page showing:
   - Company name and year-end period
   - List of all banks requiring their signature
   - Status per bank (Unsigned / Signed)
5. For each bank:
   - Clicks "View PDF" to preview confirmation letter
   - Clicks "Sign" button
   - System validates director has uploaded e-signature
   - Records signature timestamp and IP
   - Updates progress counter
6. After signing all banks, shows completion message
7. System automatically regenerates signed PDFs when ALL directors sign a bank

**Business Rules:**
- Director must have pre-uploaded e-signature in profile
- Each director signs each bank confirmation once
- Signatures are applied in parallel (not sequential)
- Token has expiration date (default 14 days)
- Cannot sign after token expires

---

### Flow 4: Signature Collection & PDF Regeneration

**Automatic Process:**

**When Director Signs:**
1. System records signature in junction table
2. Increments `signatures_collected` counter for that bank PDF
3. Checks if `signatures_collected == signatures_required`

**When All Directors Sign a Bank:**
1. Triggers background job `RegenerateBankPdfJob`
2. Job loads unsigned PDF template
3. Fetches all director signatures for that bank
4. Auto-resizes each signature image to max 150×50px (maintains aspect ratio)
5. Overlays signatures in bottom section grid
6. Generates final signed PDF
7. Saves as `signed_pdf_path`
8. Increments version number
9. Updates bank PDF status to "signed"

**When All Banks Complete:**
1. System checks if all bank PDFs have status = "signed"
2. Updates request status to "completed"
3. Sets `completed_at` timestamp
4. Enables ZIP download for admin/subscriber

**Business Rules:**
- Must collect ALL director signatures before regenerating
- Signatures appear in alphabetical order by director name
- Each regeneration increments version for audit trail
- Original unsigned PDF preserved

---

### Flow 5: Reminder System

**Reminder Schedule:**

**3 Days Before Expiry:**
1. System identifies unsigned requests expiring in 3 days
2. Queries directors who haven't signed yet (per bank)
3. Sends reminder email only to unsigned directors
4. Email shows banks they still need to sign

**1 Day Before Expiry:**
1. System sends final reminder to unsigned directors
2. Email marked as urgent/important

**On Expiry:**
1. Midnight job marks requests as "expired"
2. Directors can no longer access signing page
3. Admin/Subscriber must manually regenerate request

**Business Rules:**
- Only send to directors missing signatures
- Don't spam directors who already signed all banks
- Separate reminder per director based on their pending banks

---

### Flow 6: Export & Download

**Actors:** Admin/Subscriber

**Steps:**
1. Navigate to E-Confirmation → Requests
2. Request shows status "Completed" with green checkmark
3. Clicks "Download ZIP" button
4. System generates ZIP on-the-fly containing:
   - All signed bank PDFs
   - Filename per PDF: `{BankName}_{BranchName}_Confirmation.pdf`
   - ZIP name: `{CompanyName}_Bank_Confirmations_{YearEnd}.zip`
5. Browser downloads ZIP file

**Business Rules:**
- Download only enabled when status = "completed"
- All bank PDFs must be fully signed
- ZIP generated on-demand (not stored)
- Includes only signed versions (not unsigned originals)

---

## Data Lifecycle

### Request States

```
draft → pending_signatures → completed
                           ↓
                        expired
```

**draft:** Request created, PDFs generated, not yet sent to directors
**pending_signatures:** Emails sent, awaiting director signatures
**completed:** All directors signed all banks, all PDFs regenerated
**expired:** Token expired before completion, requires manual intervention

### Bank PDF States

```
pending → partial → signed
```

**pending:** No director signatures collected yet
**partial:** Some directors signed, waiting for remaining
**signed:** All directors signed, final PDF regenerated

---

## Progress Tracking

### Multi-Level Progress Display

**Request Level:**
- Overall completion: "2/3 banks complete (67%)"
- Color-coded progress bar
- Status badge (Pending/In Progress/Completed/Expired)

**Bank Level:**
- Per-bank completion: "RHB Bank: 8/8 directors ✓"
- Status indicator per bank
- Individual bank progress percentage

**Director Level:**
- Per-director per-bank status: Checkmark or pending icon
- Signature timestamp
- IP address logged
- Individual resend action

---

## Business Rules Summary

### Company Requirements
- Must have `year_end` date configured
- Must have at least 1 active director
- Must have at least 1 bank in registry (for auto-generation)

### Director Requirements
- Must be marked `is_active = true` in company directors
- Must have uploaded e-signature with `is_default = true`
- Cannot sign on behalf of other directors

### Bank Requirements
- Must be in banks registry with `is_active = true`
- Must have complete address information
- Can be reused across multiple companies

### Timing Rules
- Tenant alert: 30 days BEFORE year-end
- Director request: 30 days AFTER year-end
- Default validity: 14 days from generation
- Reminders: 3 days and 1 day before expiry

### Signature Rules
- All active directors must sign
- Signatures auto-resized to 150×50px max
- Signatures ordered alphabetically by director name on PDF
- Each signature recorded with timestamp and IP
- No duplicate signatures per director per bank

### PDF Generation Rules
- One unsigned PDF per bank initially
- Unsigned PDF has placeholder signature section
- Signed PDF regenerated only when ALL directors sign
- Version incremented with each regeneration
- Both unsigned and signed PDFs preserved

### Security Rules
- Tokens are unique per request (not per bank)
- Tokens expire after validity period
- IP addresses logged for all signatures
- Only company `created_by` user receives preparation alerts
- Only active directors receive signing requests

---

## Reporting & Monitoring

### Dashboard Metrics
- Total requests pending signatures
- Requests expiring soon (within 7 days)
- Average time to complete per company
- Director response rate
- Most delayed directors (for follow-up)

### Audit Trail
- Request creation timestamp and creator
- PDF generation timestamps and versions
- All signature events with IP and timestamp
- Email delivery success/failure logs
- Token access logs
- Reminder sent logs

---

## Edge Cases & Handling

### Scenario: Director Leaves Company
**Problem:** Director marked inactive after request sent
**Solution:** Admin can manually mark their signatures as "waived" or regenerate request with current directors only

### Scenario: Token Expires
**Problem:** Not all directors signed before expiry
**Solution:** Admin regenerates request with new token, sends fresh emails

### Scenario: Bank Details Change
**Problem:** Bank moved branches or changed contact
**Solution:** Update bank registry, regenerate PDFs if request still pending

### Scenario: Director Changes Signature
**Problem:** Director uploads new signature after signing some banks
**Solution:** System uses signature that was active at time of signing (historical record)

### Scenario: Year-End Date Changes
**Problem:** Company changes fiscal year-end date
**Solution:** Existing requests use snapshotted year_end, new requests use updated date

### Scenario: All Directors Except One Sign
**Problem:** One director consistently doesn't respond
**Solution:** 
- Automated reminders continue until expiry
- Admin can view which director pending
- Admin can resend individual email to that director
- Admin can contact director directly using contact info

---

## Success Criteria

### For Tenant Users
- Reduce manual follow-up time by 70%
- Complete signature collection within 7 days average
- Zero lost documents (full audit trail)
- Single-click download of all signed confirmations

### For Directors
- Clear visibility of what needs signing
- Single email with access to all banks
- Mobile-friendly signing experience
- Immediate confirmation after signing

### For Audit
- Timestamped signatures with IP verification
- Complete version history of PDFs
- Immutable audit trail
- Professional presentation of signed documents

---

## Future Enhancements (Out of Scope for MVP)

1. **Multi-language support:** Bahasa Malaysia translations
2. **Custom confirmation templates:** Per-industry customization
3. **Bank API integration:** Auto-fetch account details
4. **SMS notifications:** As fallback to email
5. **Signature capture:** Draw-on-screen for directors without uploaded signatures
6. **Delegation:** Directors can delegate signing authority
7. **Batch operations:** Bulk generate for multiple companies
8. **Analytics dashboard:** Trends and performance metrics
9. **Mobile app:** Native iOS/Android app for directors
10. **E-stamp integration:** Government e-stamping for legal compliance

---

## Glossary

**Year-End:** The last day of a company's fiscal year (e.g., 31 Dec 2026)

**Active Director:** Director with `is_active = true` in company_directors table

**E-Signature:** Digital image of director's handwritten signature stored in DirectorSignature model

**Bank Confirmation:** Letter sent to bank requesting verification of company's accounts and balances

**Token:** Unique URL parameter allowing directors to access signing page without login

**Unsigned PDF:** Initial PDF with empty signature boxes

**Signed PDF:** Final PDF with all director signatures overlaid

**Version:** Incremental counter tracking PDF regenerations

**Request:** Parent object containing multiple bank confirmations for one company's year-end

**Signature Collection:** Process of gathering all director signatures for one bank confirmation

**Parallel Signing:** All directors can sign simultaneously (not sequential approval)

**ZIP Export:** Combined archive of all signed bank confirmation PDFs

**Tenant User:** Admin or Subscriber role who manages companies

**Auto-Generation:** Scheduled creation of requests based on year-end dates

**Validity Period:** Number of days token remains active (default 14 days)

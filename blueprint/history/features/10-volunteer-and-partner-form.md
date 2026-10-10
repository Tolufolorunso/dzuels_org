# Feature: Volunteer and Partner Form

**From build-plan:** feature 10
**Build attempt:** 1
**Branch:** feature/volunteer-and-partner-form
**Status:** verified

## Goal

Provide a dedicated public "Get Involved" page (`/get-involved`, with a 301 redirect from legacy `/partner`), allowing visitors to apply as volunteer mentors or submit institutional partnership enquiries with honeypot spam protection and rate limiting, receive automated email notifications at `info@dzuelsfoundation.org`, and equip staff with an administrative inbox at `/admin/inquiries` to review, track status, and manage submitted applications.

## Design reference

- Brand styling: Poppins typography, Great Vibes script accents, brand maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and semantic dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Prototype reference: `scratch/partner.html` volunteer form fields (Full Name, Email, Phone, Age band: under 18, 18-55, above 55, Work status: employed, self-employed, unemployed, student, Specific skills) and partnership information.
- Interactive elements:
  - Hero header with community mentorship impact badges.
  - Alpine.js tab switcher allowing smooth toggling between "Volunteer Application" and "Partner Enquiry".
  - Clean responsive form controls with validation states, focus rings, and honeypot field.
  - Flash success alert with cheerful icon and confirmation message upon successful submission.
  - Admin management suite with filter tabs (All, Pending, Reviewed, Contacted), status toggle badges, and delete actions.

## In scope

1. **Database & Models:**
   - Migration `create_volunteer_applications_table`:
     - `id` (bigint PK)
     - `full_name` (string)
     - `email` (string)
     - `phone` (string)
     - `age_band` (string: `below_18`, `18_to_55`, `above_55`)
     - `work_status` (string: `employed`, `self_employed`, `unemployed`, `student`)
     - `skills` (text, nullable)
     - `status` (string: `pending`, `reviewed`, `contacted`, default `pending`)
     - `timestamps`
   - Migration `create_partner_enquiries_table`:
     - `id` (bigint PK)
     - `organization_name` (string)
     - `contact_name` (string)
     - `email` (string)
     - `phone` (string, nullable)
     - `partnership_type` (string: `corporate_sponsor`, `school_partner`, `equipment_donor`, `mentorship_provider`, `other`)
     - `message` (text)
     - `status` (string: `pending`, `reviewed`, `contacted`, default `pending`)
     - `timestamps`
   - Eloquent Models:
     - `App\Models\VolunteerApplication`: Fillable, casts, scopes (`pending()`, `recent()`), helper accessors (`age_band_label`, `work_status_label`).
     - `App\Models\PartnerEnquiry`: Fillable, casts, scopes (`pending()`, `recent()`), helper accessor (`partnership_type_label`).

2. **Notifications & Mailables:**
   - `App\Mail\NewVolunteerApplicationMail`: Mailable sent to foundation staff (`info@dzuelsfoundation.org`).
   - `App\Mail\NewPartnerEnquiryMail`: Mailable sent to foundation staff (`info@dzuelsfoundation.org`).
   - Resilient mail dispatching: Wrapped in try-catch so mail delivery issues never abort or crash user submissions.

3. **Public Get Involved Experience (`/get-involved`):**
   - Form requests:
     - `App\Http\Requests\StoreVolunteerApplicationRequest`: Validation rules, age/work enum verification, honeypot check.
     - `App\Http\Requests\StorePartnerEnquiryRequest`: Validation rules, partnership type enum verification, honeypot check.
   - Controller `App\Http\Controllers\GetInvolvedController`:
     - `index`: Renders `resources/views/get-involved/index.blade.php`.
     - `storeVolunteer`: Validates, saves application, dispatches notification, redirects with flash message.
     - `storePartner`: Validates, saves enquiry, dispatches notification, redirects with flash message.
   - Routes in `routes/web.php`:
     - `GET /get-involved` (`get-involved`)
     - `POST /get-involved/volunteer` (`get-involved.volunteer`, with `throttle:6,1`)
     - `POST /get-involved/partner` (`get-involved.partner`, with `throttle:6,1`)
     - `GET /partner` (301 redirect to `/get-involved`)

4. **Admin Inquiry Management (`/admin/inquiries`):**
   - Controller `App\Http\Controllers\Admin\InquiryController`:
     - `index`: Lists applications and enquiries with tab filtering and counts.
     - `updateVolunteerStatus`: Updates status (`pending`, `reviewed`, `contacted`).
     - `destroyVolunteer`: Deletes application.
     - `updatePartnerStatus`: Updates status (`pending`, `reviewed`, `contacted`).
     - `destroyPartner`: Deletes enquiry.
   - Views: `resources/views/admin/inquiries/index.blade.php`.
   - Admin Layout: Add "Inquiries" tab to `resources/views/components/admin-layout.blade.php`.

5. **Automated Testing:**
   - `tests/Feature/GetInvolvedTest.php`: Public page 200, volunteer form submission, partner form submission, honeypot rejection, rate limiting, and 301 redirect from `/partner`.
   - `tests/Feature/Admin/InquiryManagementTest.php`: Admin auth guards, listing inquiries, updating status, and deleting records.

## Out of scope

- Multi-step interview scheduling or automated background check integrations.
- User accounts / volunteer portal logins (staff review and contact volunteers directly).

## Build loop

- `workflow.stepReview`: feature
- `workflow.checkpointCommits`: disabled

## Build steps

- [x] **Step 1: Migrations, Models, and Mailables**
  - Create migrations for `volunteer_applications` and `partner_enquiries`.
  - Create models `App\Models\VolunteerApplication` and `App\Models\PartnerEnquiry`.
  - Create mailables `NewVolunteerApplicationMail` and `NewPartnerEnquiryMail` with Blade mail templates.
  - **Done when:** `php artisan migrate` succeeds and models/mailables instantiate cleanly.

- [x] **Step 2: Form Requests and Public Get Involved Controller & Views**
  - Create form requests `StoreVolunteerApplicationRequest` and `StorePartnerEnquiryRequest` with honeypot validation.
  - Create `app/Http/Controllers/GetInvolvedController.php` with rate limiting and mail dispatching.
  - Create `resources/views/get-involved/index.blade.php` with hero, Alpine.js tab switcher, volunteer form, partner form, and impact sidebar.
  - Register public routes in `routes/web.php` with 301 redirect from `/partner`.
  - **Done when:** `/get-involved` renders 200 OK, submits both forms with validation feedback, and redirects `/partner` to `/get-involved`.

- [x] **Step 3: Admin Inquiry Management Suite**
  - Create `app/Http/Controllers/Admin/InquiryController.php` for viewing, status updating, and deleting applications and enquiries.
  - Create `resources/views/admin/inquiries/index.blade.php`.
  - Register admin routes under `admin` prefix in `routes/web.php`.
  - Add "Inquiries" tab to `resources/views/components/admin-layout.blade.php`.
  - **Done when:** Authenticated admin can view and manage volunteer applications and partner enquiries at `/admin/inquiries`.

- [x] **Step 4: Automated Feature Tests**
  - Create `tests/Feature/GetInvolvedTest.php` covering public submissions, mail dispatching, honeypot, rate limiting, and 301 redirect.
  - Create `tests/Feature/Admin/InquiryManagementTest.php` covering admin listing, status updates, and deletions.
  - **Done when:** `php artisan test --filter=Inquiry` and `php artisan test --filter=GetInvolved` pass 100%.

- [x] **Step 5: Full Verification & Code Style Pass**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire application suite.
  - **Done when:** 0 Pint violations and 100% of tests pass across the entire suite.

## Files / areas

- `database/migrations/*_create_volunteer_applications_table.php` (new)
- `database/migrations/*_create_partner_enquiries_table.php` (new)
- `app/Models/VolunteerApplication.php` (new)
- `app/Models/PartnerEnquiry.php` (new)
- `app/Mail/NewVolunteerApplicationMail.php` (new)
- `app/Mail/NewPartnerEnquiryMail.php` (new)
- `resources/views/emails/volunteer-application.blade.php` (new)
- `resources/views/emails/partner-enquiry.blade.php` (new)
- `app/Http/Requests/StoreVolunteerApplicationRequest.php` (new)
- `app/Http/Requests/StorePartnerEnquiryRequest.php` (new)
- `app/Http/Controllers/GetInvolvedController.php` (new)
- `resources/views/get-involved/index.blade.php` (new)
- `app/Http/Controllers/Admin/InquiryController.php` (new)
- `resources/views/admin/inquiries/index.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `routes/web.php` (modified)
- `tests/Feature/GetInvolvedTest.php` (new)
- `tests/Feature/Admin/InquiryManagementTest.php` (new)

## Data / contracts

- `VolunteerApplication` attributes:
  - `full_name` (string)
  - `email` (string)
  - `phone` (string)
  - `age_band` (string: `below_18`, `18_to_55`, `above_55`)
  - `work_status` (string: `employed`, `self_employed`, `unemployed`, `student`)
  - `skills` (text, nullable)
  - `status` (string: `pending`, `reviewed`, `contacted`)
- `PartnerEnquiry` attributes:
  - `organization_name` (string)
  - `contact_name` (string)
  - `email` (string)
  - `phone` (string, nullable)
  - `partnership_type` (string: `corporate_sponsor`, `school_partner`, `equipment_donor`, `mentorship_provider`, `other`)
  - `message` (text)
  - `status` (string: `pending`, `reviewed`, `contacted`)
- Public Routes:
  - `GET /get-involved` (`get-involved`)
  - `POST /get-involved/volunteer` (`get-involved.volunteer`)
  - `POST /get-involved/partner` (`get-involved.partner`)
  - `GET /partner` (301 redirect to `/get-involved`)
- Admin Routes:
  - `GET /admin/inquiries` (`admin.inquiries.index`)
  - `PATCH /admin/inquiries/volunteers/{volunteer}` (`admin.inquiries.volunteers.status`)
  - `DELETE /admin/inquiries/volunteers/{volunteer}` (`admin.inquiries.volunteers.destroy`)
  - `PATCH /admin/inquiries/partners/{partner}` (`admin.inquiries.partners.status`)
  - `DELETE /admin/inquiries/partners/{partner}` (`admin.inquiries.partners.destroy`)

## Testing

- Feature test suite: `tests/Feature/GetInvolvedTest.php` and `tests/Feature/Admin/InquiryManagementTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.

<!-- blueprint:completion {"schemaVersion":2,"specBytes":10526,"specSha256":"c36b0fe5d2e6b42a77e8522e4a1b841f1cbb255f9261c48768d26d7da95ee58b","branch":"refs/heads/feature/volunteer-and-partner-form","head":"8a37fe2cf421eb4710fed12485836ddd37dcf7a9","baseRef":"refs/heads/main","baseCommit":"8a37fe2cf421eb4710fed12485836ddd37dcf7a9","sourceTree":"6ab5f71fd0442a82c7aa9fdc05a2b0d4c65ad9bd","landing":"local-merge","absentOptional":[]} -->

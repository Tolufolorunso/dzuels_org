# Feature: Contact Page

**From build-plan:** feature 11
**Build attempt:** 1
**Branch:** feature/contact-page
**Status:** verified

## Goal

Provide a dedicated public Contact page (`/contact`) allowing visitors, donors, and partners to reach Dzuels Educational Foundation through an accessible contact form with honeypot anti-spam and rate limiting, view verified headquarters contact information (Ijero-Ekiti address, phone hotlines, official email), inspect a responsive lazy-loaded Google Maps embed of the headquarters, trigger automated staff email notifications at `info@dzuelsfoundation.org`, and equip staff with an administrative inbox at `/admin/messages` to review, filter, update review status (`unread`, `read`, `replied`), and manage submitted messages.

## Design reference

- Brand styling: Poppins typography, Great Vibes script accents, brand maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and semantic dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Prototype reference: `scratch/contact.html` structure:
  - Header breadcrumb: "Get in touch" with subtitle "We are always available to speak with you. Get in touch with us today."
  - Form fields: First Name, Last Name, Email, Phone, Subject, Message.
  - Contact information cards:
    - Visit Us: `12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria.`
    - Contact Us: `+234 707 664 6780` and `+234 904 249 3883`
    - Email Us: `info@dzuelsfoundation.org`
  - Embedded Google Map of Ijero-Ekiti headquarters (`12 Doherty St, Ijero, Ekiti, Nigeria`).

## In scope

1. **Database & Eloquent Model:**
   - Migration `create_contact_messages_table`:
     - `id` (bigint PK)
     - `first_name` (string)
     - `last_name` (string)
     - `email` (string)
     - `phone` (string, nullable)
     - `subject` (string)
     - `message` (text)
     - `status` (string: `unread`, `read`, `replied`, default `unread`)
     - `timestamps`
   - Model `App\Models\ContactMessage`:
     - Fillable: `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `status`.
     - Scopes: `scopeUnread()`, `scopeRecent()`.
     - Accessors: `full_name`, `status_badge_class`.

2. **Notifications & Mailable:**
   - Mailable `App\Mail\NewContactMessageMail` sent to foundation staff (`info@dzuelsfoundation.org`).
   - Blade email view `resources/views/emails/contact-message.blade.php`.
   - Resilient mail dispatching: Wrapped in try-catch so mail transport hiccups never crash user submissions.

3. **Public Contact Page (`/contact`):**
   - Form request `App\Http\Requests\StoreContactMessageRequest` with honeypot (`_hp_company`) check and validation rules.
   - Controller `App\Http\Controllers\ContactController`:
     - `index`: Loads contact settings from `Setting` model (with fallbacks) and renders `contact.index`.
     - `store`: Validates input, saves message, dispatches notification email, and redirects back with flash success message.
   - Blade view `resources/views/contact/index.blade.php`:
     - Hero section with title and subtitle.
     - Contact info grid: Address, telephone hotlines, email, and working hours.
     - Contact inquiry form with honeypot field, validation errors, and success alerts.
     - Responsive lazy-loaded Google Maps embed for Ijero-Ekiti headquarters.
   - Routes in `routes/web.php`:
     - `GET /contact` (`contact`)
     - `POST /contact` (`contact.submit`, with `throttle:6,1`)

4. **Admin Message Management (`/admin/messages`):**
   - Controller `App\Http\Controllers\Admin\ContactMessageController`:
     - `index`: Lists messages with status filtering (`all`, `unread`, `read`, `replied`), search querying, and unread counts.
     - `updateStatus`: Updates message status (`unread`, `read`, `replied`).
     - `destroy`: Permanently deletes message.
   - View `resources/views/admin/messages/index.blade.php`.
   - Admin Layout: Add "Messages" tab to `resources/views/components/admin-layout.blade.php`.
   - Admin routes under `admin` prefix in `routes/web.php`.

5. **Automated Testing:**
   - `tests/Feature/ContactPageTest.php`: Public page 200, successful form submission, notification mail dispatch, honeypot spam protection, validation errors, and rate limiting.
   - `tests/Feature/Admin/ContactMessageManagementTest.php`: Admin auth guards, message listing, status filtering, status updates, and deletions.

## Out of scope

- Live chat widget or third-party CRM webhook synchronization.
- Complex multi-agent ticketing or threaded public replies.

## Build loop

- `workflow.stepReview`: feature
- `workflow.checkpointCommits`: disabled

## Build steps

- [x] **Step 1: Database Migration, Model, and Mailable**
  - Create migration for `contact_messages` table.
  - Create model `App\Models\ContactMessage` with scopes and accessors.
  - Create mailable `App\Mail\NewContactMessageMail` with Blade template `resources/views/emails/contact-message.blade.php`.
  - **Done when:** `php artisan migrate` succeeds and `ContactMessage` model/mailable instantiate cleanly.

- [x] **Step 2: Form Request, Public Contact Controller, and Public View**
  - Create form request `StoreContactMessageRequest` with honeypot validation.
  - Create `app/Http/Controllers/ContactController.php` with rate limiting and mail dispatching.
  - Create `resources/views/contact/index.blade.php` with hero, contact cards, inquiry form, and Google Maps embed.
  - Register public routes in `routes/web.php`.
  - **Done when:** `/contact` renders 200 OK and submits inquiries with validation feedback and flash alerts.

- [x] **Step 3: Admin Message Inbox Management Suite**
  - Create `app/Http/Controllers/Admin/ContactMessageController.php` for listing, status updating, and deleting messages.
  - Create `resources/views/admin/messages/index.blade.php`.
  - Register admin routes under `admin` prefix in `routes/web.php`.
  - Add "Messages" navigation link to `resources/views/components/admin-layout.blade.php`.
  - **Done when:** Authenticated staff can view, filter, update status, and manage contact messages at `/admin/messages`.

- [x] **Step 4: Automated Feature Tests**
  - Create `tests/Feature/ContactPageTest.php` covering public submissions, mail dispatching, honeypot, rate limiting, and validation.
  - Create `tests/Feature/Admin/ContactMessageManagementTest.php` covering admin auth, listing, status updates, and deletion.
  - **Done when:** `php artisan test --filter=Contact` passes 100%.

- [x] **Step 5: Full Verification & Code Style Pass**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire application suite.
  - **Done when:** 0 Pint violations and 100% of tests pass across the entire suite.

## Files / areas

- `database/migrations/*_create_contact_messages_table.php` (new)
- `app/Models/ContactMessage.php` (new)
- `app/Mail/NewContactMessageMail.php` (new)
- `resources/views/emails/contact-message.blade.php` (new)
- `app/Http/Requests/StoreContactMessageRequest.php` (new)
- `app/Http/Controllers/ContactController.php` (new)
- `resources/views/contact/index.blade.php` (new)
- `app/Http/Controllers/Admin/ContactMessageController.php` (new)
- `resources/views/admin/messages/index.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `routes/web.php` (modified)
- `tests/Feature/ContactPageTest.php` (new)
- `tests/Feature/Admin/ContactMessageManagementTest.php` (new)

## Data / contracts

- `ContactMessage` attributes:
  - `first_name` (string)
  - `last_name` (string)
  - `email` (string)
  - `phone` (string, nullable)
  - `subject` (string)
  - `message` (text)
  - `status` (string: `unread`, `read`, `replied`, default `unread`)
- Public Routes:
  - `GET /contact` (`contact`)
  - `POST /contact` (`contact.submit`)
- Admin Routes:
  - `GET /admin/messages` (`admin.messages.index`)
  - `PATCH /admin/messages/{message}/status` (`admin.messages.status`)
  - `DELETE /admin/messages/{message}` (`admin.messages.destroy`)

## Testing

- Feature test suite: `tests/Feature/ContactPageTest.php` and `tests/Feature/Admin/ContactMessageManagementTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.

<!-- blueprint:completion {"schemaVersion":2,"specBytes":8498,"specSha256":"7d7b2003ef42143c852e04ab38874ebf3d07a53bc484df831e904898716c8558","branch":"refs/heads/feature/contact-page","head":"229a0b51df6eded2d868663e2e49bc7d5a71d711","baseRef":"refs/heads/main","baseCommit":"229a0b51df6eded2d868663e2e49bc7d5a71d711","sourceTree":"77dc7698d6e9da17b2502833cd44c19403be7e35","landing":"local-merge","absentOptional":[]} -->

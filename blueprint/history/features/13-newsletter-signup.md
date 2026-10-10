# Feature: Newsletter signup

**From build-plan:** feature 13
**Build attempt:** 1
**Branch:** feature/newsletter-signup

## Goal

Provide a robust newsletter subscription system for Dzuels Educational Foundation. Public visitors can subscribe to foundation updates using the site-wide footer form on any page, protected against duplicates, spam honeypots, and rapid submissions. Subscribers receive a secure tokenized unsubscribe link to manage their preferences. Foundation staff can view, filter, delete, and export active and historical subscribers as a downloadable CSV directly from the admin panel at `/admin/subscribers`.

## In scope

1. **Database Schema & Eloquent Model:**
   - Create `newsletter_subscribers` table migration with `id`, unique `email`, `subscribed_at`, `unsubscribed_at` (nullable datetime), `unsubscribe_token` (nullable unique string), and timestamps.
   - Implement `App\Models\NewsletterSubscriber` model with fillable attributes, datetime casts, scopes (`active`, `unsubscribed`), and accessors (`is_active`, `unsubscribe_url`).

2. **Public Subscription & Unsubscribe Flow:**
   - Implement `App\Http\Controllers\NewsletterController` with:
     - `subscribe(Request $request)`: handles subscription from footer form with honeypot validation (`_dzuels_hp`), email validation, duplicate prevention (returns friendly message if already active; reactivates if previously unsubscribed), rate limiting (`throttle:6,1`), and dual response format (JSON for AJAX/Alpine, redirect back with flash for standard POST).
     - `showUnsubscribe(string $token)`: displays tokenized unsubscribe confirmation page (`resources/views/newsletter/unsubscribe.blade.php`).
     - `unsubscribe(string $token)`: marks subscriber as unsubscribed (`unsubscribed_at = now()`) and confirms cancellation.
   - Update `resources/views/components/footer.blade.php` to wire the subscription form with CSRF, honeypot input, and responsive status messaging (success, duplicate, and validation errors).

3. **Admin Subscriber Management & CSV Export (`/admin/subscribers`):**
   - Resourceful controller `App\Http\Controllers\Admin\NewsletterSubscriberController` with:
     - `index(Request $request)`: paginated listing with status filtering (all, active, unsubscribed), email search, and subscription dates.
     - `exportCsv()`: streams/generates a clean CSV file download (`dzuels_newsletter_subscribers_YYYY-MM-DD.csv`) containing ID, email, status, subscribed date, and unsubscribed date.
     - `destroy(NewsletterSubscriber $subscriber)`: deletes subscriber record with confirmation.
   - Blade view `resources/views/admin/subscribers/index.blade.php` styled with the foundation admin theme and status badges.
   - Update admin layout navigation in `resources/views/components/admin-layout.blade.php` to include the Subscribers tab.

4. **Automated Testing & Quality Gates:**
   - Public feature tests in `tests/Feature/NewsletterSubscriptionTest.php` covering subscription, JSON responses, duplicate handling, reactivation, honeypot rejection, and tokenized unsubscription.
   - Admin feature tests in `tests/Feature/Admin/NewsletterManagementTest.php` covering auth guards, subscriber index display, CSV export headers/content, and record deletion.
   - Pint code formatting check and 100% test pass rate.

## Out of scope

- Third-party email marketing provider sync (Mailchimp, Brevo, Sendgrid API synchronization).
- Bulk email broadcast drafting tool (MVP focuses on collecting subscribers and CSV export for staff).
- Double opt-in confirmation email sending (plain email verification is standard for MVP and avoids requiring live outbound mail credentials).

## Build loop

- `workflow.stepReview: "feature"` — All build steps are implemented sequentially in a single pass before final review.
- `workflow.checkpointCommits: "disabled"` — No intermediate step commits; `/complete` creates the final feature commit.

## Build steps

1. [x] **Step 1: Database Migration & Eloquent Model**
   - Create migration `database/migrations/2026_10_10_240000_create_newsletter_subscribers_table.php`.
   - Implement `App\Models\NewsletterSubscriber` with scopes, casts, and helper accessors.
   - Run `php artisan migrate`.
   - *Done when:* The `newsletter_subscribers` table exists in SQLite database and model unit/factory checks pass.

2. [x] **Step 2: Public Subscription & Tokenized Unsubscribe Flow**
   - Implement `App\Http\Controllers\NewsletterController` with `subscribe`, `showUnsubscribe`, and `unsubscribe` methods.
   - Register public routes in `routes/web.php` (`POST /newsletter/subscribe`, `GET /newsletter/unsubscribe/{token}`, `POST /newsletter/unsubscribe/{token}`).
   - Update `resources/views/components/footer.blade.php` to connect the footer newsletter form with CSRF, honeypot, and fetch/POST handling.
   - Create `resources/views/newsletter/unsubscribe.blade.php` with confirmation UI.
   - *Done when:* Visitors can subscribe via footer form with duplicate handling, and subscribers can cancel subscription using their token link.

3. [x] **Step 3: Admin Subscriber Management & CSV Export**
   - Implement `App\Http\Controllers\Admin\NewsletterSubscriberController` with `index`, `exportCsv`, and `destroy`.
   - Register admin routes under `Route::prefix('admin')->name('admin.')->middleware(['admin'])` in `routes/web.php`.
   - Create Blade view `resources/views/admin/subscribers/index.blade.php` with search, filter tabs, table, and CSV download button.
   - Add Subscribers tab to `resources/views/components/admin-layout.blade.php`.
   - *Done when:* Authenticated admins can view subscribers, filter active vs unsubscribed, delete records, and download the full list as a CSV.

4. [x] **Step 4: Automated Testing & Verification**
   - Implement `tests/Feature/NewsletterSubscriptionTest.php` covering public subscribe, duplicate protection, token unsubscription, and honeypot rejection.
   - Implement `tests/Feature/Admin/NewsletterManagementTest.php` covering admin index view, CSV export download, and deletion.
   - Run `php vendor/bin/pint --test` and `php artisan test`.
   - *Done when:* All newsletter tests pass and Pint reports 0 style violations.

## Files / areas

- `database/migrations/2026_10_10_240000_create_newsletter_subscribers_table.php` (new)
- `app/Models/NewsletterSubscriber.php` (new)
- `app/Http/Controllers/NewsletterController.php` (new)
- `app/Http/Controllers/Admin/NewsletterSubscriberController.php` (new)
- `routes/web.php` (modified)
- `resources/views/components/footer.blade.php` (modified)
- `resources/views/newsletter/unsubscribe.blade.php` (new)
- `resources/views/admin/subscribers/index.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `tests/Feature/NewsletterSubscriptionTest.php` (new)
- `tests/Feature/Admin/NewsletterManagementTest.php` (new)

## Data / contracts

- **`newsletter_subscribers` table:**
  - `id`: unsigned big integer, primary key, auto incrementing
  - `email`: string (255), unique index
  - `subscribed_at`: datetime
  - `unsubscribed_at`: datetime, nullable
  - `unsubscribe_token`: string (64), nullable, unique index
  - `created_at`, `updated_at`: timestamps
- **Honeypot Field:** `_dzuels_hp` (must be absent or empty string)
- **CSV Export Headers:** `ID`, `Email`, `Status`, `Subscribed At`, `Unsubscribed At`

## Testing

- Unit/Feature testing using Pest/PHPUnit via `php artisan test`.
- Verify duplicate prevention, email format validation, honeypot bot trap, reactivation of unsubscribed emails, token validation on unsubscribe, admin access guards, and CSV download response.
- Lint and code formatting verification using `php vendor/bin/pint --test`.

## Notes for the AI

- Follow existing codebase patterns strictly (similar to `ContactMessageController`, `InquiryController`, and `StoreContactRequest`).
- Dual response handling: if `$request->expectsJson()` or `wantsJson()`, return JSON `{ success: true, message: '...' }` so the footer form can display instant inline feedback without jarring page reloads.
- Preserve human attribution in all Git operations (no AI trailers).


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8150,"specSha256":"cc927ba7403bfc52fd3f789655662e9950900ad3de11b47dbfe62ef1e91465b3","branch":"refs/heads/feature/newsletter-signup","head":"c5ba627f898dae7c6ada29a32fde651ebc3ab415","baseRef":"refs/heads/main","baseCommit":"c5ba627f898dae7c6ada29a32fde651ebc3ab415","sourceTree":"f272062b04ab215eb4d2f6991c28d9284fb615d1","landing":"local-merge","absentOptional":[]} -->

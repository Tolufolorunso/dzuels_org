# Feature: Admin login and site settings

**From build-plan:** feature 2
**Build attempt:** 1
**Branch:** feature/admin-login-and-site-settings
**Status:** verified

## Goal

Provide a secure administrative authentication flow and site settings management interface at `/admin`. Foundation staff can authenticate with their credentials and edit verified impact numbers (165 ICT students, 591 literacy students, 15 schools supported — with manual entry or API feed options), contact hotlines, official email, physical address, social links, Flutterwave donation URL, GTBank transfer details, and charity registration number without touching application code.

## Design reference

- Brand styling tokens from Feature 1 (`resources/css/app.css`): Maroon (`#6A0406`), Crimson (`#B4070A`), Cream (`#F9DEC9`), Dark Surface (`#241012`)
- Foundation logo: `public/front/images/logo.png`
- Verified statistics baseline:
  - Students in ICT training: 165
  - Students in literacy intervention: 591
  - Schools supported: 15
- Bank details: GTBank Plc, Account Number `0450072850`, Account Name `Dzuels Educational Foundation`
- Flutterwave URL: `https://flutterwave.com/pay/j0byxgjs0nma`
- Registration & Address: 12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria

## In scope

- Database migration for `users` adding `role` (`admin`, `editor`) and `is_active` boolean.
- Database migration and Eloquent model for `Setting` (`id`, `key`, `value`, `type`, `group`, `timestamps`) with cached `Setting::get($key, $default)` and `Setting::set($key, $value)` helpers.
- Database seeder initializing the default admin user and default foundation settings (impact stats 165, 591, 15, contact info, banking details, registration number).
- Artisan command `php artisan app:make-admin` to securely create or reset administrator accounts.
- Administrative authentication at `/admin/login` with session guard, login rate limiting (throttled after 5 attempts), CSRF protection, and secure logout.
- Administrative dashboard and Site Settings management form at `/admin/settings` (accessible at `/admin`):
  - Verified Impact Statistics: ICT Students (165), Literacy Students (591), Schools Supported (15), Feed Mode (manual vs. external API), and API URL endpoint.
  - Contact Information: Hotlines (`+234 803 388 9576`), official email (`info@dzuelsfoundation.org`), physical address in Ijero-Ekiti.
  - Giving & Banking Details: Flutterwave checkout URL, GTBank account number (`0450072850`), bank name, account name.
  - Social Links & Legal: LinkedIn, Facebook, X, Instagram URLs, registration number.
- Impact statistics service (`App\Services\ImpactStatsService`) resolving impact numbers: manual database values by default, with optional API endpoint fetching and resilient fallback to database numbers on connection failure.
- Automated tests in `tests/Feature/AdminAuthTest.php` and `tests/Feature/SiteSettingsTest.php`.

## Out of scope

- Public user registration or member accounts (this is strictly a staff admin interface; public users do not register).
- Team members and partners management (Feature 4).
- Programme catalog CRUD (Feature 6).
- Events and registration CRUD (Feature 7).
- Gallery album uploads (Feature 8).

## Build loop

- `workflow.stepReview: "feature"` (review after all implementation steps complete).
- `workflow.checkpointCommits: "disabled"`.

## Build steps

1. [x] **Step 1: Setting model, migration, and seeder**
   - Create migration adding `role` and `is_active` to `users`.
   - Create migration and `App\Models\Setting` with helper methods (`Setting::get()`, `Setting::set()`, `Setting::allGrouped()`).
   - Create `Database\Seeders\SettingSeeder` with verified baseline values (165, 591, 15, contacts, bank details).
   - Create `Database\Seeders\AdminUserSeeder` and Artisan command `app:make-admin` to provision staff accounts.
   - *Done when:* `php artisan migrate --seed` runs cleanly and populates the settings and default administrator account.

2. [x] **Step 2: Admin panel setup & authentication**
   - Configure administrative routes under prefix `/admin` with guest and auth middleware.
   - Implement admin login view (`/admin/login`), session authentication controller, login rate limiting (throttled after 5 attempts), and logout action.
   - Restrict access to authenticated users with `role: admin`.
   - *Done when:* Unauthenticated requests to `/admin` redirect to `/admin/login`; valid credentials authenticate and redirect to `/admin/settings`; invalid credentials return error feedback.

3. [x] **Step 3: Site settings management interface**
   - Implement the site settings management screen at `/admin/settings` with tabbed/card organization:
     - Verified Impact Statistics (165 ICT, 591 Literacy, 15 Schools, Feed Mode toggle, API Endpoint input).
     - Contact & Organization Info (Hotlines, Email, Office address).
     - Giving & Banking Details (Flutterwave URL, GTBank Account Number, Bank Name, Account Name).
     - Social & Registration Details (LinkedIn, Facebook, X, Instagram, Charity Registration Number).
   - Add Form Request validation ensuring required fields, valid integers for counters, and valid URLs for donation/social links.
   - Save updated settings and provide flash notification upon success.
   - *Done when:* Submitting the settings form updates the values in the database, displays success flash banner, and reloads updated values into form fields.

4. [x] **Step 4: Impact statistics API feed service**
   - Implement `App\Services\ImpactStatsService` that resolves the impact numbers: if feed mode is `api` and an endpoint is configured, attempts fetching JSON metrics; falls back gracefully to local database values on timeout or network error.
   - Add test coverage for both manual values and simulated API feed responses.
   - *Done when:* `ImpactStatsService::getStats()` returns verified stats (165, 591, 15) in manual mode, and gracefully handles external API connectivity without throwing exceptions.

5. [x] **Step 5: Automated test suite**
   - Create `tests/Feature/AdminAuthTest.php` testing login, invalid password rejection, rate limiting, and logout.
   - Create `tests/Feature/SiteSettingsTest.php` testing authorization, settings form display, updating settings, input validation, and `Setting::get()` helper.
   - Run `php artisan test` and `npm run build`.
   - *Done when:* All tests in `AdminAuthTest` and `SiteSettingsTest` pass with 100% green assertions.

## Files / areas

- `database/migrations/` - Migrations for `settings` table and adding `role` to `users`.
- `app/Models/Setting.php` - Eloquent model with caching getters and setters.
- `app/Models/User.php` - Updated User model with role cast and admin check.
- `database/seeders/SettingSeeder.php` - Seeder with verified foundation impact metrics and details.
- `app/Console/Commands/MakeAdminCommand.php` - CLI command to create/update admin credentials.
- `app/Http/Controllers/Admin/AuthController.php` - Handles login, logout, and rate limiting.
- `app/Http/Controllers/Admin/SettingController.php` - Handles settings display and updating.
- `app/Http/Requests/Admin/UpdateSettingsRequest.php` - Validation rules for site settings.
- `app/Services/ImpactStatsService.php` - Resolves impact stats (manual vs. API feed).
- `resources/views/admin/layout.blade.php` - Administrative dashboard shell.
- `resources/views/admin/login.blade.php` - Admin login screen with brand styling.
- `resources/views/admin/settings.blade.php` - Settings editor form with tabbed sections.
- `routes/web.php` - Administrative route declarations under `/admin`.
- `tests/Feature/AdminAuthTest.php` - Tests for admin authentication flow.
- `tests/Feature/SiteSettingsTest.php` - Tests for settings viewing, editing, and validation.

## Data / contracts

- `settings` table:
  - `key` (string, unique, index)
  - `value` (text, nullable)
  - `type` (string: string, integer, boolean, json)
  - `group` (string: impact, contact, banking, social, general)
- Core setting keys:
  - `stat_ict_students` (int: default 165)
  - `stat_literacy_students` (int: default 591)
  - `stat_schools_supported` (int: default 15)
  - `stat_feed_mode` (string: manual | api)
  - `stat_api_endpoint` (string, nullable)
  - `contact_phone_1` (string)
  - `contact_phone_2` (string)
  - `contact_email` (string)
  - `contact_address` (string)
  - `flutterwave_url` (string)
  - `bank_name` (string)
  - `bank_account_name` (string)
  - `bank_account_number` (string)
  - `registration_number` (string)
  - `social_linkedin`, `social_facebook`, `social_twitter`, `social_instagram` (string)

## Testing

- Unit & Feature tests: `php artisan test` (`AdminAuthTest` and `SiteSettingsTest`).
- Code style: `php vendor/bin/pint --test`.
- Asset compilation: `npm run build`.

## Notes for the AI

- Follow proportional engineering: implement a clean, lightweight, repository-native Blade admin interface matching the foundation's brand palette without bringing in bloated dependencies.
- Ensure authentication throttles brute force attempts using Laravel's rate limiter (`RateLimiter`).
- Ensure all impact stats fallback cleanly to verified baseline values (`165`, `591`, `15`) if database or API feed is missing.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":9244,"specSha256":"6eac9c0fdcfede6610cc14f49abf3ecf423663ae41a1be8cca1fe78148b5dd40","branch":"refs/heads/feature/admin-login-and-site-settings","head":"1a78cbe6dc77579f3a78e1daaface2589f7b640d","baseRef":"refs/heads/main","baseCommit":"1a78cbe6dc77579f3a78e1daaface2589f7b640d","sourceTree":"b51e8c607a6743518985a78c9bdb1bb9a22dfa3a","landing":"local-merge","absentOptional":[]} -->

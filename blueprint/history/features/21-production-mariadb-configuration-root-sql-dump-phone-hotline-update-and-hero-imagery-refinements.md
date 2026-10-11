# Feature: Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements

**From build-plan:** feature 21
**Build attempt:** 1
**Branch:** feature/production-mariadb-configuration-root-sql-dump-phone-hotline-update-and-hero-imagery-refinements
**Status:** verified

## Goal

Finalize production readiness for live deployment on Namecheap cPanel (`server161.web-hosting.com` running MariaDB): transition `.env` and `.env.example` to default to MariaDB/MySQL, provide a root-level `dzuels_database.sql` for 1-click phpMyAdmin import, ensure all contact and layout data is driven dynamically by the database, update the foundation phone hotline to `+234 904 249 3883` site-wide, hide Diaspora & International Wire transfers on `/donate` for now, enrich About Us, Programmes, and Contact hero banners with contextual background imagery and maroon overlays, and fix the Spelling Bee programme image to an authentic championship photo (`spelling-01.webp`).

## In scope

- **Database Environment Configuration:**
  - Update `.env` and `.env.example` to use `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=dzuels_org`, `DB_USERNAME=root`, `DB_PASSWORD=`.
  - Ensure local MariaDB/MySQL database `dzuels_org` is created, migrated, and seeded.
  - Generate and place `dzuels_database.sql` directly into the project root directory (as well as `database/dumps/dzuels_database.sql`) containing all 24 tables with updated settings and records for 1-click import into phpMyAdmin on `server161.web-hosting.com`.
- **Dynamic Database-Driven Hotline & Contact Info:**
  - Update `database/seeders/SettingSeeder.php` with `contact_phone_1` = `+234 904 249 3883`.
  - Replace old telephone number `234 803 388 9576` everywhere across the codebase (utility-bar, footer, privacy policy, 500 error page, schema organization, admin settings, DonateController fallbacks, seeders, and tests).
  - Ensure all layout templates dynamically resolve `Setting::get('contact_phone_1')` with fallback to `+234 904 249 3883`.
- **Giving Channels Refinement:**
  - Hide the "Diaspora & International Wire" transfer channel section on `/donate` (`resources/views/donate/index.blade.php`), keeping the focus on Flutterwave online giving and GTBank Naira transfers.
- **Hero Section Contextual Imagery:**
  - **About Us Hero:** Add a contextual background image (`front/images/gallery/optimized/general-01.webp` or CMS section image) with a dark maroon gradient overlay (`from-maroon/95 via-maroon/90 to-[#2B0203]/95`) ensuring WCAG 2.2 AA text contrast.
  - **Programmes Hero:** Add a digital skills/learning background image (`front/images/gallery/optimized/itd-2025-05.webp` or CMS section image) with a dark maroon gradient overlay.
  - **Contact Us Hero:** Add an outreach/office background image (`front/images/gallery/optimized/general-05.webp` or CMS section image) with a dark maroon gradient overlay.
- **Spelling Bee Programme Cover Photo Fix:**
  - Update `ProgrammeSeeder.php` and the `programmes` database table so that `spelling-bee-contests` uses authentic championship photo `front/images/gallery/optimized/spelling-01.webp` instead of `front/images/home_1.webp`.
- **Automated Testing & Launch Health:**
  - Update existing test assertions in `SiteSettingsTest`, `LayoutTest`, `PrivacyPolicyAndErrorPagesTest`, and `DonatePageTest`.
  - Verify all 222+ tests pass with `php artisan test`.
  - Verify zero style violations with `php vendor/bin/pint --test`.
  - Verify pre-launch readiness with `php artisan app:launch-check`.

## Out of scope

- Direct remote deployment via SSH (deployment is performed by the administrator using the cPanel deployment guide and the root SQL dump).
- Permanently deleting diaspora bank records (the domiciliary account numbers remain in database settings/controllers for when diaspora donations are re-enabled).
- Redesigning the underlying page content CMS architecture (which was completed in Feature 19).

## Build loop

- Step review cadence: `workflow.stepReview: "feature"` (one review packet after all steps are complete).
- Checkpoint commits: `workflow.checkpointCommits: "disabled"` (no intermediate commits; `/complete` creates the single feature commit).
- Quality gates: `regular.audit: "manual"`, `regular.independentReview: "when-sensitive"`, `regular.check: "manual"`.

## Build steps

- [x] **Step 1: MariaDB / MySQL Configuration & Root SQL Dump Setup**
  - Update `.env` and `.env.example` with `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=dzuels_org`, `DB_USERNAME=root`, `DB_PASSWORD=`.
  - Ensure the local `dzuels_org` MySQL database is created, migrated, and seeded.
  - Generate and export `dzuels_database.sql` directly in the project root directory (as well as `database/dumps/dzuels_database.sql`).
  - Update `LaunchCheckCommand.php` to verify root or dumps `dzuels_database.sql` export.
  - Done when: `.env` and `.env.example` specify MySQL/MariaDB connection, local MySQL connects without error, and root `dzuels_database.sql` exists and is non-empty.
- [x] **Step 2: Database-Driven Contact Details & Telephone Hotline Update**
  - Update `SettingSeeder.php` setting `contact_phone_1` to `+234 904 249 3883`.
  - Update all Blade templates (`utility-bar.blade.php`, `footer.blade.php`, `privacy-policy.blade.php`, `500.blade.php`, `schema-organization.blade.php`, `admin/settings.blade.php`, and `DonateController.php`) to dynamically render `Setting::get('contact_phone_1')` with fallback to `+234 904 249 3883`.
  - Hide the "Diaspora & International Wire" section in `resources/views/donate/index.blade.php`.
  - Update database settings so `contact_phone_1` is `+234 904 249 3883`.
  - Done when: Searching the codebase for `803 388 9576` returns zero active occurrences, `+234 904 249 3883` renders on utility bar and footer, and the Diaspora Wire section is hidden on `/donate`.
- [x] **Step 3: Hero Section Contextual Imagery & Spelling Bee Cover Photo Fix**
  - Enrich hero sections on **About Us** (`resources/views/about.blade.php`), **Programmes** (`resources/views/programmes/index.blade.php`), and **Contact Us** (`resources/views/contact/index.blade.php`) with contextual background imagery and dark maroon gradient overlays.
  - Update `ProgrammeSeeder.php` and the database so `spelling-bee-contests` uses `front/images/gallery/optimized/spelling-01.webp`.
  - Re-run `php artisan db:export-sql` to refresh both root `dzuels_database.sql` and `database/dumps/dzuels_database.sql`.
  - Done when: About, Programmes, and Contact heroes display rich contextual background imagery, and the Spelling Bee card/detail page displays `spelling-01.webp`.
- [x] **Step 4: Automated Testing, Linting & Pre-Launch Health Verification**
  - Update feature test assertions in `SiteSettingsTest`, `LayoutTest`, `PrivacyPolicyAndErrorPagesTest`, and `DonatePageTest` for `+234 904 249 3883` and hidden diaspora section.
  - Format all modified PHP files with `php vendor/bin/pint`.
  - Run `php artisan app:launch-check` to verify 0 failures and 0 warnings.
  - Run full test suite with `php artisan test` to confirm all 222+ tests pass.
  - Done when: All tests pass green, Pint reports no style violations, and `app:launch-check` confirms ready for launch.

## Files / areas

- `.env` & `.env.example`
- `dzuels_database.sql` (root database dump for phpMyAdmin import)
- `database/dumps/dzuels_database.sql`
- `database/seeders/SettingSeeder.php`
- `database/seeders/ProgrammeSeeder.php`
- `resources/views/components/utility-bar.blade.php`
- `resources/views/components/footer.blade.php`
- `resources/views/components/schema-organization.blade.php`
- `resources/views/privacy-policy.blade.php`
- `resources/views/errors/500.blade.php`
- `resources/views/admin/settings.blade.php`
- `resources/views/donate/index.blade.php`
- `resources/views/about.blade.php`
- `resources/views/programmes/index.blade.php`
- `resources/views/contact/index.blade.php`
- `app/Http/Controllers/DonateController.php`
- `app/Console/Commands/LaunchCheckCommand.php`
- `tests/Feature/SiteSettingsTest.php`
- `tests/Feature/LayoutTest.php`
- `tests/Feature/PrivacyPolicyAndErrorPagesTest.php`
- `tests/Feature/DonatePageTest.php`

## Data / contracts

- Default connection: `DB_CONNECTION=mysql`
- Database: `dzuels_org` on `127.0.0.1:3306`
- Setting key `contact_phone_1`: `+234 904 249 3883`
- Root SQL dump: `dzuels_database.sql`
- Spelling Bee cover image: `front/images/gallery/optimized/spelling-01.webp`

## Testing

- Test runner: `php artisan test`
- Updated test suites: `SiteSettingsTest`, `LayoutTest`, `PrivacyPolicyAndErrorPagesTest`, `DonatePageTest`
- Diagnostics: `php artisan app:launch-check`

## Notes for the AI

- Use `mysql` driver in Laravel configuration to connect to MariaDB/MySQL.
- Ensure root `dzuels_database.sql` and `database/dumps/dzuels_database.sql` are identical and updated.
- Preserve zero AI attribution in all commits.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8975,"specSha256":"87efb60b0645554d2c593282eb4f91c6559b830a7ed00741db1915ef4c684fab","branch":"refs/heads/feature/production-mariadb-configuration-root-sql-dump-phone-hotline-update-and-hero-imagery-refinements","head":"329256daa471087948e8302271c3cd6216695b02","baseRef":"refs/heads/main","baseCommit":"329256daa471087948e8302271c3cd6216695b02","sourceTree":"0bea0cb03d49d6c23005776f6ad8a5a5eeb74b6e","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Root & Dumps Database SQL Dump**:
   - Inspect `dzuels_database.sql` directly in the project root directory.
   - Inspect `database/dumps/dzuels_database.sql`.
   - Confirm both contain all 24 tables, valid MariaDB/MySQL directives, zero phpMyAdmin internal (`pma__*`) tables, and all 219 production records.
   - Run `php artisan db:export-sql` and verify that both dumps are synced in parallel.

2. **Verify Telephone Hotline & Dynamic Settings**:
   - Open [http://localhost:8000](http://localhost:8000).
   - Verify that the top utility bar and footer render `+234 904 249 3883`.
   - Verify that old number `234 803 388 9576` is absent from all visible text, links, and schemas.
   - Verify `/privacy-policy` and `/errors/500` render `+234 904 249 3883`.
   - Open `/admin/settings` as an administrator and confirm the primary hotline input defaults to `+234 904 249 3883`.

3. **Verify Giving Channels on `/donate`**:
   - Open [http://localhost:8000/donate](http://localhost:8000/donate).
   - Confirm a clean 2-column channel layout: **Instant Online Donation** (Flutterwave) and **Direct Bank Transfer** (GTBank Naira).
   - Confirm that the **Diaspora & International Wire** section is hidden.
   - Confirm that **In-Kind & Equipment Donations** displays below.

4. **Verify Hero Section Contextual Imagery**:
   - Visit [http://localhost:8000/about](http://localhost:8000/about) and inspect the hero section: students learning background photo (`general-01.webp`) with deep maroon gradient overlay.
   - Visit [http://localhost:8000/programmes](http://localhost:8000/programmes) and inspect the hero section: digital skills photo (`itd-2025-05.webp`) with deep maroon overlay.
   - Visit [http://localhost:8000/contact](http://localhost:8000/contact) and inspect the hero section: foundation outreach photo (`general-05.webp`) with deep maroon overlay and breadcrumb navigation.
   - Confirm crisp WCAG 2.2 AA text contrast across all three hero sections.

5. **Verify Spelling Bee Programme Cover Image**:
   - Visit [http://localhost:8000/programmes](http://localhost:8000/programmes) and locate the **Spelling Bee Contests** card.
   - Confirm it renders authentic championship photo `front/images/gallery/optimized/spelling-01.webp` (not `home_1.webp`).
   - Click into [http://localhost:8000/programmes/spelling-bee-contests](http://localhost:8000/programmes/spelling-bee-contests) and confirm the cover hero photo is `spelling-01.webp`.

6. **Run Pre-Launch Readiness & Automated Verification**:
   - Run `php artisan app:launch-check` and confirm status: **READY FOR LAUNCH (0 failures, 0 warnings)**.
   - Run `php vendor/bin/pint --test` and confirm 0 style violations.
   - Run `php artisan test` and confirm all 223 tests pass green.

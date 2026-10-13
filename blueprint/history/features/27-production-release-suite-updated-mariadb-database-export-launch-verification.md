# Feature: Production release suite, updated MariaDB database export & launch verification

**From build-plan:** feature 27
**Build attempt:** 1
**Branch:** feature/production-release-suite-updated-mariadb-database-export-launch-verification
**Status:** verified

## Goal
Deliver the authoritative final production release suite for Dzuels Educational Foundation: refresh the root `dzuels_database.sql` and `database/dumps/dzuels_database.sql` exports via `php artisan db:export-sql` containing all updated settings (including dynamic logo, Givebutter URL, multi-phone lines, timezone), 6 authentic impact causes (`meta_data` on home page), authentic Famoyinka Memorial Library media and programmes, and partner records for 1-click cPanel phpMyAdmin import; enhance `app:launch-check` to comprehensively audit all Milestone 5 additions (site logo, Givebutter URL, 6 causes, manifest freshness); synchronize the `cpanel_changed_files.txt` deployment manifest; update deployment guides; and execute full automated verification and lint audit.

## Design reference
*(Not applicable - release engineering, database export, diagnostics, and deployment manifest synchronization)*

## In scope
1. **Database Export & Synchronization:**
   - Execute `php artisan db:export-sql` to dump clean MySQL/MariaDB SQL into `database/dumps/dzuels_database.sql` and mirror directly to root `dzuels_database.sql`.
   - Confirm all 24 tables and 168+ seeded records are exported with safe DDL, `utf8mb4_unicode_ci`, foreign key disabling wrappers, and batch INSERTs.

2. **Launch-Check Command Hardening:**
   - Update `App\Console\Commands\LaunchCheckCommand` to verify:
     - Core configuration (`APP_KEY`, writable storage, compiled Vite asset manifest).
     - Database connection and records across all models (Programmes, Events, Team Members, Partners, Gallery Albums, Settings, Page Sections).
     - Presence and valid configuration of `site_logo` and `givebutter_url` in settings.
     - Presence of the 6 authentic causes and foundational tagline in `page_sections` (`page_key = 'home'`, `section_key = 'causes'`).
     - ITD 2026 flagship event.
     - Existence and non-empty status of `dzuels_database.sql` and `database/dumps/dzuels_database.sql`.
     - Presence of `cpanel_changed_files.txt` deployment manifest.
     - Registration of all critical named routes.

3. **cPanel Deployment Manifest & Documentation Synchronization:**
   - Update `cpanel_changed_files.txt` to include all changed/added files across Milestones 4 and 5, clearly categorized by feature.
   - Update `docs/cpanel-deployment-guide.md` and `docs/launch-checklist.md` with table counts, dump details, and verification commands.

4. **Automated Feature Testing & Quality Audit:**
   - Update `tests/Feature/AccessibilityAndLaunchReadinessTest.php` and `tests/Feature/DatabaseExportCommandTest.php` to validate the enhanced launch-check assertions and dump integrity.
   - Execute full test suite `php artisan test` (100% green).
   - Execute Pint code style formatting audit `php vendor/bin/pint --test`.

## Out of scope
- Direct live SSH execution or automated SFTP uploading to cPanel (user or sysadmin performs the manual cPanel upload per project safety constraints).
- Production database schema mutations (all migrations are stable).
- External package additions.

## Build loop
- Step review cadence: `feature` (all steps implemented sequentially, followed by review packet).
- Checkpoint commits: `disabled`.

## Build steps
- [x] 1. **Database Export Refresh & SQL Dump Synchronization** - Run `php artisan db:export-sql` to generate fresh exports in `database/dumps/dzuels_database.sql` and root `dzuels_database.sql` containing all 26 settings, 6 impact causes, library media, and partners.  
  *Done when:* Both `database/dumps/dzuels_database.sql` and `dzuels_database.sql` are refreshed with all 24 tables, valid MySQL DDL, and updated records.

- [x] 2. **Launch Check Diagnostics Suite Hardening** - Enhance `App\Console\Commands\LaunchCheckCommand` to verify logo settings, Givebutter URL, 6 causes in `page_sections`, manifest file presence, and dump freshness.  
  *Done when:* `php artisan app:launch-check` runs all checks and outputs `READY FOR LAUNCH (0 failures, 0 warnings)`.

- [x] 3. **cPanel Deployment Manifest & Documentation Synchronization** - Update `cpanel_changed_files.txt` with all touched files for Feature 27 and verify complete coverage of changed files across Milestones 4 and 5; update `docs/cpanel-deployment-guide.md` and `docs/launch-checklist.md`.  
  *Done when:* `cpanel_changed_files.txt` lists all created/modified files accurately and deployment guides reflect the finalized platform architecture.

- [x] 4. **Automated Test Suite Expansion & Lint Verification** - Update tests in `tests/Feature/AccessibilityAndLaunchReadinessTest.php` and `tests/Feature/DatabaseExportCommandTest.php`, run `php artisan test` and `php vendor/bin/pint --test`.  
  *Done when:* Test suite passes with 100% green status and 0 Pint violations.

## Files / areas
- `dzuels_database.sql`
- `database/dumps/dzuels_database.sql`
- `app/Console/Commands/LaunchCheckCommand.php`
- `cpanel_changed_files.txt`
- `docs/cpanel-deployment-guide.md`
- `docs/launch-checklist.md`
- `tests/Feature/AccessibilityAndLaunchReadinessTest.php`
- `tests/Feature/DatabaseExportCommandTest.php`

## Data / contracts
- `dzuels_database.sql`: 24 tables, MySQL 5.7+ / 8.0+ & MariaDB 10.3+ compatible, utf8mb4 collation.
- `app:launch-check`: Exit code 0 on success, informative diagnostics on configuration, database integrity, route registration, and deployment manifests.

## Testing
- `php artisan app:launch-check`
- `php artisan test --filter=AccessibilityAndLaunchReadinessTest`
- `php artisan test --filter=DatabaseExportCommandTest`
- `php artisan test`
- `php vendor/bin/pint --test`

## Notes for the AI
- Maintain zero external package dependencies.
- `DatabaseExportCommandTest` backs up and restores `dzuels_database.sql` during tests; Step 1 will ensure the real on-disk files are updated so the test backups preserve the new versions.
- Ensure all file paths in `cpanel_changed_files.txt` match actual repository paths.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6214,"specSha256":"9b6632b48b0764bd049dec3f8a1a7266cdf5c70aca21648a1a1e98a623749884","branch":"refs/heads/feature/production-release-suite-updated-mariadb-database-export-launch-verification","head":"51d2f4fc1b1d711fd0c9ac7b8c694e2c7aa445b0","baseRef":"refs/heads/main","baseCommit":"51d2f4fc1b1d711fd0c9ac7b8c694e2c7aa445b0","sourceTree":"2be70e8fa6c8c1dc86bc6f211db8323415ea2c34","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Automated Launch Diagnostics**:
   - Run `php artisan app:launch-check` from the terminal.
   - Confirm status reports `READY FOR LAUNCH (0 failures, 0 warnings)`.
   - Verify that all core sections are validated: Core Configuration, Database & Foundation Data (all 26 settings, 6 impact causes, ITD 2026 event, albums, partners), Database Dump freshness, and cPanel manifest.

2. **Verify Refreshed Database Dumps**:
   - Inspect `dzuels_database.sql` at the project root and `database/dumps/dzuels_database.sql`.
   - Confirm that all 24 tables and 168 rows are dumped with valid MySQL DDL, `utf8mb4_unicode_ci` collation, foreign key checks disabled, and batch INSERT statements.
   - Confirm `site_logo`, `givebutter_url`, and all 6 authentic pillars in `page_sections` are present.

3. **Verify Deployment Manifest & Runbooks**:
   - Review `cpanel_changed_files.txt` to ensure all created and modified files across the release are cataloged for selective or 1-click upload to `server161.web-hosting.com`.
   - Review `docs/cpanel-deployment-guide.md` and `docs/launch-checklist.md` for complete server setup instructions.

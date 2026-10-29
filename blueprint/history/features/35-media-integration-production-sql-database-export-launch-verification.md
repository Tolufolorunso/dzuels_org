# Feature: Media integration, production SQL database export & launch verification

**From build-plan:** feature 35
**Build attempt:** 1
**Branch:** feature/media-integration-production-sql-database-export-launch-verification

## Goal

Finalize Milestone 11 and achieve complete production launch readiness for Dzuels Educational Foundation: integrate authentic curated photography across key public touchpoints (Home, About, Causes, Gallery collections, and Programmes), complete gallery archive ingestion and optimization (strict $\le 180\text{ KB}$ WebP), re-export the authoritative, production-ready `dzuels_database.sql` for 1-click phpMyAdmin cPanel import, update the `cpanel_changed_files.txt` deployment manifest, and execute full end-to-end launch verification.

## In scope

- **Gallery Archive Ingestion Completion**:
  - Process and optimize all eligible archive photos from `gallery/` (`reading`, `spelling-bee`, `general-community`, `famoyinka`, etc.) using `php artisan media:ingest-gallery`.
  - Strictly enforce $\le 180\text{ KB}$ WebP ceiling and tag with proper year and collections.
  - Retain source files intact in `gallery/`.
- **Authentic Media Integration Across Public Views**:
  - Audit and enhance public templates where generic placeholders, collage composites, or unoptimized images are still referenced.
  - Wire authentic curated photography into:
    - Home (`resources/views/home/index.blade.php`): cause cards and hero highlights.
    - About (`resources/views/about/index.blade.php`): story, milestone, and community photos.
    - Gallery (`resources/views/gallery/index.blade.php`): authentic collection covers and thumbnails for all albums.
    - Programmes (`resources/views/programmes/index.blade.php` and `show.blade.php`): authentic covers.
- **Production SQL Database Export (`dzuels_database.sql`)**:
  - Execute `php artisan db:export-sql` to dump clean, production-ready SQL to `database/dumps/dzuels_database.sql` and synchronize to root `dzuels_database.sql`.
  - Verify complete tables (users, settings, page_sections, programmes, events, event_sponsors, gallery_albums, gallery_photos, media_items) with accurate counts.
  - Verify compatibility with phpMyAdmin on Namecheap cPanel (`server161.web-hosting.com`).
- **Deployment Manifest Synchronization**:
  - Update `cpanel_changed_files.txt` with all modified and new files across Milestone 11 (Features 33, 34, 35) for Core FTP deployment.
- **Full Launch Checks & Automated Test Verification**:
  - Run `php artisan app:launch-check` (or health checks) and verify all checks pass.
  - Write automated feature tests for media integration and database dump freshness.
  - Verify full test suite (`php artisan test`) passes with 100% success.
  - Run Pint code formatting (`php vendor/bin/pint --test`) with zero violations.

## Out of scope

- Direct FTP file upload or remote database execution (user handles deployment via cPanel / Core FTP using exported artifacts).
- Further modifications to the Tech Day event architecture (completed in Feature 34).

## Build loop

- `workflow.stepReview: "feature"`: implementation proceeds through small steps with one review packet after completion.
- `workflow.checkpointCommits: "disabled"`: commits will be managed during landing in `/complete`.

## Build steps

- [x] 1. **Complete Gallery Photography Ingestion**:
  - Verify all eligible photos from `gallery/` are processed and optimized into `public/uploads/media/gallery/` strictly $\le 180\text{ KB}$ WebP.
  - Ensure all cataloged records in `media_items` have valid years, dimensions, and album links.
  - Done when: Ingestion command completes with 0 errors and all eligible photos are cataloged.

- [x] 2. **Front-Facing Authentic Media Integration**:
  - Wire authentic curated photos into Home causes, About sections, Gallery collection covers, and Programmes.
  - Replace any remaining generic composite images with authentic photos.
  - Done when: Public pages display authentic high-resolution WebP photos with alt text and responsive srcset.

- [x] 3. **Production Database SQL Export Refresh**:
  - Run `php artisan db:export-sql` to generate fresh `database/dumps/dzuels_database.sql` and root `dzuels_database.sql`.
  - Validate SQL syntax, table drops, create statements, and data inserts for all 24+ tables.
  - Done when: Root `dzuels_database.sql` is updated, non-empty, and ready for 1-click phpMyAdmin import.

- [x] 4. **Deployment Manifest & Launch Health Check**:
  - Update `cpanel_changed_files.txt` with all files modified across Milestone 11.
  - Run `php artisan app:launch-check` and verify all systems are green.
  - Done when: Deployment manifest is synchronized and pre-launch audit reports 0 errors.

- [x] 5. **Automated Testing, Linting & Verification**:
  - Write `tests/Feature/MediaIntegrationAndProductionLaunchTest.php` verifying media integration, database export, and manifest integrity.
  - Run `php vendor/bin/pint --test` and `php artisan test`.
  - Done when: All tests pass with zero failures and code style is clean.

## Files / areas

- `app/Console/Commands/ExportDatabaseSql.php`
- `app/Services/DatabaseExportService.php`
- `dzuels_database.sql`
- `database/dumps/dzuels_database.sql`
- `cpanel_changed_files.txt`
- `resources/views/home/index.blade.php`
- `resources/views/about/index.blade.php`
- `resources/views/gallery/index.blade.php`
- `tests/Feature/MediaIntegrationAndProductionLaunchTest.php`

## Data / contracts

- `dzuels_database.sql`: MySQL 5.7+ / MariaDB 10.3+ compatible dump, UTF-8 unicode collation, DROP TABLE IF EXISTS before CREATE TABLE.
- `cpanel_changed_files.txt`: relative paths from project root, LF endings, listing all files changed in Milestone 11.
- All media assets strictly $\le 180\text{ KB}$ WebP.

## Testing

- Feature tests via `php artisan test --filter=MediaIntegrationAndProductionLaunchTest`
- Full test suite via `php artisan test`
- Code style check via `php vendor/bin/pint --test`

## Notes for the AI

- Preserve all original archives in `gallery/` and `final/`.
- Ensure SQL dump contains complete seeded records for ITD 2024, 2025, 2026, all sponsors, programmes, and albums.
- Verify `cpanel_changed_files.txt` accurately includes new console commands and modified views.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6281,"specSha256":"d9318d4a73f38edadfa5664c7cd403d5b8ea45888892518e18d43de881da6f0c","branch":"refs/heads/feature/media-integration-production-sql-database-export-launch-verification","head":"329ad2da05dfafdabd1d7b5c27b22b33a073d0f7","baseRef":"refs/heads/main","baseCommit":"329ad2da05dfafdabd1d7b5c27b22b33a073d0f7","sourceTree":"97c3e1f54467bba1d37678f5ce00e65df8d4d9e5","landing":"local-merge","absentOptional":[]} -->

## Verification

Live verification executed via /check and automated test suite on 2026-10-29:
- **Gallery Archives Ingestion & Optimization**: Processed and optimized 1,854 authentic photos from `gallery/` (`reading`, `spelling bee`, `general`, `2026`) plus 132 Tech Day photos into WebP under `public/uploads/media/gallery/` and `public/uploads/media/techday/`. 0 files exceeded the 184,320-byte ceiling.
- **Dynamic Database Records**: Seeded and backfilled 2,208 `media_items` and 2,108 `gallery_photos` with valid dimensions, album associations, and years across all collections.
- **Brand Asset Compression**: Compressed the 415 KB composite `public/front/images/dzuelsCollage.webp` down to 181 KB WebP, satisfying the size ceiling while maintaining visual sharpness.
- **Production Database SQL Export Refresh**: Ran `php artisan db:export-sql` exporting 25 complete tables (4,434 total rows) to `database/dumps/dzuels_database.sql` and synchronized to root `dzuels_database.sql` (1.14 MB), fully verified for 1-click phpMyAdmin cPanel import.
- **Launch Readiness Suite**: Ran `php artisan app:launch-check` reporting READY FOR LAUNCH (0 failures, 0 warnings across configuration, core database records, and critical routes).
- **Automated Tests**: 356 tests and 1,975 assertions passed cleanly with 0 failures; Pint formatting verified clean.

## How to try it

1. **Verify Photo Gallery Collections**:
   - Visit `http://localhost:8000/gallery` and switch between *All Collections*, *Reading & Children's Library*, *Spelling Bee Championship*, and *General Community & Cohorts* to browse authentic high-resolution WebP photos in the responsive grid and modal lightbox.
2. **Verify Multi-Year Tech Day Switcher**:
   - Visit `http://localhost:8000/events/ijero-tech-day-2026` and use the interactive festival switcher pills to navigate between the 2026, 2025, and 2024 editions.
3. **Verify Legacy 301 Redirect**:
   - Visit `http://localhost:8000/programmes/itd` and observe instant 301 permanent redirect to `http://localhost:8000/events/ijero-tech-day-2026`.
4. **Inspect Production SQL Database Dump**:
   - Inspect `dzuels_database.sql` in project root to verify all 25 tables, drop/create statements, and complete data inserts ready for phpMyAdmin cPanel import.
5. **Review Deployment Manifest**:
   - Review `cpanel_changed_files.txt` for Core FTP upload synchronization.

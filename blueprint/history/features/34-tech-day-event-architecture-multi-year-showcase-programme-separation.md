# Feature: Tech Day event architecture, multi-year showcase (2024, 2025, 2026) & programme separation

**From build-plan:** feature 34
**Status:** verified
**Build attempt:** 1
**Branch:** feature/tech-day-event-architecture-multi-year-showcase-programme-separation

## Goal

Elevate Innovation and Tech Day (ITD) as Dzuels Educational Foundation's annual premier flagship event by cleanly separating it from the ongoing continuous education programmes under `/programmes`, purging all legacy techday imagery from storage and database, ingesting and optimizing curated authentic photography from `final/` (2024, 2025, and 2026) into strict $\le 180\text{ KB}$ WebP, and delivering a multi-year Tech Day event showcase with edition switcher and authentic photo archives under `/events`.

## In scope

- **Legacy Tech Day Image Purge**:
  - Delete all legacy techday image files from `public/front/images/gallery/optimized/` and `public/uploads/`.
  - Remove legacy techday records from `media_items` and `gallery_photos` for `itd-2024`, `itd-2025`, and `itd` albums.
- **Curated Tech Day Photography Ingestion (`final/`)**:
  - Build and execute `php artisan media:ingest-techday` to process authentic curated photos from `final/techday2024` (45 photos), `final/techday2025` (46 photos), and `final/techday2026` (41 photos).
  - Compress every photo strictly into $\le 180\text{ KB}$ WebP with GD fallback.
  - Catalog into `media_items` tagged with event years (`2024`, `2025`, `2026`) and link to corresponding `gallery_albums` (`itd-2024`, `itd-2025`, `itd-2026`).
  - Set top-tier authentic banner images for each Tech Day edition in the `events` table.
- **Programme Separation Architecture**:
  - Separate Tech Day from `/programmes` so the Programmes section strictly highlights continuous educational initiatives (Digital Literacy, Reading Competition, Spelling Bee, Famoyinka Library, Essay Competition).
  - Add a 301 redirect from `/programmes/innovation-and-tech-day` to the flagship Tech Day event page.
  - Display a featured "Annual Flagship Festival" banner on `/programmes` directing visitors to the Tech Day event experience.
- **Multi-Year Tech Day Event Showcase**:
  - Ensure all 3 annual editions exist in the `events` database table (2024 Inaugural, 2025 Edition, 2026 Upcoming Flagship).
  - Add an interactive multi-year edition switcher on Tech Day event pages (`/events/ijero-tech-day-*` or `/events/{slug}`) allowing visitors to toggle between 2026, 2025, and 2024.
  - Display an authentic photo masonry gallery for the selected edition populated directly from the newly ingested authentic photos.
- **Testing & Verification**:
  - Automated feature tests verifying programme separation, 301 redirect, event edition switcher, authentic photo cataloging, and legacy image purge.

## Out of scope

- Final database SQL export (`dzuels_database.sql`) and Core FTP upload verification (Feature 35).
- Further modifications to non-Tech Day gallery collections (completed in Feature 33).

## Build loop

- `workflow.stepReview: "feature"`: implementation proceeds through small steps with one review packet after completion.
- `workflow.checkpointCommits: "disabled"`: commits will be managed during landing in `/complete`.

## Build steps

- [x] 1. **Legacy Tech Day Image Purge**:
  - Build Artisan cleanup routine or command to delete obsolete techday files in `public/front/images/gallery/optimized/` and remove legacy records from `gallery_photos` and `media_items`.
  - Done when: No legacy `itd-*` or uncurated techday image files or orphan records remain in DB or disk.

- [x] 2. **Curated Tech Day Photo Ingestion (`final/`)**:
  - Implement `php artisan media:ingest-techday` to process `final/techday2024`, `final/techday2025`, and `final/techday2026`.
  - Ensure albums `itd-2024`, `itd-2025`, and `itd-2026` exist.
  - Optimize all 132 photos to $\le 180\text{ KB}$ WebP, catalog into `media_items` with year tags and `gallery_photos`.
  - Done when: All photos from `final/` are cataloged in `media_items` and viewable in Media Library.

- [x] 3. **Programme Separation & Flagship Linkage**:
  - Update `ProgrammeController.php` so `/programmes/innovation-and-tech-day` redirects (301) to the current flagship Tech Day event (`/events/ijero-tech-day-2026`).
  - Update `resources/views/programmes/index.blade.php` to exclude ITD from continuous programmes and add a dedicated "Annual Flagship Event" spotlight card linking to Tech Day.
  - Done when: Visiting `/programmes` displays only continuous educational programmes, and visiting `/programmes/innovation-and-tech-day` redirects to the Tech Day event.

- [x] 4. **Multi-Year Tech Day Event Architecture & UI Showcase**:
  - Ensure `events` table contains complete records for 2024, 2025, and 2026 with authentic themes, objectives, dates, and banner images from the newly ingested photos.
  - Enhance `resources/views/events/show.blade.php` and `resources/views/events/index.blade.php` with a multi-year edition switcher (`2026 Flagship (Upcoming)`, `2025 Edition`, `2024 Inaugural`) and photo gallery masonry for that year.
  - Done when: Viewing any Tech Day event displays the edition switcher and authentic photos from that specific year's festival.

- [x] 5. **Automated Testing, Linting & Verification**:
  - Write `tests/Feature/TechDayEventAndProgrammeSeparationTest.php` covering programme separation, redirects, multi-year switcher, and authentic photo association.
  - Run `php vendor/bin/pint --test` and `php artisan test`.
  - Done when: All tests pass with zero failures and code formatting is completely clean.

## Files / areas

- `app/Console/Commands/IngestTechDayPhotosCommand.php`
- `app/Http/Controllers/ProgrammeController.php`
- `app/Http/Controllers/EventController.php`
- `resources/views/programmes/index.blade.php`
- `resources/views/events/show.blade.php`
- `resources/views/events/index.blade.php`
- `database/seeders/EventSeeder.php`
- `tests/Feature/TechDayEventAndProgrammeSeparationTest.php`

## Data / contracts

- Event slugs: `ijero-tech-day-2026`, `ijero-tech-day-2025`, `ijero-tech-day-2024`.
- Redirect: `GET /programmes/innovation-and-tech-day` $\rightarrow$ 301 redirect to `/events/ijero-tech-day-2026`.
- Edition switcher: links between Tech Day event records by year with active pill indicators.
- Authentic media: all 132 photos from `final/` optimized to WebP $\le 180\text{ KB}$ under `uploads/media/techday/{year}/`.

## Testing

- Feature tests via `php artisan test --filter=TechDayEventAndProgrammeSeparationTest`
- Full test suite via `php artisan test`
- Code style check via `php vendor/bin/pint --test`

## Notes for the AI

- Follow strict $\le 180\text{ KB}$ WebP ceiling for all ingested photos from `final/`.
- Do not delete original files in `final/` (keep source archive safe).
- Ensure memory efficiency with `gc_collect_cycles()` during the 132 photo batch optimization.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6926,"specSha256":"abba74a0f78237c29f2cdac77a9c70f0c4f4c14b6c8fce9733116e6de04441e9","branch":"refs/heads/feature/tech-day-event-architecture-multi-year-showcase-programme-separation","head":"c8d4a8f8e1df0bbc130ae6a2639c366b0351c9b1","baseRef":"refs/heads/main","baseCommit":"c8d4a8f8e1df0bbc130ae6a2639c366b0351c9b1","sourceTree":"125726fbcd709033af5631c8ce1a611aafe33ae2","landing":"local-merge","absentOptional":[]} -->

## Verification

Live verification executed via /check and automated test suite on 2026-10-29:
- **Legacy Image Purge**: `php artisan media:purge-legacy-techday` cleanly removed all 66 legacy `itd-*` files from `public/front/images/gallery/optimized/`, removed 44 `gallery_photos` and 67 `media_items` records, and deleted the redundant generic `itd` album.
- **Curated Ingestion (`final/`)**: `php artisan media:ingest-techday` processed and optimized all 132 authentic camera photos (45 from 2024, 46 from 2025, 41 from 2026) to strict `<= 180 KB` WebP under `public/uploads/media/techday/{year}/` while preserving all source files in `final/`. 0 files exceeded the 184,320-byte ceiling, and 358.65 MB was saved.
- **Programme Separation**: `GET /programmes/innovation-and-tech-day` and `GET /programmes/itd` return HTTP 301 Permanent Redirect to `/events/ijero-tech-day-2026`. `/programmes` exclusively lists continuous educational initiatives and features the Annual Flagship Festival spotlight banner.
- **Multi-Year Showcase**: `events` table seeded with complete records for 2026 (Upcoming Flagship), 2025 (2nd Annual Edition), and 2024 (Inaugural Edition). `/events` and all Tech Day show pages render the interactive edition switcher pills and authentic photo masonry preview.
- **Automated Tests**: 349 tests and 1,924 assertions passed cleanly with 0 failures; Pint formatting verified clean.

## How to try it

1. **Verify Programme Separation & 301 Redirect**:
   - Open your browser to `http://localhost:8000/programmes/innovation-and-tech-day` and observe that you are permanently redirected (HTTP 301) to `http://localhost:8000/events/ijero-tech-day-2026`.
   - Visit `http://localhost:8000/programmes` and confirm that only continuous educational programmes are listed, accompanied by the prominent **Annual Flagship Festival** spotlight card linking to Tech Day.
2. **Explore Tech Day Multi-Year Showcase & Edition Switcher**:
   - Visit `http://localhost:8000/events/ijero-tech-day-2026`: observe the **Switch Festival Edition** switcher bar and the **ITD 2026 Authentic Photography** masonry grid showing authentic festival photos.
   - Click **2025 (2nd Edition)**: observe seamless transition to `http://localhost:8000/events/ijero-tech-day-2025`, the 2025 theme ("Empowering Rural Youth Through Innovation and Technology"), and 46 authentic curated photos.
   - Click **2024 (Inaugural)**: observe transition to `http://localhost:8000/events/ijero-tech-day-2024`, the inaugural theme ("Igniting Digital Potential in Ijero-Ekiti"), and 45 authentic curated photos.
3. **Verify Events Calendar**:
   - Visit `http://localhost:8000/events` and review the **ITD Multi-Year Editions** navigation strip and the past events archive cards with authentic banner images.
4. **Inspect Media Library**:
   - Log into `/admin/login` and navigate to **Media Library** (`/admin/media`). Use the **Event Year** chips (2026, 2025, 2024) to view the newly ingested authentic Tech Day photos.

# Feature: Brand assets, ITD 2026 event overhaul and gallery optimization

**From build-plan:** feature 18
**Build attempt:** 1
**Branch:** feature/brand-assets-itd-2026-event-overhaul-and-gallery-optimization
**Status:** verified

## Goal

Complete the Dzuels Educational Foundation brand identity with a full favicon suite generated from the foundation logo; overhaul the flagship Ijero Tech Day 2026 (ITD 2026) event page using the extracted official PDF backdrop graphic, updated October 3, 2026 date, new AI empowerment objective, removal of SeedDev and public registration forms, 3 dynamic event sponsors, and a clear separation of historical archives; and repair, optimize, and server-render the photo gallery page for flawless, professional performance.

## In scope

1. **Brand Identity & Favicon Suite**:
   - Generate multi-size `favicon.ico` (16x16, 32x32, 48x48), `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png` (180x180), `android-chrome-192x192.png`, `android-chrome-512x512.png`, and `site.webmanifest` from `public/front/images/logo.png`.
   - Wire favicon links and manifest into `resources/views/components/layout.blade.php`, `resources/views/components/admin-layout.blade.php`, and error templates (`404.blade.php`, `500.blade.php`).

2. **ITD 2026 Backdrop Image & Database Refinements**:
   - Extract the 1390×1132 backdrop graphic from `gallery/2026/Ijero_Tech_Day_2026_Backdrop_6.5x8ft.pdf` and store as `public/front/images/events/itd-2026-backdrop.webp` (and PNG fallback).
   - Add `objective` column to `events` table via migration.
   - Create `event_sponsors` migration and `App\Models\EventSponsor` model (`id`, `event_id`, `name`, `logo_path`, `website_url`, `tier`, `sort_order`).
   - Update `EventSeeder` for ITD 2026:
     - Date: Saturday, October 3, 2026 (`2026-10-03 08:00:00`).
     - Theme: "Learning Today, Creating Tomorrow".
     - Objective: "Empowering children with AI tools to learn smarter, think creatively, and build the future."
     - Banner image: `front/images/events/itd-2026-backdrop.webp`.
     - SeedDev removed from 2026 edition description.
     - `is_registration_open` set to `false`.
     - Seed 3 dynamic sponsors linked to ITD 2026.

3. **Event Views Overhaul (ITD 2026 Page & Past Events Archives)**:
   - Update `resources/views/events/show.blade.php`:
     - Display extracted backdrop banner as hero image.
     - Prominently feature the AI empowerment objective.
     - Remove "Reserve Your Spot" registration form (replace with school delegation coordination notice).
     - Render "Event Sponsors & Supporters" section featuring the 3 dynamic sponsors with logos and links.
     - Ensure zero mentions of SeedDev for 2026.
   - Update `resources/views/events/index.blade.php`:
     - Distinctly showcase ITD 2026 as the Upcoming Flagship Event.
     - Separate past events (ITD 2024, etc.) into a dedicated "Past Events & Historical Archives" section.

4. **Gallery Page Repair, Server-Rendering & Professional Optimization**:
   - Fix JavaScript execution race condition in `resources/views/gallery/index.blade.php` by defining `galleryApp` inline so Alpine binds without `ReferenceError`.
   - Server-render photo cards directly in Blade for instant display with zero layout shift and graceful no-JS fallback.
   - Interactive album filter chips (All, ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General).
   - Responsive masonry grid with smooth transitions and keyboard-accessible modal lightbox.
   - Delete scratch test file `gallery/hello.html`.

5. **Automated Testing & Launch Check Alignment**:
   - Create `tests/Feature/BrandAssetsEventAndGalleryTest.php` testing favicons, ITD 2026 event attributes, sponsors, registration removal, and gallery rendering.
   - Verify with `php artisan test`, `php artisan app:launch-check`, and `php vendor/bin/pint --test`.

## Out of scope

- Feature 19 scope (WordPress-style page content manager, role-based user management, forgotten password reset).
- Feature 20 scope (cPanel MySQL export tool and migration guide).
- Paid third-party event ticketing widgets.

## Build loop

Following project policy (`workflow.stepReview: "feature"`, `workflow.checkpointCommits: "disabled"`):
- Implement steps 1 through 5 in sequence.
- Run narrow checks during implementation.
- Run full Verify suite (`php artisan test` and `php vendor/bin/pint --test`) after all steps pass.

## Build steps

- [x] 1. **Favicon Suite & Webmanifest Generation**: Generate `favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, Android Chrome icons, and `site.webmanifest` from `logo.png`; wire into `<x-layout>`, `<x-admin-layout>`, and error pages.
       - *Done when:* Favicons load across all public and admin pages, webmanifest is accessible, and head link tags are present.

- [x] 2. **ITD 2026 Backdrop Graphic, Schema & Sponsor Models**: Extract 1390×1132 backdrop from `gallery/2026/Ijero_Tech_Day_2026_Backdrop_6.5x8ft.pdf` to `public/front/images/events/`; create migration for `objective` on `events` and `event_sponsors` table; update `Event` model, `EventSponsor` model, and `EventSeeder` with Oct 3 2026 date, AI objective, and 3 sponsors.
       - *Done when:* Migrations run cleanly, and database contains the updated ITD 2026 event with backdrop banner, objective, and 3 associated sponsors.

- [x] 3. **Event Views Overhaul (ITD 2026 & Historical Archives)**: Refactor `resources/views/events/show.blade.php` to display backdrop banner, AI objective, 3 dynamic sponsors, and school delegation notice with registration form removed; refactor `resources/views/events/index.blade.php` to clearly separate upcoming flagship event from historical past events.
       - *Done when:* `/events` and `/events/ijero-tech-day-2026` render the new backdrop, objective, 3 sponsors, no public registration form, and separated historical archives.

- [x] 4. **Gallery Page Repair, Server-Rendering & Professional Optimization**: Fix Alpine.js initialization in `resources/views/gallery/index.blade.php`; server-render photo cards in masonry grid; ensure filter chips and lightbox work smoothly; delete scratch test file `gallery/hello.html`.
       - *Done when:* `/gallery` renders photos immediately without JavaScript errors, filter chips switch collections, and lightbox displays full-screen photos with keyboard navigation.

- [x] 5. **Automated Test Suite & Verification**: Create `tests/Feature/BrandAssetsEventAndGalleryTest.php`; update `LaunchCheckCommand` if needed to reflect date/sponsor updates; verify with `php artisan test`, `php artisan app:launch-check`, and `php vendor/bin/pint --test`.
       - *Done when:* All 7+ feature tests and full regression suite pass with 100% green rate, launch check reports ready, and Pint reports no styling issues.

## Files / areas

- `public/favicon.ico`, `public/favicon-16x16.png`, `public/favicon-32x32.png`, `public/apple-touch-icon.png`, `public/site.webmanifest` - brand favicons
- `resources/views/components/layout.blade.php`, `resources/views/components/admin-layout.blade.php` - head favicon links
- `gallery/2026/Ijero_Tech_Day_2026_Backdrop_6.5x8ft.pdf` - backdrop source
- `public/front/images/events/itd-2026-backdrop.webp` - extracted backdrop banner
- `database/migrations/2026_10_11_160000_add_objective_to_events_table.php` - events objective column
- `database/migrations/2026_10_11_160001_create_event_sponsors_table.php` - event sponsors table
- `app/Models/Event.php`, `app/Models/EventSponsor.php` - Eloquent models
- `database/seeders/EventSeeder.php` - updated ITD 2026 seed data
- `resources/views/events/show.blade.php`, `resources/views/events/index.blade.php` - event views
- `resources/views/gallery/index.blade.php` - gallery view fix and server rendering
- `gallery/hello.html` - scratch file cleanup
- `tests/Feature/BrandAssetsEventAndGalleryTest.php` - feature test suite

## Data / contracts

- Event Model: `objective` string/text; `sponsors()` hasMany relation to `EventSponsor`.
- EventSponsor Model: `event_id`, `name`, `logo_path`, `website_url`, `tier`, `sort_order`.
- ITD 2026 Event Date: October 3, 2026.
- ITD 2026 Registration: `is_registration_open = false` (form removed from show view).

## Testing

- Feature test: `php artisan test --filter=BrandAssetsEventAndGalleryTest`
- Launch check: `php artisan app:launch-check`
- Full test suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- Preserve human Git attribution: `tolufolorunso <tolufolorunso@yahoo.com>` (zero AI attribution trailers).
- Keep all existing test coverage green while adding new assertions.
- Extract backdrop graphic with high visual fidelity and convert to efficient WebP.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8754,"specSha256":"30684771bbedde45614b656226b41038b6cff0383f58a90264787d66000b2181","branch":"refs/heads/feature/brand-assets-itd-2026-event-overhaul-and-gallery-optimization","head":"ad4c40298e1a570a6ddf354f563dedbcae974652","baseRef":"refs/heads/main","baseCommit":"ad4c40298e1a570a6ddf354f563dedbcae974652","sourceTree":"66c8fb3dc764d7e8217d374c0639434067eb07a2","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Brand Favicons & Webmanifest**:
   - In browser, visit `http://localhost:8000/`.
   - Inspect browser tab: confirm crisp Dzuels logo favicon displays.
   - Inspect page `<head>`: confirm links for `favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, and `site.webmanifest`.
   - Visit `http://localhost:8000/site.webmanifest`: verify valid JSON with name, short_name, and icon suite.
   - Visit admin area at `http://localhost:8000/admin/login`: confirm favicon tags are also present.

2. **Verify ITD 2026 Event Overhaul**:
   - Visit `http://localhost:8000/events/ijero-tech-day-2026`.
   - Confirm hero banner renders the extracted backdrop graphic (`itd-2026-backdrop.webp`).
   - Confirm event date is Saturday, October 3, 2026.
   - Confirm AI empowerment objective card: "Empowering children with AI tools to learn smarter, think creatively, and build the future."
   - Confirm official sponsors grid features Solape Adesuyi Associates, Help Meet A Need, and Int International.
   - Confirm "Attendance & Access" card displays the 300 student delegate allocation, 22 cohort certificates, and staggered arrival times.
   - Confirm the public registration form ("Reserve Your Spot") is absent.
   - Confirm zero mentions of SeedDev for the 2026 event edition.

3. **Verify Events Index & Historical Separation**:
   - Visit `http://localhost:8000/events`.
   - Confirm ITD 2026 is featured in the milestone spotlight with AI objective badge and sponsor partner pills.
   - Scroll down to "Historic Milestones & Completed Events": verify past editions (ITD 2024) are distinctly presented as concluded archives with links to gallery photos.

4. **Verify Gallery Repair, Server-Rendering & Lightbox**:
   - Visit `http://localhost:8000/gallery`.
   - Confirm all photo cards render immediately without blank screens or delay.
   - Click album filter chips ("ITD 2025", "ITD 2024", "Reading Competition", "Spelling Bee", "General"): verify smooth filtering of photo cards.
   - Click any photo card: confirm fullscreen lightbox modal opens instantly with dark backdrop.
   - Press Left and Right arrows (or click navigation buttons): cycle through photos smoothly.
   - Press Escape: confirm lightbox closes and re-enables page scrolling.
   - Verify scratch test file `gallery/hello.html` is permanently deleted.

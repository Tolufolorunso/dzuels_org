# Feature 37: Public Gallery anti-flash performance & Tech Day event UI overhaul with dynamic date status & SEO

**From build-plan:** feature 37
**Build attempt:** 1
**Status:** verified
**Branch:** feature/public-gallery-anti-flash-performance-tech-day-event-ui-overhaul-with-dynamic-date-status-seo

## Goal

Eliminate severe photo loading reflows and flashing on `/gallery` through intrinsic aspect-ratio reservation, CSS-level `x-cloak` protection, and smooth image fade-ins; dynamically compute event status (`is_past`, `is_future`, `is_today`, `status_label`) so concluded events like Ijero Tech Day 2026 accurately reflect their concluded status instead of hardcoding "Upcoming Flagship"; elevate the Tech Day event show and index views into a visually stunning, festival-grade showcase with authentic photography recap, dynamic edition switchers, and rich Schema.org Event JSON-LD SEO structured data.

## Design reference

- Public Gallery: `resources/views/gallery/index.blade.php`
- Events Catalog: `resources/views/events/index.blade.php`
- Tech Day & Event Show Page: `resources/views/events/show.blade.php`
- Global Layout: `resources/views/components/layout.blade.php`
- Global Styling: `resources/css/app.css`
- Design Tokens: Maroon `#6A0406`, Crimson `#B4070A`, Cream `#F9DEC9`, Mint `#56E39F`

## In scope

1. **Anti-Flash Public Gallery Performance (`/gallery` & `/gallery/{album}`):**
   - Inject global `[x-cloak] { display: none !important; }` in `resources/css/app.css` and in `<x-layout>` inline critical CSS so Alpine elements never cause a Flash of Unstyled Content (FOUC) during initial page load.
   - Enforce fixed aspect ratio containers (`aspect-4/3`) with subtle skeleton placeholders (`bg-stone-100 dark:bg-stone-800 animate-pulse` until loaded) for every gallery card so column dimensions are deterministic prior to image download.
   - Implement smooth image load transitions (`opacity-0 transition-opacity duration-300` removing `opacity-0` via `onload="this.classList.remove('opacity-0')"`).
   - Ensure server-side initial filtering aligns with `$activeSlug`, avoiding client-side snap/flash on deep-linked collection pages.
   - Implement progressive batching / "Load More Photos" for large albums so the initial DOM render remains ultra-lightweight and instantaneous.

2. **Dynamic Event Date Status Helpers (`App\Models\Event`):**
   - Add model attributes and helper methods: `is_past`, `is_future`, `is_today`, and `status_label`.
   - Remove hardcoded `$editionYear >= 2026` logic from `resources/views/events/index.blade.php` and `resources/views/events/show.blade.php`.
   - Accurately determine if an event is upcoming, happening today, or concluded based on its actual `event_date` timestamp.

3. **Tech Day & Events UI Overhaul (`/events/{slug}` and `/events`):**
   - **Hero & Header:** Elevate typography and visual hierarchy with festival kicker badges, date and venue pills, dynamic status indicator (`✅ Concluded Edition` vs `📅 Upcoming Gathering`), key objective callout, and quick stats bar (300+ Student Delegates, 22 Cohort Graduates, 10 Interactive Stations, 100% Free Access).
   - **Edition Switcher:** Polish festival edition switcher pills (2024 Inaugural, 2025 2nd Edition, 2026 Concluded/Current) with clear active states, dynamic status tags, and smooth navigation.
   - **Concluded Event Experience:** Replace confusing "Registration Closed" state with an inspiring "Event Impact & Graduation Summary" card celebrating the 22 graduating cohort students, 300 delegates, and closing ceremony milestones.
   - **Countdown Timer:** Display an interactive Alpine.js countdown timer widget only for upcoming events (`is_future = true`).
   - **Photo Recap Gallery:** Showcase authentic curated photos from the event's edition album in a responsive, hover-zoom 4:3 grid with a direct link to the full gallery album.
   - **Sponsor Showcase & Social Share Strip:** Polish official event sponsor cards and add a 1-click "Copy Link" button with toast notification alongside WhatsApp, X, and Facebook sharing.

4. **Comprehensive SEO Structured Data:**
   - Upgrade Schema.org `Event` JSON-LD in `resources/views/events/show.blade.php` to include valid `eventStatus` (`https://schema.org/EventScheduled`), `eventAttendanceMode` (`https://schema.org/OfflineEventAttendanceMode`), `location` Place and PostalAddress, `organizer`, `offers` (free admission), `typicalAgeRange`, `audience`, and `image`.
   - Fix invalid `EventMovedOnline` fallback.

5. **Automated Testing:**
   - Comprehensive test suite in `tests/Feature/GalleryAntiFlashAndTechDayEventUiTest.php` covering anti-flash elements, dynamic date status calculation, SEO JSON-LD structure, and past event UI states.

## Out of scope

- Modifying the Admin Media Hub or universal media picker (completed in Feature 36).
- Changes to `/donate` or `/get-involved` (scheduled for Feature 38).
- Database migrations or schema modifications (existing columns on `events` and `gallery_photos` fully satisfy all requirements).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all implementation steps).
- `workflow.checkpointCommits: "disabled"`.
- Verify with `php vendor/bin/pint --test` and `php artisan test`.

## Build steps

- [x] 1. **Anti-flash gallery performance & global x-cloak protection** - add `[x-cloak] { display: none !important; }` in `resources/css/app.css` and `<x-layout>`, enforce deterministic `aspect-4/3` containers with skeleton backgrounds and smooth fade-in transitions in `resources/views/gallery/index.blade.php`, and eliminate album filter snap on load. Done when visiting `/gallery` and `/gallery/itd-2026` shows zero layout shifts, zero image popping, and clean placeholders during loading.
- [x] 2. **Dynamic event date status helpers** - update `App\Models\Event` with `is_past`, `is_future`, `is_today`, and `status_label` accessors; replace all hardcoded `$editionYear >= 2026` checks in `resources/views/events/index.blade.php` and `resources/views/events/show.blade.php`. Done when ITD 2026 dynamically computes as a concluded edition and upcoming events compute as upcoming.
- [x] 3. **Tech Day show page & events index UI overhaul** - redesign `resources/views/events/show.blade.php` and `resources/views/events/index.blade.php` with festival typography, stats strip, dynamic countdown timer for future events, celebration recap card for past events, polished edition switcher pills, and authentic photography recap grid. Done when `/events` and `/events/ijero-tech-day-2026` render a modern, visually stunning festival layout with correct dynamic status.
- [x] 4. **SEO structured data & meta tags elevation** - enhance the Schema.org `Event` JSON-LD script and Open Graph tags in `resources/views/events/show.blade.php`, correcting `eventStatus` to standard `EventScheduled` and adding rich event details. Done when JSON-LD parses validly with all required attributes for Google Rich Results.
- [x] 5. **Feature tests and code formatting** - create `tests/Feature/GalleryAntiFlashAndTechDayEventUiTest.php`, run the complete test suite to green, and format with Laravel Pint. Done when all 362+ tests pass with zero failures and Pint reports zero code style issues.

## Files / areas

- `resources/css/app.css` - global `[x-cloak]` rule
- `resources/views/components/layout.blade.php` - inline critical `[x-cloak]` style
- `resources/views/gallery/index.blade.php` - anti-flash masonry grid, aspect ratios, image fade-in
- `app/Models/Event.php` - `is_past`, `is_future`, `is_today`, `status_label` accessors
- `app/Http/Controllers/EventController.php` - public photo filtering for editions
- `resources/views/events/index.blade.php` - dynamic status, festival edition pills, recap buttons
- `resources/views/events/show.blade.php` - Tech Day festival UI redesign, celebration summary card, countdown widget, SEO JSON-LD
- `tests/Feature/GalleryAntiFlashAndTechDayEventUiTest.php` - automated feature verification

## Verification evidence

- **Pint Code Style:** `php vendor/bin/pint --test` passed with 0 violations.
- **PHPUnit Test Suite:** `php artisan test` passed with 368 tests, 2,260 assertions, and 0 failures.
- **Vite Production Assets:** Built in 2.90s.
- **Manifest:** Appended to `cpanel_changed_files.txt`.


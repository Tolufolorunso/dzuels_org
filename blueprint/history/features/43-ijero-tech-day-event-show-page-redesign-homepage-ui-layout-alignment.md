# Feature 43: Ijero Tech Day event show page redesign (Homepage UI layout alignment)

**From build-plan:** feature 43
**Build attempt:** 1
**Branch:** feature/ijero-tech-day-event-show-page-redesign-homepage-ui-layout-alignment
**Status:** completed

## Goal

Redesign the Ijero Tech Day event show page (`/events/ijero-tech-day-[year]`) to mirror the visual structure, balance, and rhythm of the Homepage UI. This eliminates the left-flushed feeling reported by the user, providing a rich full-bleed hero, a floating 4-metric impact banner, a balanced 2-column story & media section with an offset cream border, a 3-column milestone celebration card, an interactive full-width schedule timeline, and a full-bleed radial maroon CTA section.

## In scope

1. **Full-Bleed Rich Hero Section**:
   - Dark burgundy/navy gradient overlay matching the homepage hero (`linear-gradient(135deg, rgba(43, 2, 3, 0.94) 0%, rgba(106, 4, 6, 0.85) 55%, rgba(36, 47, 64, 0.72) 100%)`) over the event banner.
   - Clean breadcrumbs navigation embedded in the hero header.
   - Dynamic badge row with date, time, event status pill (`Upcoming Flagship`, `Concluded Edition`, or `Happening Today`), and venue.
   - Prominent display typography (`text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white`).
   - Highlighted event theme pill (`✨ Theme: "..."`).
   - Prominent **Multi-Year Festival Edition Switcher** (`2024 Inaugural`, `2025 2nd Edition`, `2026 Upcoming Flagship`) styled with frosted glass tags and active status ring.
   - Hero action CTAs (*Register Free*, *Browse Photo Album*, *Volunteer / Partner*, *Contact Event Desk*).
   - Glassmorphism countdown timer for upcoming future editions.

2. **Floating 4-Metric Impact Banner**:
   - Mirrors the homepage floating metrics band (`relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 sm:-mt-16 mb-8`).
   - 4 key metrics with color-accented progress bars:
     - **300+** Student Delegates across Ijero-Ekiti (mint bar)
     - **22** Certified Cohort Graduates (crimson/maroon bar)
     - **10** Interactive Hands-on Stations (amber bar)
     - **100%** Free & Fully Sponsored Admission (cream/emerald bar)

3. **Balanced 2-Column Story & Media Section**:
   - Eliminates the left-flushed feeling using a balanced `grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center`.
   - **Left column:** 16:9 banner frame enclosed within an offset cream border (`border-3 border-[var(--accent-cream)] rounded-3xl`) mirroring the homepage *Who We Are* section, accompanied by quick event tags (Venue, Admission, Organizer).
   - **Right column:** *Story & Context* eyebrow, bold headline, highlighted *Key Event Objective* quote box with target icon (`🎯`), narrative paragraphs, and quick action buttons.

4. **3-Column Milestone Celebration / Highlights**:
   - 3-card grid highlighting *Cohort 2026 Certification*, *School Delegations*, and *Staggered Arrival Times* with icon badges and confirmation chips.

5. **Full-Width Interactive Agenda & Schedule Timeline**:
   - `max-w-7xl mx-auto` schedule timeline displaying session timings, titles, and speaker/lead badges.

6. **Registration Section**:
   - Clean centered registration card with CSRF, validation alerts, attendee categorization, and privacy statement.

7. **Curated Authentic Photography & Social Share**:
   - Curated photo masonry for the edition with anti-flash protection and direct album links.
   - Social sharing strip with 1-click clipboard link copy, WhatsApp, X/Twitter, and Facebook.

8. **Official Event Sponsors Strip**:
   - Clean cards showcasing partner logos and external partner links.

9. **Full-Bleed Radial Maroon CTA Section**:
   - Matches the homepage final CTA (`bg-radial from-crimson/40 via-maroon to-[#2B0203]`) with dual buttons (*Support Our Mission* and *Volunteer / Partner*).

10. **Other Foundation Gatherings**:
    - 3-card grid connecting visitors to other foundation events.

## Out of scope

- Central Media Hub EXIF deep metadata, pagination, sorting & zoom controls (scheduled for Feature 44).

## Build loop

Follow `blueprint/config.json`:
- `workflow.stepReview`: `"feature"` (single review packet at completion of implementation).
- `workflow.checkpointCommits`: `"disabled"` (work committed upon review approval).

## Build steps

- [x] 1. **Update `resources/views/events/show.blade.php` with Homepage UI layout alignment**
  - Restructure sections with full-bleed hero, floating 4-metric banner, balanced 2-column story & media section, 3-column milestone celebration, full-width agenda, and radial maroon CTA.
  - *Done when:* The show page renders with full visual parity to the Homepage rhythm, with no left-flushed content.

- [x] 2. **Create comprehensive test suite and align existing tests**
  - Create `tests/Feature/IjeroTechDayEventShowPageRedesignTest.php` asserting all sections, metrics, edition switcher, and responsive layout classes.
  - Align assertions in `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php`.
  - *Done when:* All 4 tests in the new feature test pass alongside all 54 event tests.

- [x] 3. **Code style, asset compilation, and full test suite verification**
  - Run `php vendor/bin/pint --test` and `npm run build`.
  - Run full test suite `php artisan test`.
  - *Done when:* 396 tests pass, 0 fail, Pint reports 0 violations, and Vite build succeeds.

- [x] 4. **Live server verification across all 3 festival editions**
  - Test `curl.exe` against `/events/ijero-tech-day-2024`, `/events/ijero-tech-day-2025`, and `/events/ijero-tech-day-2026`.
  - *Done when:* All return HTTP 200 with matching layout markers.

- [x] 5. **cPanel Manifest & Autopilot review handoff**
  - Update `cpanel_changed_files.txt`.
  - Finalize run-state as ready.
  - *Done when:* Review packet is presented and ready for `/complete`.

## Files / areas

- `resources/views/events/show.blade.php`
- `tests/Feature/IjeroTechDayEventShowPageRedesignTest.php`
- `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php`
- `cpanel_changed_files.txt`

## Verification Evidence

- **New Feature Tests**: `php artisan test --filter=IjeroTechDayEventShowPageRedesignTest` (4 tests, 69 assertions passed).
- **All Event Tests**: `php artisan test --filter=Event` (54 tests, 396 assertions passed).
- **Full Test Suite**: `php artisan test` (396 tests, 2,575 assertions passed).
- **Code Style**: `php vendor/bin/pint --test` passed with 0 violations.
- **Asset Compilation**: `npm run build` compiled clean in 1.30s (`public/build/`).
- **Live HTTP Verification**: `curl.exe` returned HTTP 200 across `/events/ijero-tech-day-2024`, `/events/ijero-tech-day-2025`, and `/events/ijero-tech-day-2026`.


# Feature: Media Hub sorting, year filtering & universal photo picker overhaul

**From build-plan:** feature 33
**Build attempt:** 1
**Branch:** feature/media-hub-sorting-year-filtering-universal-photo-picker-overhaul
**Status:** verified

## Goal

Provide foundation staff with a fast, intuitive media management and photo selection experience by adding multi-criteria sorting (by Year, Event/Collection, Date added, Name, and Size) and quick Year filter chips (`All`, `2026`, `2025`, `2024`) across both the Central Media Hub (`/admin/media`) and the universal modal picker (`<x-admin.media-picker>`), while ingesting, optimizing ($\le 180\text{ KB}$ WebP), and cataloging authentic photo archives from `gallery/` into structured collections.

## In scope

- Add an indexed `year` column (`unsignedSmallInteger`, nullable) to `media_items` via migration.
- Update `App\Models\MediaItem` with `year` in `$fillable`, `$casts`, and add `scopeByYear($query, $year)` and expanded sorting options (`year_desc`, `year_asc`, `name_asc`, `name_desc`, `size_desc`, `size_asc`).
- Update `MediaLibraryController::index` and `MediaLibraryController::pickerItems` to accept `year` filter parameters and return available years.
- Update `/admin/media` (`index.blade.php`) with year filter chips, improved collection tabs, and an intuitive sort dropdown.
- Update `<x-admin.media-picker>` component with year filter chips, sort dropdown, and active filter indicators so selecting photos for page heroes, programmes, and events is fast and frictionless.
- Build a resilient Artisan command `php artisan media:ingest-gallery` that scans `gallery/`, optimizes images to strict $\le 180\text{ KB}$ WebP, tags them with their corresponding collection and year, and catalogs them into `media_items` and `gallery_photos` without memory exhaustion.
- Automated tests covering year filtering, picker API, and ingestion.

## Out of scope

- Tech Day photos from `final/` (specifically designated for Feature 34).
- Programme separation and multi-year Tech Day event showcase (Feature 34).
- Final production SQL export and cPanel launch verification (Feature 35).

## Build loop

- `workflow.stepReview: "feature"`: implementation proceeds through small steps with one review packet after completion.
- `workflow.checkpointCommits: "disabled"`: commits will be managed during landing in `/complete`.

## Build steps

- [x] 1. **Database Migration & MediaItem Model Enhancement**:
  - Create migration adding indexed `year` (nullable unsigned smallint) to `media_items`.
  - Update `MediaItem.php` with `year` in `$fillable`, integer cast, `scopeByYear($query, $year)`, and enhanced `scopeOrdered($query, $sort)` supporting `year_desc`, `year_asc`.
  - Done when: `php artisan migrate` executes cleanly and `MediaItem::byYear('2025')->get()` queries without error.

- [x] 2. **MediaLibraryController & Picker API Upgrade**:
  - Update `MediaLibraryController::index` to handle `$yearFilter` and pass distinct available years to the view.
  - Update `MediaLibraryController::pickerItems` JSON API to accept `year` and `sort` query parameters and return `years` list in the response.
  - Done when: `GET /admin/media/picker-items?year=2024` returns only 2024 items in JSON.

- [x] 3. **Central Media Hub & Universal Modal Picker UI Overhaul**:
  - Add quick Year filter chips (`All`, `2026`, `2025`, `2024`) and an enhanced Sort dropdown to `/admin/media/index.blade.php`.
  - Update `<x-admin.media-picker>` Alpine.js component and modal template with Year filter chips and Sort selector.
  - Done when: Opening `<x-admin.media-picker>` allows filtering by Year and sorting by name/date/size immediately.

- [x] 4. **Resilient Gallery Photo Ingestion Artisan Command**:
  - Implement `php artisan media:ingest-gallery` to process photos from `gallery/` (`reading`, `spelling bee`, etc.) into WebP ($\le 180\text{ KB}$), tag with collection and year, and catalog into `media_items`.
  - Done when: Running the command processes gallery photos into `public/uploads/media/` and records them in database with proper collections and year tags.

- [x] 5. **Testing, Linting & Verification**:
  - Write `tests/Feature/Admin/MediaHubSortingAndYearFilterTest.php` verifying year filtering, sorting, and picker API.
  - Run `php vendor/bin/pint` and `php artisan test`.
  - Done when: All tests pass and code styling passes with zero violations.

## Files / areas

- `database/migrations/*_add_year_to_media_items_table.php`
- `app/Models/MediaItem.php`
- `app/Http/Controllers/Admin/MediaLibraryController.php`
- `resources/views/admin/media/index.blade.php`
- `resources/views/components/admin/media-picker.blade.php`
- `app/Console/Commands/IngestGalleryPhotosCommand.php`
- `tests/Feature/Admin/MediaHubSortingAndYearFilterTest.php`

## Data / contracts

- `media_items.year`: unsigned smallint, nullable, indexed.
- `GET /admin/media?year=2025&sort=name_asc&category=reading`
- `GET /admin/media/picker-items?year=2025&sort=newest&category=all&search=term`
  - Response JSON: `{ items: [...], collections: [...], years: [2026, 2025, 2024, ...] }`

## Testing

- Unit/Feature tests via `php artisan test --filter=MediaHubSortingAndYearFilterTest`
- Full test suite via `php artisan test`
- Code style check via `php vendor/bin/pint --test`

## Verification

Live verification executed via `/check` on 2026-10-29:
- **Migration & Model**: `media_items.year` indexed column exists; `MediaItem::byYear()` and `MediaItem::ordered('year_desc')` verified via live database query.
- **HTTP / JSON API**: Authenticated call to `GET /admin/media/picker-items?year=2024` returned HTTP 200 with matching items and `years: [2026, 2025, 2024]`.
- **UI & Controls**: Live HTML from `GET /admin/media?year=all` verified Event Year filter chips, `year_desc` / `year_asc` sort selectors, and year badges.
- **Ingestion**: `php artisan media:ingest-gallery --limit=3 --skip-techday` verified compression to strict $\le 180\text{ KB}$ WebP (`83.8 KB`, `142.5 KB`, `168.3 KB`), cataloging into `media_items` and `gallery_photos` with year tags, and 2.84 MB storage savings.
- **Automated Tests**: 342 tests and 1,875 assertions passed with 0 failures; Pint code style clean.

## How to try it

1. **Visit Central Media Hub**:
   - Log into `/admin/login` and navigate to **Media Library** (`/admin/media`).
   - Click the **Event Year** filter chips (`All Years`, `2026`, `2025`, `2024`) to immediately filter photos by archive year.
   - Use the **Sort Order** selector to sort by `Year (Newest -> Oldest)` or `Year (Oldest -> Newest)`.
   - Click on any photo to edit metadata: verify the **Event / Archive Year** input field.
2. **Open Universal Photo Picker**:
   - Go to any form using the media picker (e.g. **Events > New Event** or **Pages > Home > Edit**).
   - Click **"Choose from Library"**: observe the Year filter chips and Sort selector in the modal header.
   - Select a year chip to filter photos dynamically without reloading the page.
3. **Run Batch Ingestion**:
   - Run `php artisan media:ingest-gallery --help` to view options.
   - Run `php artisan media:ingest-gallery --limit=10 --skip-techday` to process and catalog photos into $\le 180\text{ KB}$ WebP.


# Feature 44: Central Media Hub deep metadata, pagination, sorting & zoom controls

**From build-plan:** feature 44
**Build attempt:** 1
**Branch:** feature/central-media-hub-deep-metadata-pagination-sorting-zoom-controls
**Status:** completed

## Goal

Elevate the Central Media Hub (`/admin/media`) into an authoritative digital asset management center by adding deep EXIF metadata extraction (`taken_at` timestamp, `color_space`), configurable per-page pagination (`50`, `80`, `100`, `120`, `150`, default: `50`), multi-criteria sorting (capture date, upload date, file size, dimensions, title), interactive photo grid zoom controls (compact, normal, large, extra-large), comprehensive metadata badges on photo cards, and an interactive photo inspector modal.

## In scope

1. **Database Migration & Model Casts**:
   - Migration `2026_10_30_140000_add_taken_at_and_color_space_to_media_items_table.php` adding `taken_at` (nullable timestamp) and `color_space` (nullable string, e.g. `sRGB`, `Adobe RGB`) with index on `taken_at`.
   - `MediaItem` model: Added `taken_at` and `color_space` to `$fillable`, cast `taken_at` to `datetime`, added helper accessors: `formatted_taken_at`, `capture_date_formatted`, `aspect_ratio`, `color_space_badge`, and `formatted_size`.

2. **Deep Metadata Extraction & Backfill Service**:
   - Dedicated service `App\Services\MediaMetadataExtractor` safely extracting EXIF tags via `exif_read_data()` and image headers via `getimagesize()`:
     - Capture timestamp (`DateTimeOriginal` / `DateTimeDigitized` / `DateTime`), parsed to UTC Carbon instance.
     - Color space: mapping EXIF `ColorSpace` (1 => `sRGB`, 2 or 65535 => `Adobe RGB` / `Uncalibrated`), checking ICC profile tags if present, defaulting safely when uncalibrated.
     - Image dimensions ($W \times H$), file size, mime type, camera make/model.
   - Integrated extraction into `MediaLibraryController::upload()` so newly uploaded photos have `taken_at` and `color_space` populated automatically before GD WebP conversion strips EXIF.
   - Artisan command `php artisan media:extract-metadata` to scan and backfill existing `media_items` from disk (backfilled 2,206 cataloged assets).

3. **Configurable Pagination Selector**:
   - Support `per_page` query parameter with allowed values: `50`, `80`, `100`, `120`, `150` (default: `50`).
   - Validate and bound the `per_page` input in `MediaLibraryController::index()`.
   - Sleek per-page selector dropdown on the Central Media Hub toolbar, preserving active filter query strings.

4. **Multi-Criteria Sorting**:
   - Expanded `MediaItem::scopeOrdered()` and `MediaLibraryController`:
     - `captured_desc` (Capture Date: Newest first)
     - `captured_asc` (Capture Date: Oldest first)
     - `newest` (Upload Date: Newest first, default)
     - `oldest` (Upload Date: Oldest first)
     - `size_desc` (File Size: Largest first)
     - `size_asc` (File Size: Smallest first)
     - `dimensions_desc` (Resolution: Highest first)
     - `dimensions_asc` (Resolution: Lowest first)
     - `name_asc` (Filename / Title: A to Z)
     - `name_desc` (Filename / Title: Z to A)
     - `year_desc` & `year_asc`

5. **Interactive Photo Grid Zoom Controls**:
   - Supported 4 responsive grid zoom levels:
     - `compact`: Dense grid (`grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3`)
     - `normal`: Standard grid (`grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6`, default)
     - `large`: Detailed preview grid (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6`)
     - `extra-large`: High-resolution inspection grid (`grid-cols-1 sm:grid-cols-2 gap-8`)
   - Zoom switcher buttons on the toolbar with Alpine.js state persistence (`localStorage.getItem('dzuels_media_zoom')`).

6. **Card Metadata Display & Interactive Inspector Modal**:
   - On photo cards: Show capture date badge (if available), dimensions, size, and color space tag.
   - Inspector Modal: Dedicated deep metadata panel/modal displaying:
     - Full photo preview with external high-res viewer link
     - Detailed technical specs table (Filename, Public URL with 1-click copy, Capture Date/Time, Upload Date/Time, Dimensions, Aspect Ratio, File Size, MIME Type, Color Space)
     - Content metadata (Title, Caption, Alt Text, Associated Collection, Year, Public Gallery Visibility, Site Usage status)
     - Quick action buttons (Edit Metadata, Copy URL, Close Inspector)

## Out of scope

- Direct RAW/DNG client-side editing or browser colour grading (not needed for web asset management).

## Build loop

Follow `blueprint/config.json`:
- `workflow.stepReview`: `"feature"` (single review packet at completion of implementation).
- `workflow.checkpointCommits`: `"disabled"` (work committed upon review approval).

## Build steps

- [x] 1. **Database Migration & Model Casts**
  - Created migration `2026_10_30_140000_add_taken_at_and_color_space_to_media_items_table.php` and migrated database.
  - Added casts, fillables, and accessors on `MediaItem`.
  - *Done when:* Columns exist in database and accessors format dates and aspect ratios accurately.

- [x] 2. **MediaMetadataExtractor Service & Backfill Command**
  - Created `App\Services\MediaMetadataExtractor` and command `php artisan media:extract-metadata`.
  - Executed command backfilling all 2,206 cataloged media assets.
  - *Done when:* Assets on disk have extracted capture dates, dimensions, and color spaces.

- [x] 3. **Controller Pagination, Sorting & Upload Integration**
  - Updated `MediaLibraryController` to support bounded `per_page` pagination (50–150), expanded sorting, and pre-optimization EXIF extraction in `upload()`.
  - *Done when:* Uploaded photos preserve camera capture dates and custom per-page values apply cleanly.

- [x] 4. **Central Media Hub UI & Photo Inspector Modal**
  - Updated `resources/views/admin/media/index.blade.php` with 4-level zoom controls, per-page selector dropdown, metadata card badges, and interactive Deep Metadata Inspector modal.
  - *Done when:* Admin can toggle zoom, change batch page size, view card metadata chips, and open full photo inspection details.

- [x] 5. **Comprehensive Automated Tests & Code Style**
  - Created `tests/Feature/Admin/CentralMediaHubDeepMetadataTest.php`.
  - Ran full test suite, Pint, and Vite build.
  - *Done when:* 405 tests pass, 0 fail, Pint passes, and Vite builds cleanly.

- [x] 6. **cPanel Manifest & Run State**
  - Updated `cpanel_changed_files.txt`.
  - Finished run-state as ready for `/complete`.
  - *Done when:* Ready to squash-merge and land.

## Files / areas

- `database/migrations/2026_10_30_140000_add_taken_at_and_color_space_to_media_items_table.php`
- `app/Services/MediaMetadataExtractor.php`
- `app/Console/Commands/ExtractMediaMetadataCommand.php`
- `app/Models/MediaItem.php`
- `app/Http/Controllers/Admin/MediaLibraryController.php`
- `resources/views/admin/media/index.blade.php`
- `tests/Feature/Admin/CentralMediaHubDeepMetadataTest.php`
- `cpanel_changed_files.txt`

## Verification Evidence

- **Feature Tests**: `php artisan test --filter=CentralMediaHubDeepMetadataTest` (9 tests, 53 assertions passed).
- **All Media Tests**: `php artisan test --filter=Media` (47 tests, 483 assertions passed).
- **Full PHP Test Suite**: `php artisan test` (405 tests, 2,628 assertions passed).
- **Code Style**: `php vendor/bin/pint --test` passed with 0 violations.
- **Asset Compilation**: `npm run build` compiled clean in 6.43s (`public/build/`).
- **Live HTTP Verification**: `curl.exe` returned HTTP 302 redirect to admin login for unauthenticated requests.


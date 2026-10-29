# Feature: Ijero Tech Day brand correction, Media visibility controls & universal picker overhaul

**From build-plan:** feature 36
**Build attempt:** 1
**Branch:** feature/ijero-tech-day-brand-correction-media-visibility-controls-universal-picker-overhaul
**Status:** verified

## Goal

Correct all occurrences of "Innovation and Tech Day" / "Innovation & Tech Day" across views, seeders, and routes to strictly "Ijero Tech Day" (ITD); implement public gallery visibility controls (`is_public`) so foundation staff can choose which photos are displayed on the public website; add clear status indicators ("Displayed in Gallery", "Used on Website", "Archive Only") across the Central Media Hub; and overhaul the universal media picker (`<x-admin.media-picker>`) to remove the arbitrary 80-item limit and allow browsing all 2,200+ photos across all years and collections.

## Design reference

- Admin Media Library (`/admin/media`): Existing responsive grid cards, enriched with usage badges (Emerald "Public Gallery", Indigo "Used on Site", Stone "Archive") and one-click visibility toggle.
- Universal Media Picker Modal (`<x-admin.media-picker>`): Existing Alpine-powered modal with search, year chips, category, and date filters, enriched with total count feedback, usage badges, and a "Load More Photos" / paginated stream that allows browsing through the entire media library.

## In scope

1. **Brand Naming Correction:**
   - Search and replace all instances of "Innovation and Tech Day" and "Innovation & Tech Day" with "Ijero Tech Day" (or ITD) across Blade templates (`components/footer.blade.php`, `components/layout.blade.php`, `components/schema-organization.blade.php`, `gallery/index.blade.php`, `admin/gallery/create.blade.php`), `GeminiService.php`, `ProgrammeController.php`, and database seeders (`ProgrammeSeeder.php`, `GallerySeeder.php`).
   - Update database record for programme id 2 to title "Ijero Tech Day (ITD)".
2. **Media Visibility Architecture & Curation:**
   - Database migration adding `is_public` (boolean, default true, indexed) to `gallery_photos` and `media_items`.
   - Curate existing dataset: keep flagship album photos (`itd-2026`, `itd-2025`, `itd-2024`, `famoyinka-library`, `essay-competition`, `girls-day`, `reading-competition-2026`, `wdada-community-outreach`, plus curated highlights of `reading-competition` and `spelling-bee`) as `is_public = true`, while marking uncurated bulk imports in the 1,448-photo `general` album as `is_public = false` (archive).
   - Update `GalleryController::index` and `GalleryController::album` to strictly query `where('is_public', true)`.
3. **Media Usage & Display Indicators:**
   - Implement usage detection helper/accessor returning whether a photo is:
     - `Displayed in Gallery`: linked to a public gallery album and `is_public = true`.
     - `Used on Website`: `file_path` actively referenced in `page_sections.image_path`, `programmes.cover_image`, `events.banner_image`, `event_sponsors.logo_path`, `team_members.photo`, `partners.logo`, `gallery_albums.cover_image`, or `settings.value`.
     - `Archive Only`: neither displayed in public gallery nor referenced on site.
   - Display distinct visual indicator badges on Media Hub cards (`admin/media/index.blade.php`) and in the media picker modal.
4. **Media Hub Visibility Controls & Bulk Actions:**
   - Add single-click visibility toggle action (`POST /admin/media/{id}/toggle-visibility`) to quickly show/hide photos from the public gallery.
   - Add bulk actions in `/admin/media`: "Show in Gallery" and "Hide from Gallery (Archive)".
   - Add visibility filter in Media Hub index: All / Public in Gallery / Archive Only.
5. **Universal Media Picker Overhaul:**
   - Update `MediaLibraryController::pickerItems` to remove the hardcoded `limit(80)`, accept `page` and `per_page`, and return pagination metadata (`total`, `current_page`, `last_page`, `has_more`).
   - Update `<x-admin.media-picker>` Alpine component to support progressive loading / "Load More" button, displaying total count (e.g. "Showing 60 of 2,207 photos") so staff can pick any photo in the entire foundation library when "All Years", "All Categories", and "All Dates" are selected.

## Out of scope

- Front-end gallery anti-flash visual refactoring with `x-cloak` and skeleton aspect-ratios (deferred to Feature 37).
- Tech Day event show page UI overhaul, dynamic past/upcoming status from `event_date`, and rich SEO schema (deferred to Feature 37).
- Redesign of `/donate` (removing Flutterwave) and `/get-involved` centered layout (deferred to Feature 38).

## Build loop

- `workflow.stepReview: "feature"`: Spec review before implementation; one review packet after implementation steps before completion.
- `workflow.checkpointCommits: "disabled"`: No intermediate checkpoint commits during build steps.

## Build steps

- [x] **Step 1: Brand Naming Correction Across All Files & Database**
   - Update views: `components/footer.blade.php`, `components/layout.blade.php`, `components/schema-organization.blade.php`, `gallery/index.blade.php`, `admin/gallery/create.blade.php`.
   - Update services & controllers: `app/Services/GeminiService.php`, `app/Http/Controllers/ProgrammeController.php`.
   - Update seeders: `database/seeders/ProgrammeSeeder.php`, `database/seeders/GallerySeeder.php`.
   - Update existing database record in `programmes` table (id 2).
   - Done when `git grep -i "innovation and tech day"` returns zero occurrences in views, controllers, seeders, and services; and `php artisan test` passes.

- [x] **Step 2: Database Migration for Media Visibility (`is_public`) & Photo Curation**
   - Create migration adding `is_public` (boolean, default true) to `gallery_photos` and `media_items` with indexes.
   - Run migration.
   - Curate existing photos: set `is_public = false` for raw bulk archive photos in `general` while preserving curated highlights across all albums.
   - Update `GalleryPhoto` and `MediaItem` Eloquent models with `is_public` in `$fillable`, `$casts`, and local scopes (`scopePublic`, `scopeArchive`).
   - Update `GalleryController::index` and `GalleryController::album` to filter photos with `where('is_public', true)`.
   - Done when uncurated bulk photos have `is_public = false`, curated photos have `is_public = true`, and public `/gallery` only renders public photos.

- [x] **Step 3: Media Usage Detection & Indicator Badges in Central Media Hub**
   - Implement efficient site-wide usage detection in `MediaLibraryController` and `MediaItem` helper (`is_used_on_site`, `is_displayed_in_gallery`).
   - Update `resources/views/admin/media/index.blade.php` to render indicator badges:
     - Emerald: "Public Gallery"
     - Indigo: "Used on Site"
     - Stone: "Archive"
   - Add single-click visibility toggle endpoint `POST /admin/media/{id}/toggle-visibility`.
   - Add bulk visibility actions in the selection bar ("Make Public in Gallery" / "Move to Archive").
   - Add visibility filter (`visibility=all|public|archive`) to Media Hub filter bar.
   - Done when Media Hub displays accurate usage badges on every card, filtering by visibility works, and toggling visibility updates the photo's public status immediately.

- [x] **Step 4: Universal Media Picker Overhaul (Browse All 2,200+ Photos)**
   - Update `MediaLibraryController::pickerItems` to support pagination (`page`, `per_page`), returning `items`, `total`, `current_page`, `last_page`, and `has_more`.
   - Update `resources/views/components/admin/media-picker.blade.php` Alpine component:
     - Track `page`, `hasMore`, `totalCount`.
     - Implement "Load More Photos" button and progressive append so admins can view all photos in the media library without limits.
     - Display total counter ("Showing X of Y photos in library").
     - Include usage indicators and badges on picker thumbnails.
   - Done when opening `<x-admin.media-picker>` with "All Years", "All Categories", and "All Dates" shows total library count and allows loading/browsing all photos across the entire library.

- [x] **Step 5: Automated Verification & Regression Testing**
   - Add dedicated test `tests/Feature/MediaVisibilityAndPickerOverhaulTest.php` covering:
     - Brand correction assertions across public views and footer.
     - `is_public` visibility toggle endpoint (individual and bulk).
     - Public gallery strictly omitting `is_public = false` photos.
     - `pickerItems` JSON API returning paginated items without the 80-item cutoff, and respecting search, year, category, and date filters.
     - Usage indicator detection for site-referenced assets vs gallery photos vs archive photos.
   - Run `php vendor/bin/pint --test` and `php artisan test`.
   - Done when Pint passes with zero violations and full test suite passes with 0 failures.

## Files / areas

- `resources/views/components/footer.blade.php`
- `resources/views/components/layout.blade.php`
- `resources/views/components/schema-organization.blade.php`
- `resources/views/gallery/index.blade.php`
- `resources/views/admin/gallery/create.blade.php`
- `resources/views/admin/media/index.blade.php`
- `resources/views/components/admin/media-picker.blade.php`
- `app/Services/GeminiService.php`
- `app/Http/Controllers/ProgrammeController.php`
- `app/Http/Controllers/GalleryController.php`
- `app/Http/Controllers/Admin/MediaLibraryController.php`
- `app/Models/GalleryPhoto.php`
- `app/Models/MediaItem.php`
- `database/migrations/xxxx_xx_xx_add_is_public_to_gallery_photos_and_media_items.php`
- `database/seeders/ProgrammeSeeder.php`
- `database/seeders/GallerySeeder.php`
- `tests/Feature/MediaVisibilityAndPickerOverhaulTest.php`

## Data / contracts

- `gallery_photos`: add `is_public` boolean column (default `true`, index).
- `media_items`: add `is_public` boolean column (default `true`, index).
- `POST /admin/media/{id}/toggle-visibility`:
  - Request: empty or JSON `{ "is_public": boolean }`
  - Response: JSON `{ "success": true, "is_public": boolean, "message": string }` or back redirect with flash.
- `POST /admin/media/bulk-visibility`:
  - Request: `{ "ids": int[], "is_public": boolean }`
  - Response: JSON `{ "success": true, "count": int, "message": string }` or back redirect with flash.
- `GET /admin/media/picker-items`:
  - Query params: `search`, `category`, `date_filter`, `year`, `sort`, `page`, `per_page`
  - Response JSON:
    ```json
    {
      "items": [
        {
          "id": 1,
          "filename": "...",
          "file_path": "...",
          "image_url": "...",
          "title": "...",
          "formatted_size": "...",
          "dimensions": "...",
          "year": 2026,
          "is_public": true,
          "is_used_on_site": true,
          "usage_badge": "Public Gallery"
        }
      ],
      "total": 2207,
      "current_page": 1,
      "last_page": 37,
      "has_more": true,
      "collections": [...],
      "years": [2026, 2025, 2024]
    }
    ```

## Testing

- Logic & Integration tests (`php artisan test`):
  - Brand correction: verify "Ijero Tech Day" rendered in footer, layout keywords, schema, and programme redirect.
  - Media visibility: test creating photos with `is_public` true/false, verify public gallery only displays `is_public = true` photos.
  - Visibility toggles: test single-click and bulk toggle endpoints.
  - Universal media picker: test `pickerItems` endpoint returns paginated results, supports filtering by year/category/date, and provides pagination metadata (`total`, `has_more`).
  - Code style: `php vendor/bin/pint --test`.

## Notes for the AI

- Maintain proportional engineering and preserve all existing comments and docstrings.
- Adhere strictly to the $\le 180\text{ KB}$ WebP ceiling for all media.
- No AI attribution in git commits per `AGENTS.md`.
- In PowerShell, do not use `php -r` with `$` or `\` without proper escaping; prefer scratch scripts or `artisan tinker --execute`.
- Log all changed and created files to `cpanel_changed_files.txt`.


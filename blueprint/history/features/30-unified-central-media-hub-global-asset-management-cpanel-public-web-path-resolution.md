# Feature: Unified Central Media Hub, global asset management & cPanel public web path resolution

**From build-plan:** feature 30
**Build attempt:** 1
**Status:** verified
**Branch:** feature/unified-central-media-hub-global-asset-management-cpanel-public-web-path-resolution

## Goal

Empower foundation staff with a dedicated, top-level **Media Library** (`/admin/media`) to browse, search, and manage all optimized foundation photos in one unified place (both gallery photos and site/page assets); enable real-time search by photo name/filename/caption, date uploaded filters (date picker & quick presets), and sorting; provide a drag-and-drop uploader strictly producing $\le 180\text{ KB}$ WebP images; upgrade the universal media picker modal `<x-admin.media-picker>` across all CMS editors; and permanently fix the cPanel upload path mismatch by binding `$app->usePublicPath(__DIR__)` in `public/index.php`.

## In scope

1. **cPanel Public Web Path Fix**:
   - In `public/index.php`, bind `$app->usePublicPath(__DIR__)` so Laravel's `public_path()` always matches the web root where `index.php` is executed.
   - Harden `ImageOptimizerService` to verify destination directories (`uploads/media`, `uploads/cms`, etc.) exist with `0755` permissions and ensure uploaded photos are saved directly into the active web directory so URLs serve with HTTP 200 without 404 path mismatches.
2. **Database Migration & `MediaItem` Model**:
   - Table `media_items`: `id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type` (`general`, `gallery`, `cms`, `partner`, `programme`), `gallery_photo_id` (nullable FK), `timestamps`.
   - Model `App\Models\MediaItem` with scopes: `search($term)`, `byDate($date)`, `byDateRange($start, $end)`, `bySourceType($type)`, `ordered($sort)`.
   - Accessors: `image_url` (`asset($this->file_path)`), `formatted_size` (e.g. `142 KB`), `dimensions` (e.g. `1600x1066`).
3. **Data Backfill / Migration Seeder**:
   - Seed all existing gallery photos (`GalleryPhoto`) and existing public uploaded images into `MediaItem` so the library immediately populates with the foundation's complete visual library.
4. **Top-Level Navigation Item**:
   - Add **"Media Library"** to the top administrative navbar in `resources/views/admin/layout.blade.php` alongside Pages CMS, Programmes, Events, and Gallery.
5. **Controller & Endpoints (`Admin\MediaLibraryController`)**:
   - `GET /admin/media` -> `index`: visual grid with media metrics, live search by photo name, date uploaded filters (date picker & quick ranges), sort controls (newest, oldest, A-Z), category tabs (*All Media*, *Gallery Collections*, *Site Assets*), and pagination.
   - `POST /admin/media/upload` -> `upload`: handles drag-and-drop or file selector uploads, optimizes to $\le 180\text{ KB}$ WebP, stores `MediaItem` records, returns JSON.
   - `PUT /admin/media/{mediaItem}` -> `update`: edit title, caption, alt text.
   - `DELETE /admin/media/{mediaItem}` -> `destroy`: delete file and record with confirmation.
   - `POST /admin/media/bulk-delete` -> `bulkDestroy`: batch deletion of selected items.
   - `GET /admin/media/picker-items` -> `pickerItems`: JSON API powering modal pickers with search and date filters.
6. **Central Media Hub View (`resources/views/admin/media/index.blade.php`)**:
   - Media metrics banner (total assets, total storage used, WebP optimization rate).
   - Top search bar with live debounce, date uploaded selector, and category tabs.
   - Drag-and-drop upload dropzone with real-time compression progress indicator.
   - Asset grid cards with zoom preview, file size tag, upload date badge, copyable public URL button, and deletion action.
7. **Modal Picker Upgrade (`<x-admin.media-picker>`)**:
   - Update component to query the unified `MediaItem` catalog with search by photo name, date filters, and category tabs.
8. **Deployment Manifest & Automated Verification**:
   - Update `cpanel_changed_files.txt`.
   - Write comprehensive Feature tests (`tests/Feature/Admin/MediaLibraryTest.php`).

## Out of scope

- Public user photo uploads (restricted strictly to authenticated administrative staff).
- Non-image document hosting (PDF annual reports are deferred to a separate milestone).
- Video or audio file hosting.

## Build loop

- `workflow.stepReview`: `"feature"` (single comprehensive review packet after all steps).
- `workflow.checkpointCommits`: `"disabled"` (working branch commits kept clean until completion).

## Build steps

- [x] **Step 1: Fix cPanel public web path & harden directory resolution**
  - Bind `$app->usePublicPath(__DIR__)` in `public/index.php`.
  - Harden `app/Services/ImageOptimizerService.php` to ensure web permissions (`0755`) and log clear diagnostics on GD/WebP processing.
  - *Done when:* `public_path()` dynamically resolves to `__DIR__`, and test uploads write directly into `uploads/` without permission or path errors.

- [x] **Step 2: Database migration, `MediaItem` model & historical backfill**
  - Create migration for `media_items` table.
  - Create `App\Models\MediaItem` with query scopes for search (photo name/filename/caption), date filtering, category filtering, and sorting.
  - Create command or seeder (`MediaItemSeeder`) that registers existing gallery photos and public upload assets into `media_items`.
  - Run migration and seeder.
  - *Done when:* `media_items` table exists, queries for search and date filtering return correct records, and existing gallery photos are populated.

- [x] **Step 3: Build `MediaLibraryController` & central media routes**
  - Define routes in `routes/web.php` under the `admin` middleware group.
  - Implement `MediaLibraryController` methods: `index`, `upload`, `update`, `destroy`, `bulkDestroy`, and `pickerItems`.
  - Validate uploads strictly with `ImageOptimizerService` enforcing $\le 180\text{ KB}$ WebP format and original cleanup.
  - *Done when:* All endpoints respond with correct HTTP status codes, JSON responses, and authorized role gates (`super_admin` and `editor`).

- [x] **Step 4: Create the `/admin/media` Central Media Hub view & admin navbar integration**
  - Add "Media Library" tab to `resources/views/admin/layout.blade.php` and `resources/views/components/admin-layout.blade.php`.
  - Build `resources/views/admin/media/index.blade.php` with Alpine.js reactivity:
    - Search input (by photo name, filename, caption).
    - Date uploaded filter (date picker + presets: All Time, Today, This Week, This Month).
    - Category switcher: All Media, Gallery Collections, Site Assets.
    - Drag-and-drop file upload zone.
    - Grid cards with WebP size badges, copyable public URLs, and edit/delete actions.
  - *Done when:* `/admin/media` renders cleanly, drag-and-drop uploads immediately appear in the grid as WebP, search and date filtering filter cards instantly, and copy URL copies to clipboard.

- [x] **Step 5: Upgrade `<x-admin.media-picker>` with unified search & date filters**
  - Update `resources/views/components/admin/media-picker.blade.php` to fetch from `/admin/media/picker-items`.
  - Enable search by photo name and date uploaded within the modal picker.
  - Keep direct "Upload & Optimize" tab synchronized with `MediaItem` storage.
  - *Done when:* Any modal picker opened in Pages CMS, Programmes, Events, Partners, or Team can search across all foundation media by name and date and select with 1 click.

- [x] **Step 6: Automated test suite, deployment manifest & Pint verification**
  - Create `tests/Feature/Admin/MediaLibraryTest.php` covering index view, search by name, date filter, WebP upload, metadata edit, single/bulk delete, and authorization.
  - Run full test suite (`php artisan test`) and Pint code style check (`php vendor/bin/pint --test`).
  - Update `cpanel_changed_files.txt` with all modified and new files.
  - *Done when:* All tests pass with zero regressions, Pint passes cleanly, and deployment manifest is up to date.

## Files / areas

- `public/index.php` (bind `usePublicPath`)
- `app/Services/ImageOptimizerService.php` (path and permissions hardening)
- `database/migrations/2026_10_14_000001_create_media_items_table.php` (schema)
- `app/Models/MediaItem.php` (model, scopes, accessors)
- `database/seeders/MediaItemSeeder.php` (backfill existing media)
- `database/seeders/DatabaseSeeder.php` (seed call)
- `app/Http/Controllers/Admin/MediaLibraryController.php` (new controller)
- `routes/web.php` (routes for central media library)
- `resources/views/admin/layout.blade.php` (navbar link)
- `resources/views/components/admin-layout.blade.php` (navbar link)
- `resources/views/admin/media/index.blade.php` (new central hub view)
- `resources/views/components/admin/media-picker.blade.php` (upgraded picker)
- `tests/Feature/Admin/MediaLibraryTest.php` (feature test suite)
- `cpanel_changed_files.txt` (deployment tracking)

## Data / contracts

- `MediaItem` Schema:
  - `id`: unsignedBigInteger PK
  - `filename`: string (e.g. `children-library-reading.webp`)
  - `file_path`: string (e.g. `uploads/media/children-library-reading.webp`)
  - `file_size`: unsignedInteger (bytes, $\le 184,320$)
  - `mime_type`: string (default `image/webp`)
  - `width`: unsignedSmallInteger, nullable
  - `height`: unsignedSmallInteger, nullable
  - `title`: string, nullable
  - `caption`: text, nullable
  - `alt_text`: text, nullable
  - `source_type`: string in `['general', 'gallery', 'cms', 'partner', 'programme']`
  - `gallery_photo_id`: unsignedBigInteger, nullable FK references `gallery_photos(id)` on delete set null
  - `timestamps`
- Response contracts:
  - `GET /admin/media/picker-items`: `{ items: [{ id, filename, file_path, image_url, title, caption, alt_text, formatted_size, date_formatted, source_type }] }`
  - `POST /admin/media/upload`: `{ success: true, item: { id, filename, file_path, image_url, formatted_size }, message: string }`

## Testing

- `tests/Feature/Admin/MediaLibraryTest.php`:
  - Guests cannot access `/admin/media` (redirect to login).
  - Authenticated admin can view `/admin/media` with stats.
  - Search by photo name filters items accurately.
  - Date uploaded filter filters items by exact date and range.
  - Drag-and-drop upload converts image to WebP under 180 KB and stores `MediaItem`.
  - Admin can update media metadata (title, caption, alt text).
  - Admin can delete media item and associated physical file.
  - Modal picker endpoint returns formatted items with search and date filters.

## Notes for the AI

- Preserve all existing 267 tests and Ensure zero regressions on Gallery, Programmes, Events, and Pages CMS.
- Use native Alpine.js reactivity in the view without heavy frontend frameworks.
- Strictly adhere to WCAG contrast standards and Tailwind v4 CSS design tokens.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10802,"specSha256":"14e1c71ad5f9d080d3057868d0f73ac82bdf5424eeab2a848c333c57ad471d80","branch":"refs/heads/feature/unified-central-media-hub-global-asset-management-cpanel-public-web-path-resolution","head":"8b36ecc6efcdf6edf9f353b52552d59a9db5ca1c","baseRef":"refs/heads/main","baseCommit":"8b36ecc6efcdf6edf9f353b52552d59a9db5ca1c","sourceTree":"35908f80538462dfb6061728a875d617d7dcf51f","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Central Media Library Hub (`/admin/media`)**:
   - Log in as admin at `http://localhost:8000/admin/login`.
   - Click the "Media Library" tab in the top navbar or desktop sidebar.
   - Verify metrics cards: Total Cataloged, Storage Footprint, WebP Efficiency %, and Standard Upload Cap (<= 180 KB).

2. **Verify Multi-Parameter Search by Photo Name & Caption**:
   - Type in the search box (e.g. `reading`, `famoyinka`, `robotics`) and verify matching photo cards are filtered instantly.

3. **Verify Date Uploaded Filtering & Presets**:
   - Choose `Today`, `This Week`, `This Month`, or `Past Year` from the Date Uploaded dropdown.
   - Confirm only photos uploaded within the selected timeframe appear.

4. **Verify Category Switching**:
   - Switch category to `Gallery Collections`, `Site & CMS Assets`, or `Uploaded Media`.
   - Verify the grid updates accordingly.

5. **Verify Drag-and-Drop Uploader with <= 180 KB WebP Compression**:
   - Drag any JPEG or PNG photo onto the dropzone.
   - Verify real-time progress bar compresses the image to <= 180 KB WebP, deletes the original, and catalogs it into the library.

6. **Verify 1-Click Copy Public URL**:
   - Click "Copy URL" on any card, verify the "Copied!" button feedback, and open the URL in a browser tab to confirm HTTP 200.

7. **Verify Metadata Editing & Single/Bulk Deletion**:
   - Click the pencil edit icon to update title, caption, or alt text.
   - Select multiple photos with checkboxes and click "Delete Selected".

8. **Verify Upgraded Modal Picker (`<x-admin.media-picker>`)**:
   - Navigate to `/admin/pages/home` or `/admin/events/create`.
   - Click "Choose from Library" and test searching by photo name and filtering by date within the modal.

9. **Verify cPanel Web Path Resolution**:
   - Verify `public/index.php` binds `$app->usePublicPath(__DIR__)` ensuring all uploads write directly to the active web directory.

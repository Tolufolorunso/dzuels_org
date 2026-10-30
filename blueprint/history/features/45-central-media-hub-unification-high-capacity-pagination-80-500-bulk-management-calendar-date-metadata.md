# Feature 45: Central Media Hub unification, high-capacity pagination (80–500), bulk management & calendar date metadata

**From build-plan:** feature 45
**Build attempt:** 1
**Branch:** feature/central-media-hub-unification-high-capacity-pagination-80-500-bulk-management-calendar-date-metadata
**Status:** completed

## Goal

Unify the digital asset pipeline across the entire website around the Central Media Hub (`/admin/media`), upgrade grid pagination to high-capacity options (80, 150, 250, 500 photos per page, default 80), introduce full calendar capture date editing (`taken_at`: Day, Month, Year) with automatic year synchronization, and equip foundation staff with high-efficiency multi-select bulk operations (bulk assign to collections, bulk toggle public gallery visibility, bulk date/year corrections, and bulk delete).

## In scope

1. **Universal Upload Pipeline & Media Registration**:
   - Enforce the platform rule: every photo uploaded anywhere on the platform (including gallery album photos, page CMS images, and direct media library uploads) is automatically optimized to $\le 180\text{ KB}$ WebP and immediately cataloged as a `MediaItem` in `media_items`.
   - Update `GalleryAlbumController::uploadPhotos()` to register each uploaded photo in `media_items` (setting `gallery_album_id`, `gallery_photo_id`, file properties, and extracted EXIF `taken_at`), ensuring zero photo silos.

2. **High-Capacity Pagination (80–500 per page)**:
   - In `MediaLibraryController::index()`, expand allowed `per_page` query parameter options to `[80, 150, 250, 500]`, setting the new default to `80`.
   - Update the toolbar dropdown selector in `resources/views/admin/media/index.blade.php` to display `80 photos`, `150 photos`, `250 photos`, and `500 photos`, preserving active search, collection, year, and sort query parameters across page transitions.

3. **Full Calendar Date Metadata (`taken_at`: Day, Month, Year)**:
   - In `MediaLibraryController::update()`, add validation for `taken_at` (`nullable|date`), parse incoming date strings, and automatically synchronize the integer `year` column (`$year = $takenAt ? Carbon::parse($takenAt)->year : null`).
   - In `resources/views/admin/media/index.blade.php`, update the photo edit modal (`editingItem` in Alpine.js) to replace the isolated integer `year` input with an HTML5 `<input type="date">` for `taken_at` (`YYYY-MM-DD`), accompanied by a synced year display.
   - Update photo card metadata display: when `taken_at` exists, render a polished calendar capture badge (e.g. `📷 17 Oct 2025` or `17 Oct 2025`), falling back to the year badge when only `year` is recorded.

4. **Multi-Select Bulk Operations in Central Media Hub**:
   - In `resources/views/admin/media/index.blade.php`, add an Alpine.js selection system:
     - Checkbox on every photo card and a master "Select All on Page" checkbox on the toolbar.
     - Shift-click or range selection support for fast multi-photo selection.
     - Sticky floating bottom batch action bar appearing when 1 or more photos are selected (`selectedIds.length > 0`), displaying selected count, "Clear Selection" button, and bulk actions:
       1. **Bulk Assign to Collection / Album:** Dropdown/modal to assign all selected media items to a chosen `GalleryAlbum` (or clear assignment).
       2. **Bulk Toggle Public Gallery:** 1-click set `is_public = true` or `is_public = false` for all selected items.
       3. **Bulk Date & Year Correction:** Modal with a calendar date picker (`taken_at`) or year input to correct dates across selected photos in one action (e.g. correcting 2024 misattributions to 2025).
       4. **Bulk Delete:** Confirmation modal to securely uncatalog and delete selected media items.
   - In `MediaLibraryController`, implement secure bulk endpoint `POST /admin/media/bulk-action` handling `assign_album`, `set_visibility`, `update_date`, and `delete` with proper authorization checks, CSRF protection, and informative flash feedback.

## Out of scope

- Multi-photo gallery attachments on Event and Programme edit forms, and public event/programme lightbox grids (Feature 46).
- Homepage flagship event dynamic status updates and site-wide copy purge of "equips public school computer labs" (Feature 47).
- Changing public `/gallery` layout or masonry rendering algorithms.
- Third-party cloud storage or AWS S3 adapters (stays on local cPanel web path).

## Build loop

- Step review cadence: `feature` (one consolidated review packet at the end of the feature).
- Checkpoint commits: `disabled` (final feature commit upon completion).
- Follow all standards in `blueprint/context/coding-standards.md` and Laravel best practices.

## Build steps

1. [x] **Step 1: Universal upload pipeline & MediaItem catalog synchronization**
   - Update `GalleryAlbumController::uploadPhotos()` to ensure all uploaded photos are immediately registered as `MediaItem` records, populating `gallery_album_id`, `gallery_photo_id`, file metadata, and extracted EXIF `taken_at`.
   - Update `ImageOptimizerService` and `MediaMetadataExtractor` interactions to guarantee capture dates are preserved before GD conversion.
   - *Done when:* Uploading photos via gallery album management creates corresponding `MediaItem` records in `media_items` with extracted timestamps and album linkage.

2. [x] **Step 2: High-capacity pagination & backend calendar date handling**
   - In `MediaLibraryController::index()`, expand supported `per_page` values to `[80, 150, 250, 500]` with `80` as the default fallback.
   - In `MediaLibraryController::update()`, accept and validate `taken_at` (`nullable|date`), updating `taken_at` and automatically computing `year = Carbon::parse($takenAt)->year`.
   - Implement `POST /admin/media/bulk-action` in `MediaLibraryController` and `routes/web.php` supporting `assign_album`, `set_visibility`, `update_date`, and `delete` actions across an array of `media_ids`.
   - *Done when:* `MediaLibraryController` validates and handles 80–500 per page pagination, updates full calendar dates with synchronized year, and executes bulk actions with test assertions.

3. [x] **Step 3: Central Media Hub UI overhaul (Toolbar, Date Editing & Multi-Select Batch Bar)**
   - In `resources/views/admin/media/index.blade.php`:
     - Update the per-page selector dropdown on the toolbar to show `80`, `150`, `250`, and `500` options.
     - Replace the single-edit modal's `year` text box with a full `<input type="date">` for `taken_at`, syncing the year and calendar date seamlessly.
     - Add card-level selection checkboxes and a "Select All on Page" master checkbox.
     - Add the floating sticky batch action bar with modals for Bulk Assign Album, Bulk Visibility, Bulk Date Correction, and Bulk Delete.
     - Upgrade card metadata badges to display formatted calendar capture dates (e.g. `📷 17 Oct 2025`).
   - *Done when:* An admin can browse 80+ photos per page, edit exact calendar capture dates (day, month, year), select multiple photos, and perform bulk assignment, visibility, date correction, or deletion directly in the browser.

4. [x] **Step 4: Automated Feature Test Suite & Verification**
   - Create `tests/Feature/Admin/CentralMediaHubBulkAndDateManagementTest.php` testing:
     - Default pagination is 80, and `per_page=150|250|500` are correctly bounded and rendered.
     - Photo update with full calendar date updates `taken_at` and synchronizes `year`.
     - Bulk assignment to an album updates all selected items.
     - Bulk visibility toggle sets `is_public` for selected items.
     - Bulk date correction updates `taken_at` and `year` across all selected items.
     - Bulk deletion removes selected media items and unlinks them.
     - Gallery album upload creates unified `MediaItem` records.
   - Run full project test suite (`php artisan test`) and code linting (`php vendor/bin/pint --test`).
   - *Done when:* All new and existing tests pass with 0 errors and lint checks pass cleanly.

## Files / areas

- `app/Http/Controllers/Admin/MediaLibraryController.php` - Pagination options (80–500), `taken_at` validation/sync, and `bulkAction()` endpoint.
- `app/Http/Controllers/Admin/GalleryAlbumController.php` - Sync gallery photo uploads into `MediaItem`.
- `app/Models/MediaItem.php` - Relationships, date formatters, and fillable attributes.
- `resources/views/admin/media/index.blade.php` - Per-page dropdown, calendar date picker in edit modal, selection checkboxes, and sticky batch action toolbar.
- `routes/web.php` - Route definition for `POST /admin/media/bulk-action`.
- `tests/Feature/Admin/CentralMediaHubBulkAndDateManagementTest.php` - Comprehensive test coverage for pagination, date management, bulk operations, and upload synchronization.

## Data / contracts

- `POST /admin/media/bulk-action`:
  - `action`: `string` required in `['assign_album', 'set_visibility', 'update_date', 'delete']`
  - `media_ids`: `array` required, each integer existing in `media_items,id`
  - `gallery_album_id`: `nullable|exists:gallery_albums,id` (for `assign_album`)
  - `is_public`: `boolean` (for `set_visibility`)
  - `taken_at`: `nullable|date` (for `update_date`)
  - `year`: `nullable|integer|min:2000|max:2035` (for `update_date`)
  - Response: JSON `{ success: true, message: string, affected: int }` or HTTP redirect with flash session.

## Testing

- Unit/Feature testing: `php artisan test tests/Feature/Admin/CentralMediaHubBulkAndDateManagementTest.php`
- Full test suite: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Preserve native PHP GD $\le 180\text{ KB}$ WebP optimization ceiling and EXIF extraction before GD conversion.
- Do not introduce external npm or composer packages; leverage Alpine.js already installed in `resources/views/admin/layout.blade.php`.
- Ensure bulk delete only removes physical files residing under `uploads/` to avoid touching static frontend assets in `public/front/`.


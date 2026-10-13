# Feature: Automated image optimization, global media picker and gallery collection/photo management

**From build-plan:** feature 22
**Build attempt:** 1

**Branch:** feature/automated-image-optimization-global-media-picker-and-gallery-collection-photo-management

## Goal

Provide foundation staff with complete media autonomy by:
1. Automatically optimizing every image uploaded through the admin dashboard into modern WebP format via native PHP GD (100% PHP 8.3 & 8.4 compatible) and removing uncompressed raw uploads.
2. Building a reusable global Media Library picker modal allowing staff to either upload fresh images or select existing gallery photos across dashboard forms.
3. Overhauling gallery collection management with full CRUD and reordering capabilities.
4. Enabling single/bulk photo deletion, photo metadata editing, and enforcing standardized year labels (e.g., "Spelling Bee 2024", "ITD 2024") across all public gallery photos.

## In scope

- **Database Migration & Model Updates:**
  - Add `year` column (`string('year', 10)->nullable()`) to `gallery_photos` table.
  - Update `GalleryPhoto` model fillable, casts, and display helpers.
- **Native PHP GD Image Optimization Service:**
  - `App\Services\ImageOptimizerService` to handle image optimization for all uploaded files.
  - Automatic resizing (aspect ratio preserved, default max width 1920px), conversion to WebP format (`imagewebp`), saving optimized WebP, and deleting unoptimized original files.
  - Zero external package dependencies, using PHP standard library GD extension compatible with PHP 8.3 and PHP 8.4 on cPanel.
- **Gallery Photo Management in Admin:**
  - Update `admin/gallery/photos.blade.php` and `AdminGalleryAlbumController`.
  - Photo metadata edit modal/form (caption, alt text, year, sort order).
  - Single photo deletion and bulk photo deletion (select multiple photos with checkboxes, confirm, and delete selected photos and disk files).
  - Photo upload handling updated to optimize images and capture the event year.
- **Gallery Collections CRUD & Reordering:**
  - Update `admin/gallery/index.blade.php` and `AdminGalleryAlbumController`.
  - Ability to create, edit, delete, and re-order albums/collections (AJAX or form-based `sort_order` updates).
  - Safeguards on album deletion (handling associated photos).
- **Public Gallery Year Label Standardization:**
  - Update `resources/views/gallery/index.blade.php` to display explicit year labels for all photos (e.g., "Spelling Bee 2024", "ITD 2024", "Reading Competition 2024", "Famoyinka Library 2025") in the masonry grid and lightbox.
  - Dynamic filter chips reflecting user-managed album collections.
- **Global Media Library Picker Component:**
  - Create `<x-admin.media-picker>` Blade/Alpine component with thumbnail preview, "Upload Image" and "Choose from Gallery" modal.
  - Admin endpoint `GET /admin/media/photos` returning JSON list of gallery photos (URL, thumbnail, caption, year, album name) with search/album filter.
  - Integrate media picker into existing admin forms (e.g., events and pages) to verify reuse of gallery photos.
- **Deployment Manifest:**
  - Log all modified and created files in `cpanel_changed_files.txt`.

## Out of scope

- Famoyinka Memorial Children's Library dedicated programme page and photo seeding (reserved for Feature 23).
- Pruning and re-seeding historical gallery photos with new activity collections (reserved for Feature 23).
- Multi-phone site settings and contact page CMS overhaul (reserved for Feature 24).
- Third-party cloud storage or CDN configuration (S3, Cloudinary).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all small implementation steps are completed).
- `workflow.checkpointCommits: "disabled"`.
- Run tests (`php artisan test`) and lint checks (`php vendor/bin/pint --test`) after implementation.

**Status:** verified

## Build steps

- [x] 1. **Database schema and model updates** - create migration adding `year` column to `gallery_photos` table; update `GalleryPhoto` model `$fillable` and helper methods; update `GalleryPhotoFactory` and existing seeders if applicable.
  *Done when:* `php artisan migrate` runs cleanly, and `GalleryPhoto::create(['gallery_album_id' => $album->id, 'image_path' => 'test.webp', 'year' => '2024'])` persists and retrieves the year attribute.

- [x] 2. **Native PHP GD Image Optimizer Service** - create `App\Services\ImageOptimizerService` that accepts an uploaded file or file path, checks MIME type, resizes if exceeding dimensions, converts to WebP with configurable quality (default 82), saves to destination, and deletes the temporary or uncompressed original file.
  *Done when:* A unit/feature test verifies that uploading a JPEG or PNG produces an optimized `.webp` file on disk while the original file is removed.

- [x] 3. **Admin gallery photo editing, single deletion and bulk deletion** - add routes and controller methods in `AdminGalleryAlbumController` for updating photo metadata (caption, year, alt_text) and bulk-deleting photos by an array of IDs; update `admin/gallery/photos.blade.php` with bulk select checkboxes, a bulk delete button with confirmation modal, and an edit photo modal.
  *Done when:* Staff can select 3 photos in an album and bulk-delete them in one action, and staff can edit a photo's caption and year directly in the table.

- [x] 4. **Gallery collections CRUD and sort reordering** - enhance `AdminGalleryAlbumController` with an endpoint/action to update album display ordering; ensure album creation, editing, toggling, and deletion work smoothly in `admin/gallery/index.blade.php` with responsive cards/table and sorting controls.
  *Done when:* Staff can create a new collection (e.g. "Community Outreach"), reorder its position, and see the updated order persisted in the database.

- [x] 5. **Standardized event and year labeling on public gallery** - update `resources/views/gallery/index.blade.php` so that both the grid card overlays and the full-screen lightbox prominently render the standardized label and year for every photo (e.g. "Spelling Bee 2024", "ITD 2024", "Reading Competition 2024"), adapting dynamically to album collections.
  *Done when:* Browsing `/gallery` displays explicit years on all photos across all collections, and filtering by dynamically managed albums works without error.

- [x] 6. **Global Media Library picker modal component** - create `GET /admin/media/photos` returning JSON collection of available photos; create reusable Blade/Alpine component `<x-admin.media-picker>` that provides thumbnail preview, direct file upload (optimized via `ImageOptimizerService`), and a "Select from Gallery" browser modal with search and album filtering; wire it into at least event and page section admin forms.
  *Done when:* Editing an event banner or page section image in `/admin` allows selecting an existing photo from the gallery or uploading a new image that immediately optimizes to WebP.

- [x] 7. **Automated tests and deployment file tracking** - write comprehensive feature tests covering image optimization, photo editing, bulk photo deletion, album reordering, and media picker JSON API; run `php artisan test` and `php vendor/bin/pint`; record all modified and created files into `cpanel_changed_files.txt`.
  *Done when:* All test assertions pass with zero failures, Pint reports no style violations, and `cpanel_changed_files.txt` lists all created/modified files.

## Files / areas

- `database/migrations/xxxx_xx_xx_xxxxxx_add_year_to_gallery_photos_table.php` - schema migration
- `app/Models/GalleryPhoto.php` - model fillable & attributes
- `app/Services/ImageOptimizerService.php` - native GD WebP optimization service
- `app/Http/Controllers/Admin/GalleryAlbumController.php` - album & photo management, bulk delete, reordering
- `app/Http/Controllers/Admin/MediaController.php` - media picker API endpoint
- `resources/views/admin/gallery/index.blade.php` - collections management & reordering UI
- `resources/views/admin/gallery/photos.blade.php` - photos list with bulk delete & metadata edit
- `resources/views/components/admin/media-picker.blade.php` - reusable media picker modal component
- `resources/views/gallery/index.blade.php` - public gallery view with standardized year labels
- `routes/web.php` - admin media and gallery routes
- `tests/Feature/Admin/GalleryManagementTest.php` - feature tests for bulk delete, photo edit, media picker
- `cpanel_changed_files.txt` - deployment file tracking

## Data / contracts

- `gallery_photos`:
  - `year`: nullable string(10), e.g. "2024", "2025", "2026".
- `ImageOptimizerService::optimize(UploadedFile $file, string $destinationDir, ?int $maxWidth = 1920, int $quality = 82): string`
  - Returns relative public path to optimized `.webp` file.
  - Automatically unlinks any temporary uncompressed upload.
- `AdminGalleryAlbumController::bulkDestroyPhotos(Request $request, GalleryAlbum $album)`:
  - Input: `photo_ids` (array of integers, required).
  - Safely deletes records and associated files on disk for photos belonging to the specified album.
- `MediaController::photos(Request $request)`:
  - Query params: `album_id` (optional), `search` (optional).
  - Returns JSON: `[{id, url, caption, year, album_title}]`.

## Testing

- Logic tests: `php artisan test --filter=GalleryManagementTest` and full suite `php artisan test`.
- Code styling: `php vendor/bin/pint --test`.

## Notes for the AI

- Use native PHP GD functions (`imagecreatefromjpeg`, `imagecreatefrompng`, `imagecreatefromwebp`, `imagecopyresampled`, `imagewebp`, `imagedestroy`) — do not add Intervention Image or other external packages.
- When unlinking deleted photos, only unlink files inside `uploads/` directory; never attempt to delete version-controlled assets in `front/images/` if referenced by legacy records.
- Preserve Alpine.js clean reactivity and ensure the media picker modal has proper keyboard accessibility (Escape to close) and focus trapping.
- Maintain documentation and add every touched/created file to `cpanel_changed_files.txt`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10093,"specSha256":"c060e57b844d46ff637a826586dd454756e369e9648a44c7e5fcf590e8bd322a","branch":"refs/heads/feature/automated-image-optimization-global-media-picker-and-gallery-collection-photo-management","head":"1da12e25e44734de59b5174e4eacba30351fb0ce","baseRef":"refs/heads/main","baseCommit":"1da12e25e44734de59b5174e4eacba30351fb0ce","sourceTree":"6df98ca06a928db8d68e38e3f4560b5054b67109","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Native GD WebP Image Optimization**:
   - Access the admin panel at `/admin` and log in with staff credentials.
   - Go to `/admin/gallery` and select any album.
   - Upload a JPEG or PNG photo.
   - Verify that the photo is uploaded, converted to `.webp`, and saved under `uploads/gallery/photos/`.
   - Verify that the original raw file is removed and no uncompressed duplicate remains.

2. **Admin Gallery Photo Management (CRUD, Bulk Delete, Year Tags)**:
   - Within the album photos view (`/admin/gallery/{id}/photos`), select multiple photos using the checkboxes.
   - Click the bulk delete button in the floating action bar and confirm deletion.
   - Verify that the selected photos are deleted from the database and only their corresponding `uploads/` files are unlinked.
   - Click **Edit** on any remaining photo to update its caption and year (e.g., "Spelling Bee 2024").
   - Confirm the updated caption and year persist immediately.

3. **Gallery Collections Management & Ordering**:
   - Go to `/admin/gallery`.
   - Click **+ New Collection** to create a new category (e.g., "Youth Mentorship").
   - Use the **Move Up** and **Move Down** buttons to re-order the collections.
   - Verify that the new collection and its reordered position persist.

4. **Public Gallery Year & Event Badges**:
   - Visit the public gallery at `/gallery`.
   - Observe the masonry grid: verify that photos display explicit standardized event and year badges (e.g. "Spelling Bee 2024", "ITD 2024").
   - Click any photo to open the lightbox modal: confirm the event title and year are prominently displayed.
   - Click on the filter chips to confirm filtering by dynamically managed collections works without error.

5. **Reusable Media Library Picker**:
   - Go to `/admin/events/create` or `/admin/events/{id}/edit`.
   - Observe the **Event Banner Image** picker component `<x-admin.media-picker>`.
   - Click **Choose from Gallery** to open the modal; search or filter by collection to select an existing photo.
   - Confirm the selected photo URL and preview update instantly.
   - Click **Upload New** in the modal to upload a banner image directly; confirm it is immediately optimized with GD to WebP and selected.
   - Go to `/admin/pages/home` and confirm the media picker operates identically on CMS page section images.

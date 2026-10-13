# Feature: Resilient image optimization pipeline, upload fallback and gallery bulk uploader

**From build-plan:** feature 25
**Build attempt:** 1
**Status:** verified

**Branch:** feature/resilient-image-optimization-pipeline-upload-fallback-and-gallery-bulk-uploader

## Goal

Resolve the image upload error (`Call to undefined function imagecreatefromjpeg()`) across the entire web application by hardening `ImageOptimizerService` with global namespace scoping, GD extension detection, and a bulletproof fallback to direct storage. Additionally, streamline the Gallery admin interface with resilient multi-photo bulk uploading and per-photo metadata editing (caption, year label, alt text, sort order).

## In scope

- **ImageOptimizerService Hardening (`app/Services/ImageOptimizerService.php`):**
  - Fully qualify all PHP GD functions to the global namespace (`\imagecreatefromjpeg`, `\imagecreatefrompng`, `\imagecreatefromwebp`, `\imagecreatefromgif`, `\imagecreatetruecolor`, `\imagecopyresampled`, `\imagewebp`, `\imagedestroy`, etc.).
  - Add explicit GD runtime checks: verify `\extension_loaded('gd')` and `\function_exists('imagecreatefromjpeg')` before attempting GD processing.
  - Implement a zero-crash, automatic fallback to direct safe file storage whenever GD is unavailable, unsupported, or encounters decoding/resampling errors.
  - Clean up GD image resources safely in a `finally` block or on error to prevent PHP memory leaks.
  - Support both `Illuminate\Http\UploadedFile` and absolute file path strings with proper destination directory creation (`public_path(...)`).
- **Gallery Multi-Photo Bulk Upload (`app/Http/Controllers/Admin/GalleryAlbumController.php`):**
  - Support batch uploading of multiple images simultaneously via `photos[]` in `uploadPhotos()`.
  - Process each uploaded file independently with try-catch isolation so a single corrupt file does not abort the entire batch.
  - Set default captions, album title alt text, and year labels while allowing immediate per-photo customization.
  - Provide accurate flash messaging reporting the exact number of photos successfully processed.
- **Per-Photo Metadata Management (`resources/views/admin/gallery/photos.blade.php`):**
  - Polish the Alpine.js photo edit modal and inline action triggers for instant caption, year label, alt text, and sort order editing.
  - Display clear badges for photo dimensions, file format (WebP or fallback), and year tag.
  - Enhance bulk selection actions (bulk delete, select all) to complement bulk upload.
- **Media Picker & Global Upload Consistency:**
  - Verify that `MediaController`, `PartnerController`, `EventController`, and `PageController` benefit immediately from the hardened optimizer without exceptions.
- **Automated Testing & Deployment Tracking:**
  - Feature tests in `tests/Feature/ImageOptimizerResilienceTest.php` testing both GD optimization and direct storage fallback.
  - Feature tests in `tests/Feature/GalleryBulkUploadTest.php` testing multi-photo batch upload and per-photo metadata updates.
  - Record modified files in `cpanel_changed_files.txt`.

## Out of scope

- Installing third-party server binaries (e.g. `ffmpeg`, `optipng`, `jpegoptim`).
- Video or audio file processing.
- Features 26 (Site logo, Givebutter U.S. donations, 6 causes CMS) and 27 (Final release suite).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all steps are complete).
- `workflow.checkpointCommits: "disabled"`.
- Run tests (`php artisan test`) and lint checks (`php vendor/bin/pint --test`) after implementation.

## Build steps

- [x] 1. **Harden ImageOptimizerService with Global Namespace Scoping & Direct Storage Fallback** - update `App\Services\ImageOptimizerService.php` to check `\extension_loaded('gd')`, qualify all GD functions to root namespace (`\imagecreatefrom...`), wrap GD decoding in try-catch, and automatically fall back to saving the original file directly with a safe unique filename if GD is missing or decoding fails.
  *Done when:* Uploading images succeeds 100% of the time, both when GD is enabled (converting to WebP) and when GD is unavailable or fails (saving direct fallback file without crashing).

- [x] 2. **Enhance GalleryAlbumController for Resilient Multi-Photo Bulk Upload** - update `App\Http\Controllers\Admin/GalleryAlbumController.php` to handle batch `photos[]` uploads with per-file error isolation, sort order sequencing, and flash status messaging.
  *Done when:* Selecting and submitting multiple photos in `/admin/gallery/albums/{album}/photos` successfully uploads and attaches all valid images to the album.

- [x] 3. **Refine Gallery Photos Admin View & Metadata Editor** - update `resources/views/admin/gallery/photos.blade.php` to improve multi-photo file input, drag-and-drop feedback, quick per-photo metadata editing modal (caption, year label, alt text, sort order), and bulk action controls.
  *Done when:* Staff can batch-upload photos, view the updated album collection, and edit each individual photo's metadata cleanly.

- [x] 4. **Automated Tests, Pint Styling & Deployment Tracking** - write automated tests for optimizer resilience, GD fallback, and gallery bulk upload; run `php artisan test` and `php vendor/bin/pint --test`; record modified files in `cpanel_changed_files.txt`.
  *Done when:* Test suite passes 100%, Pint passes with 0 violations, and `cpanel_changed_files.txt` is updated.

## Files / areas

- `app/Services/ImageOptimizerService.php` - image optimizer with resilient fallback
- `app/Http/Controllers/Admin/GalleryAlbumController.php` - gallery album & photo controller
- `app/Http/Requests/Admin/StoreGalleryPhotoRequest.php` - validation rules for photos
- `resources/views/admin/gallery/photos.blade.php` - gallery photos management & edit modal
- `tests/Feature/ImageOptimizerResilienceTest.php` - tests for optimizer and fallback
- `tests/Feature/GalleryBulkUploadTest.php` - tests for bulk upload and metadata update
- `cpanel_changed_files.txt` - deployment upload manifest

## Data / contracts

- `ImageOptimizerService::optimize($file, $dir, $maxWidth, $maxHeight, $quality): string`
  - Returns relative public path (e.g. `uploads/gallery/photos/1728839000_abc123.webp` or `uploads/gallery/photos/1728839000_abc123.jpg`).
  - Never throws unhandled GD decode exceptions; falls back to direct storage on GD error or missing extension.
- `gallery_photos` table schema:
  - `id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `timestamps`.

## Testing

- Optimizer resilience tests: `php artisan test --filter=ImageOptimizerResilienceTest`
- Gallery bulk upload tests: `php artisan test --filter=GalleryBulkUploadTest`
- Full test suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- Ensure `ImageOptimizerService` handles both `UploadedFile` instances and string file paths safely.
- When GD is missing or fails, create the destination directory if needed and save using `$file->move($destinationDir, $filename)`.
- Free GD resources with `imagedestroy` inside `finally` or immediately after processing.
- Maintain WCAG 2.2 AA accessibility and responsiveness on all admin views.
- Record every modified file in `cpanel_changed_files.txt`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":7285,"specSha256":"abc201b558d120ece56500296092fa9e53eee532a841cf4d308e13b54b372c34","branch":"refs/heads/feature/resilient-image-optimization-pipeline-upload-fallback-and-gallery-bulk-uploader","head":"45f2d3ab96d2f187012445cbe3b9c48b089d0bf2","baseRef":"refs/heads/main","baseCommit":"45f2d3ab96d2f187012445cbe3b9c48b089d0bf2","sourceTree":"45a3d0cfeda6f166491670c5247b8a8827c66619","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Test Bulk Gallery Upload**:
   - Sign in to `/admin` and navigate to **Photo Gallery** (`/admin/gallery`).
   - Click **Manage Photos** on any album.
   - Under **Upload & Optimize Photos (Bulk Uploader)**, select multiple images (e.g., 3 to 5 photos at once).
   - Notice the dynamic green counter displaying the count of selected files.
   - Enter an event year (e.g., `2026`) and optional caption, then click **Upload**.
   - Verify all selected photos appear in the grid without errors.

2. **Test Photo Metadata Editing**:
   - Click the **Edit** button on any newly uploaded photo card.
   - Update the caption, event year, or alt text in the modal and save.
   - Verify the photo card updates immediately.

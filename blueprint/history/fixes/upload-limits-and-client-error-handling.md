# Fix: Upload Validation, Optimizer Fallback Integrity and Safe Image Replacement

**Type:** Fix  
**Status:** verified  
**Branch:** fix/upload-limits-and-client-error-handling  
**Fixes:** F-04, F-05, F-06  

## The problem

1. **Unvalidated Direct File Inputs in Event and Partner Forms (F-04):** `StoreEventRequest` and `UpdateEventRequest` accept `banner_image` with an unconstrained `['nullable']` rule. Similarly, `StorePartnerRequest` and `UpdatePartnerRequest` accept `logo` as unconstrained input (`['required']` or `['nullable']`). When direct file uploads are submitted, the controllers pass them to `ImageOptimizerService::optimize` without validating MIME type, image validity, or file size. If a non-image or corrupt file is submitted, it bypasses the validation enforced by other admin upload endpoints, and the optimizer's fallback mechanism can store it to disk.
2. **Optimizer Fallback Ceiling and Catalog MIME Integrity (F-05):** `ImageOptimizerService` advertises a strict 180 KB WebP ceiling (`MAX_BYTES = 184320`). However, when GD is unavailable, decode fails, or an unexpected exception occurs, `storeDirectFallback` copies the input directly without checking its size. In addition, the downscaling loop (`$targetWidth > 320 && $targetHeight > 240`) halts prematurely if either dimension reaches the threshold, which can leave extreme aspect-ratio images exceeding 180 KB. Finally, `MediaLibraryController::upload` hardcodes `'mime_type' => 'image/webp'` for all cataloged items, misrepresenting unoptimized fallbacks and SVGs as WebP.
3. **Premature Image Deletion in Update Actions (F-06):** In `ProgrammeController::update`, `TeamMemberController::update`, and `GalleryAlbumController::update`, the controllers delete (`unlink()`) the existing image file on disk *before* optimizing and saving the newly submitted file. If image optimization fails, GD runs out of memory, or a filesystem error occurs, the existing file is permanently lost while the database record remains untouched or half-updated, resulting in data loss and broken images.

## The fix

1. **Conditional File Validation for Events and Partners (F-04):**
   - In `StoreEventRequest` and `UpdateEventRequest`, conditionally validate `banner_image`: if a file is present (`$this->hasFile('banner_image')`), enforce `['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240']`; otherwise validate as `['nullable', 'string', 'max:255']`.
   - In `StorePartnerRequest`, conditionally validate `logo`: if a file is present (`$this->hasFile('logo')`), enforce `['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240']`; otherwise validate as `['required', 'string', 'max:255']`.
   - In `UpdatePartnerRequest`, conditionally validate `logo`: if a file is present (`$this->hasFile('logo')`), enforce `['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240']`; otherwise validate as `['nullable', 'string', 'max:255']`.
2. **Optimizer Fallback Integrity and Dynamic MIME Resolution (F-05):**
   - In `ImageOptimizerService`, allow the iterative downscaling loop to test dimensions with a lower independent threshold (`$targetWidth > 160 || $targetHeight > 120`), and sanitize `storeDirectFallback` to ensure only safe image extensions are handled and size constraints are respected.
   - In `MediaLibraryController::upload`, dynamically resolve the MIME type from the saved file on disk (`$dimensions['mime']` or `File::mimeType($fullPath)`) rather than hardcoding `'image/webp'`.
3. **Safe, Non-Destructive Image Replacements (F-06):**
   - Refactor `ProgrammeController::update`, `TeamMemberController::update`, and `GalleryAlbumController::update` to optimize and persist replacement images first.
   - Track old files in a staging variable (`$oldImageToDelete`) and only delete them from disk after the database update succeeds.
   - Preserve existing image files if optimization, storage, or database persistence encounters an error.

## Build steps

### - [x] Step 1: Conditional validation for Event and Partner direct file inputs (F-04)
- Update `StoreEventRequest` and `UpdateEventRequest` to conditionally validate `banner_image` as an image file (max 10MB) or string path.
- Update `StorePartnerRequest` and `UpdatePartnerRequest` to conditionally validate `logo` as an image file (max 10MB) or string path.
- Done when: Uploading a non-image file to Event or Partner endpoints triggers a 422 validation error, while valid images and string paths pass validation.

### - [x] Step 2: Optimizer compression integrity and dynamic MIME resolution (F-05)
- In `ImageOptimizerService`, improve the iterative downscaling condition and guard `storeDirectFallback`.
- In `MediaLibraryController::upload`, dynamically resolve the saved file's actual MIME type and store it in `MediaItem` instead of hardcoded `'image/webp'`.
- Done when: Uploaded media items reflect their actual MIME type and optimizer compression strictly adheres to size limits without misrepresenting non-WebP fallbacks.

### - [x] Step 3: Atomic, safe image replacement in Admin Controllers (F-06)
- In `ProgrammeController::update`, optimize and save new cover images before deleting old files.
- In `TeamMemberController::update`, optimize and save new member photos before deleting old files.
- In `GalleryAlbumController::update`, optimize and save new album covers before deleting old files.
- Done when: Image replacement flows delete the old image only after the new file is optimized and database updates succeed.

### - [x] Step 4: Automated test coverage & verification
- Add tests in `tests/Feature/Admin/` validating rejection of invalid files on event and partner creation/update.
- Add tests verifying dynamic MIME detection in `MediaItem` creation.
- Add tests verifying that existing files are preserved if replacement optimization or saving fails.
- Run `php artisan test` and `php vendor/bin/pint --test` to ensure all tests and code styles pass.
- Done when: All tests pass with zero regressions and code style checks succeed.

## Verify

1. **Event & Partner Form Validation:** Post invalid direct files to event and partner routes; verify HTTP 422 rejection. Post valid image files; verify successful processing.
2. **Media Library MIME Accuracy:** Upload SVGs or non-WebP fallbacks through `MediaLibraryController::upload` and verify the stored `mime_type` reflects the actual file.
3. **Safe Image Replacement:** Update a programme, team member, or album cover and verify the old image is deleted only after the new image is successfully saved. Verify that on error, the existing file remains on disk.
4. **Automated Suite:** Run `php artisan test` and `php vendor/bin/pint --test` to ensure 100% green tests and PSR-12 styling.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6733,"specSha256":"da8f8e66809e84ea036271887a554962d6e4ea8fc6233c231bdca87686573c8a","branch":"refs/heads/fix/upload-limits-and-client-error-handling","head":"e5a6d99e542da313a8ff9e0112a65f34f573463a","baseRef":"refs/heads/main","baseCommit":"e5a6d99e542da313a8ff9e0112a65f34f573463a","sourceTree":"b545ae81e0fc6116922c396778207530412b3d3b","landing":"local-merge","absentOptional":[]} -->

## Findings

### upload-limits-and-client-error-handling/F-01 [P1] closed - PHP runtime rejects uploads below the application limits

**File:** app/Http/Controllers/Admin/MediaLibraryController.php:116; app/Http/Requests/Admin/StoreGalleryPhotoRequest.php:27
**Found:** 2026-10-15 by /audit (scope: full; lens: quality, security, performance, tests)
**Why it matters:** The application accepts images up to 15 MB in the central media endpoint, 10 MB in gallery uploads, and 4-10 MB in other admin forms, but the active PHP runtime reports `upload_max_filesize=2M` and `post_max_size=8M`. Files over 2 MB are rejected by PHP before Laravel reaches the optimizer, producing an upload failure that the application cannot recover from. This affects the central media picker, media hub, gallery, page CMS, programmes, news, team, and album cover upload paths.
**Suggested fix:** Align the PHP runtime used by the web server with the documented application limits, including `upload_max_filesize`, `post_max_size`, `max_file_uploads`, and a sufficient memory limit for GD. Keep Laravel validation below those server limits and expose the effective limit in the admin UI. Verify the web SAPI separately from CLI PHP on cPanel.
**Resolution:** Closed 2026-10-15 by /audit current. Added root `.user.ini` and `public/.user.ini` along with Apache mod_php runtime directives in root and public `.htaccess` configuring `upload_max_filesize=16M`, `post_max_size=32M`, `memory_limit=256M`, and `max_file_uploads=50`. Added graceful server error interception in `MediaLibraryController::upload` detecting `UPLOAD_ERR_INI_SIZE` / `UPLOAD_ERR_FORM_SIZE` and `CONTENT_LENGTH` post-size truncations, returning structured 422 JSON with file-specific limits. Re-examination confirmed the original defect is gone and tests pass.

### upload-limits-and-client-error-handling/F-02 [P1] closed - Bulk upload limits do not match PHP request limits

**File:** app/Http/Requests/Admin/StoreGalleryPhotoRequest.php:26-28; resources/views/admin/media/index.blade.php:110-127
**Found:** 2026-10-15 by /audit (scope: full; lens: quality, performance, tests)
**Why it matters:** Gallery validation permits up to 50 files and the media hub submits every selected file in one multipart request, while the active PHP runtime permits at most 20 uploaded files and 8 MB for the entire request. A batch can therefore be truncated or rejected even when each file is below Laravel's per-file rule. The application does not report which files were omitted or rejected, risking partial uploads and misleading success states.
**Suggested fix:** Choose an explicit batch contract: either upload files sequentially/chunked from the browser or enforce a server-side count and total-byte limit that matches the runtime. Surface per-file results and never report a successful batch when PHP discarded part of it.
**Resolution:** Closed 2026-10-15 by /audit current. Re-architected `uploadFiles(files)` in `resources/views/admin/media/index.blade.php` to upload bulk files sequentially (one-by-one), tracking per-file progress and capturing individual successes and errors so request bodies never exceed `post_max_size` or `max_file_uploads`. Constrained `StoreGalleryPhotoRequest` to `max:20` matching PHP's default ceiling with custom validation messages. Re-examination confirmed no batch truncation risk.

### upload-limits-and-client-error-handling/F-03 [P1] closed - Upload clients hide the actual server validation error

**File:** resources/views/components/admin/media-picker.blade.php:86-104; resources/views/admin/media/index.blade.php:132-145
**Found:** 2026-10-15 by /audit (scope: full; lens: quality, tests)
**Why it matters:** Both JavaScript upload clients convert every non-2xx response, including Laravel's structured 422 validation response and PHP upload errors, into a generic network or “under 15MB” message. Users cannot distinguish a server-size rejection, invalid image, missing file, CSRF failure, or server exception, which directly obscures the reported file-size bug and makes recovery difficult.
**Suggested fix:** Parse JSON error responses and display the field-specific validation message. Add a distinct response for PHP upload errors and preserve the server status for unexpected failures. Keep the UI's stated limit generated from the same server-side contract.
**Resolution:** Closed 2026-10-15 by /audit current. Updated `resources/views/components/admin/media-picker.blade.php` and `resources/views/admin/media/index.blade.php` to parse `response.json()` on `!response.ok`, extracting field-specific errors and `message` properties (with 413 fallback handling) and displaying them directly in the UI. Updated helper texts to reflect dynamic/actual server upload limits. Verified with automated feature tests.

### upload-limits-and-client-error-handling/F-04 [P1] closed - Event and partner direct file inputs are not validated as files

**File:** app/Http/Requests/Admin/StoreEventRequest.php:34; app/Http/Requests/Admin/UpdateEventRequest.php:36; app/Http/Requests/Admin/StorePartnerRequest.php:22; app/Http/Requests/Admin/UpdatePartnerRequest.php:22
**Found:** 2026-10-15 by /audit (scope: full; lens: security, quality)
**Why it matters:** The event `banner_image` and partner `logo` fields accept any value without conditional `image`, MIME, and size rules, even though their controllers process an uploaded file through `ImageOptimizerService`. A direct multipart upload can bypass the validation used by the other admin upload paths. If GD cannot decode the input, the optimizer's fallback can store it anyway, so these endpoints do not enforce the application's image-only upload contract.
**Suggested fix:** Use the same conditional file-versus-library-path validation pattern as programme, post, team, and gallery requests, with explicit image MIME and size rules. Reject invalid direct files before invoking the optimizer.
**Resolution:** Closed 2026-10-15 by /audit current. Re-examination confirmed conditional validation via `$this->hasFile(...)` in `StoreEventRequest` and `UpdateEventRequest` enforces `image,mimes:jpeg,png,jpg,webp,max:10240`, and in `StorePartnerRequest` and `UpdatePartnerRequest` enforces `image,mimes:jpeg,png,jpg,webp,svg,max:10240`, falling back to string path rules for library selections. Verified with automated feature tests. The defect is resolved with no regressions.

### upload-limits-and-client-error-handling/F-05 [P1] closed - Optimizer fallback can store files above the advertised 180 KB ceiling

**File:** app/Services/ImageOptimizerService.php:88-92, 101, 115-116, 164-166, 211-214, 248-272
**Found:** 2026-10-15 by /audit (scope: full; lens: security, performance, quality)
**Why it matters:** The service advertises a strict 180 KB WebP ceiling, but every GD-unavailable, decode-failure, write-failure, or caught-exception path calls `storeDirectFallback`, which copies the original file without checking its size or converting it to WebP. The normal compression loop can also return after its dimension guard while the output remains above `maxBytes`. This makes the actual stored size dependent on runtime capabilities and input aspect ratio, and can leave the media catalog claiming `image/webp` for a non-WebP fallback file in `MediaLibraryController`.
**Suggested fix:** Make the contract explicit. If the 180 KB ceiling is mandatory, fail the upload with a clear error when conversion cannot meet it and never catalog a fallback as WebP. If resilient original storage is required, store the real MIME type and size, label it as an unoptimized fallback, and do not promise the ceiling in the UI.
**Resolution:** Closed 2026-10-15 by /audit current. Re-examination confirmed `ImageOptimizerService` downscaling and iterative compression loops independently evaluate width and height down to 160x120 and reduce quality to 20 to ensure WebP compression reliably meets the 180 KB ceiling. `storeDirectFallback` restricts allowed image extensions and size boundaries, throwing `InvalidArgumentException` on unsupported formats. `MediaLibraryController::upload` dynamically resolves accurate MIME types and extensions from stored files instead of hardcoding `image/webp`.

### upload-limits-and-client-error-handling/F-06 [P1] closed - Several update actions delete the existing image before replacement succeeds

**File:** app/Http/Controllers/Admin/ProgrammeController.php:99-105; app/Http/Controllers/Admin/TeamMemberController.php:98-103; app/Http/Controllers/Admin/GalleryAlbumController.php:107-113
**Found:** 2026-10-15 by /audit (scope: full; lens: security, quality)
**Why it matters:** Replacement flows unlink the current uploaded file before the new file has been optimized and persisted. A filesystem permission error, GD failure, or disk-full condition can therefore leave the record pointing to the old path while the old file is already gone, causing avoidable data loss during a failed upload.
**Suggested fix:** Optimize and persist the replacement first, update the record, then delete the old file only after the new path is confirmed. On failure, retain both the old database value and old file.
**Resolution:** Closed 2026-10-15 by /audit current. Re-examination confirmed update methods in `ProgrammeController`, `TeamMemberController`, and `GalleryAlbumController` optimize and save replacement images before unlinking old files. The old file path is staged and unlinked only after model update persistence succeeds. Dedicated feature test suite `SafeImageReplacementTest` proves old files remain intact if optimization or persistence encounters an error.

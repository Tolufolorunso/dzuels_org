# Feature: Universal gallery media picker and centralized cover photo optimization

**From build-plan:** feature 29
**Build attempt:** 1
**Branch:** feature/29-universal-gallery-media-picker-cover-photo-optimization
**Status:** verified

## Goal

Empower foundation staff to select existing photos from any gallery album or upload and optimize new images across every photo upload form in the admin panel: replace raw file inputs with `<x-admin.media-picker>` in Gallery Album create/edit (`cover_image`), Programme create/edit (`cover_image`), News/Post create/edit (`cover_image`), and Team Member create/edit (`photo`); update controllers and form requests (`GalleryAlbumController`, `ProgrammeController`, `PostController`, `TeamMemberController`) to seamlessly accept either selected gallery image paths or direct file uploads; ensure all uploaded cover photos are processed through `ImageOptimizerService` strictly enforcing <= 180 KB WebP format and original file deletion; and synchronize the deployment manifest and test suite.

## In scope

- **Media Picker Replacement across Admin Forms:**
  - `resources/views/admin/gallery/create.blade.php` & `resources/views/admin/gallery/edit.blade.php`: replace `<input type="file" name="cover_image">` with `<x-admin.media-picker name="cover_image" ...>`.
  - `resources/views/admin/programmes/create.blade.php` & `resources/views/admin/programmes/edit.blade.php`: replace `<input type="file" name="cover_image">` with `<x-admin.media-picker name="cover_image" ...>`.
  - `resources/views/admin/news/create.blade.php` & `resources/views/admin/news/edit.blade.php`: replace `<input type="file" name="cover_image">` with `<x-admin.media-picker name="cover_image" ...>`.
  - `resources/views/admin/team/create.blade.php` & `resources/views/admin/team/edit.blade.php`: replace `<input type="file" name="photo">` with `<x-admin.media-picker name="photo" ...>`.
- **Dual-Input Validation Rules in Form Requests:**
  - `StoreGalleryAlbumRequest` & `UpdateGalleryAlbumRequest`: update `cover_image` validation to accept either an existing photo path string or a file upload.
  - `StoreProgrammeRequest` & `UpdateProgrammeRequest`: update `cover_image` validation to accept string path or file upload.
  - `StorePostRequest` & `UpdatePostRequest`: update `cover_image` validation to accept string path or file upload.
  - `StoreTeamMemberRequest` & `UpdateTeamMemberRequest`: update `photo` validation to accept string path or file upload.
- **Controller Persistence & Centralized Image Optimization:**
  - `GalleryAlbumController`: update `store` and `update` to handle both `$request->hasFile('cover_image')` (optimizing to <= 180 KB WebP and deleting original) and `$request->filled('cover_image')` (persisting selected gallery path), plus clearing image when removed.
  - `ProgrammeController`: update `store` and `update` to handle both `$request->hasFile('cover_image')` and `$request->filled('cover_image')`.
  - `PostController`: update `store` and `update` to handle both `$request->hasFile('cover_image')` and `$request->filled('cover_image')`.
  - `TeamMemberController`: update `store` and `update` to handle both `$request->hasFile('photo')` and `$request->filled('photo')`.
- **Automated Testing & Regression Verification:**
  - Update and add feature tests verifying that selecting an existing gallery photo path persists and displays correctly across gallery albums, programmes, news, and team members.
  - Verify that direct file uploads continue to be optimized to WebP <= 180 KB.
  - Run the full test suite (`php artisan test`) and Pint styling check (`php vendor/bin/pint --test`).
  - Update `cpanel_changed_files.txt`.

## Out of scope

- Changing public-facing layouts or templates (all models already output correct image URLs via accessors).
- Modifying gallery photo bulk upload in `admin/gallery/photos.blade.php` (already optimized).
- Adding third-party cloud storage or CDN packages.

## Build loop

- `workflow.stepReview: "feature"`: Small implementation steps proceed continuously, with one comprehensive review packet presented upon completion.
- `workflow.checkpointCommits: "disabled"`: Clean commits created at feature completion.
- Execute Verify command (`php artisan test` and `php vendor/bin/pint --test`) across all steps.

## Build steps

- [x] 1. **Form Requests & Dual-Input Validation Update**: Update `StoreGalleryAlbumRequest`, `UpdateGalleryAlbumRequest`, `StoreProgrammeRequest`, `UpdateProgrammeRequest`, `StorePostRequest`, `UpdatePostRequest`, `StoreTeamMemberRequest`, and `UpdateTeamMemberRequest` to validate that `cover_image` / `photo` can be either an uploaded image or an existing relative string path from the media picker.
  - *Done when:* FormRequests accept string paths (e.g. `front/images/...` or `uploads/...`) without throwing `The cover image must be an image` validation errors.

- [x] 2. **Controllers Centralized Dual-Path Storage & Optimization**: Update `GalleryAlbumController`, `ProgrammeController`, `PostController`, and `TeamMemberController` store and update actions to check for `$request->hasFile(...)` (optimizing via `ImageOptimizerService` to WebP <= 180 KB and deleting original) as well as `$request->filled(...)` (storing the selected gallery path) and handling removal.
  - *Done when:* Submitting either a selected gallery path or a new image file successfully stores the image path and updates the model, while direct uploads produce a WebP file <= 180 KB.

- [x] 3. **Admin Views Media Picker Replacement**: Replace raw `<input type="file">` elements with `<x-admin.media-picker>` in:
  - `resources/views/admin/gallery/create.blade.php` & `edit.blade.php` (`cover_image`)
  - `resources/views/admin/programmes/create.blade.php` & `edit.blade.php` (`cover_image`)
  - `resources/views/admin/news/create.blade.php` & `edit.blade.php` (`cover_image`)
  - `resources/views/admin/team/create.blade.php` & `edit.blade.php` (`photo`)
  - *Done when:* Navigating to any of these create or edit screens in the admin panel displays the full media picker card with "Choose from Gallery" (with collection filter and search) and "Upload New" buttons, live thumbnail preview, and clear button.

- [x] 4. **Feature Tests for Gallery Photo Selection & Dual-Input Handling**: Update/extend feature tests in `tests/Feature/Admin/GalleryManagementTest.php`, `ProgrammeManagementTest.php`, `NewsManagementTest.php`, and `TeamMemberManagementTest.php` to prove that selecting an existing gallery path persists and renders properly.
  - *Done when:* Tests pass verifying both file upload and existing gallery path selection across all 4 resource types.

- [x] 5. **Full Regression Suite, Code Styling & Deployment Manifest**: Run the complete test suite (`php artisan test`), verify `php vendor/bin/pint --test`, and update `cpanel_changed_files.txt`.
  - *Done when:* All 263+ tests pass, Pint passes with zero violations, and `cpanel_changed_files.txt` contains all modified files.

## Files / areas

- `app/Http/Requests/Admin/StoreGalleryAlbumRequest.php`
- `app/Http/Requests/Admin/UpdateGalleryAlbumRequest.php`
- `app/Http/Requests/Admin/StoreProgrammeRequest.php`
- `app/Http/Requests/Admin/UpdateProgrammeRequest.php`
- `app/Http/Requests/Admin/StorePostRequest.php`
- `app/Http/Requests/Admin/UpdatePostRequest.php`
- `app/Http/Requests/Admin/StoreTeamMemberRequest.php`
- `app/Http/Requests/Admin/UpdateTeamMemberRequest.php`
- `app/Http/Controllers/Admin/GalleryAlbumController.php`
- `app/Http/Controllers/Admin/ProgrammeController.php`
- `app/Http/Controllers/Admin/PostController.php`
- `app/Http/Controllers/Admin/TeamMemberController.php`
- `resources/views/admin/gallery/create.blade.php`
- `resources/views/admin/gallery/edit.blade.php`
- `resources/views/admin/programmes/create.blade.php`
- `resources/views/admin/programmes/edit.blade.php`
- `resources/views/admin/news/create.blade.php`
- `resources/views/admin/news/edit.blade.php`
- `resources/views/admin/team/create.blade.php`
- `resources/views/admin/team/edit.blade.php`
- `tests/Feature/Admin/GalleryManagementTest.php`
- `tests/Feature/Admin/ProgrammeManagementTest.php`
- `tests/Feature/Admin/PostManagementTest.php`
- `tests/Feature/Admin/TeamMemberManagementTest.php`
- `cpanel_changed_files.txt`

## Data / contracts

- `<x-admin.media-picker name="field_name" :value="$currentValue" label="..." help="..." />`:
  - Output submitted to form: hidden input with `name="field_name"` containing the string path to the chosen photo (e.g. `front/images/gallery/optimized/itd-2025-05.webp` or `uploads/media/1791916040_...webp`).
  - Direct file upload tab in modal: sends file to `admin.media.upload`, which returns optimized WebP path and URL.
- Controller handling contract:
  - If `$request->hasFile($field)`: optimize via `ImageOptimizerService::optimize($file, $dir, $w, $h)` (guaranteeing <= 180 KB WebP and deleting original).
  - If `$request->filled($field)`: assign string path directly.
  - If clearing/removing: delete previous uploaded file if local in `uploads/` and set field to null.

## Testing

- Feature tests in `tests/Feature/Admin/*ManagementTest.php` covering:
  - Creating and updating Gallery Albums using existing gallery photo paths.
  - Creating and updating Programmes using existing gallery photo paths.
  - Creating and updating News posts using existing gallery photo paths.
  - Creating and updating Team Members using existing gallery photo paths.
  - Direct file uploads continue to optimize to WebP <= 180 KB.
- Full regression: `php artisan test` (all 263+ tests passing).
- Code style: `php vendor/bin/pint --test`.

## Notes for the AI

- Ensure all Form Requests still safely validate files when a file IS uploaded, but allow strings when a path from the gallery is submitted.
- When an existing photo path is selected from the media picker, do not re-run it through the optimizer since it is already an optimized WebP asset.
- Retain existing `remove_photo` / `remove_cover_image` checkbox behavior or hidden input clearing.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10060,"specSha256":"1ef9cf0ef7214da90dd0564a2452f8494979c6a277f4e330c6c6303cdc8c7f54","branch":"refs/heads/feature/29-universal-gallery-media-picker-cover-photo-optimization","head":"08a724a1f8a4e5d6b19f224e10515f6079f21906","baseRef":"refs/heads/main","baseCommit":"08a724a1f8a4e5d6b19f224e10515f6079f21906","sourceTree":"bf192e5f45230b3b05ffb75e13bddb6636cc964d","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Universal Media Picker on Gallery Collections**:
   - Navigate to `http://localhost:8000/admin/gallery/create` or edit an existing album at `http://localhost:8000/admin/gallery/1/edit`.
   - Click **"Choose from Gallery"**: verify the modal opens displaying all collection photos, album filters, and real-time search.
   - Select a photo and verify the thumbnail and path update immediately in the form.
   - Click **"Upload New"** tab: drag or choose an image, verify auto-optimization to WebP <= 180 KB, and confirm it auto-selects.
   - Click **"Clear"**: verify the selection resets to "No image chosen yet".

2. **Verify Media Picker on Programmes**:
   - Navigate to `http://localhost:8000/admin/programmes/create` or edit a programme at `http://localhost:8000/admin/programmes/1/edit`.
   - Confirm the Cover Photo field is now an interactive media picker.
   - Select an existing gallery photo or upload an image and save.
   - Verify on the public programmes page (`http://localhost:8000/programmes`) that the selected photo renders sharply without distortion.

3. **Verify Media Picker on News / Articles**:
   - Navigate to `http://localhost:8000/admin/news/create` or edit a story.
   - Select a photo from the gallery or upload a new WebP image.
   - Save and verify on `http://localhost:8000/news` that the article's cover image renders correctly.

4. **Verify Media Picker on Team Profiles**:
   - Navigate to `http://localhost:8000/admin/team/create` or edit a profile at `http://localhost:8000/admin/team/1/edit`.
   - Pick an existing photo from the gallery or upload a headshot.
   - Save and view `http://localhost:8000/about` (Team section) to confirm the photo displays cleanly in the team grid.

5. **Verify <= 180 KB WebP Ceiling and Deletion of Originals**:
   - Direct upload a photo through any admin form or modal.
   - Check the resulting file in `public/uploads/`: confirm it is strictly `.webp` format, under 180 KB (184,320 bytes), and the raw uncompressed original file is deleted.

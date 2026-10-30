# Feature 46: Universal photo picker integration for Gallery Albums, Events & Programmes with public lightbox showcase

**From build-plan:** feature 46
**Build attempt:** 1
**Branch:** feature/universal-photo-picker-integration-for-gallery-albums-events-and-programmes-with-public-lightbox-showcase
**Status:** completed

## Goal

Unify photo attachment workflows across the administration suite by making the Central Media Hub the default source for Gallery Albums (`/admin/gallery/{album}/photos`), empowering staff to attach and directly upload photo galleries on Event and Programme edit forms (`/admin/events/{id}/edit` and `/admin/programmes/{id}/edit`), and rendering attached photography in responsive public photo grids with interactive Alpine.js lightbox viewing on `/events/{slug}` and `/programmes/{slug}`.

## In scope

1. **Gallery Album Central Media Hub Integration (`/admin/gallery/{album}/photos`)**:
   - Re-architect the photo management interface with a 2-tab segmented layout:
     - **Default Tab 1: "Select from Media Library"** (Primary default option) - an embedded modal/browser showing Central Media Hub items with live search, collection filter, and year filter; multi-selection checkboxes; "Attach Selected Photos" button.
     - **Tab 2: "Upload New Photos"** (Secondary option) - existing bulk uploader that converts to $\le 180\text{ KB}$ WebP and immediately registers both `GalleryPhoto` and `MediaItem` records.
   - Add controller endpoint `POST /admin/gallery/{album}/photos/attach-media` to link selected `MediaItem` records to the album as `GalleryPhoto` items without re-uploading.

2. **Event & Programme Photo Gallery Management**:
   - Add database migration:
     - Add `gallery_album_id` (foreign key, nullable) and `gallery_photos` (JSON, nullable) to `events` table.
     - Add `gallery_photos` (JSON, nullable) to `programmes` table.
   - Update `Event` and `Programme` models with `$fillable` and `'gallery_photos' => 'array'` casting, plus accessors to retrieve resolved photo objects (handling both attached photo paths/objects and linked album photos).
   - Create reusable multi-photo manager component `<x-admin.multi-photo-manager>` (or dedicated Alpine.js manager) embedded on `/admin/events/{id}/edit`, `/admin/events/create`, `/admin/programmes/{id}/edit`, and `/admin/programmes/create`.
   - The multi-photo manager supports:
     - Browsing and selecting multiple photos from the Central Media Hub.
     - Direct file upload that automatically optimizes to $\le 180\text{ KB}$ WebP, records in `media_items`, and appends to the gallery.
     - Reordering and removing attached photos.
     - Optional selection of a linked `gallery_album_id`.

3. **Public Event & Programme Interactive Lightbox Showcase**:
   - Update `app/Http/Controllers/EventController::show()` and `resources/views/events/show.blade.php`:
     - Render attached event photos (or linked album photos / Tech Day edition photos) in a responsive photo grid.
     - Enhance with interactive Alpine.js lightbox modal supporting click-to-expand, keyboard navigation (Escape to close, Left/Right arrows for previous/next), photo index counter, and caption overlay.
   - Update `app/Http/Controllers/ProgrammeController::show()` and `resources/views/programmes/show.blade.php`:
     - Render attached programme photos in a responsive gallery section with the interactive Alpine.js lightbox modal.

4. **Testing & Verification**:
   - Automated feature tests for:
     - Attaching media items to a gallery album via `attach-media`.
     - Storing and updating event `gallery_photos` and `gallery_album_id`.
     - Storing and updating programme `gallery_photos`.
     - Public event show page displaying photos and lightbox markup.
     - Public programme show page displaying photos and lightbox markup.

## Out of scope

- Homepage ITD section dynamic status updates (Feature 47).
- Purging "Zero Administrative Deductions" and "equips public school computer labs" copy (Feature 47).
- Third-party cloud storage or external CDN integration.

## Build loop

- Step review cadence: `feature` (one consolidated review packet at the end of the feature).
- Checkpoint commits: `disabled` (final feature commit upon completion).
- Follow all standards in `blueprint/context/coding-standards.md` and Laravel best practices.

## Build steps

- [x] 1. **Database Migration & Model Layer**:
  - Create migration adding `gallery_album_id` and `gallery_photos` JSON to `events`, and `gallery_photos` JSON to `programmes`.
  - Update `Event` and `Programme` models with fillable, casts, relations (`belongsTo(GalleryAlbum::class)`), and helper methods (`getGalleryPhotosList()`).
  - *Done when:* `php artisan migrate` runs cleanly, and model tests confirm `gallery_photos` JSON casts and relations work.

- [x] 2. **Gallery Album Media Hub Integration (`/admin/gallery/{album}/photos`)**:
  - Implement `POST /admin/gallery/{album}/photos/attach-media` in `GalleryAlbumController` to convert selected `MediaItem` records into `GalleryPhoto` records and associate them with the album.
  - Overhaul `resources/views/admin/gallery/photos.blade.php` to present a 2-tab interface defaulting to "Select from Media Library" with multi-select checkboxes and search/filter controls, keeping "Upload New Photos" as the secondary tab.
  - *Done when:* Visiting `/admin/gallery/{id}/photos` displays "Select from Media Library" by default, and selecting media items attaches them to the album without re-uploading.

- [x] 3. **Reusable Multi-Photo Gallery Admin Component**:
  - Build `<x-admin.multi-photo-manager>` Blade/Alpine component with:
    - Current photo thumbnails with captions, remove buttons, and order index.
    - "Select from Media Library" multi-select modal with search, year filter, and batch selection.
    - "Upload Photos" file input supporting multiple files with client-side preview and auto-WebP optimization.
    - Hidden inputs serializing attached photos as JSON for form submission.
  - *Done when:* Component renders attached photos, allows selecting from media hub, allows direct uploads, and updates the form payload.

- [x] 4. **Events & Programmes Admin Integration**:
  - Integrate multi-photo manager and `gallery_album_id` selector into `resources/views/admin/events/edit.blade.php`, `create.blade.php`, `resources/views/admin/programmes/edit.blade.php`, and `create.blade.php`.
  - Update `StoreEventRequest`, `UpdateEventRequest`, `StoreProgrammeRequest`, `UpdateProgrammeRequest`, and controllers (`EventController`, `ProgrammeController`) to validate and persist `gallery_photos` and `gallery_album_id`.
  - Handle direct photo uploads via `ImageOptimizerService` saving to `uploads/events` and `uploads/programmes` and registering in `media_items`.
  - *Done when:* Editing an event or programme persists attached photos and album link successfully in the database.

- [x] 5. **Public Event & Programme Interactive Lightbox Showcase**:
  - Update `EventController::show()` and `resources/views/events/show.blade.php` to display all attached event photos and support interactive Alpine.js lightbox viewing with keyboard navigation.
  - Update `ProgrammeController::show()` and `resources/views/programmes/show.blade.php` to display attached programme photos and launch the interactive Alpine.js lightbox viewer.
  - *Done when:* Visiting public `/events/{slug}` and `/programmes/{slug}` displays the gallery and clicking any photo opens the fullscreen lightbox modal with next/prev controls.

- [x] 6. **Automated Testing, Pint & Launch Verification**:
  - Create feature test `tests/Feature/Admin/UniversalPhotoPickerAndLightboxTest.php` covering album media attachment, event/programme photo persistence, and public lightbox view assertions.
  - Run `php vendor/bin/pint --test` and `php artisan test` to confirm full test suite passes.
  - *Done when:* All tests pass with zero regressions and code style passes with 0 violations.

## Verification Evidence

- Full automated test suite: 419 passed (2,719 assertions) via `php artisan test`.
- Dedicated feature tests: 7 passed in `tests/Feature/Admin/UniversalPhotoPickerAndLightboxTest.php`.
- Code formatting: 0 violations via `php vendor/bin/pint --test`.
- Assets build: built successfully via `npm run build`.


# Feature: Dynamic media collections, authentic photos, database SQL export & cPanel root alignment

**From build-plan:** feature 31
**Build attempt:** 1
**Status:** verified
**Branch:** feature/dynamic-media-collections-authentic-photos-cpanel-root

## Goal

Provide seamless cPanel root deployment support (`/home/dzuefjel/dzuels_org`) with security hardening; unify Media Categories with `GalleryAlbum` so categories in the Central Media Hub (`/admin/media`) and `<x-admin.media-picker>` are dynamic, with a "+ New Collection" modal and optional public gallery toggle; curate, optimize ($\le 180\text{ KB}$ WebP), and seed authentic photos from `picture/` across all 7 foundation programs; update CMS heroes, causes, and page sections with real imagery and catalog them in `MediaItem`; delete the temporary `picture/` folder; and export a fresh `dzuels_database.sql` for 1-click phpMyAdmin import.

## Design reference

- User-provided cPanel directory structure screenshots showing `index.php`, `.htaccess`, `front/`, `build/`, `uploads/`, and core Laravel folders sitting directly in `/home/dzuefjel/dzuels_org`.
- Existing Central Media Hub layout at `/admin/media` with filter chips, search input, date filters, and upload modal.

## In scope

- **cPanel Root Deployment & Security Hardening**:
  - Root `index.php` booting Laravel and binding `$app->usePublicPath(__DIR__)`.
  - Root `.htaccess` with rewrite engine routing to `index.php` and security rules forbidding direct HTTP requests to `.env`, `.git`, `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `tests/`, `vendor/`.
- **Dynamic Media Collections / Categories**:
  - Add `is_public` column to `gallery_albums` (default `true`).
  - Pre-seed authentic foundation programs: *Essay Competition*, *Girls Day*, *ITD (Ijero Tech Day)*, *Reading / Library*, *Spelling Bee*, *WDADA Outreach*, and *General Community*, plus system category *Site & CMS Assets*.
  - Provide a "+ New Collection" button and modal on `/admin/media` enabling staff to create new collections with title, description, and an optional "Show in Public Gallery" checkbox.
  - Endpoint `POST /admin/media/collections` for collection creation with validation and JSON/redirect responses.
  - Dynamically populate category filters in `/admin/media` and `<x-admin.media-picker>` from active `GalleryAlbum` records.
- **Authentic Photo Curation & Optimization**:
  - Curate ~12–18 sharpest, highest-impact photos per program from the 4,000+ images in `picture/` (Essay Competition, Girls Day, ITD, Reading, Spelling Bee, WDADA, General Community).
  - Optimize all curated images to WebP format strictly adhering to $\le 180\text{ KB}$ ceiling.
  - Place images in `public/front/images/gallery/optimized/` and `public/uploads/media/`.
- **Seeders & Dynamic CMS Imagery**:
  - Update `GallerySeeder`, `MediaItemSeeder`, and `PageSectionSeeder` to link the authentic photos.
  - Re-seed database with real imagery.
- **Directory Cleanup**:
  - Delete temporary `picture/` folder completely once curation and optimization are verified.
- **Database SQL Export**:
  - Run `php artisan db:export-sql` to update `database/dumps/dzuels_database.sql` and root `dzuels_database.sql`.
- **Quality & Verification**:
  - Unit/Feature tests for collection creation, dynamic filtering, root configuration, and gallery public visibility.
  - Pass all 277+ tests and Pint code formatting.

## Out of scope

- Direct file transfers or FTP pushes to cPanel (handled by user via zip upload and phpMyAdmin import).
- Third-party cloud storage or AWS S3 adapters (app uses local disk and relative public web paths).
- Unrelated page redesigns or new payment gateways.

## Build loop

- `workflow.stepReview: "feature"`: Small implementation steps verified sequentially with one review handoff at completion.
- `workflow.checkpointCommits: "disabled"`: Single squash-merge commit upon `/complete`.

## Build steps

- [x] 1. **cPanel Root Architecture Alignment & Security Hardening**: Create root `index.php` with `$app->usePublicPath(__DIR__)` and root `.htaccess` with URL rewriting and security block rules for sensitive directories. Done when direct root execution boots Laravel and sensitive directories return 403 Forbidden.
- [x] 2. **Dynamic Media Collections & Category Creation**: Add `is_public` migration to `gallery_albums`, add collection creation endpoint (`POST /admin/media/collections`), update `/admin/media` with "+ New Collection" modal, and make category filters dynamic in `/admin/media` and `<x-admin.media-picker>`. Done when staff can create new collections from the dashboard and filter media by any dynamic collection.
- [x] 3. **Authentic Photo Curation & WebP Optimization**: Process and optimize ~12–18 authentic photos per program from `picture/` into `public/front/images/gallery/optimized/` and `public/uploads/media/`, enforcing $\le 180\text{ KB}$ WebP format. Done when all curated images exist as valid WebP files $\le 180\text{ KB}$.
- [x] 4. **Seeders & Dynamic CMS Content Integration**: Update `GallerySeeder`, `MediaItemSeeder`, and `PageSectionSeeder` with authentic curated images; run migrations and seeders. Done when the gallery, central media hub, Home hero/causes, and Famoyinka Library render authentic photos.
- [x] 5. **Clean up `picture/` Folder**: Safely delete the temporary `picture/` directory from the repository. Done when `picture/` is completely removed from the workspace.
- [x] 6. **Database SQL Export, Test Suite & CPanel Manifest**: Run `php artisan db:export-sql`, execute full test suite (`php artisan test`), run Pint (`php vendor/bin/pint --test`), and update `cpanel_changed_files.txt`. Done when SQL export is generated and all tests and lint checks pass cleanly.

## Files / areas

- `index.php` (new root entry point)
- `.htaccess` (new root web server config)
- `database/migrations/xxxx_xx_xx_xxxxxx_add_is_public_to_gallery_albums_table.php`
- `app/Models/GalleryAlbum.php`
- `app/Models/MediaItem.php`
- `app/Http/Controllers/Admin/MediaLibraryController.php`
- `resources/views/admin/media/index.blade.php`
- `resources/views/components/admin/media-picker.blade.php`
- `database/seeders/GallerySeeder.php`
- `database/seeders/MediaItemSeeder.php`
- `database/seeders/PageSectionSeeder.php`
- `routes/web.php`
- `database/dumps/dzuels_database.sql` & `dzuels_database.sql`
- `cpanel_changed_files.txt`
- `tests/Feature/Admin/MediaLibraryTest.php`

## Data / contracts

- `gallery_albums`: added `is_public` (boolean, default true).
- `POST /admin/media/collections`: requires `title` (string, max:255), optional `description` (string, max:1000), optional `is_public` (boolean). Returns JSON with created album details or redirects back.
- Category filters: dynamic slugs from `GalleryAlbum::orderBy('sort_order')->get()` + system categories (`all`, `site-assets`).

## Testing

- Feature tests in `tests/Feature/Admin/MediaLibraryTest.php` covering:
  - Super admin and Editor can create new collections via `POST /admin/media/collections`.
  - Validation rules for collection title and slug uniqueness.
  - Filtering media library items by dynamic collection slug.
  - Media picker JSON endpoint returning dynamic collection items.
  - Public gallery view showing only albums where `is_public = true` and `is_active = true`.
- Full project test suite: `php artisan test` (277+ tests).
- Code formatting: `php vendor/bin/pint --test`.

## Notes for the AI

- Maintain backward compatibility with `public/index.php` for local `php artisan serve` and Vite dev server.
- The root `index.php` should check if it is running in root mode and configure Laravel accordingly.
- Keep strict $\le 180\text{ KB}$ ceiling on every optimized image.
- Delete `picture/` directory after all curated images have been converted and written to disk.

## Open questions

*(None. User confirmed Option A: Unified Gallery Albums & Media Collections with "+ New Collection" modal and public gallery toggle.)*


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8011,"specSha256":"0d209f1bf3be49c13b496a2ceb0c048300862dbba401099d1213a67d99f57336","branch":"refs/heads/feature/dynamic-media-collections-authentic-photos-cpanel-root","head":"452760c0e79d7957afe07f9debe1e0a3a86fe5e6","baseRef":"refs/heads/main","baseCommit":"452760c0e79d7957afe07f9debe1e0a3a86fe5e6","sourceTree":"e86906be565ffda674f09f487c7369447820a2b8","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Root Deployment Entry Point & Security Hardening**:
   - Check `index.php` at repository root: verify it boots Laravel and binds `$app->usePublicPath(__DIR__)`.
   - Check `.htaccess` at repository root: verify rewrite rules route traffic to `index.php` and block HTTP access to sensitive paths (`.env`, `.git`, `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `tests/`, `vendor/`).
   - Run `php artisan test --filter=RootDeploymentTest` to confirm all 3 root deployment assertions pass.

2. **Verify Dynamic Media Collections & "+ New Collection" Creation**:
   - Log into admin at `http://localhost:8000/admin/login`.
   - Navigate to **Media Library** (`/admin/media`).
   - Verify dynamic category filter buttons for all active collections (*Essay Competition*, *Girls Day*, *ITD*, *Reading / Library*, *Spelling Bee*, *WDADA Outreach*, *General Community*, *Site & CMS Assets*).
   - Click the **"+ New Collection"** button in the header.
   - Enter a collection name (e.g. `Robotics Workshop`), optional description, and toggle "Show in Public Gallery".
   - Submit and verify the new collection immediately appears in the category filters.

3. **Verify Public Gallery Visibility Scoping**:
   - Navigate to the public gallery at `http://localhost:8000/gallery`.
   - Confirm only albums with `is_public = true` and `is_active = true` are displayed to public visitors.
   - Any private or internal-only collections created from the dashboard remain strictly within `/admin/media` and the media picker.

4. **Verify Universal Media Picker Integration (`<x-admin.media-picker>`)**:
   - Navigate to any CMS editor (e.g. `/admin/pages/home` or `/admin/events/create`).
   - Open the Media Picker modal and verify the Category select dropdown lists all dynamic collections.

5. **Verify Authentic Photo Curation & <= 180 KB WebP Optimization**:
   - Navigate to `public/front/images/gallery/optimized/` and verify 120 authentic images:
     - `essay-competition-01.webp` through `16.webp`
     - `girls-day-01.webp` through `16.webp`
     - `itd-01.webp` through `16.webp`
     - `reading-01.webp` through `16.webp`
     - `famoyinka-01.webp` through `08.webp`
     - `spelling-bee-01.webp` through `16.webp`
     - `wdada-01.webp` through `16.webp`
     - `general-01.webp` through `16.webp`
   - Check file sizes to ensure 100% of images are <= 180 KB.

6. **Verify Complete Deletion of Temporary `picture/` Directory**:
   - Confirm the `picture/` directory is completely removed from the workspace.

7. **Verify Database Dump for phpMyAdmin 1-Click Import**:
   - Inspect `dzuels_database.sql` at the repository root and `database/dumps/dzuels_database.sql`.
   - Verify all 25 tables and 549 records are exported with `DROP TABLE IF EXISTS` and `CREATE TABLE` statements.

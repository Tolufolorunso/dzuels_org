# Feature: Gallery

**From build-plan:** feature 8
**Build attempt:** 1
**Branch:** feature/gallery
**Status:** verified

## Goal

Provide staff with an administrative gallery suite at `/admin/gallery` (album CRUD, cover images, photo uploads, and photo management), and deliver an interactive public gallery experience (`/gallery`) showcasing the Foundation's five curated collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) powered by the 125 pre-optimized WebP photos in `public/front/images/gallery/optimized/`, featuring interactive filter chips, lazy-loaded responsive masonry grid, and a keyboard-friendly lightbox modal.

## Design reference

- Brand styling: Poppins typography, Great Vibes accent script, maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Visual assets: 125 pre-optimized WebP images in `public/front/images/gallery/optimized/` (`itd-2024-*.webp`, `itd-2025-*.webp`, `reading-*.webp`, `spelling-*.webp`, `general-*.webp`), and `techday.webp`.
- Interactive elements:
  - Hero feature card with "Featured: Learning in action" badge.
  - Filter chips allowing seamless instant filtering between "All Collections" and individual albums.
  - Masonry columns layout with image zoom and caption overlay on hover.
  - Lightbox modal with keyboard support (Esc to close, Left/Right arrow keys to navigate), image index counter, and caption bar.

## In scope

1. **Database & Models:**
   - Migration `create_gallery_albums_table`:
     - `id` (bigint PK)
     - `title` (string, max 255) - e.g. "ITD 2024", "ITD 2025", "Reading Competition", "Spelling Bee", "General"
     - `slug` (string, unique)
     - `kicker` (string, nullable) - e.g. "Innovation and Tech Day", "Literacy Outreach", "Everyday Impact"
     - `description` (text, nullable)
     - `cover_image` (string, nullable)
     - `sort_order` (integer, default 0)
     - `is_active` (boolean, default true)
     - `timestamps`
   - Migration `create_gallery_photos_table`:
     - `id` (bigint PK)
     - `gallery_album_id` (foreignId references `gallery_albums.id` onDelete cascade)
     - `image_path` (string) - relative path to WebP asset or upload
     - `caption` (string, nullable)
     - `alt_text` (string, nullable)
     - `sort_order` (integer, default 0)
     - `timestamps`
     - Index on `[gallery_album_id, sort_order]`
   - Eloquent Models:
     - `App\Models\GalleryAlbum`:
       - Fillable attributes, casts (`is_active` => `boolean`, `sort_order` => `integer`).
       - Relationship: `hasMany(GalleryPhoto::class)`.
       - Scopes: `active()`, `ordered()`.
       - Accessors: `cover_image_url`, `photos_count`.
     - `App\Models\GalleryPhoto`:
       - Fillable attributes, casts (`sort_order` => `integer`).
       - Relationship: `belongsTo(GalleryAlbum::class)`.
       - Accessor: `image_url`.
   - Database Seeder `GallerySeeder`:
     - Seeds 5 albums:
       1. ITD 2025 (`itd-2025`) - Kicker: "Innovation and Tech Day", Description: "Highlights from the 2025 gathering..."
       2. ITD 2024 (`itd-2024`) - Kicker: "Inaugural Tech Day", Description: "The foundation's inaugural tech festival in Ijero-Ekiti..."
       3. Reading Competition (`reading-competition`) - Kicker: "Literacy Outreach", Description: "Celebrating young readers and literacy mastery..."
       4. Spelling Bee (`spelling-bee`) - Kicker: "Academic Excellence", Description: "Spelling Bee tournaments across secondary schools..."
       5. General (`general`) - Kicker: "Community & Impact", Description: "Classroom moments, mentorship, equipment distribution..."
     - Maps and seeds all 125 pre-optimized WebP photos from `public/front/images/gallery/optimized/` into their respective albums.
     - Registered in `DatabaseSeeder.php`.

2. **Admin Gallery Management (`/admin/gallery`):**
   - Controller `App\Http\Controllers\Admin\GalleryAlbumController`:
     - `index`: List albums with cover thumbnail, title, kicker, total photo count, active toggle, sort order, and actions.
     - `create` & `store`: Album creation with cover upload, validation via `StoreGalleryAlbumRequest`.
     - `edit` & `update`: Album editing, validation via `UpdateGalleryAlbumRequest`.
     - `destroy`: Delete album (photos cascade).
     - `toggleActive`: PATCH route to toggle album visibility.
     - `photos`: `GET /admin/gallery/{album}/photos` - View photo manager for an album with thumbnail grid, add photos form, and delete button for individual photos.
     - `uploadPhotos`: `POST /admin/gallery/{album}/photos` - Upload new photo(s) into an album (saved to `public/uploads/gallery/`).
     - `destroyPhoto`: `DELETE /admin/gallery/{album}/photos/{photo}` - Delete a photo record and file.
   - Admin views: `index.blade.php`, `create.blade.php`, `edit.blade.php`, `photos.blade.php`.
   - Admin navigation: Add "Gallery" tab in `resources/views/components/admin-layout.blade.php`.

3. **Public Gallery Experience (`/gallery`):**
   - Controller `App\Http\Controllers\GalleryController`:
     - `index`: Fetches active albums with photos, renders `resources/views/gallery/index.blade.php`.
     - `album`: Dedicated direct view for a single collection `GET /gallery/{slug}` (`name('gallery.album')`).
   - Views:
     - `resources/views/gallery/index.blade.php`:
       - Hero section with title, Great Vibes accent, description, and prominent featured image highlight.
       - Filter chips bar: "All Photos" + 5 album chips with photo counters.
       - Alpine.js reactive masonry photo grid:
         - Columns layout: 1 col on mobile, 2 cols on tablet, 3 cols on desktop, 4 cols on wide screens.
         - Hover effect: subtle 5% scale, gradient overlay, caption text, album tag.
         - Lazy loading (`loading="lazy"`).
       - Interactive Keyboard Lightbox Modal:
         - Full-screen backdrop.
         - High-res image display with smooth transition.
         - Image counter (`Image X of Y`).
         - Caption and collection title.
         - Navigation controls: Previous button, Next button, Close (X) button.
         - Keyboard event listeners: `Escape` closes, `ArrowLeft` previous, `ArrowRight` next.
         - Body scroll lock when open.
       - Empty state fallback if an album has no photos.
       - Bottom CTA banner: "Want to Partner on Our Next Event? Get Involved &rarr;".

4. **Automated Testing:**
   - `tests/Feature/Admin/GalleryManagementTest.php`: Admin auth, album CRUD, photo upload, toggle active, photo deletion.
   - `tests/Feature/GalleryPublicTest.php`: Public index, album filtering, single album route, 404 for inactive album, empty state fallback.

## Out of scope

- Direct video streaming player (reserved for external YouTube/Vimeo embeds in News if needed).
- Complex client-side image editing or cropping tools.

## Build loop

- `workflow.stepReview`: feature
- `workflow.checkpointCommits`: disabled

## Build steps

- [x] **Step 1: GalleryAlbum & GalleryPhoto Migrations, Models, and Seeder**
  - Create migrations for `gallery_albums` and `gallery_photos` with cascade deletes and indexes.
  - Create models `App\Models\GalleryAlbum` and `App\Models\GalleryPhoto` with relationships, scopes, and helper accessors.
  - Create `database/seeders/GallerySeeder.php` seeding the 5 collections and populating all 125 pre-optimized photos.
  - Register in `DatabaseSeeder.php` and run migrations & seed.
  - **Done when:** `php artisan migrate` succeeds and `GalleryAlbum::count() === 5 && GalleryPhoto::count() === 125`.

- [x] **Step 2: Admin Gallery & Photo Management Panel**
  - Create form requests `StoreGalleryAlbumRequest` and `UpdateGalleryAlbumRequest`.
  - Create `app/Http/Controllers/Admin/GalleryAlbumController.php` with CRUD, toggles, photo viewer, photo upload, and photo deletion.
  - Register admin routes under `admin` prefix in `routes/web.php`.
  - Create admin views: `resources/views/admin/gallery/index.blade.php`, `create.blade.php`, `edit.blade.php`, and `photos.blade.php`.
  - Add "Gallery" tab to `resources/views/components/admin-layout.blade.php`.
  - **Done when:** Authenticated admin can view, create, edit albums and manage photos at `/admin/gallery`.

- [x] **Step 3: Public Gallery Page with Filter Chips, Masonry Grid & Lightbox**
  - Create `app/Http/Controllers/GalleryController.php` with `index` and `album(slug)`.
  - Register public routes in `routes/web.php` (`GET /gallery` and `GET /gallery/{slug}`).
  - Create `resources/views/gallery/index.blade.php` with hero showcase, interactive filter chips, responsive masonry layout, lazy loading, and keyboard-friendly lightbox modal.
  - **Done when:** `/gallery` renders 200 OK, displays filter tabs, loads photos, and lightbox operates smoothly with keyboard controls.

- [x] **Step 4: Automated Feature Tests**
  - Create `tests/Feature/Admin/GalleryManagementTest.php` covering admin album CRUD, photo upload, and photo deletion.
  - Create `tests/Feature/GalleryPublicTest.php` covering public gallery index, filter by collection, empty state fallback, and album route.
  - **Done when:** `php artisan test --filter=Gallery` passes 100%.

- [x] **Step 5: Full Verification & Code Style Pass**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire application suite.
  - **Done when:** 0 Pint violations and 100% of tests pass across the entire suite.

## Files / areas

- `database/migrations/*_create_gallery_albums_table.php` (new)
- `database/migrations/*_create_gallery_photos_table.php` (new)
- `app/Models/GalleryAlbum.php` (new)
- `app/Models/GalleryPhoto.php` (new)
- `database/seeders/GallerySeeder.php` (new)
- `database/seeders/DatabaseSeeder.php` (modified)
- `app/Http/Requests/Admin/StoreGalleryAlbumRequest.php` (new)
- `app/Http/Requests/Admin/UpdateGalleryAlbumRequest.php` (new)
- `app/Http/Controllers/Admin/GalleryAlbumController.php` (new)
- `resources/views/admin/gallery/index.blade.php` (new)
- `resources/views/admin/gallery/create.blade.php` (new)
- `resources/views/admin/gallery/edit.blade.php` (new)
- `resources/views/admin/gallery/photos.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `app/Http/Controllers/GalleryController.php` (new)
- `resources/views/gallery/index.blade.php` (new)
- `routes/web.php` (modified)
- `tests/Feature/Admin/GalleryManagementTest.php` (new)
- `tests/Feature/GalleryPublicTest.php` (new)

## Data / contracts

- `GalleryAlbum` Model:
  - `title` (string, max 255)
  - `slug` (string, unique)
  - `kicker` (string, nullable)
  - `description` (text, nullable)
  - `cover_image` (string, nullable)
  - `sort_order` (integer, default 0)
  - `is_active` (boolean, default true)
- `GalleryPhoto` Model:
  - `gallery_album_id` (foreignId, cascade)
  - `image_path` (string)
  - `caption` (string, nullable)
  - `alt_text` (string, nullable)
  - `sort_order` (integer, default 0)
- Public Routes:
  - `GET /gallery` (`gallery.index`)
  - `GET /gallery/{slug}` (`gallery.album`)
- Admin Routes:
  - `GET /admin/gallery` (`admin.gallery.index`)
  - `GET /admin/gallery/create` (`admin.gallery.create`)
  - `POST /admin/gallery` (`admin.gallery.store`)
  - `GET /admin/gallery/{album}/edit` (`admin.gallery.edit`)
  - `PUT /admin/gallery/{album}` (`admin.gallery.update`)
  - `DELETE /admin/gallery/{album}` (`admin.gallery.destroy`)
  - `PATCH /admin/gallery/{album}/toggle` (`admin.gallery.toggle`)
  - `GET /admin/gallery/{album}/photos` (`admin.gallery.photos`)
  - `POST /admin/gallery/{album}/photos` (`admin.gallery.photos.upload`)
  - `DELETE /admin/gallery/{album}/photos/{photo}` (`admin.gallery.photos.destroy`)

## Testing

- Feature test suite: `tests/Feature/Admin/GalleryManagementTest.php` and `tests/Feature/GalleryPublicTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":12208,"specSha256":"7400ce7a0834adfed2e75a3f44ac8a9b6a59700788f0da53cec335bde4ec30b1","branch":"refs/heads/feature/gallery","head":"1ed7db136f0435adc923ca3f18eaebfd9914331d","baseRef":"refs/heads/main","baseCommit":"1ed7db136f0435adc923ca3f18eaebfd9914331d","sourceTree":"1c90e87160ef21570fad6078e56c5773c55c1396","landing":"local-merge","absentOptional":[]} -->

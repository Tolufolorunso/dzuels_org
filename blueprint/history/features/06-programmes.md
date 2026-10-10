# Feature: Programmes

**From build-plan:** feature 6
**Build attempt:** 1
**Branch:** feature/programmes
**Status:** verified

## Goal

Provide staff with a full admin management interface for Foundation programmes at `/admin/programmes` (CRUD, image uploads, featured flag, active toggle, sort order), and deliver public-facing programmes index (`/programmes`) and individual programme detail pages (`/programmes/{slug}`), replacing the legacy external Google Drive PDF link with a permanent web initiative page for the Ijero-Ekiti Digital Infrastructure and Literacy project.

## Design reference

- Brand styling: Poppins typography, Great Vibes accent script, maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Visual assets: `public/front/images/dzuelsCollage.webp`, `public/front/images/event_1.jpg`, `public/front/images/about_1.webp`, `public/front/images/home_1.webp`.
- Card interaction: 5% image zoom hover on programme cards.

## In scope

1. **Database & Model:**
   - Migration `create_programmes_table`: `title`, `slug`, `summary`, `body`, `cover_image`, `partner_name`, `gallery_album_id`, `is_featured`, `is_active`, `sort_order`, `seo_title`, `seo_description`.
   - Eloquent Model `Programme` with fillable attributes, boolean/integer casting, scopes (`active`, `featured`, `ordered`), and `cover_image_url` accessor.
   - Database seeder `ProgrammeSeeder` seeding the 4 core initiatives:
     - Digital Infrastructure & Literacy in Ijero-Ekiti (`ijero-digital-literacy`)
     - Innovation & Tech Day (`innovation-and-tech-day`)
     - Reading Competition (`reading-competition`)
     - Spelling Bee Contests (`spelling-bee-contests`)

2. **Admin Management Panel (`/admin/programmes`):**
   - Resource controller `App\Http\Controllers\Admin\ProgrammeController`.
   - Form requests `StoreProgrammeRequest` and `UpdateProgrammeRequest` validating inputs and optional cover image upload (stored in `public/uploads/programmes/`).
   - Admin views: `index.blade.php` (table listing thumbnail, title, slug, partner, featured, active, order, actions), `create.blade.php`, `edit.blade.php`.
   - Toggle active status via `PATCH /admin/programmes/{programme}/toggle`.
   - Admin navigation: Added "Programmes" tab in `resources/views/components/admin-layout.blade.php`.

3. **Public Programmes Experience:**
   - Public controller `App\Http\Controllers\ProgrammeController` (`index` and `show`).
   - Public routes:
     - `GET /programmes` (`name('programmes.index')`): Hero banner, featured programme callout, responsive cards grid with image zoom hover, and partner badges.
     - `GET /programmes/{slug}` (`name('programmes.show')`): Breadcrumb, partner badge, detailed narrative, quick facts sidebar (Region, Partner, Beneficiaries), support/volunteer CTA, other active programmes carousel/list, and dynamic SEO metadata.
   - Inactive programme detail returns 404.
   - Empty database on index renders friendly placeholder state.

4. **Integration & Navigation:**
   - Update footer and homepage links to point to `/programmes` and `/programmes/ijero-digital-literacy`.

5. **Automated Testing:**
   - `tests/Feature/Admin/ProgrammeManagementTest.php` testing auth protection, CRUD, image upload, active toggles, validation.
   - `tests/Feature/ProgrammePublicTest.php` testing public index, detail page by slug, 404 for inactive/missing slug, and empty database fallback.

## Out of scope

- Direct gallery photo album linkage until Feature 8 (Gallery).
- Event registration processing (reserved for Feature 7: Events).
- Volunteer application processing (reserved for Feature 10: Volunteer and partner form).

## Build loop

- `workflow.stepReview`: feature (single review packet after all steps complete).
- `workflow.checkpointCommits`: disabled.

## Build steps

- [x] **Step 1: Programme Migration, Model, and Seeder**
  - Create migration `create_programmes_table` with all specified columns and indexes.
  - Create model `app/Models/Programme.php` with casts, scopes (`active`, `featured`, `ordered`), and `cover_image_url` accessor.
  - Create `database/seeders/ProgrammeSeeder.php` seeding the 4 foundational programmes.
  - Register in `DatabaseSeeder.php` and run migration/seeding.
  - **Done when:** `php artisan migrate` succeeds and `Programme::count() >= 4`.

- [x] **Step 2: Admin Programme Management (Controller, Requests, Views)**
  - Create `StoreProgrammeRequest` and `UpdateProgrammeRequest` in `app/Http/Requests/Admin/`.
  - Create `app/Http/Controllers/Admin/ProgrammeController.php` with CRUD and `toggleActive`.
  - Register admin routes in `routes/web.php` under `admin` middleware group.
  - Create admin views `resources/views/admin/programmes/index.blade.php`, `create.blade.php`, and `edit.blade.php`.
  - Add "Programmes" tab in `resources/views/components/admin-layout.blade.php`.
  - **Done when:** Authenticated admin can view, create, edit, toggle, and delete programmes via `/admin/programmes`.

- [x] **Step 3: Public Programmes Pages (Index & Detail)**
  - Create `app/Http/Controllers/ProgrammeController.php` handling `index` and `show(slug)`.
  - Register routes `Route::get('/programmes', ...)->name('programmes.index');` and `Route::get('/programmes/{slug}', ...)->name('programmes.show');` in `routes/web.php`.
  - Create `resources/views/programmes/index.blade.php` with hero, featured project card, grid with hover zoom, and CTA.
  - Create `resources/views/programmes/show.blade.php` with breadcrumb, partner badge, cover image, narrative body, quick facts panel, related programmes, and CTA.
  - **Done when:** `/programmes` and `/programmes/ijero-digital-literacy` render with 200 OK and expected content.

- [x] **Step 4: Automated Feature Tests**
  - Create `tests/Feature/Admin/ProgrammeManagementTest.php` covering admin authentication, index, store, update, toggle, delete, and validation.
  - Create `tests/Feature/ProgrammePublicTest.php` covering public index, detail page, inactive 404, and empty database fallback.
  - **Done when:** `php artisan test --filter=Programme` passes 100%.

- [x] **Step 5: Full Verification & Code Style**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire project suite.
  - **Done when:** Zero Pint violations and all tests pass.

## Files / areas

- `database/migrations/*_create_programmes_table.php` (new)
- `app/Models/Programme.php` (new)
- `database/seeders/ProgrammeSeeder.php` (new)
- `database/seeders/DatabaseSeeder.php` (modified)
- `app/Http/Requests/Admin/StoreProgrammeRequest.php` (new)
- `app/Http/Requests/Admin/UpdateProgrammeRequest.php` (new)
- `app/Http/Controllers/Admin/ProgrammeController.php` (new)
- `resources/views/admin/programmes/index.blade.php` (new)
- `resources/views/admin/programmes/create.blade.php` (new)
- `resources/views/admin/programmes/edit.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `app/Http/Controllers/ProgrammeController.php` (new)
- `resources/views/programmes/index.blade.php` (new)
- `resources/views/programmes/show.blade.php` (new)
- `routes/web.php` (modified)
- `tests/Feature/Admin/ProgrammeManagementTest.php` (new)
- `tests/Feature/ProgrammePublicTest.php` (new)

## Data / contracts

- `Programme` Model:
  - `title` (string, max 255)
  - `slug` (string, unique)
  - `summary` (text)
  - `body` (longText)
  - `cover_image` (string, nullable)
  - `partner_name` (string, nullable)
  - `gallery_album_id` (unsignedBigInteger, nullable)
  - `is_featured` (boolean, default false)
  - `is_active` (boolean, default true)
  - `sort_order` (integer, default 0)
  - `seo_title` (string, nullable)
  - `seo_description` (text, nullable)
- Public Routes:
  - `GET /programmes` (`programmes.index`)
  - `GET /programmes/{slug}` (`programmes.show`)
- Admin Routes:
  - `GET /admin/programmes` (`admin.programmes.index`)
  - `GET /admin/programmes/create` (`admin.programmes.create`)
  - `POST /admin/programmes` (`admin.programmes.store`)
  - `GET /admin/programmes/{programme}/edit` (`admin.programmes.edit`)
  - `PUT /admin/programmes/{programme}` (`admin.programmes.update`)
  - `DELETE /admin/programmes/{programme}` (`admin.programmes.destroy`)
  - `PATCH /admin/programmes/{programme}/toggle` (`admin.programmes.toggle`)

## Testing

- Feature test suite: `tests/Feature/Admin/ProgrammeManagementTest.php` and `tests/Feature/ProgrammePublicTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8873,"specSha256":"db7a0225220c283ccc436195de72fd1063e88454cc1a70e1152c16b8d96c05e9","branch":"refs/heads/feature/programmes","head":"46bdf34de792694b26bb602bd52d9240c61f1c25","baseRef":"refs/heads/main","baseCommit":"46bdf34de792694b26bb602bd52d9240c61f1c25","sourceTree":"04fcb6e3fd3af988415632853c75f6f66b0d02aa","landing":"local-merge","absentOptional":[]} -->

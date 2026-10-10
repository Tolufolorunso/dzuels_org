# Feature: News

**From build-plan:** feature 12
**Build attempt:** 1
**Branch:** feature/news

## Goal

Provide a complete news and editorial publishing system for Dzuels Educational Foundation. Foundation staff can create, edit, upload cover photos, publish, and delete news articles in the admin panel at `/admin/news`. Public visitors can browse published articles at `/news`, read formatted stories with social share buttons (WhatsApp, X, Facebook, copy link) and related posts at `/news/{slug}`, and discover the three most recent articles directly on the homepage.

## In scope

1. **Database Schema & Eloquent Model:**
   - Create `posts` table migration with `title`, unique `slug`, `excerpt`, `body`, `cover_image`, `programme_id` (nullable foreign key), `status` (`draft` or `published`), `published_at` (nullable datetime), `seo_title`, `seo_description`, and timestamps.
   - Implement `App\Models\Post` model with relationship to `Programme`, scopes (`published`, `recent`), casts, and helper accessors (`cover_image_url`, `reading_time_minutes`, `status_badge`).

2. **Admin News Management Suite (`/admin/news`):**
   - Resourceful controller `App\Http\Controllers\Admin\PostController` handling `index`, `create`, `store`, `edit`, `update`, `destroy`, and `togglePublish`.
   - Form request classes `StorePostRequest` and `UpdatePostRequest` with title, slug, excerpt, body, cover image (max 5MB, mimes: jpeg, png, webp, jpg), status, and published_at validation.
   - Admin views in `resources/views/admin/news/` (`index.blade.php`, `create.blade.php`, `edit.blade.php`) styled with the foundation admin theme and flash feedback.
   - Cover photo upload handler storing images in `public/uploads/news/` with unique sanitized filenames.
   - Update admin layout navigation in `resources/views/components/admin-layout.blade.php` to include the News tab.

3. **Public News Views (`/news` & `/news/{slug}`):**
   - `App\Http\Controllers\NewsController` with `index` (paginated published posts) and `show` (single published post with 404 for drafts or nonexistent slugs, plus related posts).
   - Public views `resources/views/news/index.blade.php` and `resources/views/news/show.blade.php` following Dzuels brand aesthetics (maroon, cream, light/dark mode support, responsive typography).
   - Social share bar on article detail page: WhatsApp (`https://api.whatsapp.com/send?text=...`), X / Twitter (`https://twitter.com/intent/tweet?...`), Facebook (`https://www.facebook.com/sharer/sharer.php?...`), and an Alpine.js clipboard copy button with visual feedback tooltip.
   - Related posts module showing up to 3 other published posts.
   - Update public desktop and mobile header navigation (`resources/views/components/header.blade.php`) and footer quick links (`resources/views/components/footer.blade.php`) to include News.

4. **Homepage News Integration:**
   - Update `App\Http\Controllers\HomeController` to fetch the latest 3 published posts (`Post::published()->recent()->take(3)->get()`).
   - Add a dedicated "Latest News & Impact Updates" section in `resources/views/home.blade.php` featuring responsive cards with cover photos, dates, excerpts, and links to `/news/{slug}`, plus a "View All News &rarr;" button to `/news`.

5. **Automated Testing & Quality Gates:**
   - Public feature tests in `tests/Feature/NewsPublicTest.php` verifying index listing, detail view, share buttons, draft invisibility, and homepage integration.
   - Admin feature tests in `tests/Feature/Admin/NewsManagementTest.php` verifying authentication guards, post creation with file upload, validation errors, editing, publishing toggle, and deletion.
   - Pint code formatting check and 100% test pass rate.

## Out of scope

- Multi-author profiles or public user comments (not required; articles are authored by the foundation).
- Newsletter email dispatch on post creation (reserved for Feature 13: Newsletter signup).
- Advanced WYSIWYG rich text editor with embedded media (clean multiline textarea with formatted paragraph/line breaks satisfies MVP requirements safely).
- Automated social media API auto-posting.

## Build loop

- `workflow.stepReview: "feature"` — All build steps are implemented sequentially in a single pass before final review.
- `workflow.checkpointCommits: "disabled"` — No intermediate step commits; `/complete` creates the final feature commit.

## Build steps

1. [x] **Step 1: Database Migration & Eloquent Model**
   - Create migration `database/migrations/2026_10_10_000007_create_posts_table.php`.
   - Implement `App\Models\Post` with fillable attributes, casts, `belongsTo(Programme::class)`, scopes (`scopePublished`, `scopeRecent`), and accessors (`getCoverImageUrlAttribute`, `getReadingTimeMinutesAttribute`).
   - Run `php artisan migrate`.
   - *Done when:* The `posts` table exists in SQLite database and model unit/factory checks pass.

2. [x] **Step 2: Admin News Management Suite**
   - Create form requests `App\Http\Requests\Admin\StorePostRequest` and `App\Http\Requests\Admin\UpdatePostRequest`.
   - Create `App\Http\Controllers\Admin\PostController` for listing, creating, storing, editing, updating, destroying, and toggling publish state for posts.
   - Register admin routes under `Route::prefix('admin')->name('admin.')->middleware(['admin'])` in `routes/web.php`.
   - Create Blade views `resources/views/admin/news/index.blade.php`, `resources/views/admin/news/create.blade.php`, and `resources/views/admin/news/edit.blade.php`.
   - Add News navigation tab in `resources/views/components/admin-layout.blade.php`.
   - *Done when:* An authenticated admin can create, view, edit, toggle publish, and delete posts at `/admin/news` with cover image upload.

3. [x] **Step 3: Public News Index & Article Show Views**
   - Create `App\Http\Controllers\NewsController` with `index` and `show` actions.
   - Register public routes `/news` and `/news/{slug}` in `routes/web.php`.
   - Create Blade views `resources/views/news/index.blade.php` and `resources/views/news/show.blade.php` with social share buttons (WhatsApp, X, Facebook, Alpine copy link) and related posts.
   - Add News navigation link in `resources/views/components/header.blade.php` and `resources/views/components/footer.blade.php`.
   - *Done when:* Public visitors can view paginated published posts at `/news` and view full articles with functional share links at `/news/{slug}`, while draft posts return 404.

4. [x] **Step 4: Homepage Latest 3 News Integration**
   - Update `App\Http\Controllers\HomeController` to pass `$latestPosts = Post::published()->recent()->take(3)->get()` to `home.blade.php`.
   - Add the "Latest News & Impact Stories" section in `resources/views/home.blade.php` with 3-column card grid and "View All News" link.
   - *Done when:* The homepage displays up to 3 latest published news posts with cover image, title, date, excerpt, and link to article.

5. [x] **Step 5: Automated Testing & Verification**
   - Implement `tests/Feature/NewsPublicTest.php` covering public index, article show, 404 for drafts, and homepage section.
   - Implement `tests/Feature/Admin/NewsManagementTest.php` covering admin authentication, post creation with cover upload, edit, toggle publish, and delete.
   - Run `php vendor/bin/pint --test` and `php artisan test`.
   - *Done when:* All news tests pass and Pint reports 0 style violations.

## Files / areas

- `database/migrations/2026_10_10_000007_create_posts_table.php` (new)
- `app/Models/Post.php` (new)
- `app/Http/Requests/Admin/StorePostRequest.php` (new)
- `app/Http/Requests/Admin/UpdatePostRequest.php` (new)
- `app/Http/Controllers/Admin/PostController.php` (new)
- `app/Http/Controllers/NewsController.php` (new)
- `app/Http/Controllers/HomeController.php` (modified)
- `routes/web.php` (modified)
- `resources/views/admin/news/index.blade.php` (new)
- `resources/views/admin/news/create.blade.php` (new)
- `resources/views/admin/news/edit.blade.php` (new)
- `resources/views/news/index.blade.php` (new)
- `resources/views/news/show.blade.php` (new)
- `resources/views/home.blade.php` (modified)
- `resources/views/components/header.blade.php` (modified)
- `resources/views/components/footer.blade.php` (modified)
- `resources/views/components/admin-layout.blade.php` (modified)
- `tests/Feature/NewsPublicTest.php` (new)
- `tests/Feature/Admin/NewsManagementTest.php` (new)

## Data / contracts

- **`posts` table:**
  - `id`: unsigned big integer, primary key, auto incrementing
  - `title`: string (255)
  - `slug`: string (255), unique index
  - `excerpt`: text, nullable
  - `body`: longText
  - `cover_image`: string (255), nullable
  - `programme_id`: unsigned big integer, nullable, foreign key references `programmes(id)` on delete set null
  - `status`: string (30), default `'draft'`, values: `'draft'`, `'published'`
  - `published_at`: datetime, nullable
  - `seo_title`: string (255), nullable
  - `seo_description`: text, nullable
  - `created_at`, `updated_at`: timestamps
- **Social Sharing URLs:**
  - WhatsApp: `https://api.whatsapp.com/send?text={title}%20{url}`
  - X / Twitter: `https://twitter.com/intent/tweet?text={title}&url={url}`
  - Facebook: `https://www.facebook.com/sharer/sharer.php?u={url}`
  - Copy Link: Alpine.js `navigator.clipboard.writeText(window.location.href)`

## Testing

- Unit/Feature testing using Pest/PHPUnit via `php artisan test`.
- Cover guest access rejection to admin routes, validation errors, file uploads, publication scopes, 404 responses for draft posts on public routes, and public index rendering.
- Lint and code formatting verification using `php vendor/bin/pint --test`.

## Notes for the AI

- Follow existing codebase patterns strictly (see `Admin\ProgrammeController`, `Admin\EventController`, `Programme`, and `Event` models).
- Cover image upload must support PNG, JPG, JPEG, and WEBP formats and store in `public/uploads/news/`.
- Handle draft status strictly: never expose draft posts on public `/news` or `/news/{slug}`.
- Preserve human attribution in all Git operations (no AI trailers).


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10075,"specSha256":"66d8d3dfcc44595214e0d1dc3acebb8c6d45ed75d81a1513eb4be51ee7f9b1a8","branch":"refs/heads/feature/news","head":"eb10b4bc93d536322bd530ce380d027efbcd14e2","baseRef":"refs/heads/main","baseCommit":"eb10b4bc93d536322bd530ce380d027efbcd14e2","sourceTree":"e12384290adeb87a4618fe051fdb67b19f211c1f","landing":"local-merge","absentOptional":[]} -->

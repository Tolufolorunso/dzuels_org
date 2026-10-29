# Feature 40: Pages CMS overhaul: dynamic public content parity, visibility toggles & section reordering

**From build-plan:** feature 40
**Build attempt:** 1
**Status:** verified
**Branch:** feature/pages-cms-dynamic-content-parity-visibility-toggles-section-reordering

## Goal

Overhaul the Pages CMS to establish complete, bidirectional parity between what admins edit in `/admin/pages/{page}/edit` and what visitors see on live public pages (`/about`, `/donate`, `/programmes`, `/contact`, `/get-involved`, `/home`). Add an `is_active` boolean column to `page_sections` with admin 1-click visibility toggles so sections can be hidden/shown on demand; add move up/down sort controls to reorder sections; and synchronize `PageSectionSeeder` and public Blade templates with the living copy so that editing headlines, descriptions, and media in the CMS updates the live site cleanly with fallback integrity.

## Design reference

- Pages CMS Controller: `app/Http/Controllers/Admin/PageController.php`
- Pages CMS View: `resources/views/admin/pages/edit.blade.php`
- Pages Index: `resources/views/admin/pages/index.blade.php`
- Model: `app/Models/PageSection.php`
- Seeder: `database/seeders/PageSectionSeeder.php`
- Public Views:
  - `resources/views/about.blade.php`
  - `resources/views/donate/index.blade.php`
  - `resources/views/programmes/index.blade.php`
  - `resources/views/contact/index.blade.php`
  - `resources/views/get-involved/index.blade.php`
  - `resources/views/home.blade.php`
- Brand Design Tokens: Maroon `#6A0406`, Crimson `#B4070A`, Cream `#F9DEC9`, Mint `#56E39F`

## In scope

1. **Database Schema & Eloquent Model (`page_sections`):**
   - Create migration `add_is_active_to_page_sections_table` adding `is_active` (boolean, default true) after `sort_order`.
   - Update `App\Models\PageSection`:
     - Add `'is_active'` to `$fillable`.
     - Add `'is_active' => 'boolean'` to `casts()`.
     - Add `scopeActive(Builder $query): Builder`.
     - Ensure `scopeForPage` orders by `sort_order ASC, id ASC`.

2. **CMS Controller Endpoints & Reordering / Toggle Actions (`PageController.php`):**
   - `PUT /admin/pages/{page}/sections/{section}`: Validate and update `is_active`, `sort_order`, headline, subheadline, body_text, button_text, button_url, image_path, meta_data.
   - `POST /admin/pages/{page}/sections/{section}/toggle-status`: 1-click toggle for `is_active` with feedback redirect.
   - `POST /admin/pages/{page}/sections/{section}/move-up`: Swap `sort_order` with the previous section on the same page.
   - `POST /admin/pages/{page}/sections/{section}/move-down`: Swap `sort_order` with the next section on the same page.
   - Secure all endpoints behind `auth` and `can:manage-cms`.

3. **Admin Visual Section Editor Polish (`resources/views/admin/pages/edit.blade.php`):**
   - Visual status pill: Emerald "Active (Visible on Live Page)" vs Stone "Inactive (Hidden from Live Page)".
   - 1-click "Toggle Visibility" action button directly in the section card header.
   - Reordering toolbar: "Move Up" (▲) and "Move Down" (▼) buttons on each section card, disabled appropriately for first/last items.
   - `is_active` checkbox toggle inside the form.
   - Preserve existing `<x-admin.ai-button>` Gemini assistant and `<x-admin.media-picker>` image picker.

4. **Public Template Content Parity & Section Visibility Guard:**
   - Update `about.blade.php`, `donate/index.blade.php`, `programmes/index.blade.php`, `contact/index.blade.php`, and `get-involved/index.blade.php`:
     - Bind major sections (`hero`, `story`, `approach`, `famoyinka_library`, `giving_channels`, `where_your_gift_goes`, `faq`, `pathways`, `benefits`, `office`, `form`, `map`) to dynamic `$sections` values.
     - Wrap sections with `@if ($sec?->is_active ?? true)` so marking a section inactive in the CMS hides it on the live site without breaking page layout.
     - Fallback to verified default text if a section record is absent.

5. **Seeder Parity & Database Sync (`PageSectionSeeder.php`):**
   - Synchronize `PageSectionSeeder` to seed every section with the living copy the public sees.
   - Purge old Flutterwave references from `donate` sections.
   - Clean up duplicate `contact` section entries in the seeder array.
   - Ensure `'is_active' => true` is seeded on all sections.

6. **Automated Testing & Verification:**
   - Create `tests/Feature/Admin/PagesCmsOverhaulTest.php` testing:
     - Admin section listing and edit access.
     - Toggling `is_active` hides/shows the section on public views.
     - Move Up / Move Down reorders sections and updates `sort_order`.
     - Updating copy in CMS updates public page output.
     - Role authorization prevents unauthorized users from altering CMS sections.
   - Run full test suite (`php artisan test`) and Pint checks (`php vendor/bin/pint --test`).

## Out of scope

- Creating new database tables (only adding `is_active` column to `page_sections`).
- Replacing public layout header or footer components.
- Modifying form submission intake endpoints for volunteer/partner applications.

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all implementation steps).
- `workflow.checkpointCommits: "disabled"`.
- Verify with `php vendor/bin/pint --test` and `php artisan test`.

## Build steps

- [x] 1. **Database migration & model updates** - create migration adding `is_active` (boolean, default true) to `page_sections` table; update `PageSection` model `$fillable`, `casts()`, and active query scopes. Done when running migration succeeds and `PageSection::first()->is_active` returns boolean true.
- [x] 2. **Admin CMS controller actions & reordering routes** - add `toggle-status`, `move-up`, and `move-down` routes in `routes/web.php`; implement `toggleStatus`, `moveUp`, and `moveDown` methods in `PageController.php` with proper sort order swapping and flash feedback. Done when calling toggle and move endpoints updates database sort_order and is_active cleanly.
- [x] 3. **Admin Pages visual editor UI upgrade** - update `resources/views/admin/pages/edit.blade.php` to render active/inactive visibility badges, quick toggle buttons, move up / move down controls, and `is_active` form controls. Done when visiting `/admin/pages/{page}/edit` renders the visibility controls and reordering buttons.
- [x] 4. **Seeder parity & public template synchronization** - update `PageSectionSeeder.php` with living copy for all 6 pages (`about`, `donate`, `programmes`, `contact`, `get_involved`, `home`); update public Blade templates to dynamically render section headlines, subheadlines, and copy, and hide sections when `is_active === false`. Done when seeding populates live content and turning off a section in CMS hides it on the public page.
- [x] 5. **Automated testing and code formatting** - create `tests/Feature/Admin/PagesCmsOverhaulTest.php`, run full test suite, format with Pint, and compile assets with `npm run build`. Done when all tests pass with 0 failures and Pint reports zero code style violations.

## Files / areas

- `database/migrations/xxxx_xx_xx_xxxxxx_add_is_active_to_page_sections_table.php` - adds `is_active` column
- `app/Models/PageSection.php` - updated fillable, casts, active scope
- `app/Http/Controllers/Admin/PageController.php` - toggleStatus, moveUp, moveDown, updated validation
- `routes/web.php` - CMS reorder and toggle routes
- `resources/views/admin/pages/edit.blade.php` - visibility toggle and move up/down controls
- `database/seeders/PageSectionSeeder.php` - living copy parity for all 6 pages
- `resources/views/about.blade.php` - dynamic section parity and is_active check
- `resources/views/donate/index.blade.php` - dynamic section parity and is_active check
- `resources/views/programmes/index.blade.php` - dynamic section parity and is_active check
- `resources/views/contact/index.blade.php` - dynamic section parity and is_active check
- `resources/views/get-involved/index.blade.php` - dynamic section parity and is_active check
- `resources/views/home.blade.php` - dynamic section parity and is_active check
- `tests/Feature/Admin/PagesCmsOverhaulTest.php` - feature verification suite

## Data / contracts

- `page_sections` table:
  - `id` (bigint PK)
  - `page_key` (string, indexed)
  - `section_key` (string, indexed)
  - `headline` (string, nullable)
  - `subheadline` (string, nullable)
  - `body_text` (text, nullable)
  - `image_path` (string, nullable)
  - `button_text` (string, nullable)
  - `button_url` (string, nullable)
  - `meta_data` (json, nullable)
  - `sort_order` (integer, default 0)
  - `is_active` (boolean, default true)
  - `timestamps`
- Reordering:
  - `POST /admin/pages/{page}/sections/{section}/move-up`
  - `POST /admin/pages/{page}/sections/{section}/move-down`
- Visibility:
  - `POST /admin/pages/{page}/sections/{section}/toggle-status`

## Testing

- Feature tests via PHPUnit: `php artisan test --filter=PagesCmsOverhaulTest`
- Regression suite: `php artisan test`
- Code formatting & standards: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain zero AI attribution in git commits or pull requests per `AGENTS.md`.
- Adhere to proportional engineering: reliable Move Up / Move Down buttons with sort_order swapping, no heavy external drag-and-drop JS libraries.


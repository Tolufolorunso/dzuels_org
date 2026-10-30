# Feature 42: Pages CMS modular component architecture & universal inline text styling

**From build-plan:** feature 42
**Build attempt:** 1
**Branch:** feature/pages-cms-modular-component-architecture-and-universal-inline-text-styling
**Status:** completed

## Goal

Overhaul the Pages CMS architecture around actual page components rather than generic form fields, using `/about` as the reference archetype (Hero, Story with 3 stats & floating badge, 6 Approach Pillars, Famoyinka Library with 4 feature bullets & buttons, Governance section intros, and CTA banner). Enable universal inline text styling in Pages CMS using lightweight, secure styling tags (e.g. `[accent]quality education[/accent]`, `[crimson]...[/crimson]`, `[mint]...[/mint]`, `[bold]...[/bold]`, etc.) with 1-click helper buttons and live preview in the CMS editor, rendering safely across all public templates without XSS risks.

## In scope

1. **Universal Inline Text Styling Parser (`CmsTextFormatter`)**:
   - Create `app/Support/CmsTextFormatter.php` providing `format(?string $text): HtmlString`.
   - Supported tags:
     - `[accent]...[/accent]`: theme accent color (`text-maroon dark:text-mint font-semibold`)
     - `[crimson]...[/crimson]`: bright crimson red (`text-crimson font-bold`)
     - `[mint]...[/mint]`: energetic green (`text-mint font-bold`)
     - `[cream]...[/cream]`: warm cream (`text-[var(--accent-cream)] font-bold`)
     - `[maroon]...[/maroon]`: deep maroon (`text-maroon dark:text-crimson font-bold`)
     - `[gold]...[/gold]`: amber gold (`text-amber-500 font-bold`)
     - `[bold]...[/bold]`: bold emphasis (`<strong class="font-bold text-[var(--heading)]">`)
     - `[italic]...[/italic]`: italic styling (`<em class="italic">`)
     - `[underline]...[/underline]`: decorated underline (`<span class="underline decoration-crimson/50 underline-offset-4">`)
     - `[badge]...[/badge]`: pill badge highlight (`<span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-maroon/10 text-maroon dark:bg-mint/10 dark:text-mint">`)
   - Safe parsing: HTML-escapes user input before tag expansion to prevent XSS. Preserves line breaks with `<br>`.
   - Register global helper `format_cms_text(?string $text)` and Blade directive `@cms($text)`.
   - Build `<x-admin.inline-styler>` component with 1-click helper buttons and real-time live preview.

2. **About Page Modular Architecture & Dynamic Component Parity**:
   - Model structured component sub-items in `page_sections.meta_data` JSON:
     - `story`: 3 Milestone highlights (`milestones`: array of `value` and `label`), floating badge (`badge`: `kicker`, `title`).
     - `approach`: 6 Approach Pillars (`pillars`: array of `title`, `description`, `icon`).
     - `famoyinka_library`: 4 Feature bullets (`bullets`: array of strings), location badge (`badge`: `location`, `title`).
   - Update `database/seeders/PageSectionSeeder.php` with structured seed data for `/about`.
   - Update `resources/views/about.blade.php` to render dynamic component pillars, milestones, and bullets with graceful fallbacks.

3. **Admin Pages CMS Editor Overhaul (`/admin/pages/edit.blade.php`)**:
   - Dedicated modular sub-forms for structured sections on `/about` (Milestones & badge, 6 Approach Pillars, Library bullets).
   - 1-click inline styling helper toolbar attached to all Headline, Subheadline, and Body textarea inputs.
   - Update `app/Http/Controllers/Admin/PageController.php` validation rules for `meta_data.milestones`, `meta_data.badge`, `meta_data.bullets`, and `meta_data.pillars`.

4. **Public Template Integration**:
   - Update `resources/views/home.blade.php`, `resources/views/about.blade.php`, `resources/views/donate/index.blade.php`, `resources/views/get-involved/index.blade.php`, `resources/views/programmes/index.blade.php`, and `resources/views/contact/index.blade.php` to pipe text through `format_cms_text()`.

5. **Automated Test Coverage**:
   - Write comprehensive tests in `tests/Feature/PagesCmsModularAndInlineStylingTest.php`.

## Out of scope

- Event show page UI overhaul (scheduled for Feature 43).
- Media Hub EXIF metadata, pagination, and zoom controls (scheduled for Feature 44).

## Build loop

Follow `blueprint/config.json`:
- `workflow.stepReview`: `"feature"` (single review packet at completion of implementation).
- `workflow.checkpointCommits`: `"disabled"` (work committed upon review approval).

## Build steps

- [x] 1. **Universal inline text styling parser & Blade directives**
  - Implement `app/Support/CmsTextFormatter.php` with safe HTML escaping and BBCode tag replacement.
  - Register `format_cms_text()` and `@cms()` in `AppServiceProvider.php`.
  - Create `<x-admin.inline-styler>` component with 1-click insertion buttons and live preview.
  - *Done when:* `format_cms_text("Addressing poverty through [accent]quality education[/accent]")` returns an `HtmlString` containing `<span class="text-maroon dark:text-mint font-semibold">quality education</span>`, and scripts like `<script>` are properly escaped.

- [x] 2. **About page modular component data structure & Blade parity**
  - Enrich `PageSectionSeeder.php` with `meta_data` for `/about` (`story.milestones`, `story.badge`, `approach.pillars`, `famoyinka_library.bullets`).
  - Update `resources/views/about.blade.php` to dynamically loop through `meta_data.pillars`, `meta_data.milestones`, and `meta_data.bullets`.
  - Re-run `php artisan db:seed --class=PageSectionSeeder`.
  - *Done when:* `/about` renders the 6 approach pillars and 3 milestones dynamically from the database.

- [x] 3. **Admin Pages CMS editor modular UI & inline styling toolbar**
  - Update `resources/views/admin/pages/edit.blade.php` with modular component sub-forms for `/about` (milestones, approach pillars, library bullets) alongside existing `/home` modular sub-forms.
  - Embed `<x-admin.inline-styler>` toolbar on headline, subheadline, and body inputs.
  - Update `app/Http/Controllers/Admin/PageController.php` to accept and persist `meta_data.milestones`, `meta_data.badge`, `meta_data.bullets`, and `meta_data.pillars`.
  - *Done when:* Admin can edit each pillar and milestone individually in `/admin/pages/about` and save successfully.

- [x] 4. **Public templates inline styling integration**
  - Update public Blade views to wrap dynamic section headlines and bodies with `format_cms_text()`.
  - Verify styling on the homepage and about page (e.g. styling "quality education" in a sentence).
  - *Done when:* Text containing `[accent]...[/accent]`, `[crimson]...[/crimson]`, etc., renders with distinct Tailwind colors on the live public site.

- [x] 5. **Automated test suite & verification**
  - Create `tests/Feature/PagesCmsModularAndInlineStylingTest.php`.
  - Run `php artisan test` and `php vendor/bin/pint --test`.
  - Verify assets with `npm run build`.
  - *Done when:* All 387+ tests pass and Pint passes with 0 violations.

## Files / areas

- `app/Support/CmsTextFormatter.php`
- `app/Providers/AppServiceProvider.php`
- `resources/views/components/admin/inline-styler.blade.php`
- `app/Http/Controllers/Admin/PageController.php`
- `database/seeders/PageSectionSeeder.php`
- `resources/views/admin/pages/edit.blade.php`
- `resources/views/about.blade.php`
- `resources/views/home.blade.php`
- `resources/views/donate/index.blade.php`
- `resources/views/get-involved/index.blade.php`
- `resources/views/programmes/index.blade.php`
- `resources/views/contact/index.blade.php`
- `tests/Feature/PagesCmsModularAndInlineStylingTest.php`
- `cpanel_changed_files.txt`

## Verification Evidence

- **Logic & Unit Tests**: `php artisan test --filter=PagesCmsModularAndInlineStylingTest` (5 tests, 43 assertions passed).
- **Pages CMS Integration**: `php artisan test --filter=PagesCms` (12 tests, 103 assertions passed).
- **Full Suite**: `php artisan test` (392 tests passed).
- **Code Style**: `php vendor/bin/pint --test` (0 violations).
- **Vite Build**: `npm run build` completed cleanly in 2.18s.
- **Live HTTP Verification**: Verified HTTP 200 responses and rendered elements on `/` and `/about`.


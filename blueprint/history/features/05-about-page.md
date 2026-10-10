# Feature: About page

**From build-plan:** feature 5
**Build attempt:** 1
**Branch:** feature/about-page
**Status:** verified

## Goal

Provide a public `/about` page that tells Dzuels Educational Foundation's 10-year story (since 2015), details its holistic child-welfare approach across 6 key pillars, showcases the Board of Trustees, Management team, and Advisory Board (with domain badges and LinkedIn profile links), displays partner organizations, and provides clear calls to action to volunteer and donate.

## Design reference

- Static prototype: `scratch/about.html`
- Images: `public/front/images/about_1.webp` (with `about_1.jpg` fallback), `public/front/images/about.jpg`, and leadership photos in `public/front/images/`
- Brand design system: Poppins headings/body, Great Vibes accent script, maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), with dark mode tokens (`var(--surface)`, `var(--text-strong)`, `var(--border)`)

## In scope

1. **About Route & Controller:**
   - Dedicated `AboutController` (invokable) bound to `GET /about` with route name `about`.
   - Safe data retrieval for active trustees, management, advisors, and partners with error resiliency.

2. **Public About Blade View (`resources/views/about.blade.php`):**
   - **Hero & Breadcrumbs:** Warm styled banner with breadcrumbs (`Home / About Us`), subtitle, and main heading "Transforming Lives Page by Page".
   - **Our Story & 10-Year Journey:** Narrative highlighting the foundation's establishment on 22nd June 2015, registration, and 10+ years of educational and literacy intervention in Nigeria, accompanied by `about_1.webp` / `about_1.jpg` and a floating "10+ Years of Impact" badge.
   - **Holistic Child Approach:** 6 structured cards explaining the integrated model:
     1. Tuition & School Fees Support
     2. Educational Materials & Uniforms
     3. Equipping Non-Profit Schools & Labs
     4. Mentorship & Transformational Leadership
     5. Family Nutrition & Widow Support
     6. Healthcare & Medical Intervention
   - **Board of Trustees:** Grid displaying active trustee members (`TeamMember::byGroup('trustee')`) with portrait photo, name, founder highlight for Bolaji Agbelusi, and trustee designation.
   - **Management Team:** Grid displaying active management members (`TeamMember::byGroup('management')`) with photo, name, country role (e.g. Country Manager USA, Country Manager Nigeria), and location badge.
   - **Advisory Board:** Responsive card grid displaying active advisors (`TeamMember::byGroup('advisor')`) with name, professional domain pill (e.g. Education Services, Medical Services, ICT Services, Corporate Legal Services), title snippet, and accessible external LinkedIn profile link button (`target="_blank" rel="noopener noreferrer"` with `aria-label`).
   - **Partners Showcase:** Dynamic partner logo showcase (`Partner::active()`) with `logo_url`, accessible alt text, and optional website link.
   - **Call to Action (CTA):** Section encouraging visitors to get involved as volunteers (`/get-involved` or `/contact`) or donate (`/donate`).

3. **Fallback & Empty States:**
   - Graceful fallback text if no members or partners are seeded/active in any group.
   - Default avatar silhouette fallback when a team member has no photo.

4. **Automated Testing:**
   - Feature tests in `tests/Feature/AboutPageTest.php` proving page loads (200 OK), displays story text, renders trustees, management, advisors with LinkedIn links, renders partners, and handles empty database states safely.

## Out of scope

- Direct front-end editing (already provided via `/admin/team` and `/admin/partners` in Feature 4).
- Volunteer application form processing (reserved for Feature 10: Volunteer and partner form).
- Program detail routing (reserved for Feature 6: Programmes).

## Build loop

- `workflow.stepReview`: feature (single review packet after all steps complete).
- `workflow.checkpointCommits`: disabled.

## Build steps

- [x] **Step 1: AboutController and Route**
  - Create `app/Http/Controllers/AboutController.php` querying `TeamMember` (by group: trustee, management, advisor) and `Partner`.
  - Register `Route::get('/about', AboutController::class)->name('about');` in `routes/web.php`.
  - **Done when:** `GET /about` route resolves and returns a view without errors.

- [x] **Step 2: About Page Blade Template**
  - Create `resources/views/about.blade.php` extending `<x-layout>` with full responsive sections: Hero/Breadcrumb, Story with image & 10-year badge, 6-pillar Holistic Model, Board of Trustees, Management, Advisory Board with LinkedIn links, Partners, and CTA.
  - Use Tailwind CSS v4 design tokens and semantic HTML with dark mode classes.
  - **Done when:** `resources/views/about.blade.php` is complete with all required content, styling, and responsive layout.

- [x] **Step 3: Feature Tests**
  - Create `tests/Feature/AboutPageTest.php` testing:
    - Successful page render (200 OK) with story and approach sections.
    - Presence of Board of Trustees with photos and names.
    - Presence of Management team with country roles.
    - Presence of Advisory Board members with domain pills and LinkedIn URLs.
    - Presence of partner logos with links.
    - Resilient rendering when database tables are empty.
  - **Done when:** `php artisan test --filter=AboutPageTest` passes 100%.

- [x] **Step 4: Quality & Formatting Verification**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire project test suite.
  - **Done when:** Pint passes with 0 violations and all unit/feature tests pass.

## Files / areas

- `app/Http/Controllers/AboutController.php` (new)
- `routes/web.php` (modified)
- `resources/views/about.blade.php` (new)
- `tests/Feature/AboutPageTest.php` (new)

## Data / contracts

- `TeamMember` model:
  - `TeamMember::active()->byGroup('trustee')->ordered()->get()`
  - `TeamMember::active()->byGroup('management')->ordered()->get()`
  - `TeamMember::active()->byGroup('advisor')->ordered()->get()`
- `Partner` model:
  - `Partner::active()->ordered()->get()`
- Public Route:
  - `GET /about` (`name('about')`)

## Testing

- Unit/Feature test command: `php artisan test`
- Code formatting check: `php vendor/bin/pint --test`
- Test file: `tests/Feature/AboutPageTest.php`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6616,"specSha256":"4b52e0c5a32f5a94ba963a86f61673407148a05b1c12995f8312653a1d8c5701","branch":"refs/heads/feature/about-page","head":"48515d166b18a9de49d4b9cc4685db9f8df96298","baseRef":"refs/heads/main","baseCommit":"48515d166b18a9de49d4b9cc4685db9f8df96298","sourceTree":"ec0b8b3a9d8f8774a0647eab6ad1bb9218080e25","landing":"local-merge","absentOptional":[]} -->

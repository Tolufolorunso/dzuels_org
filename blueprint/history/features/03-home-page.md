# Feature: Home page

**From build-plan:** feature 3
**Build attempt:** 1
**Branch:** feature/home-page

## Goal

Build the complete, mobile-responsive, high-trust public home page for Dzuels Educational Foundation (`/`), incorporating all verified brand assets, dynamic impact metrics from `ImpactStatsService`, organizational mission and vision, the 7-pillar DRNICER values band, holistic causes checklist, featured Ijero-Ekiti digital literacy project, upcoming Ijero Tech Day event teaser, partner logos strip, and primary calls to action (Donate and Volunteer).

## Design reference

- Prototype reference: `C:\Users\oreofe\.gemini\antigravity-ide\brain\97d4515b-2483-4b65-b99c-062080df6196\scratch\prototypes_backup\home.html`
- Shared brand tokens in `resources/css/app.css` (`--color-maroon`, `--color-crimson`, `--color-cream`, `--color-mint`, `--color-bg-alt`, etc.)
- Existing verified image assets in `public/front/images/` (`home_1.webp`, `about_1.webp`, `cause.webp`, `cause.png`, `dzuelsCollage.webp`, `ascend.jpg`, `SAA.jpg`, `helpmeetaneed.png`, `intinternational.png`)

## In scope

1. **Route & Controller Wiring:**
   - Dedicated `app/Http/Controllers/HomeController.php` that injects `ImpactStatsService` and passes verified impact statistics (`ict_students`, `literacy_students`, `schools_supported`, `source`) to the home view.
   - Update `routes/web.php` route `/` to dispatch to `HomeController`.

2. **Full Home Page Blade Template (`resources/views/home.blade.php`):**
   - **Hero Section:** High-impact dark maroon gradient banner over `home_1.webp`, Great Vibes calligraphy accent ("quality education"), "Dzuels Educational Foundation" pill badge, and Donate / Volunteer CTAs.
   - **Floating Impact Statistics Band:** Card overlapping hero with 3 verified metrics (165 ICT students, 591 literacy students, 15 schools supported) with visual progress meters (mint, maroon, cream).
   - **Who We Are Section:** Two-column grid with `about_1.webp` inside an offset cream decorative border, 10-year track record copy, and link to `/about`.
   - **Vision & Mission Cards:** Dual structured cards highlighting the organization's vision and mission with icons.
   - **DRNICER Values Band:** Prominent full-width band showcasing the 7 foundational principles: Discipline, Responsibility, No-Excuse, Integrity, Compassion, Excellence, Respect.
   - **Our Causes Section:** Two-column layout featuring `cause.webp` and the 8-pillar holistic child intervention checklist with checkmark icons.
   - **Featured Project Card:** Showcase card for the Ijero-Ekiti Digital Infrastructure & Literacy project with `dzuelsCollage.webp`, description, and link to programmes/volunteer.
   - **Upcoming Event Callout:** High-contrast gradient teaser for the Ijero Tech Day & 10th Anniversary Celebration with direct link to register/view event.
   - **Partners Strip:** Showcase strip for partner organizations (ASCEND, Solape Adesuyi Associates, Help Meet A Need, Int International) with hover transition.
   - **Final Call to Action:** High-conversion donation and volunteer appeal banner.

3. **Light/Dark Mode Styling:**
   - Full compatibility with the existing flash-free theme toggle and layout tokens.
   - Accessible contrast in both light (default) and dark mode for all cards, text, and badges.

4. **Automated Feature Testing:**
   - `tests/Feature/HomepageTest.php` testing successful HTTP 200 response, rendering of all 10 core sections, dynamic display of impact numbers from `Setting` / `ImpactStatsService`, and verification of key CTA links.

## Out of scope

- Dynamic admin CRUD management for team members and partner logos (reserved for Feature 4: Team and partners).
- Full programmes catalog and single programme detail routes (reserved for Feature 6: Programmes).
- Event registration form processing and inbox storage (reserved for Feature 7: Events & Ijero Tech Day).
- Newsletter submission backend processing (reserved for Feature 13: Newsletter signup).

## Build loop

Configured with `workflow.stepReview: "feature"` and `workflow.checkpointCommits: "disabled"`. Implementation steps 1 through 4 will execute in sequence, ending with a comprehensive feature-level review packet before `/complete`.

## Build steps

- [x] 1. **Controller & Route Setup** - Create `app/Http/Controllers/HomeController.php` that receives `ImpactStatsService`, retrieves the resolved statistics array, and renders `home` view with `$stats`. Update `routes/web.php` to point `Route::get('/', HomeController::class)->name('home')`.
  - _Done when:_ `php artisan test` passes and `curl http://localhost:8000` (or `GET /`) invokes `HomeController` without errors.

- [x] 2. **Complete Home Page Blade Template** - Replace the placeholder in `resources/views/home.blade.php` with the 10 production sections using `<x-layout>`, brand CSS variables, responsive Tailwind v4 classes, and pre-optimized WebP assets.
  - _Done when:_ Visiting `/` displays the hero, impact stat meters, who we are, vision/mission, DRNICER values band, causes checklist, featured project card, event callout, partner logos, and closing CTA in both light and dark modes.

- [x] 3. **Automated Feature Tests** - Create `tests/Feature/HomepageTest.php` with test cases verifying HTTP 200, visibility of all key section headings, dynamic rendering of impact stats seeded from database settings, and correct CTA destinations (`/donate`, `/get-involved`, `/about`).
  - _Done when:_ `php artisan test --filter=HomepageTest` passes cleanly with all assertions verified.

- [x] 4. **Code Quality and Asset Verification** - Run Pint formatting check and Vite asset build.
  - _Done when:_ `php vendor/bin/pint --test` reports zero violations, `npm run build` succeeds, and full `php artisan test` suite remains 100% green.

## Files / areas

- `app/Http/Controllers/HomeController.php` (new)
- `routes/web.php` (modified)
- `resources/views/home.blade.php` (modified)
- `tests/Feature/HomepageTest.php` (new)

## Data / contracts

- `ImpactStatsService::getStats()` contract:
  ```php
  array{
      ict_students: int,
      literacy_students: int,
      schools_supported: int,
      source: string
  }
  ```
- View contract in `resources/views/home.blade.php`:
  Receives `$stats` array; displays `$stats['ict_students']` (default 165), `$stats['literacy_students']` (default 591), and `$stats['schools_supported']` (default 15).

## Testing

- Automated: `php artisan test --filter=HomepageTest` testing status, content assertions, and dynamic statistics rendering.
- Code style: `php vendor/bin/pint --test`.
- Build verification: `npm run build`.

## Notes for the AI

- Reuse `<x-layout>` which already provides `<x-utility-bar>`, `<x-header>`, `<x-footer>`, and the flash-free `<x-theme-toggle>`.
- Use existing WebP images in `public/front/images/` (`home_1.webp`, `about_1.webp`, `cause.webp`, `dzuelsCollage.webp`, etc.).
- Ensure script accent font class `font-accent` or `script-accent` matches the typography defined in `resources/css/app.css`.
- Ensure buttons use clean accessible classes and standard URL helpers (`url('/donate')`, `url('/about')`, `url('/get-involved')`, `url('/events')`).


<!-- blueprint:completion {"schemaVersion":2,"specBytes":7188,"specSha256":"4ee5486843abf3b5c328f5a3eb594203ac775e188ff18b8b9a218db652a8e551","branch":"refs/heads/feature/home-page","head":"896d3c4872d52147967010d7ffd784754ffcb4b5","baseRef":"refs/heads/main","baseCommit":"896d3c4872d52147967010d7ffd784754ffcb4b5","sourceTree":"1659fe2b7abe59b39146d458e65a6a6ac75bd43f","landing":"local-merge","absentOptional":[]} -->

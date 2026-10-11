# Feature: Accessibility and launch readiness

**From build-plan:** feature 17
**Build attempt:** 1
**Branch:** feature/accessibility-and-launch-readiness
**Status:** verified

## Goal

Achieve comprehensive WCAG 2.2 AA accessibility across the Dzuels Educational Foundation platform in both light and dark themes—including high-contrast text ratios, universal visible keyboard focus styling, skip-to-content links, ARIA labels, semantic landmarks, and descriptive image alts; optimize images and performance for Core Web Vitals; and provide an automated launch-check command and production deployment runbook covering domain pointing, SSL, and Google Search Console submission.

## In scope

1. **WCAG 2.2 AA Color Contrast & Focus Indicators**:
   - Audit and enforce contrast ratios (minimum 4.5:1 for standard text, 3:1 for large text and UI components) across light and dark modes in `resources/css/app.css`.
   - Implement universal visible `:focus-visible` styling (`outline: 2px solid var(--primary); outline-offset: 2px;`) across all interactive elements (buttons, links, inputs, selects, toggles) so keyboard navigation is unmistakable.

2. **Keyboard Navigation & Landmarks (Skip to Content)**:
   - Add skip-to-content link (`<a href="#main-content" ...>Skip to main content</a>`) on the public layout (`resources/views/components/layout.blade.php`) and admin layout.
   - Ensure `<main id="main-content">` landmark exists across all public templates and layouts.
   - Verify logical `Tab` navigation order, keyboard accessibility of mobile navigation toggle, theme switcher, and gallery lightbox (`Escape` to close, keyboard navigation).

3. **Form Accessibility & ARIA Announcements**:
   - Audit all public form views (`contact/index.blade.php`, `get-involved/index.blade.php`, `events/show.blade.php`):
     - Ensure each `<input>`, `<textarea>`, and `<select>` has an associated `<label for="...">` with matching `id`.
     - Link validation error messages and assistive hints using `aria-describedby`.
     - Mark mandatory inputs with `aria-required="true"`.
     - Ensure decorative icons and SVGs have `aria-hidden="true"`, and interactive toggles include accurate `aria-expanded` and `aria-label` attributes.

4. **Responsive Images & Core Web Vitals Optimization**:
   - Audit all images across views: verify descriptive `alt` tags and proper aspect-ratio preservation to prevent Cumulative Layout Shift (CLS).
   - Ensure above-the-fold hero images use eager loading / high priority (`fetchpriority="high"` / `loading="eager"`), while below-the-fold images use `loading="lazy"` and `decoding="async"`.

5. **Automated Launch Readiness Command & Documentation**:
   - Create Artisan command `app:launch-check` (`App\Console\Commands\LaunchCheckCommand`) verifying:
     - Environment key & configuration readiness (`APP_KEY`, production settings).
     - Database and migrations status, and presence of essential seed data (settings, active event, programmes, team members).
     - Storage directory permissions and public storage link.
     - HTTP health of all core routes (`/`, `/about`, `/programmes`, `/events`, `/gallery`, `/donate`, `/get-involved`, `/contact`, `/privacy-policy`, `/sitemap.xml`, `/robots.txt`).
   - Create production deployment runbook `docs/launch-checklist.md` with:
     - Domain DNS configuration (`dzuelsfoundation.org` A/CNAME records).
     - SSL/TLS certificate setup (Let's Encrypt / Certbot / Nginx / Apache).
     - Production caching commands (`config:cache`, `route:cache`, `view:cache`).
     - Google Search Console submission and sitemap indexation steps.

6. **Automated Test Suite**:
   - Create `tests/Feature/AccessibilityAndLaunchReadinessTest.php` testing skip links, landmark regions, form label associations, image alts, and `app:launch-check` command execution.

## Out of scope

- Deferred non-MVP roadmap items (annual report PDF uploads, donor receipts, USD/GBP donation processors).
- Third-party paid accessibility widget subscriptions.
- Paid automated SEO monitoring subscriptions.

## Build loop

Following project policy (`workflow.stepReview: "feature"`, `workflow.checkpointCommits: "disabled"`):
- Implement steps 1 through 5 in sequence.
- Run narrow checks during implementation.
- Run full Verify suite (`php artisan test` and `php vendor/bin/pint --test`) after all steps pass.

## Build steps

- [x] 1. **WCAG 2.2 AA Contrast & Focus Indicators**: Update `resources/css/app.css` with universal accessible `:focus-visible` ring styling and verify light/dark mode color tokens for strict 4.5:1+ contrast compliance.
       - *Done when:* Keyboard tab focus displays clear, high-contrast focus rings on all interactive elements in both light and dark modes, and CSS tokens adhere to WCAG 2.2 AA standards.

- [x] 2. **Skip-to-Content Link & Structural Landmarks**: Add accessible skip link in `resources/views/components/layout.blade.php` and assign `id="main-content"` to the `<main>` tag in public and admin layouts.
       - *Done when:* Pressing Tab upon page load reveals the "Skip to main content" link and hitting Enter moves focus directly to `#main-content`.

- [x] 3. **Form Accessibility & ARIA Attributes**: Audit forms in `contact/index.blade.php`, `get-involved/index.blade.php`, and `events/show.blade.php`; add `aria-required="true"`, `aria-describedby` for validation errors, and `aria-hidden="true"` on decorative icons.
       - *Done when:* Screen readers and automated accessibility tests confirm all form inputs have associated labels, ARIA error references, and proper toggle state announcements.

- [x] 4. **Image Accessibility & Performance Tuning**: Update images across public views with explicit `alt` attributes, `decoding="async"`, and optimized loading priorities (`fetchpriority="high"` for hero, `loading="lazy"` for below-the-fold assets).
       - *Done when:* All public images pass accessibility alt-text audits and hero images load with high priority without CLS.

- [x] 5. **Launch-Check Artisan Command & Production Runbook**: Implement `app:launch-check` Artisan command and create `docs/launch-checklist.md` documenting domain pointing, SSL setup, production caching, and Google Search Console submission; verify with `php artisan test` and `php vendor/bin/pint --test`.
       - *Done when:* `php artisan app:launch-check` passes with full green status, documentation is created, and automated tests pass with 100% green rate.

## Files / areas

- `resources/css/app.css` - accessible focus rings and color tokens
- `resources/views/components/layout.blade.php` - skip-to-content link and `#main-content` landmark
- `resources/views/components/admin-layout.blade.php` - admin landmark and skip link
- `resources/views/contact/index.blade.php` - form accessibility & ARIA
- `resources/views/get-involved/index.blade.php` - form accessibility & ARIA
- `resources/views/events/show.blade.php` - form accessibility & ARIA
- `resources/views/home.blade.php` - image performance and alt checks
- `app/Console/Commands/LaunchCheckCommand.php` - automated launch check CLI command
- `docs/launch-checklist.md` - production launch runbook
- `tests/Feature/AccessibilityAndLaunchReadinessTest.php` - feature test suite

## Data / contracts

- Command: `php artisan app:launch-check` returns exit code 0 when all pre-launch checks pass.
- Skip link: target `#main-content`, visible on `:focus-visible`.
- Form contracts: all inputs have `id` matching `<label for="...">`.

## Testing

- Feature test: `php artisan test --filter=AccessibilityAndLaunchReadinessTest`
- Launch check command: `php artisan app:launch-check`
- Full test suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand color palette (`maroon`, `cream`, `crimson`, `mint`, `surface`, `border`) while ensuring WCAG 2.2 AA contrast.
- Ensure all interactive elements remain fully usable on mobile touch and desktop keyboard navigation.
- Strictly adhere to human Git attribution `tolufolorunso <tolufolorunso@yahoo.com>`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8067,"specSha256":"1f7dc63f0179be2759b9e9094ccbe295e4ba5035c467c57786ab60472c815efa","branch":"refs/heads/feature/accessibility-and-launch-readiness","head":"2ae1c90d07df51b6f0d7a12f5996562d82abfece","baseRef":"refs/heads/main","baseCommit":"2ae1c90d07df51b6f0d7a12f5996562d82abfece","sourceTree":"b28a92ebbdd2eff77f4ec07617d32d05e9333f77","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Skip-to-Content & Keyboard Navigation**:
   - Navigate to `http://localhost:8000`.
   - Press Tab immediately upon page load: verify the "Skip to main content" banner appears in the top-left viewport.
   - Press Enter: confirm keyboard focus moves directly to the `<main id="main-content">` container.
   - Tab through the navigation bar and interactive buttons in both light and dark themes: confirm prominent, high-contrast focus rings (`:focus-visible`).

2. **Verify Form Accessibility & ARIA Announcements**:
   - Navigate to `http://localhost:8000/contact`: inspect name, email, subject, and message fields to verify matching `<label for="...">` tags, `aria-required="true"`, and error message bindings.
   - Navigate to `http://localhost:8000/get-involved`: verify volunteer and partner form inputs for explicit labels and ARIA descriptions.
   - Navigate to `http://localhost:8000/events/ijero-tech-day-2026`: verify attendee registration form accessibility.

3. **Verify Core Web Vitals & Image Optimization**:
   - On the homepage (`/`), inspect the hero banner image: confirm `loading="eager" fetchpriority="high" decoding="async"`.
   - Inspect below-the-fold images (About, Cause, Featured Project, Partner logos): confirm `loading="lazy" decoding="async"` with descriptive alt text.

4. **Run Automated Launch Diagnostic Command**:
   - In terminal, execute:
     ```bash
     php artisan app:launch-check
     ```
   - Confirm all 3 suites pass (Environment & Core Config, Database & Essential Foundation Data, Critical Route Architecture) with 0 failures and 0 warnings.

5. **Review Production Deployment Runbook**:
   - Open `docs/launch-checklist.md` and review the step-by-step instructions for DNS records (`dzuelsfoundation.org`), Let's Encrypt / Certbot SSL setup, production caching, and Google Search Console submission.

# Feature: Public layout and brand theme

**From build-plan:** feature 1
**Build attempt:** 1
**Branch:** feature/public-layout-and-brand-theme
**Status:** verified

## Goal

Establish the public layout foundation and brand design system for Dzuels Educational Foundation. Every public page will share a unified, mobile-responsive layout consisting of a top utility bar, sticky header with navigation and Donate CTA, an accessible flash-free light/dark mode theme toggle, and a rich 4-column footer.

## Design reference

- Design mockup prototype: [`prototypes/home.html`](file:///c:/Users/oreofe/Desktop/dzuels/web%20apps/dzuels_org/prototypes/home.html)
- Design tokens: [`prototypes/theme.css`](file:///c:/Users/oreofe/Desktop/dzuels/web%20apps/dzuels_org/prototypes/theme.css)
- Brand logo: `public/front/images/logo.png`
- Primary colors: Maroon (`#6A0406`), Crimson (`#B4070A`), Warm Cream (`#F9DEC9`), Soft Mint (`#56E39F`), Dark Background (`#140607`), Dark Surface (`#241012`), Utility & Footer Background (`#2B0203`)
- Fonts: Google Fonts Poppins (body & headings) and Great Vibes (calligraphic accent script)

## In scope

- Port brand tokens and typography into Tailwind CSS v4 in `resources/css/app.css` (`@theme` definitions and theme CSS variables).
- Font loading for Poppins (weights 300, 400, 500, 600, 700) and Great Vibes in `resources/views/components/layout.blade.php`.
- Blade base layout component `resources/views/components/layout.blade.php` with dynamic title, meta tags, and slot.
- Flash-free dark mode architecture: inline head script reading `localStorage.getItem('dzuels-theme')` immediately applying or removing the `dark` class on `document.documentElement` before render.
- Utility bar component `resources/views/components/utility-bar.blade.php` with organization contact numbers, official email, address teaser, and social links.
- Sticky header component `resources/views/components/header.blade.php` with logo, desktop navigation menu, Donate action button, theme toggle, and accessible mobile drawer navigation.
- Accessible theme toggle component `resources/views/components/theme-toggle.blade.php` with sun/moon iconography and click handler.
- 4-column footer component `resources/views/components/footer.blade.php` with organization bio, address in Ijero-Ekiti, quick links, programmes/giving links, newsletter signup preview, and copyright/legal links.
- Update `resources/views/home.blade.php` to render within the new `<x-layout>`.
- Automated PHPUnit tests in `tests/Feature/LayoutTest.php` asserting layout elements, navigation links, and theme toggle anti-flicker script.

## Out of scope

- Filament administrative panel at `/admin` (Feature 2).
- Dynamic database-stored site settings or impact counters (Feature 2).
- Homepage content sections (Hero, Impact stats, Mission/Vision, DRNICER values, Causes, Featured Project - Feature 3).
- Backend newsletter subscription submission processing (Feature 13 - footer form renders as client-safe preview).
- Dynamic database records for programmes, events, team members, or gallery albums (Features 4, 6, 7, 8).

## Build loop

- `workflow.stepReview: "feature"` (review after all implementation steps complete).
- `workflow.checkpointCommits: "disabled"`.

## Build steps

1. [x] **Step 1: Design tokens & typography in Tailwind v4**
   - Port brand color tokens (`maroon`, `crimson`, `cream`, `mint`, `dark-bg`, `dark-surface`, `utility-bg`) and typography (`font-sans: Poppins`, `font-script: Great Vibes`) into `resources/css/app.css`.
   - Configure font imports and Tailwind v4 theme variables to support light and dark modes cleanly.
   - *Done when:* `npm run build` compiles without errors and asset files are generated with the new design tokens.

2. [x] **Step 2: Base Blade layout & flash-free theme engine**
   - Create `resources/views/components/layout.blade.php` with semantic HTML5 document structure, `@vite` directives, and `<x-utility-bar>`, `<x-header>`, `{{ $slot }}`, and `<x-footer>`.
   - Add inline `<script>` in `<head>` that checks `localStorage.getItem('dzuels-theme')` (defaulting to light) and syncs `document.documentElement.classList` before stylesheets render, eliminating any theme flicker.
   - Create `resources/views/components/theme-toggle.blade.php` with SVG sun/moon icons and event listener that toggles the `dark` class and updates `localStorage`.
   - *Done when:* Opening the layout in browser displays light mode by default, clicking theme toggle toggles dark mode, and page reload maintains dark mode without flashing light mode.

3. [x] **Step 3: Top utility bar**
   - Create `resources/views/components/utility-bar.blade.php` displaying phone numbers (`+234 803 388 9576`), email (`info@dzuelsfoundation.org`), location (`Ijero-Ekiti, Ekiti State, Nigeria`), and social links.
   - Style using rich dark background (`#2B0203`), subtle borders, and cream hover accents.
   - *Done when:* The utility bar renders responsive contact information and social links at the top of the viewport.

4. [x] **Step 4: Sticky header & responsive navigation**
   - Create `resources/views/components/header.blade.php` containing the Dzuels logo (`public/front/images/logo.png`), navigation links (Home, About, Programmes, Events, Gallery, Get Involved, Contact), Donate CTA button (`/donate`), theme toggle, and mobile menu hamburger button.
   - Implement responsive mobile menu drawer that opens and closes cleanly with keyboard accessibility and ARIA attributes (`aria-expanded`, `aria-label`).
   - *Done when:* On desktop (>=1024px), full horizontal navigation is visible; on mobile (<1024px), hamburger button reveals full navigation links and Donate CTA.

5. [x] **Step 5: 4-column brand footer**
   - Create `resources/views/components/footer.blade.php` with:
     - Foundation bio, logo, and physical address in Ijero-Ekiti.
     - Quick Links column (About Our Story, Board of Trustees, Management & Advisors, Photo Gallery, Contact Us).
     - Programmes & Giving column (Ijero Digital Literacy, Innovation & Tech Day, Reading Competition, Spelling Bee, Online Donation, Bank Transfer Details).
     - Stay Connected newsletter column with input and submit button preview.
     - Bottom bar with copyright year, Privacy Policy link, and Terms link.
   - *Done when:* Footer renders with dark brand styling (`#2B0203` in light mode, `#0E0405` in dark mode) and all links and columns are laid out correctly across mobile and desktop viewports.

6. [x] **Step 6: Homepage integration & automated tests**
   - Update `resources/views/home.blade.php` to extend `<x-layout>`.
   - Create `tests/Feature/LayoutTest.php` to verify:
     - Root URL `/` returns HTTP 200.
     - Layout renders the header, navigation links, Donate CTA, and footer.
     - Anti-flicker theme script is present in the `<head>`.
     - Contact information (`+234 803 388 9576`, `info@dzuelsfoundation.org`) is present.
   - Update `tests/Feature/HomepageTest.php` so all existing tests pass.
   - Run `php artisan test` and `npm run build`.
   - *Done when:* `php artisan test` passes with 100% green tests and `npm run build` completes successfully.

## Files / areas

- `resources/css/app.css` - Tailwind CSS v4 design tokens and theme variables.
- `resources/views/components/layout.blade.php` - Base HTML layout shell with head meta and anti-flicker script.
- `resources/views/components/utility-bar.blade.php` - Top contact and social bar.
- `resources/views/components/header.blade.php` - Main navigation header with mobile drawer.
- `resources/views/components/theme-toggle.blade.php` - Light/dark mode toggle button with SVG icons.
- `resources/views/components/footer.blade.php` - 4-column footer with address, links, and copyright.
- `resources/views/home.blade.php` - Home view wrapped in `<x-layout>`.
- `tests/Feature/LayoutTest.php` - Feature tests for layout structure, navigation links, and theme engine.
- `tests/Feature/HomepageTest.php` - Updated test suite for homepage rendering.

## Data / contracts

- Theme storage: Client `localStorage` key `dzuels-theme` (`"light"` or `"dark"`).
- Root class: `dark` class added to `document.documentElement` when dark mode is active.
- Core public navigation routes:
  - `/` (Home)
  - `/about` (About Us)
  - `/programmes` (Programmes)
  - `/events` (Events & Ijero Tech Day)
  - `/gallery` (Photo Gallery)
  - `/get-involved` (Volunteer & Partner)
  - `/contact` (Contact Us)
  - `/donate` (Donate)
  - `/privacy-policy` (Privacy Policy)

## Testing

- Logic & structure tests: `php artisan test` (executes `tests/Feature/LayoutTest.php` and `tests/Feature/HomepageTest.php`).
- Build verification: `npm run build` (compiles Tailwind v4 CSS and JS assets).

## Notes for the AI

- Follow proportional engineering: keep components native Blade without unnecessary overhead.
- Ensure the anti-flicker script is an inline blocking script inside `<head>` placed before stylesheets so the user never sees a white flash when in dark mode.
- Use local WebP images and SVG icons instead of raster icons.
- Mobile menu toggle must be clean and functional without heavy dependencies.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":9152,"specSha256":"a9302060b17833aeff460ae2b215b5f918f73b0dca8508c0454e6a708a9aca10","branch":"refs/heads/feature/public-layout-and-brand-theme","head":"2580de294bd7f9f3cae1a358ea94b222b07a9ce5","baseRef":"refs/heads/main","baseCommit":"2580de294bd7f9f3cae1a358ea94b222b07a9ce5","sourceTree":"96ee5712419834cde5f94b962cc35ce411349832","landing":"local-merge","absentOptional":[]} -->

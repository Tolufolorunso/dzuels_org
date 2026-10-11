# Feature: Site refinement and ITD 2026 overhaul

**From build-plan:** feature 14  
**Build attempt:** 1  
**Branch:** feature/site-refinement-and-itd-2026-overhaul  
**Status:** verified

## Goal

Refine the Dzuels web application by making News and Newsletter modules dormant in the UI, eliminating all horizontal scrolling across all screens (320px–1024px) with a strict mobile-responsiveness pass, elevating `/admin` into a professional and usable administrative dashboard, and updating the flagship live event to **Ijero Tech-Day 2026 (ITD 2026)** on October 17, 2026 based on the foundation's event checklist, structured for reusable annual editions.

## In scope

1. **Dormant News & Newsletter UI**:
   - Remove News links from public header navigation (desktop & mobile drawer) and footer quick links.
   - Remove footer newsletter subscription form; replace with a clean community touchpoint / contact card.
   - Remove or bypass latest news teaser section on the homepage.
   - Hide News and Newsletter Subscribers tabs from the admin dashboard navigation.
   - Preserve existing models, migrations, controllers, and database tables intact for future re-activation.
2. **Mobile & Tablet Responsiveness Pass (Zero Horizontal Scroll)**:
   - Enforce viewport containment on root layout (`max-w-full`, `overflow-x: hidden`).
   - Audit and adjust components across all screen sizes (320px to 1024px): utility bar, navigation header, impact stats strip, causes check-grid, values band, gallery filter chips and masonry grid, donation cards, forms, and modals.
   - Replace any rigid fixed-pixel sizing with percentage units, `max-w-full`, and flexible auto-wrapping layouts.
3. **Professional Admin Dashboard Redesign**:
   - Replace the crowded 10-tab horizontal navbar with a sleek, modern, vertical sidebar layout with dark brand maroon accents, crisp SVG iconography, and responsive mobile drawer toggle.
   - Redesign administrative top bar with section breadcrumbs, admin profile badge, live site shortcut, and logout button.
   - Upgrade the admin dashboard overview with KPI metric cards (Registered Attendees, Active Programmes, Team Members, Inquiries, Volunteer Applications) and quick action shortcuts.
4. **ITD 2026 Event Overhaul & Annual Reusability**:
   - Seed and configure **Ijero Tech-Day 2026 (ITD 2026)**:
     - Date: Saturday, October 17, 2026 (08:00 AM)
     - Venue: AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)
     - Theme: *"Learning Today, Creating Tomorrow"*
     - Objective: Empowering 300 children with AI tools (10 batches of 30, inside AI session + garden Image Booth and Tech Quiz stations), plus 22 cohort graduate certificates.
   - Update homepage event teaser and public events pages (`/events`, `/events/ijero-tech-day-2026`).
   - Update event registration form and modal to support attendee categories (Student, Teacher, Guest, Cohort Graduate) and batch allocation.
   - Maintain a dynamic, extensible structure so subsequent annual events (ITD 2027+) can be created and managed via admin.
5. **Testing & Standards**:
   - Align existing test assertions with dormant news/newsletter state and ITD 2026 slug.
   - Add feature tests for dormant UI state, responsive markup integrity, ITD 2026 registrations, and admin dashboard KPI rendering.
   - 100% test pass rate with Laravel Pint compliance.

## Out of scope

- Deleting database tables or migrations for news posts or newsletter subscribers (they remain dormant in the database).
- Online payment processing on-server (Flutterwave hosted link and GTBank transfers remain).
- Multi-tenancy or public user accounts.

## Build loop

- `workflow.stepReview: "feature"`: Implementation proceeds through all steps with a single review packet upon completion.
- `workflow.checkpointCommits: "disabled"`: No intermediate checkpoint commits.
- Final completion and landing handled via `/complete`.

## Build steps

1. [x] **Step 1: Make News and Newsletter dormant in the UI**
   - Update `resources/views/components/header.blade.php`: remove News link from desktop nav and mobile drawer.
   - Update `resources/views/components/footer.blade.php`: remove News link; replace the Newsletter form column with a clean "Get in Touch" / connect touchpoint linking to `/contact` and `/get-involved`.
   - Update `resources/views/home.blade.php` and `HomeController.php`: hide the latest news section.
   - Update `resources/views/components/admin-layout.blade.php`: remove News and Subscribers tabs.
   - *Done when*: No News links or Newsletter subscription forms appear on the public header, footer, homepage, or admin navigation, while routes and database schemas remain intact.

2. [x] **Step 2: Mobile and tablet responsiveness overhaul (Zero horizontal scrolling)**
   - Update `resources/views/components/layout.blade.php` and `resources/css/app.css` to enforce `max-width: 100%` and `overflow-x: hidden` on root containers.
   - Audit and update all public templates (`home.blade.php`, `about.blade.php`, `programmes/`, `events/`, `gallery.blade.php`, `donate.blade.php`, `get-involved.blade.php`, `contact.blade.php`) ensuring all containers use `max-w-full`, `%` widths, flex wrapping, and overflow containment.
   - Verify layout stability on 320px, 375px, 414px, and 768px viewports.
   - *Done when*: Every public page renders completely within the viewport without horizontal scrollbars across all device breakpoints down to 320px.

3. [x] **Step 3: Redesign the administrative layout and dashboard**
   - Redesign `resources/views/components/admin-layout.blade.php`:
     - Implement a modern vertical sidebar layout with brand maroon palette, crisp SVG icons, clean active indicators, and mobile off-canvas drawer toggle.
     - Add top bar with section header, user status badge, live site shortcut, and sign-out button.
     - Organize navigation into logical groups: Overview, Content Management (Programmes, Events, Gallery), Organization (Team, Partners, Site Settings), and Inboxes (Messages, Volunteer Inquiries).
   - Upgrade `resources/views/admin/dashboard.blade.php` with high-level KPI cards (Event Registrations, Active Programmes, Team Members, Inquiries, Messages) and quick links.
   - *Done when*: Navigating to `/admin` displays a modern, professional SaaS-style dashboard layout with responsive mobile support, clean card aesthetics, and no visual clutter.

4. [x] **Step 4: Overhaul live event to ITD 2026 with reusable annual architecture**
   - Update `database/seeders/EventSeeder.php` with the live ITD 2026 data:
     - Slug: `ijero-tech-day-2026`
     - Title: `Ijero Tech-Day 2026 (ITD 2026)`
     - Date: October 17, 2026 (08:00 AM)
     - Venue: `AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)`
     - Theme: *"Learning Today, Creating Tomorrow"*
     - Objective: Empowering 300 children with AI tools across 10 batches of 30, inside interactive practical + garden stations (AI Image Creation Booth & Tech Quiz), plus 22 cohort graduate certificates.
   - Re-run seeder or update event record via migration/seeder.
   - Update homepage event teaser card in `resources/views/home.blade.php` to link to `/events/ijero-tech-day-2026`.
   - Update `resources/views/events/show.blade.php` and registration modal to feature the ITD 2026 theme, schedule, and attendee categories (Student, Teacher, Guest, Cohort Graduate).
   - Ensure the event model and admin controls seamlessly allow adding 2027 and future editions.
   - *Done when*: Homepage teaser and event show page display the October 17, 2026 date, theme, and schedule, and attendee registrations submit and store successfully.

5. [x] **Step 5: Verification, test suite alignment, and Pint formatting**
   - Update existing tests referencing legacy event slugs or header news links.
   - Add new tests in `tests/Feature/SiteRefinementAndItdTest.php` and `tests/Feature/Admin/DashboardRedesignTest.php`.
   - Run `php artisan test` to confirm 100% test pass rate.
   - Run `php vendor/bin/pint` to enforce pristine coding standards.
   - *Done when*: Full test suite passes 100% with 0 failures and Pint reports 0 violations.

## Files / areas

- `resources/views/components/layout.blade.php`
- `resources/views/components/header.blade.php`
- `resources/views/components/footer.blade.php`
- `resources/views/components/utility-bar.blade.php`
- `resources/views/components/admin-layout.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/home.blade.php`
- `resources/views/events/index.blade.php`
- `resources/views/events/show.blade.php`
- `resources/views/components/event-registration-modal.blade.php`
- `resources/css/app.css`
- `database/seeders/EventSeeder.php`
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/EventController.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `tests/Feature/SiteRefinementAndItdTest.php`
- `tests/Feature/Admin/DashboardRedesignTest.php`

## Data / contracts

- `events`:
  - `slug`: `ijero-tech-day-2026`
  - `title`: `Ijero Tech-Day 2026 (ITD 2026)`
  - `event_date`: `2026-10-17 08:00:00`
  - `venue`: `AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)`
  - `theme`: `Learning Today, Creating Tomorrow`
- `event_registrations`:
  - `event_id`: foreign key
  - `full_name`, `email`, `phone`
  - `attendee_type`: `student`, `teacher`, `guest`, `cohort_graduate`
  - `notes`: school name or batch detail

## Testing

- Feature tests for dormant UI state:
  - Header & footer do not contain News or Newsletter form.
  - Admin sidebar excludes News and Subscribers tabs.
- Feature tests for ITD 2026:
  - Event detail page renders correct date (Oct 17, 2026), venue, and theme.
  - Event registration form submits successfully and creates an `EventRegistration` record.
- Feature tests for Admin Dashboard:
  - Authenticated admin accessing `/admin` sees the redesigned dashboard and KPI cards.
- Viewport / Responsiveness verification:
  - CSS contains strict root overflow containment and no fixed overflow-causing wrappers.

## Notes for the AI

- Do not drop or migrate away the `posts` or `newsletter_subscribers` tables. They are intentionally kept dormant so they can be re-enabled whenever foundation leadership is ready.
- Maintain human Git attribution (`tolufolorunso <tolufolorunso@yahoo.com>`). Never insert AI attribution trailers.
- Follow Tailwind v4 and Blade component patterns.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10422,"specSha256":"6df95fa4bfcdd91a5e0c3febea2c06abb356674157be719674f22bc2b0849468","branch":"refs/heads/feature/site-refinement-and-itd-2026-overhaul","head":"1fe0422ad2fce823428d0d493a7418f18d6c35c8","baseRef":"refs/heads/main","baseCommit":"1fe0422ad2fce823428d0d493a7418f18d6c35c8","sourceTree":"022595e48db98e509a99f38dda1236d40d6de973","landing":"local-merge","absentOptional":[]} -->

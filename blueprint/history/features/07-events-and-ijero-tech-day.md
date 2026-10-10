# Feature: Events & Ijero Tech Day

**From build-plan:** feature 7
**Build attempt:** 1
**Branch:** feature/events-ijero-tech-day
**Status:** verified

## Goal

Provide staff with a full administrative events management suite at `/admin/events` (CRUD, banner image upload, active/publish toggle, registration toggle, schedule editor, and attendee registrations list), and deliver a public-facing events catalog (`/events`) and dedicated event detail page (`/events/{slug}`) starting with the *Ijero Tech Day & 10th Anniversary Celebration*, featuring schedule highlights, attendee registration with duplicate prevention and flash success feedback, and event highlights.

## Design reference

- Brand styling: Poppins typography, Great Vibes accent script, maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Visual assets: `public/front/images/techday.webp`, `public/front/images/gallery/` photos, `public/front/images/dzuelsCollage.webp`.
- Card and detail layout: Rich event hero with date badge, schedule timeline with time and speaker chips, clean two-column registration form with clear validation feedback.

## In scope

1. **Database & Models:**
   - Migration `create_events_table`:
     - Columns: `id`, `title`, `slug`, `summary`, `description`, `event_date`, `end_date`, `venue`, `banner_image`, `schedule` (json), `is_registration_open` (boolean, default true), `is_active` (boolean, default true), `sort_order` (integer, default 0), `seo_title`, `seo_description`, timestamps.
   - Migration `create_event_registrations_table`:
     - Columns: `id`, `event_id` (foreignId cascade), `full_name`, `email`, `phone`, `attendee_type`, `notes`, timestamps.
     - Unique index on `[event_id, email]` for duplicate prevention.
   - Eloquent Model `App\Models\Event`:
     - Fillable attributes, casts (`datetime` for dates, `array` for schedule, `boolean` for flags, `integer` for sort_order).
     - Relationships: `hasMany(EventRegistration::class)`.
     - Scopes: `active()`, `upcoming()`, `ordered()`.
     - Accessors: `banner_image_url`, `is_past`, `formatted_date`.
   - Eloquent Model `App\Models\EventRegistration`:
     - Fillable attributes, relationship `belongsTo(Event::class)`.
   - Database Seeder `EventSeeder`:
     - Seeds the milestone event: "Ijero Tech Day & 10th Anniversary Celebration" (`ijero-tech-day-10th-anniversary`), scheduled date, venue, banner image (`front/images/techday.webp`), 4-part schedule items, registration open, active.
     - Registered in `DatabaseSeeder.php`.

2. **Admin Events Management (`/admin/events`):**
   - Resource controller `App\Http\Controllers\Admin\EventController`:
     - `index`: List events with banner thumbnail, title, date, venue, registration toggle, active toggle, attendee registration count badge with quick link to registrations view, action buttons.
     - `create` & `store`: Create form with image upload (stored in `public/uploads/events/`), JSON schedule items, validation via `StoreEventRequest`.
     - `edit` & `update`: Edit form with image replacement, schedule management, validation via `UpdateEventRequest`.
     - `destroy`: Delete event and associated uploaded banner.
     - `toggleActive`: PATCH route to toggle event visibility.
     - `toggleRegistration`: PATCH route to open or close attendee registrations.
     - `registrations`: `GET /admin/events/{event}/registrations` listing registered attendees (name, email, phone, type, notes, date) with delete action.
   - Admin views: `index.blade.php`, `create.blade.php`, `edit.blade.php`, `registrations.blade.php`.
   - Admin Layout: Add "Events" tab to `resources/views/components/admin-layout.blade.php`.

3. **Public Events Experience:**
   - Public controller `App\Http\Controllers\EventController` (`index`, `show`, `register`).
   - Routes:
     - `GET /events` (`name('events.index')`): Hero section, upcoming events grid, past events section (if any), empty state fallback.
     - `GET /events/{slug}` (`name('events.show')`): Breadcrumb, banner image, date/venue badges, narrative description, schedule timeline, attendee registration form (or closed notice), highlights gallery preview, and share CTA.
     - `POST /events/{slug}/register` (`name('events.register')`): Validates `full_name`, `email`, `phone`, `attendee_type`, `notes`, checks if registration is open, prevents duplicate registrations for same event/email, creates `EventRegistration`, and redirects back with flash `success`.
   - Inactive events return 404 for public visitors.

4. **Integration & Navigation:**
   - Header: ensure `Events` menu item is active on `/events*`.
   - Homepage: connect upcoming event teaser CTA directly to `/events/ijero-tech-day-10th-anniversary`.

5. **Automated Testing:**
   - `tests/Feature/Admin/EventManagementTest.php`: Admin auth protection, CRUD, image upload, toggle active/registration, view registrations.
   - `tests/Feature/EventPublicTest.php`: Public index, event detail page by slug, registration submission, duplicate registration prevention, closed registration handling, inactive 404, empty database fallback.

## Out of scope

- Paid ticketing or payment gateways (all Dzuels Foundation community events are 100% free).
- External SMS gateway messaging (registrations are recorded in the admin panel for staff coordination).
- Direct multi-day room breakout reservations.

## Build loop

- `workflow.stepReview`: feature
- `workflow.checkpointCommits`: disabled

## Build steps

- [x] **Step 1: Event and EventRegistration Migrations, Models, and Seeder**
  - Create migrations for `events` and `event_registrations` tables with appropriate indexes and cascade deletes.
  - Create `app/Models/Event.php` and `app/Models/EventRegistration.php` with relations, scopes, and helper accessors.
  - Create `database/seeders/EventSeeder.php` with the Ijero Tech Day milestone event.
  - Register in `DatabaseSeeder.php` and run migrations & seed.
  - **Done when:** `php artisan migrate` succeeds and `Event::count() >= 1`.

- [x] **Step 2: Admin Event Management & Registrations View**
  - Create `StoreEventRequest` and `UpdateEventRequest` in `app/Http/Requests/Admin/`.
  - Create `app/Http/Controllers/Admin/EventController.php` with CRUD, `toggleActive`, `toggleRegistration`, and `registrations`.
  - Register admin routes under `admin` prefix in `routes/web.php`.
  - Create views `resources/views/admin/events/index.blade.php`, `create.blade.php`, `edit.blade.php`, and `registrations.blade.php`.
  - Add "Events" tab in `resources/views/components/admin-layout.blade.php`.
  - **Done when:** Authenticated admin can view, create, edit, toggle, and view attendee registrations at `/admin/events`.

- [x] **Step 3: Public Events Index, Detail Page, and Registration Flow**
  - Create `app/Http/Controllers/EventController.php` with `index`, `show(slug)`, and `register(slug)`.
  - Register public routes in `routes/web.php` (`GET /events`, `GET /events/{slug}`, `POST /events/{slug}/register`).
  - Create `resources/views/events/index.blade.php` and `resources/views/events/show.blade.php`.
  - Link homepage event teaser CTA to the flagship event.
  - **Done when:** `/events` and `/events/ijero-tech-day-10th-anniversary` render properly, and submitting the registration form creates an `EventRegistration` and displays success feedback.

- [x] **Step 4: Automated Feature Tests**
  - Create `tests/Feature/Admin/EventManagementTest.php` covering admin CRUD, toggles, image upload, and attendee listing.
  - Create `tests/Feature/EventPublicTest.php` covering public catalog, event detail, registration submission, duplicate prevention, closed registration, and inactive 404.
  - **Done when:** `php artisan test --filter=Event` passes 100%.

- [x] **Step 5: Full Verification & Code Style Pass**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire project suite.
  - **Done when:** 0 Pint violations and 100% of tests pass across the whole application.

## Files / areas

- `database/migrations/*_create_events_table.php` (new)
- `database/migrations/*_create_event_registrations_table.php` (new)
- `app/Models/Event.php` (new)
- `app/Models/EventRegistration.php` (new)
- `database/seeders/EventSeeder.php` (new)
- `database/seeders/DatabaseSeeder.php` (modified)
- `app/Http/Requests/Admin/StoreEventRequest.php` (new)
- `app/Http/Requests/Admin/UpdateEventRequest.php` (new)
- `app/Http/Controllers/Admin/EventController.php` (new)
- `resources/views/admin/events/index.blade.php` (new)
- `resources/views/admin/events/create.blade.php` (new)
- `resources/views/admin/events/edit.blade.php` (new)
- `resources/views/admin/events/registrations.blade.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `app/Http/Controllers/EventController.php` (new)
- `resources/views/events/index.blade.php` (new)
- `resources/views/events/show.blade.php` (new)
- `resources/views/home.blade.php` (modified)
- `routes/web.php` (modified)
- `tests/Feature/Admin/EventManagementTest.php` (new)
- `tests/Feature/EventPublicTest.php` (new)

## Data / contracts

- `Event` Model:
  - `title` (string, max 255)
  - `slug` (string, unique)
  - `summary` (text)
  - `description` (longText)
  - `event_date` (dateTime, nullable)
  - `end_date` (dateTime, nullable)
  - `venue` (string, nullable)
  - `banner_image` (string, nullable)
  - `schedule` (json, nullable)
  - `is_registration_open` (boolean, default true)
  - `is_active` (boolean, default true)
  - `sort_order` (integer, default 0)
  - `seo_title` (string, nullable)
  - `seo_description` (text, nullable)
- `EventRegistration` Model:
  - `event_id` (foreignId, cascade)
  - `full_name` (string, max 255)
  - `email` (string, max 255)
  - `phone` (string, max 50)
  - `attendee_type` (string, nullable)
  - `notes` (text, nullable)
- Public Routes:
  - `GET /events` (`events.index`)
  - `GET /events/{slug}` (`events.show`)
  - `POST /events/{slug}/register` (`events.register`)
- Admin Routes:
  - `GET /admin/events` (`admin.events.index`)
  - `GET /admin/events/create` (`admin.events.create`)
  - `POST /admin/events` (`admin.events.store`)
  - `GET /admin/events/{event}/edit` (`admin.events.edit`)
  - `PUT /admin/events/{event}` (`admin.events.update`)
  - `DELETE /admin/events/{event}` (`admin.events.destroy`)
  - `PATCH /admin/events/{event}/toggle-active` (`admin.events.toggle-active`)
  - `PATCH /admin/events/{event}/toggle-registration` (`admin.events.toggle-registration`)
  - `GET /admin/events/{event}/registrations` (`admin.events.registrations`)
  - `DELETE /admin/events/{event}/registrations/{registration}` (`admin.events.registrations.destroy`)

## Testing

- Feature test suite: `tests/Feature/Admin/EventManagementTest.php` and `tests/Feature/EventPublicTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":11262,"specSha256":"036f434f1039c5a2992ec722b6ddc68c6720097bf9b93ad4ff91cc0a489564d2","branch":"refs/heads/feature/events-ijero-tech-day","head":"f038ebf58d02203775029f225ae65bb8fb6853ab","baseRef":"refs/heads/main","baseCommit":"f038ebf58d02203775029f225ae65bb8fb6853ab","sourceTree":"f7bf1b3e047b47e03d9661cb3aaf6e01966c0148","landing":"local-merge","absentOptional":[]} -->

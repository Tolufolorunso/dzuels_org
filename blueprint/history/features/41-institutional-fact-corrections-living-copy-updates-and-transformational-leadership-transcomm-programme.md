# Feature: Institutional fact corrections, living copy updates & Transformational Leadership (TRANSCOMM) programme

**From build-plan:** feature 41
**Build attempt:** 1
**Branch:** feature/institutional-fact-corrections-living-copy-updates-and-transformational-leadership-transcomm-programme
**Status:** verified

## Goal

Correct historical and institutional records across the codebase, seeders, and public templates to align with leadership facts: Dzuels Foundation did not equip any public schools, but rather established a well-equipped computer laboratory and a resourceful library at Dzuels Foundation in Ijero-Ekiti (benefiting over 500 students to date) and trained 30 teachers. Apply the 4 exact living copy updates across Page Sections and Blade views, catalogue and seed the new `Transformational leadership (TRANSCOMM)` programme, and update baseline statistics across settings, services, and public impact counters.

## In scope

1. **Institutional Fact Corrections & Copy Replacements**:
   - **Correction 1 (Computer Lab & Library at Dzuels Foundation vs Public Schools):**
     - Replace: `"Establishing modern computer laboratories and solar-powered learning facilities in public schools across Ijero-Ekiti, equipping over 165 students with practical coding and computer skills."`
     - With: `"Establishing a well-equipped computer laboratory and a resourceful library at Dzuels Foundation, providing children with access to practical computer training, coding skills, reading materials, and a supportive learning environment. To date, over 500 students have benefited from these learning opportunities."`
     - Update in `PageSectionSeeder` (`home` -> `featured_project`), `home.blade.php`, and `ProgrammeSeeder` (`ijero-digital-literacy`).
   - **Correction 2 (30 Teachers Trained & Foundation Facility Reality):**
     - Replace statements claiming the foundation equipped public schools with the accurate fact: we trained 30 teachers in digital pedagogy and digital skills, and operate our learning hub at Dzuels Foundation.
     - Update `stat_schools_supported` baseline / label to reflect "30 Teachers Trained" (or partner schools engaged for training) and `stat_ict_students` to 500+ in `SettingSeeder`, `ImpactStatsService`, and public statistics bands.
   - **Exact Copy Replacement 1 (Causes Headline):**
     - Replace: `"Holistic support for youth & community development"`
     - With: `"Programs that Bridge the Opportunity Divide"`
     - Update in `PageSectionSeeder` (`home` -> `causes`) and `home.blade.php`.
   - **Exact Copy Replacement 2 (Causes Body Text):**
     - Replace: `"We combine direct education sponsorship with skills acquisition, advocacy, family stability, transformative leadership, and community health."`
     - With: `"From digital hubs in Ijero-Ekiti to annual literacy contest, and much more in between, our causes are designed to uplift communities from the ground up."`
     - Update in `PageSectionSeeder` (`home` -> `causes`) and `home.blade.php`.
   - **Exact Copy Replacement 3 (Home CTA Body Text):**
     - Replace: `"Whether through corporate sponsorship, volunteer mentoring, or financial donations, your partnership empowers the next generation."`
     - With: `"Whether through individual, family or corporate sponsorship, volunteer mentorship, financial donations or legacy giving, your partnership empowers the next generation."`
     - Update in `PageSectionSeeder` (`home` -> `cta`) and `home.blade.php`.
2. **Add Programme: Transformational leadership (TRANSCOMM)**:
   - Add new entry to `database/seeders/ProgrammeSeeder.php`:
     - Title: `Transformational leadership (TRANSCOMM)`
     - Slug: `transformational-leadership-transcomm`
     - Summary: A values-driven mentorship and character development initiative empowering emerging youth leaders with ethical leadership, public speaking, critical thinking, and community impact.
     - Body: Comprehensive overview of TRANSCOMM, emphasizing values-driven leadership, ethical character formation, self-mastery, public speaking, community volunteerism, and youth development.
     - Partner: `Priscilla's Upper Room & Leadership Mentors`
     - Cover image: `front/images/about_1.webp` (optimized WebP)
     - `is_featured`: `true`
     - `is_active`: `true`
     - `sort_order`: `6`
     - SEO title and description.
3. **Database Seeders & Re-Seeding**:
   - Re-run `SettingSeeder`, `PageSectionSeeder`, and `ProgrammeSeeder` to synchronize database state with new copy and programme.
4. **Automated Test Coverage**:
   - Update existing test assertions in `tests/Feature/HomepageTest.php` and `tests/Feature/SiteSettingsTest.php`.
   - Create `tests/Feature/InstitutionalFactsAndTranscommTest.php` verifying the 4 exact text replacements, the new TRANSCOMM programme, and updated baseline metrics.

## Out of scope

- Pages CMS modular component architecture overhaul (scheduled for Feature 42).
- Inline styling parser tags and editor helper buttons (scheduled for Feature 42).
- Event show page UI overhaul (scheduled for Feature 43).
- Media Hub EXIF metadata, pagination, and zoom controls (scheduled for Feature 44).

## Build loop

Follow `blueprint/config.json`:
- `workflow.stepReview`: `"feature"` (single review packet at completion of implementation).
- `workflow.checkpointCommits`: `"disabled"` (work committed upon review approval).

## Build steps

- [x] 1. **Apply the 4 exact copy replacements & institutional fact corrections across seeders and public templates**
  - Update `PageSectionSeeder.php` with the new headline for causes, new body text for causes, new body text for featured project, and new body text for home CTA.
  - Update `resources/views/home.blade.php`, `resources/views/about.blade.php`, `resources/views/donate/index.blade.php`, and `resources/views/get-involved/index.blade.php` to render the corrected text and fallbacks.
  - *Done when:* Public homepage renders `"Programs that Bridge the Opportunity Divide"`, `"From digital hubs in Ijero-Ekiti to annual literacy contest, and much more in between, our causes are designed to uplift communities from the ground up."`, `"Establishing a well-equipped computer laboratory and a resourceful library at Dzuels Foundation..."`, and `"Whether through individual, family or corporate sponsorship..."`.

- [x] 2. **Catalogue and seed Transformational leadership (TRANSCOMM) programme**
  - Add `Transformational leadership (TRANSCOMM)` in `database/seeders/ProgrammeSeeder.php` with full summary, body, SEO attributes, and cover image.
  - Correct the description of `ijero-digital-literacy` in `ProgrammeSeeder.php` to reflect the lab at Dzuels Foundation and 30 teachers trained.
  - Execute `php artisan db:seed --class=ProgrammeSeeder`.
  - *Done when:* `/programmes` displays the TRANSCOMM programme card and `/programmes/transformational-leadership-transcomm` renders its full narrative with status 200.

- [x] 3. **Synchronize verified baseline metrics (500+ students, 30 teachers trained)**
  - Update default stat fallbacks in `SettingSeeder.php` and `ImpactStatsService.php` (`stat_ict_students` = 500, `stat_schools_supported` = 30 / teachers trained).
  - Update stat cards in `resources/views/home.blade.php` and `resources/views/about.blade.php` with the updated labels and numbers.
  - Execute `php artisan db:seed --class=SettingSeeder` and `php artisan db:seed --class=PageSectionSeeder`.
  - *Done when:* Homepage impact statistics band displays 500+ students in ICT & coding training and 30+ teachers trained in digital skills.

- [x] 4. **Automated test suite & verification**
  - Update `tests/Feature/HomepageTest.php` to assert the new copy and stats.
  - Create `tests/Feature/InstitutionalFactsAndTranscommTest.php` testing public pages, programme detail, and updated settings.
  - Run `php artisan test` to ensure 100% green passing suite.
  - Run `php vendor/bin/pint --test` for strict code formatting.
  - *Done when:* `php artisan test` passes with zero failures and Pint reports zero code style violations.

## Files / areas

- `database/seeders/PageSectionSeeder.php`
- `database/seeders/ProgrammeSeeder.php`
- `database/seeders/SettingSeeder.php`
- `app/Services/ImpactStatsService.php`
- `resources/views/home.blade.php`
- `resources/views/about.blade.php`
- `resources/views/donate/index.blade.php`
- `resources/views/get-involved/index.blade.php`
- `resources/views/admin/settings.blade.php`
- `tests/Feature/HomepageTest.php`
- `tests/Feature/InstitutionalFactsAndTranscommTest.php`
- `cpanel_changed_files.txt`

## Data / contracts

- `Programme`: Slug `transformational-leadership-transcomm`, title `Transformational leadership (TRANSCOMM)`, active and featured.
- `PageSection`: Updated living text in sections `causes`, `featured_project`, and `cta` on `home`.
- `Setting`: `stat_ict_students` default `500`, `stat_schools_supported` default `30` (teachers trained).

## Testing

- Logic and HTTP test suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`
- No new external dependencies.

## Notes for the AI

- Follow `AGENTS.md` strictly: zero AI attribution trailers in git commits.
- Track all modified and created files in `cpanel_changed_files.txt`.
- Respect existing database migrations and ensure backward compatibility.


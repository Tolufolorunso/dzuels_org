# Feature 47: Homepage Flagship Event status dynamic alignment & site-wide institutional copy corrections

**From build-plan:** feature 47
**Build attempt:** 1
**Branch:** feature/homepage-flagship-event-status-dynamic-alignment-and-site-wide-institutional-copy-corrections
**Status:** completed

## Goal

Align the Homepage Flagship Event teaser (Section 8) to dynamically calculate and reflect the event's real-time date status (transitioning smoothly from "Upcoming Flagship Event" to "Recent Flagship Event • Highlights" with dual action links to event highlights and the photo gallery), purge false claims of "Zero Administrative Deductions" and independent Board overhead funding from `/donate` in favor of grounded stewardship transparency, and conduct a thorough site-wide purge replacing all lingering claims of "equips public school computer labs" across templates, controllers, seeders, and SQL dumps with the verified institutional fact that Dzuels Foundation equipped its own computer lab and library at Dzuels Foundation in Ijero-Ekiti (benefiting 500+ students) and urgently requires support to expand.

## In scope

1. **Homepage Section 8 Dynamic Flagship Event Alignment (`resources/views/home.blade.php`, `app/Http/Controllers/HomeController.php`):**
   - Query or resolve the flagship event (e.g. `Event::where('slug', 'ijero-tech-day-2026')->first()`) with safe fallbacks in `HomeController` and pass to `home.blade.php`.
   - Replace hardcoded "Upcoming Flagship Event • Oct 17, 2026" with dynamic date-aware status pill:
     - Concluded/Past (`is_past` or `event_date <= now()`): Display "Recent Flagship Event • Oct 17, 2026" and "Highlights" pill.
     - Today: Display "Happening Today • Oct 17, 2026".
     - Upcoming: Display "Upcoming Flagship Event • Oct 17, 2026".
   - Replace single static "Register to Attend &rarr;" CTA:
     - Concluded/Past: Render primary button "Explore Event Highlights &rarr;" (`/events/ijero-tech-day-2026`) alongside secondary pill link "View Photo Gallery &rarr;" (`/events/ijero-tech-day-2026#photo-gallery`).
     - Upcoming: Render "Register to Attend &rarr;" (`/events/ijero-tech-day-2026`).
   - Preserve theme tagline `"Learning Today, Creating Tomorrow"` and core narrative.

2. **`/donate` Stewardship Card Overhaul (`resources/views/donate/index.blade.php`):**
   - Purge `<strong ...>Zero Administrative Deductions</strong>` and the claim that foundation overhead, leases, and staffing are funded independently by the Board of Trustees.
   - Replace with authentic stewardship copy:
     - Title: `Transparent Stewardship & Direct Impact`
     - Description: `We maintain strict financial accountability and transparent reporting. Contributions directly fund learning center facilities, student workshops, and direct educational supplies without wasteful overhead.`
   - In the `100% Direct Learner Benefit` bullet: replace `Every Naira and Dollar received directly equips public school computer labs...` with `Every Naira and Dollar received directly sustains our computer lab and library at Dzuels Foundation in Ijero-Ekiti, procures curriculum textbooks, or funds student literacy prizes.`
   - In hero body fallback text: replace `...in Ijero-Ekiti and rural public schools.` with `...at Dzuels Foundation in Ijero-Ekiti and underserved community learners.`

3. **Site-Wide Purge of "equips public school computer labs" Across Platform:**
   - `app/Http/Controllers/DonateController.php`:
     - Update tier `School Lab Terminal` to `Community Lab Terminal` and description from `Helps maintain and equip community school computer workstations...` to `Helps maintain and equip computer workstations at our Ijero-Ekiti learning center with offline educational software.`
     - Update pillar `Classroom Books & Learning Gear` description from `...to public schools.` to `...to underprivileged students and community schools.`
   - `resources/views/get-involved/index.blade.php`:
     - Update `Direct Classroom Mentorship`: change `...directly to public school students.` to `...directly to community students at our learning center.`
   - `database/seeders/PageSectionSeeder.php`:
     - Update contact hero body text: change `...school computer labs...` to `...our learning center computer lab and library...`
     - Update donate hero body text: change `...classroom computer labs... in rural Nigerian schools.` to `...our community computer lab and library at Dzuels Foundation in Ijero-Ekiti, essential textbooks, digital literacy, and student empowerment.`
   - Synchronize SQL dumps `database/dumps/dzuels_database.sql` and root `dzuels_database.sql` via `php artisan db:export-sql`.

4. **Testing, Linting & Verification:**
   - Update `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php` and `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php` to assert the new stewardship copy and verify that `Zero Administrative Deductions` is no longer rendered.
   - Update `tests/Feature/HomepageTest.php` to reflect dynamic status ("Recent Flagship Event" / "Oct 17, 2026" / "Explore Event Highlights").
   - Create comprehensive feature test `tests/Feature/HomepageFlagshipStatusAndInstitutionalCopyPurgeTest.php` testing both past and future event date scenarios, donate stewardship card verification, and site-wide absence of false claims.
   - Run `php vendor/bin/pint --test` and execute full test suite (`php artisan test`).

## Out of scope

- Creating new database tables or running database schema migrations.
- Modifying donation processing channels (GTBank transfer and Givebutter U.S. giving remain unchanged).
- Redesigning other homepage sections beyond Section 8.
- Modifying the Central Media Hub or Gallery photo picker (completed in Features 45 & 46).

## Build loop

- Step review: `feature` (one consolidated review packet at completion of implementation).
- Checkpoint commits: `disabled`.

## Build steps

- [x] 1. **Homepage Section 8 Dynamic Flagship Event Alignment**
  - Update `app/Http/Controllers/HomeController.php` to retrieve or resolve the active flagship event (`ijero-tech-day-2026`).
  - Update `resources/views/home.blade.php` Section 8 to dynamically display status pill ("Recent Flagship Event • Oct 17, 2026" vs "Upcoming Flagship Event") and dual action links ("Explore Event Highlights &rarr;" and "View Photo Gallery &rarr;" when concluded; "Register to Attend &rarr;" when upcoming).
  - *Done when:* Visiting `/` renders the dynamic flagship event callout matching event date status with working links, verified via test.

- [x] 2. **`/donate` Stewardship Card Overhaul & False Claim Purge**
  - Edit `resources/views/donate/index.blade.php` to remove "Zero Administrative Deductions" and false claims about Board funding overhead.
  - Insert "Transparent Stewardship & Direct Impact" and truthful learning center stewardship copy.
  - Update `100% Direct Learner Benefit` bullet to accurately reference sustaining our computer lab and library at Dzuels Foundation in Ijero-Ekiti.
  - *Done when:* Visiting `/donate` displays the updated stewardship card with zero mentions of "Zero Administrative Deductions" or Board funding overhead, verified via test.

- [x] 3. **Site-Wide Purge of "equips public school computer labs" Across Controllers, Views, Seeders & SQL Dumps**
  - Update `app/Http/Controllers/DonateController.php` tiers and pillars.
  - Update `resources/views/get-involved/index.blade.php` mentorship bullet.
  - Update `database/seeders/PageSectionSeeder.php` contact and donate hero body texts.
  - Update database dumps (`database/dumps/dzuels_database.sql` and root `dzuels_database.sql`).
  - *Done when:* `git grep -i "Zero Administrative Deductions"` and `git grep -i "public school computer labs"` return zero occurrences in application source code, verified via grep and test.

- [x] 4. **Test Suite Updates, Pint Formatting & Full Verification**
  - Update assertions in `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php`, `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php`, and `tests/Feature/HomepageTest.php`.
  - Create `tests/Feature/HomepageFlagshipStatusAndInstitutionalCopyPurgeTest.php`.
  - Format code using `php vendor/bin/pint`.
  - Run full test suite with `php artisan test`.
  - *Done when:* Full test suite passes 100% with all assertions verified.

## Verification Evidence

- Full automated test suite: 425 passed (2,755 assertions) via `php artisan test`.
- Dedicated feature test: 6 passed (32 assertions) in `tests/Feature/HomepageFlagshipStatusAndInstitutionalCopyPurgeTest.php`.
- Code formatting: 0 violations via `php vendor/bin/pint --test`.
- Database export: 25 tables, 4,447 rows dumped to `database/dumps/dzuels_database.sql` and synced to root `dzuels_database.sql`.


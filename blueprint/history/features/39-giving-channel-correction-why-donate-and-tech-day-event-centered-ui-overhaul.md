# Feature 39: Giving channel correction (remove mock wires, add "Why Donate") & Tech Day event show page centered UI overhaul

**From build-plan:** feature 39
**Build attempt:** 1
**Status:** verified
**Branch:** feature/giving-channel-correction-why-donate-and-tech-day-event-centered-ui-overhaul

## Goal

Purge all fabricated Domiciliary foreign currency accounts and SWIFT code from the public donation channels, replacing them with an inspiring, authoritative "Why Donate to Our Cause" stewardship card (100% direct learner impact, zero administrative deductions, CAC legal standing, and tangible metrics); and redesign the Ijero Tech Day event show page (`/events/ijero-tech-day-[year]`) to eliminate the left-flushed layout bias, creating a balanced, centered festival showcase with centered hero metrics, edition switcher pills, countdown timer, and balanced narrative and milestone celebration cards.

## Design reference

- Donate Page: `resources/views/donate/index.blade.php`
- Donate Controller: `app/Http/Controllers/DonateController.php`
- Tech Day & Event Show Page: `resources/views/events/show.blade.php`
- Events Catalog: `resources/views/events/index.blade.php`
- Brand Design Tokens: Maroon `#6A0406`, Crimson `#B4070A`, Cream `#F9DEC9`, Mint `#56E39F`

## In scope

1. **Giving Channels Correction & "Why Donate to Our Cause" (`/donate`):**
   - Remove the unverified Domiciliary account numbers (USD `0450072881`, GBP `0450073060`, EUR `0450072898`) and SWIFT code (`GTBINGLA`) from `DonateController.php` and `resources/views/donate/index.blade.php`.
   - Replace Channel 3 in the 4-channel giving grid with an authoritative "Why Donate to Our Cause" stewardship card:
     - *100% Direct Impact:* All public donations directly fund student computer hardware, textbooks, literacy prizes, and classroom infrastructure in Ijero-Ekiti.
     - *Zero Administrative Deductions:* Foundation administrative and staff expenses are sustained independently by the Board of Trustees; public gifts are not consumed by overhead.
     - *CAC Legal Standing & Track Record:* Registered charity under Nigeria's Corporate Affairs Commission with 10 years of audited service, 165+ coding students, 591+ literacy learners, and 15 partner schools.
     - *Transparent Donor Updates:* Verified photo reports and direct progress updates provided to all contributors.
   - Maintain the balanced 4-channel grid:
     1. Direct Bank Transfer (GTBank NGN `0450072850` with 1-click copy)
     2. U.S. Diaspora Giving (Givebutter with cards, Apple Pay, Google Pay, Venmo, PayPal)
     3. Why Donate to Our Cause (Stewardship, 0% admin deduction, CAC verification)
     4. In-Kind & Equipment Donations (Hardware, STEM kits, solar inverters)

2. **Tech Day Event Show Page Centered UI Overhaul (`/events/{slug}`):**
   - Eliminate the left-flushed layout bias across `resources/views/events/show.blade.php`.
   - **Hero & Header Section:**
     - Wrap header content in a centered container (`max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10 text-center`).
     - Center the date badge, time badge, edition badge, and status pill (`justify-center`).
     - Center the festival title `<h1>` and summary/theme (`max-w-4xl mx-auto text-center`).
     - Center the 4-stat festival metrics bar (`300+ Delegates`, `22 Graduates`, `10 Stations`, `100% Free`) with `max-w-3xl mx-auto`.
     - Center the multi-year edition switcher pills (`justify-center`).
     - Center the interactive countdown timer widget (`max-w-3xl mx-auto text-center`).
   - **Showcase & Content Section:**
     - Center the 16:9 banner container (`max-w-5xl mx-auto mb-12`).
     - Rebalance the content and milestone card layout (`max-w-5xl mx-auto space-y-12`) so narrative overview, agenda schedule, authentic photo recap, milestone graduation summary (full-width 3-column milestone celebration card), and official sponsors form a balanced, centered, harmonious composition.

3. **Automated Testing & Code Quality:**
   - Update `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php` and `tests/Feature/DonatePageTest.php` to assert the complete absence of Domiciliary accounts (`0450072881`, `0450073060`, `0450072898`, `GTBINGLA`) and verify the new "Why Donate to Our Cause" stewardship card.
   - Create `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php` to verify the updated donation grid and centered Tech Day event show page.
   - Full test suite passes (`php artisan test`) and Pint checks pass (`php vendor/bin/pint --test`).

## Out of scope

- Database migration changes (existing schema on `events` and `page_sections` is preserved; `is_active` for `page_sections` is part of Feature 40).
- Admin Media Hub or Universal Picker alterations (completed in Milestone 11 & 12).
- Volunteer/Partner intake form endpoint modifications (functioning and secure).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all implementation steps).
- `workflow.checkpointCommits: "disabled"`.
- Verify with `php vendor/bin/pint --test` and `php artisan test`.

## Build steps

- [x] 1. **Purge mock Domiciliary accounts & add "Why Donate to Our Cause"** - remove unverified wire accounts from `DonateController.php` and `resources/views/donate/index.blade.php`; elevate an authoritative "Why Donate to Our Cause" card highlighting 100% direct impact, 0% admin deductions, and CAC standing in the 4-channel grid. Done when visiting `/donate` displays zero wire/SWIFT accounts and renders the prominent "Why Donate to Our Cause" stewardship card.
- [x] 2. **Tech Day event show page centered UI overhaul** - redesign `resources/views/events/show.blade.php` to eliminate the left-flushed skew; center hero typography, date badges, 4-stat metric bar, edition switcher pills, countdown timer, banner container, and milestone cards (`mx-auto text-center`). Done when visiting `/events/ijero-tech-day-2026` renders a centered, balanced, festival-grade page.
- [x] 3. **Automated testing and code formatting** - update existing donate test assertions, create `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php`, run full test suite to green, and format with Laravel Pint. Done when all 377 tests pass with zero failures and Pint reports zero code style violations.

## Files / areas

- `app/Http/Controllers/DonateController.php` - removed mock Domiciliary accounts
- `resources/views/donate/index.blade.php` - updated Channel 3 with "Why Donate to Our Cause" stewardship card
- `resources/views/events/show.blade.php` - centered, balanced layout overhaul for hero, banner, and content cards
- `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php` - updated wire account assertions
- `tests/Feature/DonatePageTest.php` - updated wire account assertions
- `tests/Feature/GivingChannelCorrectionAndTechDayEventCenteredUiTest.php` - new automated verification test suite

## Verification

- `php vendor/bin/pint --test` passed with zero violations.
- `php artisan test` passed with 377 passed tests (2,377 assertions).
- `npm run build` compiled frontend assets cleanly.


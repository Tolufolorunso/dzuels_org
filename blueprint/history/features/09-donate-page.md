# Feature: Donate Page

**From build-plan:** feature 9
**Build attempt:** 1
**Branch:** feature/donate-page
**Status:** verified

## Goal

Provide a dedicated, high-trust public Donate page at `/donate` empowering donors across Nigeria and the global diaspora to support Dzuels Educational Foundation through one-click online giving via Flutterwave, direct GTBank bank transfer details with interactive one-click copy buttons, international domiciliary wire transfer instructions, an in-kind donation contact pathway, an interactive "Where your gift goes" financial transparency breakdown, and an expandable FAQ section.

## Design reference

- Brand styling: Poppins typography, Great Vibes script accents, brand maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`), and semantic dark mode tokens (`var(--surface)`, `var(--text)`, `var(--border)`).
- Prototype reference: `scratch/partner.html` cash donation and online Flutterwave section, bank accounts (GTBank account `0450072850`, Swift `GTBINGLA`, USD `0450072881`, GBP `0450073060`, EUR `0450072898`), and package/equipment in-kind donations.
- Interactive elements:
  - Hero header with verified non-profit badge (`CAC/IT/NO 123456`).
  - Online donation card with Flutterwave link button, badge, and suggested giving impact tiers.
  - Local bank transfer card with large monospace account number and interactive Alpine.js "Copy" button with visual "Copied!" feedback.
  - Diaspora wire transfer accordion or grid for USD, GBP, and EUR domiciliary accounts with copy buttons.
  - "Where your gift goes" four-pillar allocation cards (Digital Literacy, Competitions, Classroom Materials, Operational Outreach).
  - Expandable Alpine.js FAQ accordion for common donor questions.

## In scope

1. **Controller & Routing:**
   - Create `App\Http\Controllers\DonateController`:
     - Fetches banking and donation settings from `Setting` model (`flutterwave_url`, `bank_name`, `bank_account_name`, `bank_account_number`, `contact_email`, `contact_phone_1`, `registration_number`).
     - Provides robust fallback defaults when database settings are empty or unset.
     - Prepares giving tiers, impact allocation breakdown, and FAQ items.
     - Renders `resources/views/donate/index.blade.php`.
   - Register `GET /donate` route named `donate` in `routes/web.php`.
   - Ensure header and footer Donate buttons correctly route to `route('donate')`.

2. **Public View (`resources/views/donate/index.blade.php`):**
   - **Hero Section:**
     - High-impact headline: "Your Generosity Powers Real Transformation".
     - Non-profit credibility badge (`CAC/IT/NO 123456`, 100% Volunteer Driven).
     - Subtitle emphasizing direct rural community impact in Ijero-Ekiti and beyond.
   - **Payment Channels Grid:**
     - *Channel 1: Instant Online Giving (Flutterwave):* Direct CTA button with `target="_blank" rel="noopener noreferrer"`, card logos (Visa, Mastercard, Verve), suggested impact amounts (₦5,000, ₦20,000, ₦50,000, Custom).
     - *Channel 2: Direct Local Bank Transfer (GTBank):* Bank name, account name, account number `0450072850`, and instant clipboard copy button with checkmark animation.
     - *Channel 3: International & Diaspora Wire Transfers:* Swift code `GTBINGLA`, USD (`0450072881`), GBP (`0450073060`), EUR (`0450072898`) with individual copy controls.
     - *Channel 4: In-Kind Educational & Tech Donations:* Laptops, tablets, robotics kits, textbooks, solar/inverter gear, with direct `mailto:` and `tel:` links.
   - **Where Your Gift Goes (Transparency Breakdown):**
     - 4 thematic percentage cards:
       - 40% Digital Literacy Labs & ICT Workstations.
       - 25% Academic Excellence (Spelling Bee, Reading Competition Scholarships).
       - 20% Classroom Resources & Rural School Refurbishment.
       - 15% Volunteer Coordination, Logistics & Field Verification.
   - **Frequently Asked Questions (FAQ):**
     - Alpine.js collapsible accordion addressing:
       - Official registration and governance.
       - Online payment security and encryption.
       - International giving from the diaspora.
       - Receipts and transaction acknowledgment.
       - Material/equipment donation logistics.
   - **Volunteer Teaser / Bottom Banner:**
     - "Want to give your time as well? Join us as a mentor or field volunteer" linking to `/get-involved`.

3. **Automated Testing:**
   - Create `tests/Feature/DonatePageTest.php`:
     - Test that `/donate` returns HTTP 200 and displays brand hero and giving channels.
     - Test that dynamic settings (`flutterwave_url`, `bank_account_number`, `bank_name`, `bank_account_name`) are reflected on the page.
     - Test fallback values when settings are missing from database.
     - Test that FAQ entries and transparency breakdown render correctly.
     - Test that external donation link has `target="_blank"` and `rel="noopener noreferrer"`.

## Out of scope

- Direct server-side Flutterwave webhook payment processing or card capture (handled securely offsite via the foundation's official Flutterwave payment link).
- Automated tax receipt generation system (scheduled for future Feature 21).
- Recurring monthly subscription billing engine (scheduled for future Feature 21).

## Build loop

- `workflow.stepReview`: feature
- `workflow.checkpointCommits`: disabled

## Build steps

- [x] **Step 1: DonateController and Route Registration**
  - Create `app/Http/Controllers/DonateController.php` with dynamic settings retrieval, fallbacks, and view data preparation.
  - Register `Route::get('/donate', DonateController::class)->name('donate');` in `routes/web.php`.
  - **Done when:** `GET /donate` route is registered in `php artisan route:list` and invokes `DonateController`.

- [x] **Step 2: Donate Page Blade View with Interactive Giving Channels, Copy Buttons & FAQ**
  - Create `resources/views/donate/index.blade.php` with hero, Flutterwave instant giving, GTBank local transfer with Alpine clipboard copy, international domiciliary accounts, in-kind equipment support, "Where your gift goes" breakdown, and collapsible FAQ.
  - Verify header and footer links connect cleanly to `route('donate')`.
  - **Done when:** Visiting `/donate` renders 200 OK with all 4 payment channels, interactive copy feedback, transparency stats, and responsive FAQ accordion.

- [x] **Step 3: Automated Feature Testing**
  - Create `tests/Feature/DonatePageTest.php` covering route availability, dynamic settings rendering, fallback resilience, and safe external links.
  - **Done when:** `php artisan test --filter=DonatePageTest` passes 100%.

- [x] **Step 4: Full Verification & Code Style Pass**
  - Run `php vendor/bin/pint --test` and `php artisan test` across the entire application suite.
  - **Done when:** 0 Pint violations and 100% of tests pass across the entire suite.

## Files / areas

- `app/Http/Controllers/DonateController.php` (new)
- `resources/views/donate/index.blade.php` (new)
- `routes/web.php` (modified)
- `tests/Feature/DonatePageTest.php` (new)

## Data / contracts

- Settings consumed:
  - `flutterwave_url` (string, URL)
  - `bank_name` (string)
  - `bank_account_name` (string)
  - `bank_account_number` (string)
  - `contact_email` (string)
  - `contact_phone_1` (string)
  - `registration_number` (string)
- Routes:
  - `GET /donate` (`donate`)

## Testing

- Feature test suite: `tests/Feature/DonatePageTest.php`
- Command: `php artisan test`
- Code styling: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain brand tokens: maroon (`#6A0406`), crimson (`#B4070A`), cream (`#F9DEC9`), mint (`#56E39F`).
- Support seamless dark mode (`dark:bg-[var(--surface-dark)]`, etc.) with contrast compliance.
- No AI attribution in commits or code comments.
- Do not make external network requests.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":7800,"specSha256":"fe966ff70c104ce57d5cf2ae7e22482e14dbe980a6875fd99a9d1b0c6447951c","branch":"refs/heads/feature/donate-page","head":"885a7d5b5935655baaa242069b1719b143576d54","baseRef":"refs/heads/main","baseCommit":"885a7d5b5935655baaa242069b1719b143576d54","sourceTree":"d3a66bb3c65d4a272987da22d362eebcacf6aeca","landing":"local-merge","absentOptional":[]} -->

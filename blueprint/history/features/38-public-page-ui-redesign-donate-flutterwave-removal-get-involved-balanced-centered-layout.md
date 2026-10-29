# Feature 38: Public page UI redesign for /donate (Flutterwave removal) and /get-involved (balanced, centered layout)

**From build-plan:** feature 38
**Build attempt:** 1
**Status:** verified
**Branch:** feature/public-page-ui-redesign-donate-flutterwave-removal-get-involved-balanced-centered-layout

## Goal

Remove Flutterwave from the public donation page while elevating GTBank direct transfer (with 1-click copy) and Givebutter U.S. diaspora giving into a clean, balanced giving channels grid; redesign `/get-involved` to eliminate the left-heavy, asymmetrical layout, replacing it with a balanced, centered design featuring prominent pathway cards for volunteers, partners, and sponsors, centered interactive forms, and structured partnership impact showcases.

## Design reference

- Donate Page: `resources/views/donate/index.blade.php`
- Donate Controller: `app/Http/Controllers/DonateController.php`
- Get Involved Page: `resources/views/get-involved/index.blade.php`
- Footer Component: `resources/views/components/footer.blade.php`
- Brand Design Tokens: Maroon `#6A0406`, Crimson `#B4070A`, Cream `#F9DEC9`, Mint `#56E39F`

## In scope

1. **Donate Page Redesign & Flutterwave Removal (`/donate`):**
   - Remove Flutterwave giving channel card and references from the public donate page.
   - Update footer giving link from "Online Giving (Flutterwave)" to "Donate & Support" or "GTBank Direct Transfer".
   - Elevate GTBank local transfer channel as the primary Nigerian giving option with high-contrast copyable account number (`0450072850`), account name (`Dzuels Educational Foundation`), bank name, suggested tiers, and WhatsApp/Email confirmation links.
   - Elevate Givebutter U.S. diaspora channel as the primary online card giving option with multi-currency payment badges (Credit Cards, Apple Pay, Google Pay, Venmo, PayPal, ACH).
   - Rebalance giving channels into a clean 2x2 or 4-channel grid (GTBank Local Transfer, Givebutter U.S. Diaspora, International Domiciliary Wires, In-Kind Equipment & Solar Donations).
   - Update FAQs in `DonateController.php` and `donate/index.blade.php` to remove Flutterwave mentions.

2. **Get Involved Page Layout Balancing & Redesign (`/get-involved`):**
   - Eliminate the awkward left-flushed layout.
   - Add a balanced 3-Pathway visual card selector above the forms:
     1. Volunteer Mentorship (teaching, lab instruction, spelling bee coaching)
     2. Institutional Partnerships (CSR, corporate backing, school alliances)
     3. In-Kind & Equipment Support (computers, STEM kits, solar infrastructure)
   - Redesign the interactive application area into a centered, spacious, balanced card layout (`max-w-4xl mx-auto`) with centered tab switcher, balanced 2-column input fields, and clear submit actions.
   - Add a balanced 3-column benefits and governance grid below the form (Why Volunteer With Us, Institutional Standards & Track Record, Direct Secretariat Hotline).

3. **Automated Testing & Code Quality:**
   - Create `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php` to verify Flutterwave removal from public views, GTBank 1-click copy elements, Givebutter link presence, centered pathway elements on `/get-involved`, and successful form submissions.
   - Full test suite passes (`php artisan test`) and Pint checks pass (`php vendor/bin/pint --test`).

## Out of scope

- Backend database migration changes (existing tables and schema remain intact).
- Admin media hub or gallery modifications (completed in Features 36 & 37).
- Changing form submission endpoints or email notification business logic.

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all implementation steps).
- `workflow.checkpointCommits: "disabled"`.
- Verify with `php vendor/bin/pint --test` and `php artisan test`.

## Build steps

- [x] 1. **Donate page overhaul & Flutterwave removal** - remove Flutterwave from `resources/views/donate/index.blade.php`, `app/Http/Controllers/DonateController.php` FAQs, and `resources/views/components/footer.blade.php`; elevate GTBank direct transfer with 1-click copy buttons and Givebutter U.S. diaspora giving in a balanced 4-channel grid. Done when visiting `/donate` shows zero mentions of Flutterwave and renders prominent GTBank and Givebutter giving channels.
- [x] 2. **Get involved page redesign with balanced centered layout** - redesign `resources/views/get-involved/index.blade.php` with a 3-pathway selector header, centered spacious form container (`max-w-4xl mx-auto`) with balanced 2-column input grids, and a 3-column benefits & governance grid below the form. Done when visiting `/get-involved` renders a balanced, centered, visually stunning page without any left-flushed skew.
- [x] 3. **Feature tests and code formatting** - create `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php`, run full test suite to green, and format with Laravel Pint. Done when all 368+ tests pass and Pint reports zero code style violations.

## Files / areas

- `app/Http/Controllers/DonateController.php` - updated FAQs and settings array
- `resources/views/donate/index.blade.php` - redesigned giving channels grid without Flutterwave
- `resources/views/components/footer.blade.php` - updated donation links
- `resources/views/get-involved/index.blade.php` - balanced centered layout, pathway cards, centered forms
- `tests/Feature/DonateAndGetInvolvedUiRedesignTest.php` - automated verification test
- `tests/Feature/DonatePageTest.php` - updated tests asserting Flutterwave absence and elevated bank channels
- `tests/Feature/DatabaseExportCommandTest.php` - protected test teardown against Windows sharing lock cascades

## Verification

- `php vendor/bin/pint --test` passed with zero violations.
- `php artisan test` passed with 374 passed tests (2,320 assertions).
- `npm run build` compiled frontend assets cleanly.


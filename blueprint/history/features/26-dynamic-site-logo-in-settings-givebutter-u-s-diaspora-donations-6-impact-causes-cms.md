# Feature: Dynamic site logo in settings, Givebutter U.S. diaspora donations & 6 Impact Causes CMS

**From build-plan:** feature 26
**Build attempt:** 1
**Branch:** feature/dynamic-site-logo-in-settings-givebutter-u-s-diaspora-donations-6-impact-causes-cms
**Status:** verified

## Goal
Enable dynamic site logo management in `/admin/settings` propagating across public and admin interfaces with automatic fallback; integrate Givebutter U.S. diaspora donation channel with a dedicated button labeled “For U.S. Donors Only.” on `/donate`; and overhaul the Home page causes section to showcase the foundation's 6 authentic pillars (Paul's academy, Peter's net, Deborah congress, Naomi's kitchen, Priscilla's upper room, Luke's Tent) and foundational tagline ("AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD"), fully editable in the Admin Page Content Editor (`/admin/pages/home`).

## Design reference
- `public/front/images/cause.jpg`: Official foundation banner detailing the 6 pillars and the overarching tagline: "AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD".
- `public/front/images/logo.png`: Default high-resolution foundation brand asset used as reliable fallback.
- Givebutter channel: Clean, accessible card design matching existing Flutterwave and GTBank cards on `/donate`, with distinctive U.S. diaspora badge and button labeled exactly: **“For U.S. Donors Only.”**.

## In scope
1. **Dynamic Site Logo:**
   - Add `site_logo` setting in `Setting.php` with `Setting::getLogoUrl(): string` helper returning uploaded/selected path or fallback to `asset('front/images/logo.png')`.
   - Add site logo field with `<x-admin.media-picker>` in `/admin/settings` (`resources/views/admin/settings.blade.php`).
   - Validate and persist `site_logo` in `UpdateSettingsRequest.php` and `SettingController.php`.
   - Update header (`components/header.blade.php`), footer (`components/footer.blade.php`), admin sidebar/navbar (`components/admin-layout.blade.php`, `admin/layout.blade.php`), login/auth screens, and schema organization component to render `Setting::getLogoUrl()`.
   - Seed default `site_logo` in `SettingSeeder.php`.

2. **Givebutter U.S. Diaspora Giving Channel:**
   - Seed `givebutter_url = 'https://givebutter.com/dzuels-foundation'` in `SettingSeeder.php`.
   - Add `givebutter_url` field to `UpdateSettingsRequest.php`, `SettingController.php`, and the Giving & Banking section of `/admin/settings`.
   - Update `DonateController.php` to pass `givebutter_url` in `$settings`.
   - Add a dedicated giving channel card for Givebutter on `/donate` (`resources/views/donate/index.blade.php`) highlighting U.S. payment methods (Credit/Debit, Venmo, PayPal, Apple Pay, Bank Transfer) with button labeled strictly: **“For U.S. Donors Only.”**.
   - Update Donate FAQs to highlight Givebutter for U.S. supporters.

3. **6 Impact Causes Section CMS:**
   - Seed `meta_data` for the `causes` section in `PageSectionSeeder.php` with the foundational tagline and 6 authentic pillars:
     1. Education Support (Paul's academy)
     2. Skills Acquisition (Peter's net)
     3. Advocacy (Deborah congress)
     4. Feeding families (Naomi's kitchen)
     5. Transformative leadership Training (Priscilla's upper room)
     6. Health awareness and support (Luke's Tent)
   - Update `PageController.php` to validate and persist `meta_data` (tagline and array of pillars) for page sections.
   - Expand `resources/views/admin/pages/edit.blade.php` to render dedicated editing inputs for the foundational tagline and the 6 impact pillars when editing the `causes` section on `/admin/pages/home`.
   - Update `resources/views/home.blade.php` to replace the old 8 bullets with the 6 authentic pillars grid, displaying ministry labels, descriptions, and the foundational tagline banner.

4. **Testing & Deployment Manifest:**
   - Automated feature tests for logo fallback/updating, Givebutter settings/links, and causes CMS/display.
   - Full test suite passing and Pint style verification.
   - Update `cpanel_changed_files.txt`.

## Out of scope
- Payment gateway webhook integrations for Givebutter (external hosted checkout).
- Modification of other unrelated page sections or admin modules.
- Changes to database table schemas (using existing `settings.value` and `page_sections.meta_data` JSON attributes).

## Build loop
- Step review cadence: `feature` (all steps implemented sequentially, followed by review packet).
- Checkpoint commits: `disabled`.

## Build steps
- [x] 1. **Dynamic Site Logo in Settings & Site-Wide Integration** - Implement `Setting::getLogoUrl()`, add `site_logo` media picker in `/admin/settings`, support validation and saving in `UpdateSettingsRequest` & `SettingController`, update `components/header.blade.php`, `components/footer.blade.php`, `components/admin-layout.blade.php`, and auth views to reference `Setting::getLogoUrl()`, and seed in `SettingSeeder.php`.  
  *Done when:* Logo can be selected or changed in `/admin/settings`, appears across public headers, footers, and admin sidebars, and cleanly defaults to `front/images/logo.png` if unset.

- [x] 2. **Givebutter U.S. Diaspora Giving on `/donate`** - Seed `givebutter_url`, expose editable input in `/admin/settings` Giving & Banking section, pass `givebutter_url` in `DonateController`, and render the dedicated Givebutter card on `/donate` with button labeled exactly “For U.S. Donors Only.”.  
  *Done when:* `/donate` displays the Givebutter donation channel with button text “For U.S. Donors Only.” linking to the configured Givebutter URL in a new window, and staff can edit the URL in `/admin/settings`.

- [x] 3. **6 Impact Causes CMS & Home Page Overhaul** - Seed the 6 authentic pillars and foundational tagline in `PageSectionSeeder.php`, expand `PageController::updateSection` to validate and store `meta_data`, add form fields for the tagline and 6 pillars in `resources/views/admin/pages/edit.blade.php`, and overhaul the causes section in `resources/views/home.blade.php` to render the 6 pillars with ministry tags and tagline banner.  
  *Done when:* Home page displays the 6 authentic causes from `cause.jpg` alongside the foundational tagline banner, and staff can update all 6 pillars and tagline from `/admin/pages/home`.

- [x] 4. **Automated Verification, Pint Code Style & Deployment Manifest** - Add feature tests in `tests/Feature/SiteLogoAndGivebutterTest.php` and `tests/Feature/ImpactCausesTest.php`, execute `php artisan test` and `php vendor/bin/pint --test`, and record all touched files in `cpanel_changed_files.txt`.  
  *Done when:* Test suite passes with 100% green status, style audit passes, and `cpanel_changed_files.txt` is updated.

## Files / areas
- `app/Models/Setting.php`
- `app/Http/Controllers/Admin/SettingController.php`
- `app/Http/Requests/Admin/UpdateSettingsRequest.php`
- `resources/views/admin/settings.blade.php`
- `resources/views/components/header.blade.php`
- `resources/views/components/footer.blade.php`
- `resources/views/components/admin-layout.blade.php`
- `resources/views/admin/layout.blade.php`
- `resources/views/admin/login.blade.php`
- `resources/views/admin/auth/forgot-password.blade.php`
- `resources/views/admin/auth/reset-password.blade.php`
- `resources/views/components/schema-organization.blade.php`
- `app/Http/Controllers/DonateController.php`
- `resources/views/donate/index.blade.php`
- `database/seeders/SettingSeeder.php`
- `database/seeders/PageSectionSeeder.php`
- `app/Http/Controllers/Admin/PageController.php`
- `resources/views/admin/pages/edit.blade.php`
- `resources/views/home.blade.php`
- `tests/Feature/SiteLogoAndGivebutterTest.php`
- `tests/Feature/ImpactCausesTest.php`
- `cpanel_changed_files.txt`

## Data / contracts
- **`settings` table:**
  - Key: `site_logo`, Type: `string`, Group: `general`, Default: `front/images/logo.png`.
  - Key: `givebutter_url`, Type: `string`, Group: `banking`, Default: `https://givebutter.com/dzuels-foundation`.
- **`page_sections` table (`page_key = 'home'`, `section_key = 'causes'`):**
  - `meta_data['tagline']`: `AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD`
  - `meta_data['pillars']`: Array of 6 objects with `{ title, ministry, description, icon }`.
- **Givebutter Button:** Must render with label **“For U.S. Donors Only.”** and target `_blank` with `rel="noopener noreferrer"`.

## Testing
- `php artisan test --filter=SiteLogoAndGivebutterTest`
- `php artisan test --filter=ImpactCausesTest`
- `php artisan test`
- `php vendor/bin/pint --test`

## Notes for the AI
- Maintain zero external package dependencies.
- Use `Setting::getLogoUrl()` helper everywhere logo is referenced so a single setting update immediately reflects across the entire application.
- Button on Givebutter card must explicitly be: “For U.S. Donors Only.”.
- Preserve graceful defaults if `meta_data` is empty or partially populated.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":8924,"specSha256":"a93c537a904a4e76e195eeb579676702ea8a6c64cad5b8512c9e88d1d6173ed7","branch":"refs/heads/feature/dynamic-site-logo-in-settings-givebutter-u-s-diaspora-donations-6-impact-causes-cms","head":"c68619dcf72ad64d75161583a135eb631017afac","baseRef":"refs/heads/main","baseCommit":"c68619dcf72ad64d75161583a135eb631017afac","sourceTree":"d33422119123d10590652e868a043e5fe24e50f3","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Dynamic Site Logo in Settings & Navigation**:
   - Log in to `/admin` and navigate to **Settings** (`/admin/settings`).
   - Under Section 1 (**General Information**), click **Choose from Library** on the **Site Logo** field to pick an image or upload a custom logo.
   - Save settings and verify the new logo appears in the public header (`/`), public footer, and admin navigation/sidebar.
   - If cleared or unset, verify it cleanly defaults to `front/images/logo.png`.

2. **Verify Givebutter U.S. Giving Channel on `/donate`**:
   - Go to `/donate` and locate the third donation card: **Givebutter (U.S. & International Donors)**.
   - Note the supported methods (Credit/Debit, Venmo, PayPal, Apple Pay, Bank Transfer).
   - Verify the action button is strictly labeled: **“For U.S. Donors Only.”** and opens `https://givebutter.com/dzuels-foundation` in a new tab.
   - In `/admin/settings` under Giving & Banking, verify staff can update the Givebutter URL.

3. **Verify 6 Impact Causes CMS on `/admin/pages/home` & Public Home (`/`)**:
   - Go to `/admin/pages/home` and scroll down to the **Impact Causes (6 Core Pillars)** section.
   - Verify the editable **Foundational Tagline** input ("AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD") and all 6 authentic pillars (Paul's academy, Peter's net, Deborah congress, Naomi's kitchen, Priscilla's upper room, Luke's Tent).
   - Edit any pillar description or icon, save, and check the homepage (`/`) to confirm real-time updates.

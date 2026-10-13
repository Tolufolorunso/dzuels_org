# Feature: Site-wide multi-phone settings, dynamic contact CMS and dynamic partners strip

**From build-plan:** feature 24
**Build attempt:** 1
**Status:** verified

**Branch:** feature/site-wide-multi-phone-settings-dynamic-contact-cms-and-dynamic-partners-strip

## Goal

Provide comprehensive, dynamic communication and institutional partnership management across the web application by:
1. Expanding site settings in `/admin/settings` to support up to 5 contact phone numbers (`contact_phone_1` through `contact_phone_5`), working hours, timezone (configurable to "GMT +1"), and operating schedule notes.
2. Unifying all phone displays across the entire web application (utility bar, mobile navigation menu, footer, contact cards, error pages, and legal pages) so that every telephone number strictly originates from site settings.
3. Completely eliminating hardcoded copy on the Contact page (`/contact`) so that working hours, timezone, operating schedule, office address, and contact card descriptions are fully editable from the dashboard.
4. Enhancing the "Our Valued Partners - Collaborating for greater impact" logo strip on both Home (`/`) and About (`/about`) pages to render dynamically from managed database records, backed by image optimization and media picker integration in `/admin/partners`.

## In scope

- **Site Settings Expansion (`Setting` Model & Admin):**
  - Expand `Setting` model with helper methods:
    - `Setting::getPhoneNumbers(): array` returning all configured non-empty phone numbers in priority order.
    - `Setting::getPrimaryPhone(): string` returning `contact_phone_1` with fallback default.
  - Add settings keys to `SettingSeeder.php` and `SettingController.php`:
    - `contact_phone_1` (Primary Hotline)
    - `contact_phone_2` (Phone Line 2)
    - `contact_phone_3` (Phone Line 3)
    - `contact_phone_4` (Phone Line 4)
    - `contact_phone_5` (Phone Line 5)
    - `contact_working_hours` (e.g. `Mon – Fri: 8:00 AM – 5:00 PM`)
    - `contact_timezone` (e.g. `GMT +1` or `WAT`)
    - `contact_working_days_note` (e.g. `Closed on public holidays`)
  - Update `app/Http/Requests/Admin/UpdateSettingsRequest.php` to validate all 5 phone fields and working hour fields.
  - Update `resources/views/admin/settings.blade.php` Section 2 with 5 dedicated phone input fields and schedule configuration inputs.
- **Site-Wide Phone Number Unification:**
  - Update `resources/views/components/utility-bar.blade.php`: display `Setting::get('contact_phone_1')`.
  - Update `resources/views/components/navbar.blade.php`: display `Setting::get('contact_phone_1')` in mobile menu.
  - Update `resources/views/components/footer.blade.php`: display `Setting::get('contact_phone_1')`.
  - Update `resources/views/components/schema-organization.blade.php`: use `Setting::get('contact_phone_1')`.
  - Update `resources/views/donate/index.blade.php`: dynamically pull hotline from `Setting::get('contact_phone_1')`.
  - Update `resources/views/privacy-policy.blade.php`: render all active phone numbers from `Setting::getPhoneNumbers()`.
  - Update `resources/views/errors/404.blade.php` and `resources/views/errors/500.blade.php`: dynamically pull hotline from `Setting::get('contact_phone_1')`.
- **Dynamic Contact Page CMS (`/contact`):**
  - Update `app/Http/Controllers/ContactController.php` to pass `phoneNumbers` (`Setting::getPhoneNumbers()`), `address`, `email`, `workingHours`, `timezone`, `workingDaysNote`, and page sections.
  - Update `resources/views/contact/index.blade.php`:
    - Direct Lines card: dynamically loops through all active phone numbers (supporting 1 to 5 numbers) with formatted `tel:` links.
    - Working Hours card: displays `$workingHours`, `($timezone)`, and `$workingDaysNote`.
    - Form header and map details: pull from `$sections` with clean fallback values.
  - Update `database/seeders/PageSectionSeeder.php` to seed all contact sections (`hero`, `office`, `form`, `map`) ensuring full manageability in `/admin/pages/contact`.
- **Dynamic Partners Strip & Partner Management Overhaul:**
  - Update `app/Http/Controllers/Admin/PartnerController.php`:
    - Integrate `ImageOptimizerService` to automatically resize and convert uploaded partner logos to WebP format.
    - Support `<x-admin.media-picker>` for selecting existing media or uploading new logos.
  - Update `resources/views/admin/partners/create.blade.php` and `resources/views/admin/partners/edit.blade.php` with `<x-admin.media-picker>`.
  - Update `resources/views/home.blade.php`: dynamic partner logos loop without static fallback duplicates.
  - Update `resources/views/about.blade.php`: dynamic partner logos loop with clean empty-state messaging when no partners exist.
  - Update `database/seeders/PartnerSeeder.php`: optimize partner logos into WebP format (`ascend.webp`, `SAA.webp`, `helpmeetaneed.webp`, `intinternational.webp`).
- **Deployment Manifest & Automated Testing:**
  - Track all created/modified files in `cpanel_changed_files.txt`.
  - Write comprehensive feature tests in `tests/Feature/SiteSettingsPhoneTest.php`, `tests/Feature/ContactPublicTest.php`, and `tests/Feature/PartnerPublicTest.php`.

## Out of scope

- External CRM or ticketing integration (Zendesk, Salesforce).
- Twilio/VoIP in-browser softphone dialing.
- Adding additional features to Milestone 4 (Feature 24 is the third and final feature of Milestone 4).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all steps are complete).
- `workflow.checkpointCommits: "disabled"`.
- Run tests (`php artisan test`) and lint checks (`php vendor/bin/pint --test`) after implementation.

## Build steps

- [x] 1. **Expand Setting model and seeders for 5 phone numbers & schedule** - add `getPhoneNumbers()` and `getPrimaryPhone()` to `App\Models\Setting`; update `database/seeders/SettingSeeder.php` to seed `contact_phone_1` through `contact_phone_5`, `contact_working_hours`, `contact_timezone` ("GMT +1"), and `contact_working_days_note`; re-seed settings.
  *Done when:* `Setting::getPhoneNumbers()` returns array with configured numbers, and all 5 phone keys exist in the database.

- [x] 2. **Update Admin Settings controller and form for multi-phone & schedule** - update `App\Http\Controllers\Admin\SettingController.php`, `App\Http\Requests\Admin\UpdateSettingsRequest.php`, and `resources/views/admin/settings.blade.php` to provide inputs for all 5 phone lines and working schedule fields with validation.
  *Done when:* Submitting `/admin/settings` successfully saves and persists all 5 phone numbers and operating schedule in cache and database.

- [x] 3. **Unify site-wide telephone displays to pull strictly from site settings** - update `utility-bar.blade.php`, `navbar.blade.php`, `footer.blade.php`, `schema-organization.blade.php`, `donate/index.blade.php`, `privacy-policy.blade.php`, and error pages (`404.blade.php`, `500.blade.php`) to use `Setting::get('contact_phone_1')` or `Setting::getPhoneNumbers()`.
  *Done when:* No hardcoded telephone strings remain in layouts or error pages, and changes in `/admin/settings` reflect site-wide immediately.

- [x] 4. **Make Contact page completely dynamic with multi-phone rendering** - update `app/Http/Controllers/ContactController.php`, `resources/views/contact/index.blade.php`, and `database/seeders/PageSectionSeeder.php` so all 4 contact cards (address, phones, email, hours/timezone) and form/map sections pull strictly from settings and CMS page sections.
  *Done when:* Visiting `/contact` displays all configured phone lines (up to 5) as interactive `tel:` links, displays the configured timezone "GMT +1" and schedule, and has zero hardcoded copy.

- [x] 5. **Upgrade Admin Partner management with ImageOptimizerService & Media Picker** - update `App\Http\Controllers\Admin\PartnerController.php`, `resources/views/admin/partners/create.blade.php`, and `resources/views/admin/partners/edit.blade.php` to use `ImageOptimizerService` and `<x-admin.media-picker>` for logo uploads and selection.
  *Done when:* Partner logos uploaded in admin are automatically converted to WebP, and existing media picker items can be selected as partner logos.

- [x] 6. **Ensure Home and About page partner strips render dynamically** - update `resources/views/home.blade.php` and `resources/views/about.blade.php` to render active partners dynamically from `$partners`, remove hardcoded image fallbacks, and optimize seeded partner logos in `database/seeders/PartnerSeeder.php`.
  *Done when:* Both `/` and `/about` display all active partners with links, and adding/removing partners in `/admin/partners` updates both pages immediately.

- [x] 7. **Automated tests, code style, and deployment tracking** - write feature tests for multi-phone settings, contact page dynamic rendering, and partner management; run `php artisan test` and `php vendor/bin/pint --test`; record all modified files in `cpanel_changed_files.txt`.
  *Done when:* Full test suite passes 100%, Pint passes with 0 violations, and `cpanel_changed_files.txt` lists all updated and new files.

## Files / areas

- `app/Models/Setting.php` - Setting model helpers for phone numbers
- `app/Http/Controllers/Admin/SettingController.php` - admin settings management for 5 phones & schedule
- `app/Http/Requests/Admin/UpdateSettingsRequest.php` - validation rules for 5 phones & schedule
- `resources/views/admin/settings.blade.php` - admin form for 5 phones & operating schedule
- `database/seeders/SettingSeeder.php` - seed defaults for 5 phones and schedule
- `resources/views/components/utility-bar.blade.php` - utility bar hotline
- `resources/views/components/navbar.blade.php` - mobile menu hotline
- `resources/views/components/footer.blade.php` - footer hotline
- `resources/views/components/schema-organization.blade.php` - structured data phone
- `resources/views/donate/index.blade.php` - donate page hotline
- `resources/views/privacy-policy.blade.php` - privacy policy contact phones
- `resources/views/errors/404.blade.php` - 404 error page hotline
- `resources/views/errors/500.blade.php` - 500 error page hotline
- `app/Http/Controllers/ContactController.php` - contact page controller
- `resources/views/contact/index.blade.php` - dynamic contact page view
- `database/seeders/PageSectionSeeder.php` - contact page sections seeding
- `app/Http/Controllers/Admin/PartnerController.php` - partner management with image optimization
- `resources/views/admin/partners/create.blade.php` - partner create view with media picker
- `resources/views/admin/partners/edit.blade.php` - partner edit view with media picker
- `resources/views/home.blade.php` - dynamic partners strip on Home
- `resources/views/about.blade.php` - dynamic partners strip on About
- `database/seeders/PartnerSeeder.php` - partner logos seeding
- `tests/Feature/SiteSettingsPhoneTest.php` - tests for multi-phone settings and site-wide propagation
- `tests/Feature/ContactPublicTest.php` - tests for dynamic contact page
- `cpanel_changed_files.txt` - cPanel deployment manifest

## Data / contracts

- `settings` table keys:
  - `contact_phone_1`: "+234 904 249 3883" (string, group: contact)
  - `contact_phone_2`: "+234 810 000 0000" (string, group: contact)
  - `contact_phone_3`: null (string, group: contact)
  - `contact_phone_4`: null (string, group: contact)
  - `contact_phone_5`: null (string, group: contact)
  - `contact_working_hours`: "Mon – Fri: 8:00 AM – 5:00 PM" (string, group: contact)
  - `contact_timezone`: "GMT +1" (string, group: contact)
  - `contact_working_days_note`: "Closed on public holidays" (string, group: contact)
- `Setting::getPhoneNumbers()`: returns array of non-empty phone number strings.
- `partners` table:
  - `name`, `logo`, `website_url`, `sort_order`, `is_active`

## Testing

- Settings tests: `php artisan test --filter=SiteSettingsPhoneTest`
- Contact page tests: `php artisan test --filter=ContactPublicTest`
- Partner tests: `php artisan test --filter=PartnerTest`
- Full test suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- All phone numbers across the entire website MUST pull from `Setting` (site settings), exactly as requested by user.
- Ensure `tel:` links clean special characters with `preg_replace('/[^0-9+]/', '', $phone)`.
- Use `ImageOptimizerService` for partner logo uploads in `PartnerController`.
- Keep dark/light theme contrast WCAG 2.2 AA compliant.
- Log every touched file in `cpanel_changed_files.txt`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":12490,"specSha256":"1d32a07435e2427fd1678db7ba9201adcb697ac38cae504afb295a83039fce9c","branch":"refs/heads/feature/site-wide-multi-phone-settings-dynamic-contact-cms-and-dynamic-partners-strip","head":"832cde1e9ba4979ac7b81301a9d88118d1c21679","baseRef":"refs/heads/main","baseCommit":"832cde1e9ba4979ac7b81301a9d88118d1c21679","sourceTree":"f85252cded8d7ef4fe06aab7222f3c84cae794f3","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Site Settings Multi-Phone & Operating Hours**:
   - Visit `/admin/settings`.
   - In Section 2 ("Contact Details"), view the 5 dedicated phone input fields (`contact_phone_1` to `contact_phone_5`), working hours, timezone ("GMT +1"), and working days note.
   - Update a phone line or operating schedule and save; verify persistence in the dashboard.

2. **Site-Wide Unified Phone Numbers**:
   - Check the utility bar on any public page (top bar): confirms phone line matches `contact_phone_1` with click-to-call link.
   - Check mobile navigation menu: verify phone line matches `contact_phone_1`.
   - Check footer: verify phone line matches `contact_phone_1`.
   - Check `/donate`, `/privacy-policy`, and `/contact`: verify phone numbers match configured settings.

3. **Dynamic Contact Page CMS**:
   - Visit `/contact`.
   - Confirm all 4 contact cards (Office, Direct Lines, Email, Hours) reflect database settings.
   - Verify all active phone numbers render with clean `tel:` links.
   - Verify working hours, timezone ("(GMT +1)"), and operating schedule note.

4. **Dynamic Partners Strip & Partner Management**:
   - Visit `/admin/partners`: test creating or editing a partner with the `<x-admin.media-picker>`. Note that uploaded partner logos are automatically converted to WebP via `ImageOptimizerService`.
   - Visit `/` (Home page) and `/about` (About page): verify the "Our Valued Partners" carousel/strip displays active partners dynamically from database without hardcoded image fallbacks.

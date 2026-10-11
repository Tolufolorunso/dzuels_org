# Feature: Privacy policy and error pages

**From build-plan:** feature 16
**Build attempt:** 1
**Branch:** feature/privacy-policy-and-error-pages
**Status:** verified

## Goal

Provide a transparent, accessible, and comprehensive Privacy Policy page for Dzuels Educational Foundation explaining data practices across contact inquiries, volunteer applications, and event registrations; ensure all public intake forms link directly to the policy; and deliver friendly, branded, accessible 404 and 500 error pages with prominent recovery pathways back to Home and Donate.

## In scope

1. **Privacy Policy Page (`/privacy-policy`)**:
   - Controller `App\Http\Controllers\PrivacyPolicyController` and route `GET /privacy-policy`.
   - Full Blade view `resources/views/privacy-policy.blade.php` wrapped in `<x-layout>` with dedicated title, meta description, canonical URL, and BreadcrumbList schema.
   - Comprehensive, plain-language privacy sections tailored to Dzuels Educational Foundation (CAC/IT/NO 123456):
     - Scope and organization identity
     - Information collected (contact messages, volunteer details, partner inquiries, event registrations)
     - Purpose and legal grounds for processing
     - Payment handling notice (Flutterwave hosted checkout / direct bank transfer; no card numbers stored on foundation servers)
     - Data storage, security, and retention policies
     - Non-disclosure and third-party data processor terms
     - Rights of data subjects (access, rectification, erasure)
     - Official contact information for data protection inquiries (`info@dzuelsfoundation.org`, AAoJ Memorial Learning Center, Ijero-Ekiti).
2. **Form Privacy Disclosures**:
   - Add reassuring privacy notice and links to `/privacy-policy` in:
     - Volunteer and Partner application forms (`resources/views/get-involved/index.blade.php`)
     - Contact message submission form (`resources/views/contact/index.blade.php`)
     - Event attendee registration form (`resources/views/events/show.blade.php`)
3. **Custom Error Pages (`resources/views/errors/`)**:
   - `resources/views/errors/404.blade.php`: Branded "Page Not Found" layout with clear explanation, prominent call-to-action buttons to **Home** (`/`) and **Donate** (`/donate`), and helpful secondary links to Programmes, Events, and Gallery.
   - `resources/views/errors/500.blade.php`: Branded "Server Error" layout with empathetic copy, recovery buttons to **Home** (`/`) and **Donate** (`/donate`), and direct support contact email.
4. **Sitemap Integration**:
   - Include `/privacy-policy` in `SitemapController` as a static public URL with `<changefreq>monthly</changefreq>` and `<priority>0.5</priority>`.
5. **Feature Test Suite**:
   - `tests/Feature/PrivacyPolicyAndErrorPagesTest.php` verifying the privacy policy route, content, form privacy links, sitemap presence, 404 response handling, and error page recovery links.

## Out of scope

- Feature 17: Accessibility audits (WCAG 2.2 AA contrast, Lighthouse performance benchmarks, DNS pointing, Google Search Console manual submission).
- Terms of service or legal document generation outside the privacy policy.
- Automated error tracking service integrations (e.g., Sentry, Bugsnag).

## Build loop

Following project policy (`workflow.stepReview: "feature"`, `workflow.checkpointCommits: "disabled"`):
- Implement steps 1 through 5 in sequence.
- Run narrow checks during implementation.
- Run full Verify suite (`php artisan test` and `php vendor/bin/pint --test`) after all steps pass.

## Build steps

- [x] 1. **Privacy Policy Page & Route**: Implement `App\Http\Controllers\PrivacyPolicyController`, route `GET /privacy-policy`, and `resources/views/privacy-policy.blade.php` with full layout, SEO tags, breadcrumbs, and detailed privacy disclosures.
       - *Done when:* `GET /privacy-policy` returns HTTP 200 with structured privacy policy sections and proper metadata.

- [x] 2. **Form Privacy Notices**: Update public forms in `get-involved/index.blade.php`, `contact/index.blade.php`, and `events/show.blade.php` to include an explicit notice linking to `/privacy-policy`.
       - *Done when:* All public form areas display a readable privacy assurance notice with a link to `/privacy-policy`.

- [x] 3. **Branded 404 Error Page**: Create `resources/views/errors/404.blade.php` styled with foundation brand tokens, friendly message, and prominent recovery links to Home and Donate.
       - *Done when:* Accessing an undefined URL returns 404 and displays the branded error view with working Home and Donate links.

- [x] 4. **Branded 500 Error Page**: Create `resources/views/errors/500.blade.php` styled with foundation brand tokens, reassuring copy, recovery links to Home and Donate, and support email.
       - *Done when:* The 500 template renders with brand styling, clear recovery links, and support contact.

- [x] 5. **Sitemap Inclusion & Automated Test Suite**: Add `/privacy-policy` to `SitemapController`; create `tests/Feature/PrivacyPolicyAndErrorPagesTest.php` testing privacy page, form links, sitemap presence, 404 rendering, and 500 template; verify with `php artisan test` and `php vendor/bin/pint --test`.
       - *Done when:* All tests in `PrivacyPolicyAndErrorPagesTest` and full regression suite pass with 100% green status.

## Files / areas

- `app/Http/Controllers/PrivacyPolicyController.php` - handles `/privacy-policy`
- `routes/web.php` - route definition
- `resources/views/privacy-policy.blade.php` - privacy policy view
- `resources/views/get-involved/index.blade.php` - volunteer/partner form privacy link
- `resources/views/contact/index.blade.php` - contact form privacy link
- `resources/views/events/show.blade.php` - event registration form privacy link
- `resources/views/errors/404.blade.php` - custom 404 error page
- `resources/views/errors/500.blade.php` - custom 500 error page
- `app/Http/Controllers/SitemapController.php` - inclusion of `/privacy-policy`
- `tests/Feature/PrivacyPolicyAndErrorPagesTest.php` - automated tests

## Data / contracts

- Route: `GET /privacy-policy` -> name `privacy-policy`
- Error views: Laravel convention `resources/views/errors/404.blade.php` and `resources/views/errors/500.blade.php`
- Recovery paths: Must include links to `url('/')` and `url('/donate')`
- Form disclosures: Inform users that data submitted is processed solely for foundation operations in accordance with the Privacy Policy.

## Testing

- Feature tests: `php artisan test --filter=PrivacyPolicyAndErrorPagesTest`
- Full suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- Maintain dark mode anti-flicker and brand palette (`maroon`, `cream`, `crimson`, `mint`, `surface`, `border`) in all error and privacy views.
- Error pages should not depend on complex database queries or settings that could fail when the database is unreachable in a 500 scenario.
- Strictly adhere to human Git attribution `tolufolorunso <tolufolorunso@yahoo.com>`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":6995,"specSha256":"7346a1e621b75cbc3ed856f6746a6159416dd0bd41c8a0304da975593af867cf","branch":"refs/heads/feature/privacy-policy-and-error-pages","head":"d80553a6d390c1f5a54fdd41d4e93bff0e81e65a","baseRef":"refs/heads/main","baseCommit":"d80553a6d390c1f5a54fdd41d4e93bff0e81e65a","sourceTree":"c142a856922bd8bfa9851ff05e27c2379c3ed17e","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Privacy Policy Page**:
   - Navigate to `http://localhost:8000/privacy-policy`.
   - Verify page renders with `<x-layout>`, header, dark mode toggle, and footer.
   - Verify comprehensive data handling disclosures for Dzuels Educational Foundation (CAC/IT/NO 123456).
   - Check page source to verify canonical URL (`http://localhost:8000/privacy-policy`) and BreadcrumbList JSON-LD schema.

2. **Verify Form Privacy Notices & Links**:
   - Navigate to `http://localhost:8000/contact` and verify privacy notice below message field with clickable link to `/privacy-policy`.
   - Navigate to `http://localhost:8000/get-involved` and verify privacy notices below both Volunteer and Partner forms with clickable links to `/privacy-policy`.
   - Navigate to `http://localhost:8000/events/itd-2026-ijero-ekiti` and verify privacy notice in attendee registration form with clickable link to `/privacy-policy`.

3. **Verify Custom 404 Error Page**:
   - Navigate to an intentional non-existent URL (e.g. `http://localhost:8000/non-existent-test-404`).
   - Confirm HTTP 404 status, branded badge, empathetic copy, and prominent buttons to **Return to Homepage** (`/`) and **Support With a Donation** (`/donate`).
   - Confirm active section links to About, Programmes, Events, Gallery, and Contact.

4. **Verify Custom 500 Error Page**:
   - Verify `resources/views/errors/500.blade.php` renders cleanly with brand styling, anti-flicker dark mode engine, recovery buttons to Home (`/`) and Donate (`/donate`), and direct foundation support email (`info@dzuelsfoundation.org`).

5. **Verify Sitemap XML Integration**:
   - Navigate to `http://localhost:8000/sitemap.xml`.
   - Verify that `<loc>http://localhost:8000/privacy-policy</loc>` is included with `<changefreq>monthly</changefreq>` and `<priority>0.5</priority>`.

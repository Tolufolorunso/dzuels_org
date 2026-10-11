# Feature: SEO foundation

**From build-plan:** feature 15
**Build attempt:** 1
**Branch:** feature/seo-foundation
**Status:** verified

## Goal

Equip every public page of the Dzuels Educational Foundation web application with comprehensive, production-grade technical and content SEO: unique titles, descriptive meta descriptions, canonical URLs, complete Open Graph and Twitter Card tags, Organization and BreadcrumbList JSON-LD structured data, an auto-generated dynamic `sitemap.xml`, a configured `robots.txt` disallowing administration paths, and verification of the 301 permanent redirect from `/partner` to `/get-involved`.

## In scope

1. **Public Layout SEO Header (`<x-layout>`)**:
   - Dynamic `<title>` and `<meta name="description">` with curated foundation fallbacks.
   - `<link rel="canonical" href="...">` reflecting the canonical page URL, supporting explicit overrides.
   - Full Open Graph metadata: `og:site_name`, `og:title`, `og:description`, `og:url`, `og:type`, `og:image`, `og:locale` (`en_NG`).
   - Full Twitter Card metadata: `twitter:card` (`summary_large_image`), `twitter:title`, `twitter:description`, `twitter:image`.
   - `<meta name="robots" content="...">` defaulting to `index, follow`.
   - Global Organization JSON-LD script block with organization name, official logo URL, canonical website URL, contact points, postal address (Ijero-Ekiti, Ekiti State, Nigeria), and social links from `Setting`.
   - BreadcrumbList JSON-LD schema support for hierarchical pages.

2. **Page-by-Page SEO Metadata**:
   - Audit and configure distinct, high-relevance title tags, meta descriptions, and breadcrumb structures across all public Blade templates:
     - Home (`/`)
     - About Us (`/about`)
     - Programmes Index (`/programmes`)
     - Programme Details (`/programmes/{slug}`) - leveraging `seo_title`, `seo_description`, and featured image
     - Events Index (`/events`)
     - Event Details (`/events/{slug}`) - leveraging `seo_title`, `seo_description`, and featured image
     - Photo Gallery Index (`/gallery`)
     - Photo Gallery Album (`/gallery/{slug}`)
     - Donate Page (`/donate`)
     - Get Involved (`/get-involved`)
     - Contact Us (`/contact`)

3. **Dynamic XML Sitemap (`/sitemap.xml`)**:
   - Dedicated `SitemapController` serving standard sitemap XML (`http://www.sitemaps.org/schemas/sitemap/0.9`).
   - Dynamically aggregates all static public pages (`/`, `/about`, `/programmes`, `/events`, `/gallery`, `/donate`, `/get-involved`, `/contact`) with assigned priorities and change frequencies.
   - Dynamically includes active programmes (`is_active = true`), active events (`is_active = true`), and active gallery albums (`is_active = true`), populating `<lastmod>` with each model's `updated_at`.
   - Strictly excludes admin URLs (`/admin/*`), draft/inactive records, and dormant modules (news/newsletter).

4. **Robots Directives (`/robots.txt`)**:
   - Route and updated `public/robots.txt` specifying `User-agent: *`, `Allow: /`, `Disallow: /admin`, `Disallow: /admin/`, and dynamic declaration `Sitemap: {{ url('/sitemap.xml') }}`.

5. **Legacy 301 Redirect**:
   - Ensure `/partner` performs a silent, permanent 301 redirect to `/get-involved`.

6. **Feature Test Suite**:
   - Dedicated `tests/Feature/SeoFoundationTest.php` testing SEO headers, canonical URLs, Open Graph / Twitter tags, JSON-LD validity, sitemap XML output and dynamic URL inclusion, robots.txt directives, and the 301 redirect.

## Out of scope

- Feature 16: Privacy policy and error pages (404/500).
- Feature 17: WCAG 2.2 AA accessibility audit, Lighthouse performance benchmarks, live DNS pointing, and manual Google Search Console verification.
- Re-enabling public routes or navigation for dormant News and Newsletter modules.

## Build loop

Following project policy (`workflow.stepReview: "feature"`, `workflow.checkpointCommits: "disabled"`):
- Step 1 to 5 implemented in sequence.
- Tests and verification executed after each step.
- Full verification pass and code walkthrough at completion before `/complete`.

## Build steps

- [x] 1. **Public Layout SEO Tags & Metadata Props**: Enhance `resources/views/components/layout.blade.php` to accept canonical URL, OG tags, Twitter tags, robots directives, and schema slots/props with high-trust defaults.
       - *Done when:* Public layout emits `<title>`, `<meta name="description">`, `<link rel="canonical">`, Open Graph tags (`og:title`, `og:description`, `og:image`, `og:url`, `og:type`), Twitter Card tags, and robots meta.

- [x] 2. **JSON-LD Structured Data Components**: Create reusable schema views/components for Organization (`@type: EducationalOrganization` / `NGO`) and BreadcrumbList (`@type: BreadcrumbList`), binding site settings for contact, address, and social links.
       - *Done when:* Public pages render valid, parseable JSON-LD blocks in `<head>` for Organization and hierarchical breadcrumbs.

- [x] 3. **Page-by-Page SEO Metadata across Public Templates**: Audit and enrich all public Blade views (`home`, `about`, `programmes.index`, `programmes.show`, `events.index`, `events.show`, `gallery.index`, `gallery.album`, `donate`, `get-involved`, `contact`) with unique, descriptive titles, meta descriptions, and breadcrumb structures.
       - *Done when:* Every public page renders its own distinct title and meta description, and detail pages respect model-level `seo_title` / `seo_description`.

- [x] 4. **Dynamic Sitemap XML Endpoint (`/sitemap.xml`)**: Implement `App\Http\Controllers\SitemapController`, register route `GET /sitemap.xml`, and render standard XML including static routes and active programmes, events, and gallery albums with `<lastmod>`.
       - *Done when:* `GET /sitemap.xml` returns HTTP 200 with `text/xml` or `application/xml` content-type, valid XML syntax, and contains active URLs while excluding admin and dormant routes.

- [x] 5. **Robots.txt Directives & 301 Redirect**: Implement `App\Http\Controllers\RobotsController` for `GET /robots.txt` (and update `public/robots.txt`), disallowing `/admin` and declaring the sitemap URL; verify `/partner` 301 redirect to `/get-involved`.
       - *Done when:* `GET /robots.txt` outputs `Disallow: /admin` and dynamic `Sitemap:` line, and `GET /partner` returns 301 redirecting to `/get-involved`.

- [x] 6. **Automated SEO Test Suite & Verification**: Create `tests/Feature/SeoFoundationTest.php` covering all metadata elements, JSON-LD schemas, sitemap XML structure, robots.txt, and 301 redirects; run test suite and Pint formatting check.
       - *Done when:* `php artisan test` and `php vendor/bin/pint --test` pass with 100% green status.

## Files / areas

- `resources/views/components/layout.blade.php` - public layout `<head>` metadata, canonical, Open Graph, Twitter cards, JSON-LD blocks
- `resources/views/components/schema-organization.blade.php` - Organization JSON-LD component
- `resources/views/components/schema-breadcrumbs.blade.php` - BreadcrumbList JSON-LD component
- `resources/views/home.blade.php` - Home page title & meta description
- `resources/views/about.blade.php` - About page title, description, and breadcrumbs
- `resources/views/programmes/index.blade.php` - Programmes index SEO
- `resources/views/programmes/show.blade.php` - Programme detail dynamic SEO & breadcrumbs
- `resources/views/events/index.blade.php` - Events index SEO
- `resources/views/events/show.blade.php` - Event detail dynamic SEO & breadcrumbs
- `resources/views/gallery/index.blade.php` - Gallery index SEO
- `resources/views/gallery/album.blade.php` - Gallery album SEO & breadcrumbs
- `resources/views/donate.blade.php` - Donate page SEO & breadcrumbs
- `resources/views/get-involved.blade.php` - Get Involved SEO & breadcrumbs
- `resources/views/contact.blade.php` - Contact page SEO & breadcrumbs
- `app/Http/Controllers/SitemapController.php` - dynamic sitemap generation
- `app/Http/Controllers/RobotsController.php` - dynamic robots.txt generation
- `public/robots.txt` - static robots fallback aligned with sitemap and admin disallow
- `routes/web.php` - sitemap and robots routes
- `tests/Feature/SeoFoundationTest.php` - comprehensive automated SEO test suite

## Data / contracts

- **Sitemap XML**: Valid XML declaration `<?xml version="1.0" encoding="UTF-8"?>`, namespace `http://www.sitemaps.org/schemas/sitemap/0.9`, `<urlset>` containing `<url>` elements with `<loc>`, `<lastmod>` (W3C ISO 8601 `Y-m-d\TH:i:sP` or `Y-m-d`), `<changefreq>`, and `<priority>`.
- **Robots.txt**: `User-agent: *`, `Allow: /`, `Disallow: /admin`, `Disallow: /admin/`, `Sitemap: <url>/sitemap.xml`.
- **JSON-LD Schema**:
  - `Organization`: `@context: "https://schema.org"`, `@type: "EducationalOrganization"`, `name: "Dzuels Educational Foundation"`, `url`, `logo`, `contactPoint`, `address`, `sameAs`.
  - `BreadcrumbList`: `@context: "https://schema.org"`, `@type: "BreadcrumbList"`, `itemListElement: [{ "@type": "ListItem", "position": 1, "name": "...", "item": "..." }]`.
- **HTTP 301**: Permanent redirect `/partner` -> `/get-involved`.

## Testing

- Logic and HTTP test suite: `php artisan test --filter=SeoFoundationTest`
- Complete regression suite: `php artisan test`
- Code formatting and style: `php vendor/bin/pint --test`

## Notes for the AI

- Preserve all existing styling, anti-flicker dark mode script, and asset bundles in `layout.blade.php`.
- Ensure JSON-LD scripts are encoded safely using `json_encode(..., JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)` or Blade `@json(...)`.
- The sitemap must only include public, active entities (`is_active = true`) and must gracefully handle cases where no programmes/events/albums exist.
- News and Newsletter modules remain dormant; do not include them in the sitemap or public navigation.
- Use human Git attribution `tolufolorunso <tolufolorunso@yahoo.com>` for any git actions.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":9883,"specSha256":"d7d28e900c4f45d2c4b1ace571cfa5c4fa9dd397efb5e00b83c6e8e06cd8da56","branch":"refs/heads/feature/seo-foundation","head":"f807ccd01e2547c18851839d6492ce86aa76a62a","baseRef":"refs/heads/main","baseCommit":"f807ccd01e2547c18851839d6492ce86aa76a62a","sourceTree":"a1e1a5421956def2fd01a0e7027f5a750ac1c851","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Sitemap XML**:
   - Navigate to `http://localhost:8000/sitemap.xml`.
   - Verify that standard XML is returned with header `Content-Type: text/xml; charset=utf-8`.
   - Verify that all static core pages (`/`, `/about`, `/programmes`, `/events`, `/gallery`, `/donate`, `/get-involved`, `/contact`) and all active dynamic programmes, events, and gallery albums are listed with their respective `<lastmod>`, `<changefreq>`, and `<priority>`.

2. **Verify Robots Directives**:
   - Navigate to `http://localhost:8000/robots.txt`.
   - Verify that `text/plain` is returned containing `User-agent: *`, `Disallow: /admin`, and `Sitemap: http://localhost:8000/sitemap.xml`.

3. **Verify 301 Permanent Redirect**:
   - Navigate to `http://localhost:8000/partner`.
   - Observe immediate 301 redirection to `http://localhost:8000/get-involved`.

4. **Verify SEO Meta Tags & JSON-LD**:
   - Inspect page source on `http://localhost:8000/`, `http://localhost:8000/about`, and any dynamic programme or event detail page.
   - Verify unique `<title>`, `<meta name="description">`, `<link rel="canonical">`, Open Graph tags (`og:*`), Twitter Cards (`twitter:*`), and `<script type="application/ld+json">` blocks for `EducationalOrganization` and `BreadcrumbList`.

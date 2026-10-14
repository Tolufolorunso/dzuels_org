# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash 137dd1ae8185d1237a8a242987fa04bf1b699db4be1b5d147b5006a4e62377fa -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (registered 2015) providing education support, digital skills, poverty relief, and youth empowerment in Ijero-Ekiti and beyond.

- **Milestones 1–7 (Shipped):** Overhauled platform across 29 features: WCAG 2.2 AA accessibility, responsive design, brand asset suite, ITD 2026 overhaul, server-rendered lightbox, WordPress-style Page CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), MySQL export tooling, hotline updates, hero overlays, dynamic contact CMS, dynamic partners strip, resilient media pipeline with GD fallback, multi-photo bulk uploader, dynamic site logo in settings, Givebutter U.S. donations, authentic 6 Impact Causes CMS, $\le 180\text{ KB}$ WebP optimization engine, dynamic 4-photo Famoyinka Library CMS, Get Involved overhaul, and universal media picker `<x-admin.media-picker>` across all forms.
- **Milestone 8 (Active Target):** Unified Central Media Hub, Global Asset Management & cPanel Public Path Hardening:
  1. Fix cPanel upload path mismatch by binding `$app->usePublicPath(__DIR__)` in `public/index.php` so all uploaded media saves directly to the active web folder and serves cleanly without 404 errors.
  2. Build a dedicated top-level **Media Library** dashboard at `/admin/media` with an admin navbar tab.
  3. Provide a unified visual grid showcasing all foundation media (both gallery collection photos and general site/CMS assets) with multi-criteria search by photo name/caption/filename, date uploaded filters (date picker & quick ranges), sort controls (newest, oldest, A-Z), category tabs (*All Media*, *Gallery Collections*, *Site Assets*), file size badges ($\le 180\text{ KB}$ WebP), dimensions, and copyable public URLs.
  4. Add direct drag-and-drop WebP uploading into the central hub with strict $\le 180\text{ KB}$ ceiling and original file deletion.
  5. Connect `<x-admin.media-picker>` across all CMS editors to the unified media catalog with the same search and date filters.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, GTBank transfer details, and Givebutter U.S. giving.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries.
- **Event Participants & Schools:** Students and educators attending ITD 2026 to learn AI tools, spelling bees, and reading competitions.
- **Beneficiaries, Parents & Students:** Community members accessing literacy resources, activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, role permissions, global settings (logo, multi-phone lines, Givebutter link, hours/timezone), media library deletions, and database exports.
  - **Editor / Content Manager:** Accessing the Central Media Library to upload and organize assets, updating page content, programmes, events, sponsors, partner logos, and gallery photos.
  - **Support / Viewer:** Reading incoming contact enquiries, volunteer submissions, and event records.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- Multi-role staff access at `/admin` (Super Admin, Editor, Support); no public user registrations.
- Form submissions collect personal data handled in strict compliance with the foundation's privacy policy.
- Online payments are offloaded completely to Flutterwave and Givebutter hosted checkouts; no payment card data is processed on-server.

## Features

### Milestones 1–7 Features (Completed & Shipped)

1. **Public layout & brand theme** - Header, utility bar, footer, brand typography, flash-free dark mode. *(Completed)*
2. **Admin login & site settings** - Secure `/admin` sign-in with editable impact numbers and contact info. *(Completed)*
3. **Home page** - Mission, vision, DRNICER values, causes, featured project, event teaser, partners. *(Completed)*
4. **Team & partners** - Dynamic management of Trustees, Management, Advisors, and Partners. *(Completed)*
5. **About page** - 10-year story, approach, leadership profiles with LinkedIn, and partners. *(Completed)*
6. **Programmes** - Programme catalog with dedicated detail pages. *(Completed)*
7. **Events & Ijero Tech Day** - Dedicated ITD event landing page and schedule highlights. *(Completed)*
8. **Gallery** - 5 collections, 125 pre-optimized WebP photos, masonry grid, and lightbox. *(Completed)*
9. **Donate page** - Flutterwave online giving, GTBank transfer details, and trust FAQs. *(Completed)*
10. **Volunteer & partner form** - Get Involved intake forms with spam protection. *(Completed)*
11. **Contact page** - Contact form, hotline numbers, office address, and Google Map. *(Completed)*
12. **News** - Staff article publishing engine. *(Backend completed; dormant in UI)*
13. **Newsletter signup** - Footer subscriber capture with CSV export. *(Backend completed; dormant in UI)*
14. **Site refinement & ITD 2026 overhaul** - Responsiveness pass (no horizontal scroll) and modern admin UI. *(Completed)*
15. **SEO foundation** - Dynamic meta tags, Open Graph, Schema.org JSON-LD, sitemap, and robots.txt. *(Completed)*
16. **Privacy policy & error pages** - CAC-compliant privacy terms and branded 404/500 error pages. *(Completed)*
17. **Accessibility & launch readiness** - WCAG 2.2 AA contrast, visible focus, skip links, and `app:launch-check`. *(Completed)*
18. **Brand assets & gallery optimization** - Full favicon suite (`favicon.ico`, apple-touch-icon, webmanifest); ITD 2026 overhaul; gallery lightbox. *(Completed)*
19. **Page CMS & user access control** - Visual page content editor in `/admin/pages`; RBAC (`super_admin`, `editor`, `support`); password reset. *(Completed)*
20. **MySQL export suite** - Automated Artisan export (`php artisan db:export-sql`) for 1-click phpMyAdmin import. *(Completed)*
21. **Production MariaDB config & hotline update** - MariaDB production config, root `dzuels_database.sql`, hotline `+234 904 249 3883`. *(Completed)*
22. **Automated image optimization & gallery management** - Native GD WebP optimizer, media picker component, gallery collections CRUD. *(Completed)*
23. **Famoyinka Library programme & visual outreach** - Dedicated public programme page, dedicated album, curated action photos. *(Completed)*
24. **Multi-phone settings & dynamic contact CMS** - Up to 5 contact hotlines, dynamic `/contact` page with timezone, dynamic partners strip. *(Completed)*
25. **Resilient image pipeline & bulk uploader** - GD fallback to direct storage, multi-image bulk gallery upload, metadata editor. *(Completed)*
26. **Site logo in settings & 6 Impact Causes CMS** - Customizable site logo, Givebutter U.S. giving button, 6 authentic causes on Home. *(Completed)*
27. **Production release suite & launch verification** - Refreshed `dzuels_database.sql`, synchronized upload manifest, test & lint audit. *(Completed)*
28. **180 KB WebP optimizer hardening, unclipped causes & Library CMS** - Strict $\le 180\text{ KB}$ WebP ceiling, original cleanup, unclipped causes graphic, 4-photo dynamic Library mosaic, Get Involved UI overhaul. *(Completed)*
29. **Universal gallery media picker & cover photo optimizer** - `<x-admin.media-picker>` integrated across Gallery Album, Programme, News, and Team forms; centralized cover photo optimization ($\le 180\text{ KB}$ WebP); dual-input validators. *(Completed)*

### Milestone 8 Features (Active Build Order)

30. **Unified Central Media Hub, global asset management & cPanel public web path resolution** - bind `$app->usePublicPath(__DIR__)` in `public/index.php` and verify directory creation with correct permissions so uploaded media on cPanel saves directly to the active web folder and serves without 404s; create a top-level **Media Library** dashboard at `/admin/media` with an admin navbar tab; present a unified visual grid showcasing all foundation media (both gallery collection photos and general site/CMS assets) with multi-criteria search by photo name/caption/filename, date uploaded filters (date picker & quick ranges), sort controls (newest, oldest, A-Z), category tabs (*All Media*, *Gallery Collections*, *Site Assets*), file size badges ($\le 180\text{ KB}$ WebP), dimensions, and copyable public URLs; add direct drag-and-drop WebP uploading into the central hub; and connect `<x-admin.media-picker>` across all CMS editors to the unified media catalog with the same search and date filters.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Donor receipts and recurring giving**: Automated receipts and recurring gifts.

## Data model

### User
- `id` (bigint, PK), `name` (string), `email` (string, unique), `password` (string), `role` (super_admin, editor, support), `is_active` (boolean), `timestamps`

### MediaItem / Unified Asset Catalog
- `id` (bigint, PK)
- `filename` (string)
- `file_path` (string) - relative public path (e.g. `uploads/media/filename.webp`)
- `file_size` (integer) - bytes
- `mime_type` (string) - e.g. `image/webp`
- `width` (integer, nullable), `height` (integer, nullable)
- `title` (string, nullable), `caption` (text, nullable), `alt_text` (text, nullable)
- `source_type` (string: `gallery`, `general`, `cms`, `partner`, `programme`)
- `gallery_photo_id` (bigint, FK, nullable)
- `timestamps`

### Page & PageSection (Dynamic CMS)
- `id` (bigint, PK), `page_key` (string), `section_key` (string), `headline` (string, nullable), `subheadline` (string, nullable), `body_text` (text, nullable), `image_path` (string, nullable), `button_text` (string, nullable), `button_url` (string, nullable), `meta_data` (json, nullable), `sort_order` (integer), `timestamps`

### Event & EventSponsor
- `events`: `id`, `title`, `slug`, `summary`, `description`, `objective`, `event_date`, `venue`, `banner_image`, `schedule` (json), `is_registration_open`, `sort_order`, `timestamps`
- `event_sponsors`: `id`, `event_id` (FK), `name`, `logo_path`, `tier`, `website_url`, `sort_order`, `timestamps`

### Setting
- `id` (bigint, PK), `key` (string, unique) - `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `contact_working_hours`, `contact_timezone`, `contact_working_days_note`, impact numbers, banking, registration, `value` (text, nullable), `type` (text, integer, json, boolean), `timestamps`

### Programme
- `id` (bigint, PK), `title` (string), `slug` (string, unique), `summary` (text), `body` (longText), `cover_image` (string, nullable), `partner_name` (string, nullable), `sort_order` (integer), `is_featured` (boolean), `timestamps`

### GalleryAlbum & GalleryPhoto
- `gallery_albums`: `id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `timestamps`
- `gallery_photos`: `id`, `gallery_album_id` (FK), `image_path`, `caption`, `alt_text`, `year` (nullable), `sort_order`, `timestamps`

### TeamMember & Partner
- `team_members`: `id`, `name`, `role`, `group` (trustee, management, advisor), `field`, `bio`, `photo`, `linkedin_url`, `sort_order`, `is_active`, `timestamps`
- `partners`: `id`, `name`, `logo`, `website_url`, `sort_order`, `is_active`, `timestamps`

### ContactMessage & VolunteerApplication
- `contact_messages`: `id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `is_read`, `timestamps`
- `volunteer_applications`: `id`, `full_name`, `email`, `phone`, `age_band`, `work_status`, `skills`, `status`, `timestamps`

## Tech stack

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+ in development and production (`server161.web-hosting.com`), Eloquent ORM.
- **Image Optimization & Upload Pipeline:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies. Strict iterative optimization loop enforcing <= 180 KB WebP output and automated uncompressed original deletion.
- **Admin Panel:** Tailored SaaS administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js for interactive widgets, lightbox, multi-photo bulk upload, central media manager, and media picker modal.
- **Deployment Manifest:** Project root `cpanel_changed_files.txt` tracking created and modified files for selective cPanel synchronization.
- **Testing & Quality:** PHPUnit 12 (`php artisan test`) and Laravel Pint code style formatting.

## Monetization

Non-profit charity donations. The platform does not sell products or show advertisements. Giving flows through:
1. Flutterwave hosted e-payment checkout (`https://flutterwave.com/pay/j0byxgjs0nma`).
2. GTBank Plc. direct bank transfers (NGN Account `0450072850`).
3. Givebutter hosted e-payment checkout for U.S. diaspora donors (`https://givebutter.com/dzuels-foundation`).
4. In-kind and international giving coordination via email (`info@dzuelsfoundation.org`).

## UI/UX

- **Design Tokens:**
  - Maroon Heading: `#6A0406` | Crimson Primary: `#B4070A` | Warm Cream: `#F9DEC9` | Soft Mint Accent: `#56E39F` | Body Text: `#3B4456`
  - Page Background (Light default): `#FFFFFF` | Dark Mode Background: `#140607` | Dark Mode Surface: `#241012` | Footer Background: `#2B0203`
- **Core Public Routes:**
  - `/` (Home), `/about` (About Us), `/programmes` & `/programmes/{slug}`, `/events` & `/events/{slug}`, `/gallery` & `/gallery/{album}`, `/donate`, `/get-involved`, `/contact`, `/privacy-policy`, `/admin` (Dashboard & Central Media Hub).

## Deployment

- **Target Hosting:** Namecheap cPanel (`server161.web-hosting.com`) with PHP 8.3/8.4 and MariaDB 10.3+.
- **Database Migration:** Managed via root `dzuels_database.sql` and `php artisan db:export-sql` for 1-click import into cPanel phpMyAdmin.
- **File Upload Tracking:** Active `cpanel_changed_files.txt` at project root.
- **Web Root Alignment:** Explicit binding of `$app->usePublicPath(__DIR__)` in `public/index.php` ensuring all uploaded assets write directly to the active web folder (`public_html/uploads/...`) without 404 path mismatches.
- **Domain:** `dzuelsfoundation.org` with AutoSSL / Let's Encrypt certificates.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm run build`, `php artisan optimize`.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1` or `localhost`.

## Open questions

*(None. All requirements for Milestone 8 / Feature 30 confirmed and ready for implementation.)*

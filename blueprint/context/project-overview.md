# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash e5163fb1fbb406c7fbd5218310eb47e5b3c02af4a0f4a59fa8c11a7e4b63058f -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (registered 2015) providing education support, digital skills, poverty relief, and youth empowerment in Ijero-Ekiti and beyond.

- **Milestone 1 (Shipped):** Overhauled the web application across 17 features: verified WCAG 2.2 AA accessibility, responsive design (zero horizontal overflow), SEO foundation, dynamic settings, event showcase, pre-optimized WebP photo gallery, donation channels, contact/volunteer inboxes, and launch readiness diagnostic commands.
- **Milestone 2 (Shipped):** Completed brand assets (full favicon suite), ITD 2026 overhaul (October 3, 2026 date, AI empowerment objective, dynamic sponsor tiering), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Shipped):** Production MariaDB configuration, root `dzuels_database.sql` export, hotline update (`+234 904 249 3883`), hero imagery overlays, and Spelling Bee image fixes.
- **Milestone 4 (Shipped):** Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Children's Library Integration, multi-phone settings (up to 5 lines), dynamic contact CMS (custom schedule/timezone), and dynamic partners strip on Home and About.
- **Milestone 5 (Shipped):** Production Launch Hardening, Resilient Media Pipeline, Givebutter Diaspora Donations & 6 Impact Causes CMS across Features 25–27.
- **Milestone 6 (Shipped):** Production Refinements, Image Optimizer Hardening (<= 180 KB WebP), Dynamic Library Photos & Get Involved UI Overhaul across Feature 28.
- **Milestone 7 (Active Target):** Universal Gallery Media Picker & Centralized Cover Photo Optimizer:
  1. Universal integration of `<x-admin.media-picker>` across Gallery Collection create/edit (`cover_image`), Programme create/edit (`cover_image`), News create/edit (`cover_image`), and Team Member create/edit (`photo`) so every photo upload has the option to select existing photos from the gallery.
  2. Centralized optimization enforcing $\le 180\text{ KB}$ WebP format and original file cleanup on all cover photo uploads.
  3. Dual-input controller and Form Request handling for direct file uploads and selected gallery paths.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, GTBank transfer details, and Givebutter U.S. giving.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries.
- **Event Participants & Schools:** Students and educators attending ITD 2026 to learn AI tools, spelling bees, and reading competitions.
- **Beneficiaries, Parents & Students:** Community members accessing literacy resources, activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, role permissions, global settings (logo, multi-phone lines, Givebutter link, hours/timezone), and database exports.
  - **Editor / Content Manager:** Updating page text/images via visual CMS (including the 6 impact causes and library photos in `/admin/pages/home`), managing programmes, events, sponsors, partner logos, and managing gallery photos with bulk upload.
  - **Support / Viewer:** Reading incoming contact enquiries, volunteer submissions, and event records.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- Multi-role staff access at `/admin` (Super Admin, Editor, Support); no public user registrations.
- Form submissions collect personal data handled in strict compliance with the foundation's privacy policy.
- Online payments are offloaded completely to Flutterwave and Givebutter hosted checkouts; no payment card data is processed on-server.

## Features

### Milestone 1 Features (Completed & Shipped)

1. **Public layout and brand theme** - Header, utility bar, footer, brand typography, and flash-free dark mode. *(Completed)*
2. **Admin login and site settings** - Secure `/admin` sign-in with editable impact numbers and contact info. *(Completed)*
3. **Home page** - Mission, vision, DRNICER values, causes, featured project, event teaser, and partners. *(Completed)*
4. **Team and partners** - Dynamic management of Trustees, Management, Advisors, and Partners. *(Completed)*
5. **About page** - 10-year story, approach, leadership profiles with LinkedIn, and partners. *(Completed)*
6. **Programmes** - Programme catalog with dedicated detail pages. *(Completed)*
7. **Events & Ijero Tech Day** - Dedicated ITD event landing page and schedule highlights. *(Completed)*
8. **Gallery** - 5 collections, 125 pre-optimized WebP photos, masonry grid, and lightbox. *(Completed)*
9. **Donate page** - Flutterwave online giving, GTBank transfer details, and trust FAQs. *(Completed)*
10. **Volunteer and partner form** - Get Involved intake forms with spam protection. *(Completed)*
11. **Contact page** - Contact form, hotline numbers, office address, and Google Map. *(Completed)*
12. **News** - Staff article publishing engine. *(Backend completed; dormant in UI)*
13. **Newsletter signup** - Footer subscriber capture with CSV export. *(Backend completed; dormant in UI)*
14. **Site refinement & ITD 2026 overhaul** - Responsiveness pass (no horizontal scroll) and modern admin UI. *(Completed)*
15. **SEO foundation** - Dynamic meta tags, Open Graph, Schema.org JSON-LD, sitemap, and robots.txt. *(Completed)*
16. **Privacy policy and error pages** - CAC-compliant privacy terms and branded 404/500 error pages. *(Completed)*
17. **Accessibility and launch readiness** - WCAG 2.2 AA contrast, visible focus, skip links, and `app:launch-check`. *(Completed)*

### Milestone 2 Features (Completed & Shipped)

18. **Brand assets, ITD 2026 event overhaul and gallery optimization** - Full favicon suite (`favicon.ico`, apple-touch-icon, webmanifest); ITD 2026 event page updated with backdrop banner, October 3 2026 date, revised AI empowerment objective, dynamic sponsors, and distinct archives; server-rendered gallery masonry and lightbox. *(Completed)*
19. **Site-wide page content management, user access control and password recovery** - WordPress-style page content editor in `/admin/pages`; role-based user management (`super_admin`, `editor`, `support`); password reset workflow. *(Completed)*
20. **MySQL database export and cPanel phpMyAdmin migration suite** - Automated Artisan database export (`php artisan db:export-sql`) generating clean MySQL/MariaDB SQL dump and cPanel deployment guide. *(Completed)*

### Milestone 3 Features (Completed & Shipped)

21. **Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements** - MariaDB production config; root `dzuels_database.sql`; hotline `+234 904 249 3883` site-wide; contextual hero imagery overlays; authentic Spelling Bee cover photo. *(Completed)*

### Milestone 4 Features (Completed & Shipped)

22. **Automated image optimization, global media picker and gallery collection/photo management** - Native PHP GD WebP optimizer on dashboard uploads; global media picker component; gallery collections CRUD/reordering; bulk and single photo deletion; standardized year labeling. *(Completed)*
23. **Famoyinka Memorial Children's Library programme integration and curated visual outreach** - Dedicated public programme page at `/programmes/famoyinka-memorial-library` and dedicated gallery album; curated authentic action photos of children; pruned repetitive shots; cross-linked on Home and About. *(Completed)*
24. **Site-wide multi-phone settings, dynamic contact CMS and dynamic partners strip** - Up to 5 contact phone lines in `/admin/settings` unified site-wide; 100% dynamic `/contact` page (custom working hours, timezone `GMT +1`, schedule note); dynamic partners strip on Home and About driven by `/admin/partners`. *(Completed)*

### Milestone 5 Features (Completed & Shipped)

25. **Resilient image optimization pipeline, upload fallback & gallery bulk uploader** - Resolve GD global namespace scoping and extension checks in `ImageOptimizerService` with automatic zero-crash fallback to direct storage; add multi-image bulk uploading in `/admin/gallery`; provide per-photo metadata editor (caption, alt text, year label, sort order). *(Completed)*
26. **Dynamic site logo in settings, Givebutter U.S. diaspora donations & 6 Impact Causes CMS** - Customizable site logo in `/admin/settings` propagating site-wide; Givebutter U.S. diaspora donation button on `/donate` with "For U.S. Donors Only" badge; overhaul Home causes into the authentic 6 pillars (`cause.jpg`) editable via `/admin/pages/home`. *(Completed)*
27. **Production release suite, updated MariaDB database export & launch verification** - Refresh root `dzuels_database.sql` via `php artisan db:export-sql`; synchronize `cpanel_changed_files.txt`; perform full test & lint audit; finalize production deployment readiness. *(Completed)*

### Milestone 6 Features (Completed & Shipped)

28. **High-performance WebP image optimizer (<= 180 KB ceiling), full unclipped causes display, dynamic Famoyinka Library photos in CMS & Get Involved UI overhaul** - Harden `ImageOptimizerService` to strictly produce WebP images with a hard maximum size limit of 180 KB via iterative quality and dimension scaling, delete uncompressed original files, retroactively optimize all existing uploads in `public/uploads/`, and unify all admin file uploaders (`GalleryAlbumController`, `MediaController`, `PageController`, `ProgrammeController`, `TeamMemberController`); remove image clipping on the homepage 6 Impact Causes section so the complete graphic is fully visible without cropped edges or obscuring badges; make the 4 Famoyinka Memorial Children's Library mosaic photos and labels editable via `/admin/pages/home` with graceful fallbacks; and overhaul `/get-involved` layout and styling with tab-synchronized sidebar content (dedicated partnership impact metrics and contact cards when "Partner With Us" is active), sticky alignment, and Tailwind v4 linear gradients. *(Completed)*

### Milestone 7 Features (Active Build Order)

29. **Universal gallery media picker and centralized cover photo optimization** - empower staff to select existing photos from any gallery album or upload and optimize new images across every photo upload form in the admin panel: replace raw file inputs with `<x-admin.media-picker>` in Gallery Album create/edit (`cover_image`), Programme create/edit (`cover_image`), News/Post create/edit (`cover_image`), and Team Member create/edit (`photo`); update controllers and form requests (`GalleryAlbumController`, `ProgrammeController`, `PostController`, `TeamMemberController`) to seamlessly accept either selected gallery image paths or direct file uploads; ensure all uploaded cover photos are processed through `ImageOptimizerService` strictly enforcing $\le 180\text{ KB}$ WebP format and original file deletion; and synchronize the deployment manifest and test suite.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Donor receipts and recurring giving**: Automated receipts and recurring gifts.

## Data model

### User
- `id` (bigint, PK)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (string: super_admin, editor, support)
- `is_active` (boolean, default true)
- `timestamps`

### PasswordResetToken
- `email` (string, index)
- `token` (string)
- `created_at` (timestamp, nullable)

### Page & PageSection (Dynamic CMS)
- `id` (bigint, PK)
- `page_key` (string, index) - e.g. `home`, `about`, `programmes`, `contact`, `donate`, `get_involved`
- `section_key` (string, index) - e.g. `hero`, `causes`, `famoyinka_library`, `vision_mission`, `values`, `story`, `bank_details`
- `headline` (string, nullable)
- `subheadline` (string, nullable)
- `body_text` (text, nullable)
- `image_path` (string, nullable)
- `button_text` (string, nullable)
- `button_url` (string, nullable)
- `meta_data` (json, nullable) - structured data for 6 impact pillars and Famoyinka library 4 mosaic photos & labels
- `sort_order` (integer, default 0)
- `timestamps`

### Event & EventSponsor
- `events`: `id`, `title`, `slug`, `summary`, `description`, `objective`, `event_date`, `venue`, `banner_image`, `schedule` (json), `is_registration_open`, `sort_order`, `timestamps`
- `event_sponsors`: `id`, `event_id` (FK), `name`, `logo_path`, `tier`, `website_url`, `sort_order`, `timestamps`

### Setting
- `id` (bigint, PK)
- `key` (string, unique) - `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `contact_working_hours`, `contact_timezone`, `contact_working_days_note`, impact numbers, banking, registration
- `value` (text, nullable)
- `type` (string: text, integer, json, boolean)
- `timestamps`

### Programme
- `id` (bigint, PK)
- `title` (string) - includes "Famoyinka Memorial Children's Library"
- `slug` (string, unique) - `famoyinka-memorial-library`
- `summary` (text)
- `body` (longText)
- `cover_image` (string, nullable)
- `partner_name` (string, nullable)
- `sort_order` (integer, default 0)
- `is_featured` (boolean, default false)
- `timestamps`

### GalleryAlbum & GalleryPhoto
- `gallery_albums`: `id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `timestamps`
- `gallery_photos`: `id`, `gallery_album_id` (FK), `image_path`, `caption`, `alt_text`, `year` (string/int, nullable), `sort_order`, `timestamps`

### TeamMember & Partner
- `team_members`: `id`, `name`, `role`, `group` (trustee, management, advisor), `field`, `bio`, `photo`, `linkedin_url`, `sort_order`, `is_active`, `timestamps`
- `partners`: `id`, `name`, `logo`, `website_url`, `sort_order`, `is_active`, `timestamps`

### ContactMessage & VolunteerApplication
- `contact_messages`: `id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `is_read`, `timestamps`
- `volunteer_applications`: `id`, `full_name`, `email`, `phone`, `age_band`, `work_status`, `skills`, `status`, `timestamps`

## Tech stack

- **Backend:** Laravel 13 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+ in development and production (`server161.web-hosting.com`), Eloquent ORM.
- **Image Optimization & Upload Pipeline:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies. Strict iterative optimization loop enforcing <= 180 KB WebP output and automated uncompressed original deletion.
- **Admin Panel:** Tailored SaaS administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js for interactive widgets, lightbox, multi-photo bulk upload, and media picker modal.
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
  - Maroon Heading: `#6A0406`
  - Crimson Primary: `#B4070A`
  - Warm Cream: `#F9DEC9`
  - Soft Mint Accent: `#56E39F`
  - Body Text: `#3B4456`
  - Page Background (Light default): `#FFFFFF`
  - Dark Mode Background: `#140607`
  - Dark Mode Surface: `#241012`
  - Footer Background: `#2B0203`
- **Core Public Routes:**
  - `/` - Homepage (dynamic partners strip, library feature with 4 dynamic photos, and 6 unclipped impact causes)
  - `/about` - About Us (dynamic partners strip & leadership)
  - `/programmes` & `/programmes/{slug}` - Programmes catalog (including `/programmes/famoyinka-memorial-library`)
  - `/events` & `/events/{slug}` - Events Showcase & ITD 2026
  - `/gallery` & `/gallery/{album}` - Photo Gallery with dynamic collections, year labels, and lightbox
  - `/donate` - Giving page (Flutterwave NGN, GTBank NGN, and Givebutter U.S. Only)
  - `/get-involved` - Volunteer and partner application with tab-synchronized sidebar
  - `/contact` - Contact details (up to 5 phone lines, custom hours/timezone) & enquiry form
  - `/privacy-policy` - Privacy terms
  - `/admin` - Professional administrative dashboard with global media picker, settings logo, and bulk gallery uploader

## Deployment

- **Target Hosting:** Namecheap cPanel (`server161.web-hosting.com`) with PHP 8.3/8.4 and MariaDB 10.3+.
- **Database Migration:** Managed via root `dzuels_database.sql` and `php artisan db:export-sql` for 1-click import into cPanel phpMyAdmin.
- **File Upload Tracking:** Active `cpanel_changed_files.txt` at project root.
- **Web Root:** `public/` directory (mapped to `public_html` or domain document root).
- **Domain:** `dzuelsfoundation.org` with AutoSSL / Let's Encrypt certificates.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm run build`, `php artisan optimize`.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1` or `localhost`.

## Open questions

*(None. All requirements for Milestone 6 / Feature 28 confirmed and ready for implementation.)*

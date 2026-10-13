# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash 82f337a6d246116dd811d6a185dce8822f812c156d1e312cd22e2253283f024f -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (registered 2015) providing education support, digital skills, poverty relief, and youth empowerment in Ijero-Ekiti and beyond.

- **Milestone 1 (Shipped):** Overhauled the web application across 17 features: verified WCAG 2.2 AA accessibility, responsive design (zero horizontal overflow), SEO foundation, dynamic settings, event showcase, pre-optimized WebP photo gallery, donation channels, contact/volunteer inboxes, and launch readiness diagnostic commands.
- **Milestone 2 (Shipped):** Completed brand assets (full favicon suite), ITD 2026 overhaul (October 3, 2026 date, AI empowerment objective, dynamic sponsor tiering), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Shipped):** Production MariaDB configuration, root `dzuels_database.sql` export, hotline update (`+234 904 249 3883`), hero imagery overlays, and Spelling Bee image fixes.
- **Milestone 4 (Active Focus):** Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Children's Library Integration & Global Admin Controls:
  1. Auto-optimize all dashboard image uploads via native PHP GD (WebP conversion + deleting originals, 100% PHP 8.3 & 8.4 compatible).
  2. Implement reusable Media Library picker modal across dashboard forms to upload or choose existing gallery images.
  3. Dynamic gallery collections CRUD/reordering, single and bulk photo deletion, and standardized event/year photo labeling.
  4. Integrate Famoyinka Memorial Children's Library as a dedicated public programme and gallery collection; seed authentic action photography of children; prune repetitive photos; and cross-link on Home and About.
  5. Expand site settings to support up to 5 contact phone lines site-wide.
  6. Make `/contact` 100% dynamic (custom office hours and timezone like "GMT +1").
  7. Render dynamic partners strip on Home and About pages driven by `/admin/partners`.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, and GTBank transfer details.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries.
- **Event Participants & Schools:** Students and educators attending ITD 2026 to learn AI tools, spelling bees, and reading competitions.
- **Beneficiaries, Parents & Students:** Community members accessing literacy resources, activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, role permissions, global settings (multi-phone lines, hours/timezones), and database exports.
  - **Editor / Content Manager:** Updating page text/images via visual CMS, managing programmes (including the library), events, sponsors, partner logos, and gallery collections/photos.
  - **Support / Viewer:** Reading incoming contact enquiries, volunteer submissions, and event records.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- Multi-role staff access at `/admin` (Super Admin, Editor, Support); no public user registrations.
- Form submissions collect personal data handled in strict compliance with the foundation's privacy policy.
- Online payments are offloaded completely to Flutterwave's hosted checkout; no payment card data is processed on-server.

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

18. **Brand assets, ITD 2026 event overhaul and gallery optimization** - Full favicon suite (`favicon.ico`, apple-touch-icon, webmanifest); ITD 2026 event page updated with backdrop banner, October 3 2026 date, revised AI empowerment objective, SeedDev and public registration forms removed, 3 dynamic sponsors with extensible admin management, and distinct past event archives; gallery page server-rendered with responsive masonry, smooth filtering, and working modal lightbox. *(Completed)*
19. **Site-wide page content management, user access control and password recovery** - WordPress-style page content editor in `/admin/pages` allowing staff to update text and section images across all public pages; role-based user management where admins create and manage staff accounts with granular access levels; and password reset workflow. *(Completed)*
20. **MySQL database export and cPanel phpMyAdmin migration suite** - Automated Artisan database export command (`php artisan db:export-sql`) generating a clean, MySQL/MariaDB-compliant `.sql` dump file with proper schemas and all seeded foundation data for 1-click import into cPanel phpMyAdmin, accompanied by a step-by-step cPanel deployment and `.env` credentials guide. *(Completed)*

### Milestone 3 Features (Completed & Shipped)

21. **Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements** - Configure `.env` and `.env.example` for local and production MariaDB/MySQL (`server161.web-hosting.com`); provide root `dzuels_database.sql` for 1-click phpMyAdmin import; ensure all layout and contact data is strictly driven from database settings; replace phone number with `+234 904 249 3883` site-wide; hide Diaspora & International Wire on `/donate`; add contextual background imagery to About, Programmes, and Contact hero sections; and fix Spelling Bee cover photo to an authentic contest image (`spelling-01.webp`). *(Completed)*

### Milestone 4 Features (Active Build Order)

22. **Automated image optimization, global media picker and gallery collection/photo management** - Auto-convert all dashboard uploads to WebP and delete original uncompressed files using PHP 8.3/8.4 native GD; reusable Media Library picker modal in admin forms to upload or choose existing gallery images; full CRUD and reordering for gallery collections/categories; single and bulk photo deletion in `/admin/gallery`; and photo metadata editing with standardized year labels across all collections (e.g., "Spelling Bee 2024").
23. **Famoyinka Memorial Children's Library programme integration and curated visual outreach** - Dedicated public programme at `/programmes/famoyinka-memorial-library` and dedicated gallery collection; prune repetitive gallery photos (such as duplicate Reading Competition shots); seed authentic action photography of children (crafts, transformational leadership games, Ikogosi excursion, library activities and book borrowing); and prominent cross-linking of the library on Home and About pages.
24. **Site-wide multi-phone settings, dynamic contact CMS and dynamic partners strip** - Support up to 5 contact phone numbers in `/admin/settings` (`contact_phone_1` to `contact_phone_5`); unify all site-wide phone displays (header utility bar, mobile menu, footer, contact cards, error pages) to pull from site settings; make `/contact` 100% dynamic (custom office hours and timezone like "GMT +1", address, card descriptions); and dynamic "Our Valued Partners" strip on Home and About pages driven by `/admin/partners`.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Diaspora donations**: Direct USD/GBP donation channels.
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
- `section_key` (string, index) - e.g. `hero`, `vision_mission`, `values`, `story`, `bank_details`
- `headline` (string, nullable)
- `subheadline` (string, nullable)
- `body_text` (text, nullable)
- `image_path` (string, nullable)
- `button_text` (string, nullable)
- `button_url` (string, nullable)
- `meta_data` (json, nullable)
- `sort_order` (integer, default 0)
- `timestamps`

### Event
- `id` (bigint, PK)
- `title` (string) - "Ijero Tech-Day 2026 (ITD 2026)"
- `slug` (string, unique)
- `summary` (text)
- `description` (longText)
- `objective` (text, nullable)
- `event_date` (dateTime, nullable)
- `venue` (string, nullable)
- `banner_image` (string, nullable)
- `schedule` (json, nullable)
- `is_registration_open` (boolean, default false)
- `sort_order` (integer, default 0)
- `timestamps`

### EventSponsor
- `id` (bigint, PK)
- `event_id` (foreignId, references `events.id`, onDelete cascade)
- `name` (string)
- `logo_path` (string, nullable)
- `tier` (string, nullable) - lead, partner, supporter
- `website_url` (string, nullable)
- `sort_order` (integer, default 0)
- `timestamps`

### Setting
- `id` (bigint, PK)
- `key` (string, unique) - `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, registration
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
- **Image Optimization:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies.
- **Admin Panel:** Tailored SaaS administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js for interactive widgets, lightbox, and the reusable media picker modal.
- **Deployment Manifest:** Project root `cpanel_changed_files.txt` tracking created and modified files for selective cPanel synchronization.
- **Testing & Quality:** PHPUnit 12 (`php artisan test`) and Laravel Pint code style formatting.

## Monetization

Non-profit charity donations. The platform does not sell products or show advertisements. Giving flows through:
1. Flutterwave hosted e-payment checkout (`https://flutterwave.com/pay/j0byxgjs0nma`).
2. GTBank Plc. direct bank transfers (NGN Account `0450072850`).
3. In-kind and international giving coordination via email (`info@dzuelsfoundation.org`).

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
  - `/` - Homepage (dynamic partners strip & library feature)
  - `/about` - About Us (dynamic partners strip & leadership)
  - `/programmes` & `/programmes/{slug}` - Programmes catalog (including `/programmes/famoyinka-memorial-library`)
  - `/events` & `/events/{slug}` - Events Showcase & ITD 2026
  - `/gallery` & `/gallery/{album}` - Photo Gallery with dynamic collections, year labels, and lightbox
  - `/donate` - Giving page (Flutterwave & GTBank NGN)
  - `/get-involved` - Volunteer and partner application
  - `/contact` - Contact details (up to 5 phone lines, custom hours/timezone) & enquiry form
  - `/privacy-policy` - Privacy terms
  - `/admin` - Professional administrative dashboard with global media picker

## Deployment

- **Target Hosting:** Namecheap cPanel (`server161.web-hosting.com`) with PHP 8.3/8.4 and MariaDB 10.3+.
- **Database Migration:** Managed via root `dzuels_database.sql` and `php artisan db:export-sql` for 1-click import into cPanel phpMyAdmin.
- **File Upload Tracking:** Active `cpanel_changed_files.txt` at project root.
- **Web Root:** `public/` directory (mapped to `public_html` or domain document root).
- **Domain:** `dzuelsfoundation.org` with AutoSSL / Let's Encrypt certificates.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm run build`, `php artisan optimize`.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1` or `localhost`.

## Open questions

*(None. All requirements, GD compatibility with PHP 8.4, multi-phone rules, and 3-feature boundary for Milestone 4 confirmed.)*

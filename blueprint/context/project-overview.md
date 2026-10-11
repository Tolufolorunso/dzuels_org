# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash bbde7e9d4760b56ae81a06c722660d0bb28f66a0e79f8b3b37275e9639cb4056 -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (registered 2015) providing education support, digital skills, poverty relief, and youth empowerment in Ijero-Ekiti and beyond.

- **Milestone 1 (Shipped):** Overhauled the web application across 17 features: verified WCAG 2.2 AA accessibility, responsive design (zero horizontal overflow), SEO foundation, dynamic settings, event showcase, pre-optimized WebP photo gallery, donation channels, contact/volunteer inboxes, and launch readiness diagnostic commands.
- **Milestone 2 (Shipped):** Completed brand assets (full favicon suite), ITD 2026 overhaul (October 3, 2026 date, AI empowerment objective, dynamic sponsor tiering), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Active Focus):** Complete live production server configuration on Namecheap cPanel (`server161.web-hosting.com` running MariaDB) and final launch polish:
  1. Transition `.env` and `.env.example` to default to MariaDB/MySQL.
  2. Provide ready-to-import `dzuels_database.sql` directly in the project root for 1-click phpMyAdmin import.
  3. Ensure all layout, hotline, and contact data is strictly driven from database records (zero hardcoded values).
  4. Replace telephone number with `+234 904 249 3883` site-wide.
  5. Hide Diaspora & International Wire transfers channel on `/donate` for now.
  6. Add contextual background imagery with maroon gradient overlays to About, Programmes, and Contact hero sections.
  7. Fix Spelling Bee cover photo to authentic contest photo (`front/images/gallery/optimized/spelling-01.webp`).

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, and GTBank transfer details.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries.
- **Event Participants & Schools:** Students and educators attending ITD 2026 to learn AI tools and celebrate cohort graduations.
- **Beneficiaries & Parents:** Community members accessing literacy resources and visual photo archives.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, role permissions, global settings, and database exports.
  - **Editor / Content Manager:** Updating page text/images via the visual CMS, managing programmes, events, sponsors, and gallery albums.
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

### Milestone 3 Features (Active Build Order)

21. **Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements** - Configure `.env` and `.env.example` for local and production MariaDB/MySQL (`server161.web-hosting.com`); provide root `dzuels_database.sql` for 1-click phpMyAdmin import; ensure all layout and contact data is strictly driven from database settings; replace phone number with `+234 904 249 3883` site-wide; hide Diaspora & International Wire on `/donate`; add contextual background imagery to About, Programmes, and Contact hero sections; and fix Spelling Bee cover photo to an authentic contest image (`spelling-01.webp`).

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
- `objective` (text, nullable) - "Empowering children with AI tools to learn smarter, think creatively, and build the future."
- `event_date` (dateTime, nullable) - Oct 3, 2026
- `venue` (string, nullable) - AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)
- `banner_image` (string, nullable) - Extracted backdrop banner
- `schedule` (json, nullable) - 10 batches of 30, inside/garden rotation
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
- `key` (string, unique)
- `value` (text, nullable) - includes `contact_phone_1` (`+234 904 249 3883`)
- `type` (string: text, integer, json, boolean)
- `timestamps`

### Programme
- `id` (bigint, PK)
- `title` (string)
- `slug` (string, unique)
- `summary` (text)
- `body` (longText)
- `cover_image` (string, nullable) - Spelling Bee uses `front/images/gallery/optimized/spelling-01.webp`
- `partner_name` (string, nullable)
- `sort_order` (integer, default 0)
- `is_featured` (boolean, default false)
- `timestamps`

### GalleryAlbum & GalleryPhoto
- `gallery_albums`: `id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `timestamps`
- `gallery_photos`: `id`, `gallery_album_id` (FK), `image_path`, `caption`, `alt_text`, `sort_order`, `timestamps`

### TeamMember & Partner
- `team_members`: `id`, `name`, `role`, `group` (trustee, management, advisor), `field`, `bio`, `photo`, `linkedin_url`, `sort_order`, `is_active`, `timestamps`
- `partners`: `id`, `name`, `logo`, `website_url`, `sort_order`, `is_active`, `timestamps`

### ContactMessage & VolunteerApplication
- `contact_messages`: `id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `is_read`, `timestamps`
- `volunteer_applications`: `id`, `full_name`, `email`, `phone`, `age_band`, `work_status`, `skills`, `status`, `timestamps`

## Tech stack

- **Backend:** Laravel 13 on PHP 8.3+, MariaDB 10.3+ / MySQL 8.0+ in development and production (`server161.web-hosting.com`), Eloquent ORM.
- **Admin Panel:** Tailored SaaS administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js (robust inline/modular initialization) for interactive widgets.
- **Typography:** Self-hosted Poppins (body & headings) and Great Vibes (accent script).
- **Brand Assets:** Multi-resolution favicons (`favicon.ico`, apple-touch-icon, webmanifest) derived from foundation logo.
- **Payments:** Hosted Flutterwave checkout link + GTBank manual transfer details.
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
  - `/` - Homepage
  - `/about` - About Us (hero with contextual background image)
  - `/programmes` & `/programmes/{slug}` - Programmes (hero with contextual background image)
  - `/events` & `/events/{slug}` - Events Showcase & ITD 2026
  - `/gallery` & `/gallery/{album}` - Photo Gallery
  - `/donate` - Giving page (Flutterwave & GTBank NGN)
  - `/get-involved` - Volunteer and partner application
  - `/contact` - Contact details (+234 904 249 3883) & enquiry form
  - `/privacy-policy` - Privacy terms
  - `/admin` - Professional administrative dashboard

## Deployment

- **Target Hosting:** Namecheap cPanel (`server161.web-hosting.com`) with PHP 8.3+ and MariaDB 10.3+.
- **Database Migration:** Managed via root `dzuels_database.sql` and `php artisan db:export-sql` for 1-click import into cPanel phpMyAdmin.
- **Web Root:** `public/` directory (mapped to `public_html` or domain document root).
- **Domain:** `dzuelsfoundation.org` with AutoSSL / Let's Encrypt certificates.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm run build`, `php artisan optimize`.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1` or `localhost`.

## Open questions

*(None. All requirements, server host specifications, phone hotlines, and visual assets are confirmed.)*

# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), a Reading Competition, and a Spelling Bee, alongside the Famoyinka Memorial Children's Library.

### Milestone 1: Core Web Platform (Completed & Shipped)
The foundation's web presence was rebuilt into a modern, fast, accessible, SEO-optimized Laravel application with verified WCAG 2.2 AA compliance, brand color tokens, dynamic settings, team/partner/programme showcases, payment details, contact forms, and security safeguards across 17 completed features.

### Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment (Completed & Shipped)
To make the platform independent and self-manageable by non-technical staff:
- **Brand Identity & Favicons**: Complete the brand asset suite (`favicon.ico`, PNG touch icons, webmanifest) using the foundation logo.
- **ITD 2026 Refinements & Historical Separation**: Updated the flagship event page for October 3, 2026 with the backdrop extracted from the official PDF, the new AI empowerment objective, SeedDev and public registration forms removed, 3 dynamic sponsors with flexible admin controls, and distinct past event archives.
- **Gallery Stability & Polish**: Server-rendered responsive masonry grid, filter chips, and working lightbox.
- **WordPress-Style Page Content Management**: Visual section editor in `/admin/pages` for Home, About, Programmes, Contact, and Donate.
- **Role-Based Access Control & Password Recovery**: Super Admin, Editor, and Support roles with password reset workflows.
- **MySQL Database Export Suite**: Automated `php artisan db:export-sql` generating clean SQL dumps for phpMyAdmin on cPanel.

### Milestone 3: Production Server Readiness & Launch Refinements (Completed & Shipped)
Finalized production readiness for live deployment on Namecheap cPanel (`server161.web-hosting.com` running MariaDB):
- **MariaDB / MySQL Architecture**: Transition `.env` and `.env.example` to default to MariaDB/MySQL. Provide root `dzuels_database.sql` for 1-click phpMyAdmin import.
- **Dynamic Database-Driven Content**: Ensure layout elements, impact statistics, and contacts pull dynamically from the database.
- **Hotline & Contact Update**: Completely replace old telephone numbers with `+234 904 249 3883` site-wide.
- **Giving Channels Refinement**: Hide the Diaspora & International Wire transfers channel on `/donate` for now.
- **Contextual Hero Section Imagery**: Enrich About Us, Programmes, and Contact page heroes with rich, contextual background imagery and maroon gradient overlays.
- **Spelling Bee Programme Image Fix**: Replace placeholder with authentic Spelling Bee championship photo (`spelling-01.webp`).

### Milestone 4: Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Library & Global Admin Controls (Completed & Shipped)
Empowering foundation staff with full media flexibility and addressing visual storytelling feedback:
- **Automated Upload Image Optimization**: Native PHP GD processing on every dashboard image upload: automatically resize, convert to WebP, save to public uploads, and delete the unoptimized original. Compatible with PHP 8.3 & 8.4.
- **Global Media Library Picker**: A reusable media modal component across all dashboard edit forms (events, programmes, page sections, partners) allowing staff to either upload a new image or pick an existing photo from the gallery.
- **Gallery & Collection Overhaul**: Full CRUD and sort reordering for collections/categories (moving beyond fixed albums); single and bulk photo deletion; photo metadata editing; and standardized event and year labeling across all photos (e.g. "Spelling Bee 2024", "Reading Competition 2024", "Famoyinka Library 2025").
- **Famoyinka Memorial Children's Library Integration**: Dedicated official programme at `/programmes/famoyinka-memorial-library` and dedicated gallery collection; visual curation introducing authentic action photography of children (crafts, transformational leadership games, Ikogosi trip, library reading and book borrowing); pruning repetitive photos; and cross-linking the library across Home and About.
- **Multi-Phone Site Settings (Up to 5 Lines)**: Expand site settings to store up to 5 contact hotlines (`contact_phone_1` through `contact_phone_5`), unifying all site-wide phone displays (header utility bar, mobile nav, footer, contact cards, error pages) to read strictly from site settings with empty numbers gracefully omitted.
- **Fully Dynamic Contact Page & Dynamic Partners Strip**: Remove all hardcoded copy on `/contact` (office hours and timezone configurable from dashboard, e.g. "Mon – Fri: 8:00 AM – 5:00 PM (GMT +1)"); and render the "Our Valued Partners - Collaborating for greater impact" logo strip dynamically on both Home (`/`) and About (`/about`) with full management in `/admin/partners`.

### Milestone 5: Production Launch Hardening, Resilient Media Pipeline, Givebutter Diaspora Donations & 6 Impact Causes CMS (Active Target)
Finalizing the web platform for live release and addressing critical operational feedback:
- **Resilient Upload Engine & GD Fallback**: Eliminate `imagecreatefromjpeg` crashes by correctly scoping GD functions in the global namespace (`\imagecreatefrom...`), checking extension availability, and implementing a graceful fallback to direct storage so photo uploads always succeed without crashing regardless of server GD configuration.
- **Gallery Multi-Photo Bulk Upload**: Streamline gallery management by allowing staff to select and upload multiple images simultaneously into an album, with subsequent per-photo metadata editing (caption, year label, alt text, sort order).
- **Dynamic Site Logo Management**: Empower staff to upload or select a brand logo directly in `/admin/settings`, with real-time propagation across the header navbar, footer, and admin layout.
- **U.S. Diaspora Giving (Givebutter)**: Expand giving channels on `/donate` with an official Givebutter link (`https://givebutter.com/dzuels-foundation`) labeled "For U.S. Donors Only", fully manageable from site settings (`givebutter_url`).
- **Authentic 6 Key Impact Pillars (`cause.jpg`)**: Replace the generic causes with the foundation's official 6 pillars:
  1. Education Support (Paul's academy)
  2. Skills Acquisition (Peter's net)
  3. Advocacy (Deborah congress)
  4. Feeding families (Naomi's kitchen)
  5. Transformative leadership Training (Priscilla's upper room)
  6. Health awareness and support (Luke's Tent)
  Anchored by the philosophy *"An Integrated Approach to the Total Well Being of the Child"*, editable in `/admin/pages/home`.
- **Production Export & Launch Readiness**: Re-export `dzuels_database.sql` containing all updated seeders, settings, and causes for 1-click phpMyAdmin import on Namecheap cPanel (`server161.web-hosting.com`), update `cpanel_changed_files.txt`, and verify deployment integrity.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships.
- **Event participants & schools**: Attending ITD 2026, reading competitions, and spelling bees.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, role permissions, global site settings (logo, up to 5 phone lines, Givebutter link, office hours/timezone), and database exports.
  - **Editor / Content Manager**: Updating page content (including the 6 impact pillars in `/admin/pages/home`), programmes, events, sponsors, partner logos, and managing gallery photos with bulk upload.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event registrations.

## 3. Features & Milestones

### Milestone 1: Core Web Platform (Shipped, Features 1–17)
- 1–17. Public layout, brand theme, admin dashboard, dynamic settings, home, about, programmes, events, gallery, donate, volunteer, contact, news, newsletter, responsiveness overhaul, SEO foundation, accessibility compliance.

### Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment (Shipped, Features 18–20)
- 18. Brand assets, ITD 2026 event overhaul, and gallery optimization.
- 19. Site-wide page content management, user access control, and password recovery.
- 20. MySQL database export and cPanel phpMyAdmin migration suite.

### Milestone 3: Production Server Readiness & Launch Refinements (Shipped, Feature 21)
- 21. Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements.

### Milestone 4: Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Library & Global Admin Controls (Shipped, Features 22–24)
- 22. **Automated image optimization, global media picker and gallery collection/photo management**:
  - Auto-convert all dashboard uploads to WebP and delete original uncompressed files using PHP 8.3/8.4 native GD.
  - Reusable Media Library picker modal in admin forms to upload or choose existing gallery images.
  - CRUD and reordering for gallery collections/categories.
  - Single and bulk photo deletion in `/admin/gallery`.
  - Photo metadata editing with standardized year labels across all collections (e.g., "Spelling Bee 2024").
- 23. **Famoyinka Memorial Children's Library programme integration and curated visual outreach**:
  - Dedicated public programme at `/programmes/famoyinka-memorial-library` and dedicated gallery collection.
  - Prune repetitive gallery photos and seed diverse action photos of children (crafts, transformational leadership games, Ikogosi trip, library reading and book borrowing).
  - Prominent cross-linking of the library on Home and About pages.
- 24. **Site-wide multi-phone settings, dynamic contact CMS and dynamic partners strip**:
  - Support up to 5 contact phone numbers in `/admin/settings` (`contact_phone_1` to `contact_phone_5`).
  - Unify all site-wide phone displays (header utility bar, mobile menu, footer, contact cards, error pages) to pull from site settings.
  - Make `/contact` 100% dynamic (custom office hours and timezone like "GMT +1", address, card descriptions).
  - Dynamic "Our Valued Partners" strip on Home and About pages driven by `/admin/partners`.

### Milestone 5: Production Launch Hardening, Resilient Media Pipeline, Givebutter Diaspora Donations & 6 Impact Causes CMS (Features 25–27)
- 25. **Resilient image optimization pipeline, upload fallback & gallery bulk uploader**:
  - Resolve GD global namespace scoping and extension availability checks in `ImageOptimizerService`.
  - Graceful direct-save fallback ensuring zero-crash uploads even without GD.
  - Multi-file bulk upload in `/admin/gallery` with per-photo metadata editing.
- 26. **Dynamic site logo in settings, Givebutter U.S. diaspora donations & 6 Impact Causes CMS**:
  - Dynamic site logo upload and selection in `/admin/settings` propagating site-wide.
  - Givebutter donation channel (`https://givebutter.com/dzuels-foundation`) with "For U.S. Donors Only" badge and admin setting.
  - 6 Key Impact Causes on Home page matching `cause.jpg` editable via `/admin/pages/home`.
- 27. **Production release suite, updated MariaDB database export & launch verification**:
  - Root `dzuels_database.sql` update via `php artisan db:export-sql`.
  - Synchronization of `cpanel_changed_files.txt` deployment manifest.
  - Comprehensive test suite, code style audit, and production configuration check.

## 4. Data - What are we storing?

- **settings**: extended key/values for `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours` (e.g. "Mon – Fri: 8:00 AM – 5:00 PM"), `office_timezone` (e.g. "GMT +1"), impact numbers, banking, and registration.
- **page_sections**: includes Home page section `causes` with structured 6-pillar copy, subtitle, and icons.
- **gallery_albums**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, timestamps.
- **programmes**: id, title, slug, excerpt, description, image_path, is_active, sort_order, timestamps (including `famoyinka-memorial-library`).
- **partners**: id, name, logo, website_url, sort_order, is_active, timestamps.
- **users**, **page_sections**, **event_sponsors**, **events**, **team_members**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 13 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for cPanel uploads.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4.
- Domain: `dzuelsfoundation.org`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt`.
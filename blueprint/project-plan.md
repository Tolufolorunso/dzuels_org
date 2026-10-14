# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), a Reading Competition, and a Spelling Bee, alongside the Famoyinka Memorial Children's Library.

### Milestones 1–7 (Completed & Shipped, Features 1–29)
The foundation's platform has been successfully built, hardened, and deployed:
- **Milestone 1 (Shipped):** Core Web Platform across 17 features (WCAG 2.2 AA accessibility, responsive design, SEO foundation, dynamic settings, event showcase, WebP gallery, donation channels, contact/volunteer inboxes).
- **Milestone 2 (Shipped):** Brand identity & full favicon suite, ITD 2026 overhaul (October 3, 2026, AI empowerment objective), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Shipped):** Production MariaDB configuration, root `dzuels_database.sql` export, hotline update (`+234 904 249 3883`), hero imagery overlays, and Spelling Bee photo fixes.
- **Milestone 4 (Shipped):** Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Children's Library Integration, multi-phone settings (up to 5 lines), dynamic contact CMS (custom schedule/timezone), and dynamic partners strip on Home and About.
- **Milestone 5 (Shipped):** Resilient media pipeline with GD fallback, gallery multi-photo bulk uploader, dynamic site logo in settings, Givebutter U.S. diaspora donations, and the authentic 6 Key Impact Causes CMS.
- **Milestone 6 (Shipped):** Production refinements, image optimizer hardening (strict $\le 180\text{ KB}$ WebP ceiling), full unclipped causes graphic on Home, dynamic 4-photo Famoyinka Library CMS, and Get Involved UI overhaul.
- **Milestone 7 (Shipped):** Universal gallery media picker `<x-admin.media-picker>` across Gallery Album, Programme, News, and Team forms, centralized cover photo optimization, and dual-input request validators.

### Milestone 8: Unified Central Media Hub, Global Asset Management & cPanel Public Path Hardening (Active Target)
Empowering foundation staff with a single, centralized Media Library dashboard to manage and reuse all optimized visual assets across the entire foundation, while eliminating cPanel path mismatches:
- **cPanel Public Web Path Resolution**: Explicitly bind `$app->usePublicPath(__DIR__)` in `public/index.php` and verify directory write permissions so that image uploads in cPanel shared hosting write directly to the active web root (`public_html/uploads/...`), eliminating broken 404 image URLs. Ensure GD WebP extension handling operates smoothly on live hosting.
- **Dedicated Central Media Library (`/admin/media`)**: Introduce a prominent, top-level "Media Library" section in the admin navigation. Staff can browse all optimized foundation photos in a clean visual grid, with:
  - **Comprehensive Multi-Param Search**: Instant search by **photo name** (filename, title, caption, or original file name).
  - **Date Uploaded Filtering**: Filter by date uploaded (date picker and quick timeframes like "This Week", "This Month", "Past Year"), plus sorting (Newest to Oldest, Oldest to Newest, Alphabetical).
  - **Category Tabs**: Filter between *"All Media"*, *"Gallery Collections"*, and *"Site & Page Assets"*.
  - **Asset Inspection & Actions**: File size badges (confirming WebP $\le 180\text{ KB}$), pixel dimensions, date uploaded badge, copyable public URLs, photo preview, and single/bulk deletion.
- **Centralized Drag-and-Drop Uploader**: Fast, standalone upload zone in `/admin/media` where staff can drag and drop new photos at any time. Uploads are instantly resized, converted to WebP under the strict 180 KB ceiling, and stored in the central repository ready for immediate use.
- **Enhanced Modal Picker Integration**: Upgrade `<x-admin.media-picker>` across all dashboard editors (Pages CMS, Programmes, Events, Partners, Team) to include this exact search by name, date filter, and category tabs for seamless 1-click selection or instant upload.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships.
- **Event participants & schools**: Attending ITD 2026, reading competitions, and spelling bees.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, role permissions, global site settings (logo, up to 5 phone lines, Givebutter link, office hours/timezone), media library deletions, and database exports.
  - **Editor / Content Manager**: Accessing the Central Media Library to upload and organize assets, updating page content, programmes, events, sponsors, partner logos, and gallery photos.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event registrations.

## 3. Features & Milestones

### Milestones 1–7 (Shipped, Features 1–29)
Features 1–29 fully completed, tested, and shipped.

### Milestone 8 (Active Target, Feature 30)
- 30. **Unified Central Media Hub, global asset management & cPanel public web path resolution**:
  - Bind `$app->usePublicPath(__DIR__)` in `public/index.php` and verify directory creation with correct permissions so uploaded media on cPanel saves directly to the active web folder and serves without 404s.
  - Build top-level `/admin/media` interface with visual grid, multi-param search by photo name/caption/filename, date uploaded filters (date picker & quick ranges), sort controls (newest, oldest, A-Z), category tabs (*All Media*, *Gallery Collections*, *Site Assets*), file size badges ($\le 180\text{ KB}$ WebP), dimensions, and copyable public URLs.
  - Direct drag-and-drop WebP uploading into the central hub with strict $\le 180\text{ KB}$ ceiling and original file deletion.
  - Connect `<x-admin.media-picker>` across all CMS editors to the unified media catalog with the same search and date filters.

## 4. Data - What are we storing?

- **settings**: `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, timestamps.
- **media_uploads / site assets**: centralized cataloging of non-gallery uploads (heroes, banners, partner logos, team photos, cause graphics) with filename, file_path, file_size, mime_type, and dimensions.
- **page_sections**, **programmes**, **partners**, **users**, **events**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for cPanel uploads.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4.
- Domain: `dzuelsfoundation.org` (and `dzf.dzuelsfoundation.org`).
- Web root alignment: `public_path()` bound to `__DIR__` in `index.php` for seamless asset serving from `public_html/`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt`.
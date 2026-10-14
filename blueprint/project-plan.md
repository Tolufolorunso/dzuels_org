# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–8 (Completed & Shipped, Features 1–30)
The foundation's platform has been successfully built, hardened, and deployed:
- **Milestone 1 (Shipped):** Core Web Platform across 17 features (WCAG 2.2 AA accessibility, responsive design, SEO foundation, dynamic settings, event showcase, WebP gallery, donation channels, contact/volunteer inboxes).
- **Milestone 2 (Shipped):** Brand identity & full favicon suite, ITD 2026 overhaul (October 3, 2026, AI empowerment objective), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Shipped):** Production MariaDB configuration, root `dzuels_database.sql` export, hotline update (`+234 904 249 3883`), hero imagery overlays, and Spelling Bee photo fixes.
- **Milestone 4 (Shipped):** Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Children's Library Integration, multi-phone settings (up to 5 lines), dynamic contact CMS (custom schedule/timezone), and dynamic partners strip on Home and About.
- **Milestone 5 (Shipped):** Resilient media pipeline with GD fallback, gallery multi-photo bulk uploader, dynamic site logo in settings, Givebutter U.S. diaspora donations, and the authentic 6 Key Impact Causes CMS.
- **Milestone 6 (Shipped):** Production refinements, image optimizer hardening (strict <= 180 KB WebP ceiling), full unclipped causes graphic on Home, dynamic 4-photo Famoyinka Library CMS, and Get Involved UI overhaul.
- **Milestone 7 (Shipped):** Universal gallery media picker `<x-admin.media-picker>` across Gallery Album, Programme, News, and Team forms, centralized cover photo optimization, and dual-input request validators.
- **Milestone 8 (Shipped):** Unified Central Media Hub (`/admin/media`), global asset management, multi-criteria search, date filters, drag-and-drop WebP uploader, and cPanel `usePublicPath(__DIR__)` resolution.

### Milestone 9: Dynamic Media Collections, Authentic Photo Integration & cPanel Root Alignment (Active Target)
- **Dynamic Collections & Dashboard Category Creation**:
  - Unify Gallery Albums and Media Collections under a single dynamic model (`GalleryAlbum` / Collections).
  - Pre-seed authentic foundation collections: **Essay Competition**, **Girls Day**, **ITD (Ijero Tech Day)**, **Reading / Library**, **Spelling Bee**, **WDADA Outreach**, **General Community**, and system collection **Site & CMS Assets**.
  - Provide a **"+ New Collection"** button and management modal on `/admin/media` so staff can create new collections on the fly with title, description, and an optional "Show in Public Gallery" toggle.
  - In `/admin/media` and `<x-admin.media-picker>`, enable filtering and assigning photos directly to any collection.
- **Authentic Photo Curation & Optimization from `picture/` Archive**:
  - Curate the sharpest, highest-impact photos from the 4,000+ images in `picture/` across Essay Competition, Girls Day, ITD, Reading, Spelling Bee, WDADA, and General Community (Cohorts 1 & 2, Ikogosi excursion, Love Sale).
  - Convert all selected photos to crisp WebP format (<= 180 KB ceiling).
  - Populate albums, Home hero/causes, Famoyinka Library, About story, and catalog them into `MediaItem`.
  - Once processed, delete the temporary `picture/` directory to maintain a clean codebase.
- **cPanel Root Deployment Alignment**:
  - Provide root `index.php` and root `.htaccess` in the repository so the application boots cleanly whether executed in local development (`public/index.php`) or deployed directly to `/home/dzuefjel/dzuels_org/` where `index.php`, `.htaccess`, `front/`, `build/`, and `uploads/` sit at the root.
- **Complete MySQL / MariaDB Database Dump**:
  - Run `php artisan db:export-sql` to produce the updated `database/dumps/dzuels_database.sql` and root `dzuels_database.sql` containing all authentic albums, photos, media items, and CMS sections for 1-click phpMyAdmin import.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships.
- **Event participants & schools**: Attending ITD 2026, reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, role permissions, global site settings, media library collections, deletions, and database exports.
  - **Editor / Content Manager**: Accessing the Central Media Library to create collections, upload and organize photos, update page content, programmes, events, sponsors, and gallery photos.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event registrations.

## 3. Features & Milestones

### Milestones 1–8 (Shipped, Features 1–30)
Features 1–30 fully completed, tested, and shipped.

### Milestone 9 (Active Target, Feature 31)
- 31. **Dynamic media collections/categories, authentic photo curation from picture archive, database SQL export & cPanel root architecture alignment**:
  - Unify Media Categories with `GalleryAlbum` so categories in the Central Media Hub are dynamic, and staff can create new collections directly from `/admin/media` with an optional public gallery toggle.
  - Curate and convert high-impact photos from `picture/` (Essay Competition, Girls Day, ITD, Reading, Spelling Bee, WDADA, General Community) into <= 180 KB WebP format.
  - Update seeders and CMS page sections with authentic imagery, catalog photos into `MediaItem`, and delete `picture/` upon completion.
  - Add root deployment configuration (`index.php`, `.htaccess`) supporting direct `/home/dzuefjel/dzuels_org` cPanel execution without breaking local development.
  - Export refreshed `dzuels_database.sql` for 1-click phpMyAdmin deployment.

## 4. Data - What are we storing?

- **settings**: `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, title, caption, alt_text, source_type, gallery_photo_id, timestamps.
- **page_sections**, **programmes**, **partners**, **users**, **events**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for cPanel uploads.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- Supports root document root execution via universal `index.php` and `.htaccess`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt`.
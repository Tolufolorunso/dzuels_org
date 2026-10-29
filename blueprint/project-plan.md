# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–10 (Completed & Shipped in Production, Features 1–32)
The foundation's platform has been successfully built, hardened, and deployed to live production (`https://dzf.dzuelsfoundation.org/`):
- **Milestone 1 (Shipped):** Core Web Platform across 17 features (WCAG 2.2 AA accessibility, responsive design, SEO foundation, dynamic settings, event showcase, WebP gallery, donation channels, contact/volunteer inboxes).
- **Milestone 2 (Shipped):** Brand identity & full favicon suite, ITD 2026 overhaul (October 3, 2026, AI empowerment objective), server-rendered gallery lightbox, WordPress-style Page Content CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), password recovery, and MySQL export tooling.
- **Milestone 3 (Shipped):** Production MariaDB configuration, root `dzuels_database.sql` export, hotline update (`+234 904 249 3883`), hero imagery overlays, and Spelling Bee photo fixes.
- **Milestone 4 (Shipped):** Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Children's Library Integration, multi-phone settings (up to 5 lines), dynamic contact CMS (custom schedule/timezone), and dynamic partners strip on Home and About.
- **Milestone 5 (Shipped):** Resilient media pipeline with GD fallback, gallery multi-photo bulk uploader, dynamic site logo in settings, Givebutter U.S. diaspora donations, and the authentic 6 Key Impact Causes CMS.
- **Milestone 6 (Shipped):** Production refinements, image optimizer hardening (strict <= 180 KB WebP ceiling), full unclipped causes graphic on Home, dynamic 4-photo Famoyinka Library CMS, and Get Involved UI overhaul.
- **Milestone 7 (Shipped):** Universal gallery media picker `<x-admin.media-picker>` across Gallery Album, Programme, News, and Team forms, centralized cover photo optimization, and dual-input request validators.
- **Milestone 8 (Shipped):** Unified Central Media Hub (`/admin/media`), global asset management, multi-criteria search, date filters, drag-and-drop WebP uploader, and cPanel `usePublicPath(__DIR__)` resolution.
- **Milestone 9 (Shipped):** Dynamic media collections/categories, authentic 120-photo curation from picture archive across all 7 foundation programs, refreshed `dzuels_database.sql`, and cPanel root directory architecture alignment.
- **Milestone 10 (Shipped):** Native Google Gemini AI content assistant, multimodal image analysis across admin inputs, server speed optimization (Gzip compression, immutable caching), and Schema.org rich structured data suite.

### Milestone 11: Events Architecture, Tech Day Flagship Showcase & Media Hub Overhaul (Active Target)
- **Central Media Hub Sorting & Year-Based Photo Selection**:
  - Add multi-criteria sorting (by Year, Event/Collection, Date added, Name) and quick Year filter chips (`2024`, `2025`, `2026`) in `/admin/media` and the modal picker (`<x-admin.media-picker>`) so staff can locate photos effortlessly.
  - Ingest, optimize (strict <= 180 KB WebP ceiling), and catalog authentic photo archives from `gallery/` (Reading Competition, Spelling Bee, General Community) into dynamic collections.
- **Tech Day Flagship Event & Programme Separation**:
  - Separate Tech Day from regular ongoing programmes on `/programmes`, establishing it exclusively as Dzuels' premier annual flagship event under `/events`.
  - Purge legacy techday images from DB and file storage.
  - Ingest, optimize (<= 180 KB WebP), and catalog authentic multi-year photo archives from `final/`:
    - `final/techday2024` (45 photos)
    - `final/techday2025` (46 photos)
    - `final/techday2026` (41 photos)
  - Create a multi-year Tech Day event showcase with edition switching (2024 Inaugural, 2025 Edition, 2026 AI Empowerment Edition) and authentic photo galleries.
- **Media Alignment, Database Export & Production Deployment**:
  - Update page hero banners, causes, and event highlights with newly curated authentic photography.
  - Export refreshed `dzuels_database.sql` for 1-click phpMyAdmin cPanel import.
  - Log all modified files in `cpanel_changed_files.txt` for Core FTP deployment.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships.
- **Event participants & schools**: Attending ITD 2026, reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, AI configuration and API keys, role permissions, global site settings, media library collections, deletions, and database exports.
  - **Editor / Content Manager**: Writing and refining page copy, programme descriptions, event agendas, and photo metadata with the AI writing assistant and media picker.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event registrations.

## 3. Features & Milestones

### Milestones 1–10 (Shipped, Features 1–32)
Features 1–32 fully completed, tested, and shipped in production.

### Milestone 11 (Active Target, Features 33–35)
- 33. **Media Hub sorting, year filtering & universal photo picker overhaul**:
  - Add multi-criteria sorting (by Year, Event/Collection, Newest/Oldest, and A–Z) with quick year filter chips (`2024`, `2025`, `2026`) in the Central Media Hub (`/admin/media`) and modal picker (`<x-admin.media-picker>`).
  - Ingest, optimize (<= 180 KB WebP), and catalog authentic photo archives from `gallery/` into structured collections (`reading`, `spelling-bee`, `general-community`).
- 34. **Tech Day event architecture, multi-year showcase (2024, 2025, 2026) & programme separation**:
  - Separate Tech Day from `/programmes` to highlight it exclusively as the foundation's premier annual flagship event under `/events`.
  - Purge legacy techday imagery from storage and database.
  - Ingest, optimize (<= 180 KB WebP), and catalog authentic photos from `final/techday2024`, `final/techday2025`, and `final/techday2026`.
  - Build a multi-year Tech Day event showcase with edition switcher and authentic photo archives.
- 35. **Media integration, production SQL database export & launch verification**:
  - Wire new authentic photography into event cards, page heroes, and impact highlights.
  - Run full launch checks and verify automated test suite.
  - Export fresh root `dzuels_database.sql` for 1-click phpMyAdmin cPanel import.
  - Log all modified files in `cpanel_changed_files.txt` for Core FTP deployment.

## 4. Data - What are we storing?

- **settings**: `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, title, caption, alt_text, source_type, gallery_photo_id, timestamps.
- **events**: id, title, slug, summary, description, event_date, end_date, venue, theme, objective, banner_image, schedule, is_registration_open, is_active, sort_order, timestamps.
- **page_sections**, **programmes**, **partners**, **users**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **AI Integration:** Google Gemini REST API via native Laravel `Http` client (zero heavy packages, multimodal vision).
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with strict <= 180 KB WebP ceiling.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker, AI preview popover).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for Core FTP uploads to `/home/dzuefjel/dzuels_org`.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- Supports root document root execution via universal `index.php` and `.htaccess`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt` formatted for Core FTP.

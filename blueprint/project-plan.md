# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Ijero Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–14 (Completed & Shipped in Production, Features 1–44)
The foundation's platform has been successfully built, hardened, and deployed to live production (`https://dzf.dzuelsfoundation.org/`):
- **Milestones 1–10 (Shipped):** Core Web Platform, Brand Identity, MariaDB root SQL dump, Media Hub, Resilient GD image pipeline (strict <= 180 KB WebP), Givebutter U.S. donations, Universal Media Picker, and Gemini Multimodal AI Assistant.
- **Milestone 11 (Shipped):** Media Hub multi-criteria sorting and year filters, Tech Day separation from `/programmes`, curated 132-photo ingestion from `final/`, multi-year event showcase (2024, 2025, 2026), full 1,854-photo gallery archive ingestion, refreshed `dzuels_database.sql` (25 tables, 4,434 rows), and launch readiness check (0 errors).
- **Milestone 12 (Shipped):** Ijero Tech Day brand alignment, media gallery visibility toggles, anti-flash public gallery performance (`x-cloak`, fixed 4:3 aspect containers), dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`), Flutterwave removal from `/donate` with elevated GTBank 1-click copy and Givebutter U.S. giving, and balanced centered intake layout on `/get-involved`.
- **Milestone 13 (Shipped):** Giving channel correction (purged mock wire accounts, added "Why Donate" stewardship card), Tech Day event show page centered layout overhaul, and Pages CMS full content parity across all public pages with visibility toggles and section reordering.
- **Milestone 14 (Shipped):** Institutional fact corrections (foundation established computer lab and library at Dzuels Foundation in Ijero-Ekiti benefiting 500+ students, trained 30 teachers; did not equip public schools); catalogue new `Transformational leadership (TRANSCOMM)` programme; Pages CMS modular component architecture with inline text styling tags (`[accent]`, `[crimson]`, `[mint]`, `[bold]`) and 1-click editor buttons; Tech Day event show page redesign aligned with Homepage UI structure; and Central Media Hub deep EXIF metadata (capture timestamp, color space, file properties), pagination, sorting, and interactive zoom controls.

### Milestone 15: Unified Central Media Architecture, Event & Programme Photo Galleries, High-Capacity Media Hub & Stewardship Truthfulness (Active Target, Features 45–47)
- **Central Media Hub Unification, High-Capacity Pagination (80–500), Bulk Management & Calendar Date Metadata (Feature 45)**:
  - Establish the universal platform rule: every photo across the entire website originates from and resides in the central Media Library (`media_items`). Every image uploaded anywhere on the platform (albums, events, programmes, media hub) is automatically optimized (strict $\le 180\text{ KB}$ WebP ceiling) and registered into `media_items`.
  - Expand `/admin/media` pagination options to `80`, `150`, `250`, and `500` photos per page (default: `80`).
  - Upgrade photo date management: replace bare `year` text box with a full calendar date input (`taken_at`: Day, Month, Year) in photo edit modals, automatically synchronizing `year` while allowing staff to correct misdated photos.
  - Implement bulk management tools in `/admin/media`: select multiple photos (or select all) to bulk-assign to albums/events/programmes, bulk-toggle public gallery visibility, bulk-update capture date/year, and bulk-delete with confirmation.
- **Universal Photo Picker Integration for Gallery Albums, Events & Programmes with Public Lightbox Showcase (Feature 46)**:
  - Overhaul `/admin/gallery/{album}/photos` so the default interface is selecting existing photos directly from the Central Media Hub (with search, category, and year filters), while offering direct uploading as a secondary option that automatically registers uploads into the Media Hub and links them to the album.
  - Add multi-photo gallery management to `/admin/events/{id}/edit` and `/admin/programmes/{id}/edit` powered by the central media picker and direct-to-hub upload fallback.
  - On public `/events/{slug}` and `/programmes/{slug}` pages, render attached event/programme photos in a responsive photo grid with interactive lightbox viewing.
- **Homepage Flagship Event Status Dynamic Alignment & Site-Wide Institutional Copy Corrections (Feature 47)**:
  - Redesign Homepage Section 8 (ITD Showcase): eliminate hardcoded `"Upcoming Flagship Event • Oct 17, 2026"` and `"Register to Attend"`. Dynamically reflect the event's completed status (`"Recent Flagship Event • Highlights"`) with primary action pointing to `"Explore Event Gallery & Highlights"`.
  - Donate stewardship card correction on `/donate`: remove the `"Zero Administrative Deductions"` claim and the statement that the Board of Trustees independently funds all admin overhead. Replace with authentic, grounded stewardship emphasizing direct community impact, transparency, and CAC registered charity oversight.
  - Comprehensive site-wide purge of "equipping public schools": replace all remaining occurrences across views, controllers, seeders, and SQL dumps with accurate facts: Dzuels Foundation established its *own* well-equipped computer laboratory and library at Dzuels Foundation in Ijero-Ekiti (500+ students benefited), and urgently requires donor support to maintain, expand, and do more.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving and local GTBank transfer) with grounded, truthful stewardship.
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships through balanced pathways.
- **Event participants & schools**: Attending Ijero Tech Day, reading competitions, spelling bees, essay contests, and girls in tech initiatives, and browsing rich event photography.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, AI configuration and API keys, role permissions, global site settings, unified media library collections, bulk photo management, deletions, and database exports.
  - **Editor / Content Manager**: Writing and refining living page copy, managing multi-photo event and programme showcases, selecting photos from the central media library, reordering sections, and generating copy with the Gemini AI assistant.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and partner enquiries.

## 3. Features & Milestones

### Milestones 1–14 (Shipped, Features 1–44)
Features 1–44 fully completed, tested, and shipped in production.

### Milestone 15: Unified Central Media Architecture, Event & Programme Photo Galleries, High-Capacity Media Hub & Stewardship Truthfulness (Active Target, Features 45–47)
- 45. **Central Media Hub unification, high-capacity pagination (80–500), bulk management & calendar date metadata**:
  - Universal upload pipeline: any image uploaded anywhere on the platform is automatically optimized ($\le 180\text{ KB}$ WebP) and registered into `media_items`.
  - Expand `/admin/media` pagination options: `80`, `150`, `250`, `500` per page (default: `80`).
  - Full capture date control: add `taken_at` date picker (Day, Month, Year) in photo edit modal, keeping `year` in sync automatically and enabling manual correction of misdated photos.
  - Multi-select bulk management in `/admin/media`: bulk assign to albums/collections, bulk assign to events or programmes, bulk toggle public gallery visibility, bulk update date/year, and bulk delete.
- 46. **Universal photo picker integration for Gallery Albums, Events & Programmes with public lightbox showcase**:
  - Overhaul `/admin/gallery/{album}/photos`: make the default action selecting photos from the Central Media Hub, with direct uploading as a secondary action.
  - Add multi-photo attachment capability to `/admin/events/{id}/edit` and `/admin/programmes/{id}/edit` backed by the Central Media Hub.
  - Display attached photos on public `/events/{slug}` and `/programmes/{slug}` pages in a responsive photo grid with lightbox modal viewing.
- 47. **Homepage Flagship Event status dynamic alignment & site-wide institutional copy corrections**:
  - Dynamically align Homepage Section 8 to reflect current/completed flagship event status with accurate labels and actions (`"Explore Event Gallery & Highlights"`).
  - Purge `"Zero Administrative Deductions"` and claims of independent trustee funding from `/donate`, replacing with authentic stewardship.
  - Site-wide purge of "equips public school computer labs": accurately state that Dzuels Foundation equipped its own computer lab and library at Dzuels Foundation in Ijero-Ekiti (500+ students benefited) and seeks support to do more.

## 4. Data - What are we storing?

- **settings**: `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, media_item_id, image_path, caption, alt_text, year, sort_order, is_public, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, taken_at, color_space, title, caption, alt_text, source_type, year, is_public, gallery_album_id, gallery_photo_id, timestamps.
- **events**: id, title, slug, summary, description, event_date, end_date, venue, theme, objective, banner_image, schedule, is_registration_open, is_active, sort_order, timestamps.
- **event_media**: id, event_id (FK), media_item_id (FK), sort_order, timestamps.
- **programme_media**: id, programme_id (FK), media_item_id (FK), sort_order, timestamps.
- **page_sections**: id, page_key, section_key, headline, subheadline, body_text, image_path, button_text, button_url, meta_data, sort_order, is_active, timestamps.
- **programmes**, **partners**, **users**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**, **partner_enquiries**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **AI Integration:** Google Gemini REST API via native Laravel `Http` client (zero heavy packages, multimodal vision).
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with strict <= 180 KB WebP ceiling, preserving EXIF metadata on uploads before conversion.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker, lightbox, AI preview popover).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for Core FTP uploads to `/home/dzuefjel/dzuels_org`.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- Supports root document root execution via universal `index.php` and `.htaccess`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt` formatted for Core FTP.

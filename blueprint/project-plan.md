# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–11 (Completed & Shipped in Production, Features 1–35)
The foundation's platform has been successfully built, hardened, and deployed to live production (`https://dzf.dzuelsfoundation.org/`):
- **Milestones 1–10 (Shipped):** Core Web Platform, Brand Identity, MariaDB root SQL dump, Media Hub, Resilient GD image pipeline (strict <= 180 KB WebP), Givebutter U.S. donations, Universal Media Picker, and Gemini Multimodal AI Assistant.
- **Milestone 11 (Shipped):** Media Hub multi-criteria sorting and year filters, Tech Day separation from `/programmes`, curated 132-photo ingestion from `final/`, multi-year event showcase (2024, 2025, 2026), full 1,854-photo gallery archive ingestion, refreshed `dzuels_database.sql` (25 tables, 4,434 rows), and launch readiness check (0 errors).

### Milestone 12: Production UI & Experience Polish, Media Visibility Control & Brand Corrections (Active Target)
- **Brand Naming Alignment ("Ijero Tech Day")**:
  - Correct all naming across templates, seeders, routes, and SEO metadata from "Innovation and Tech Day" to "Ijero Tech Day" (ITD).
- **Media Visibility Controls & Usage Indicators**:
  - Add admin visibility toggles to control which photos appear on the public `/gallery`.
  - Add visual badges/indicators in `/admin/media` and the media picker: "Displayed in Gallery", "Used on Website" (actively referenced in CMS sections, programmes, events, settings, team members), and "Archive Only".
  - Ensure universal media picker displays all library photos across all years and collections without arbitrary exclusions.
- **Public Gallery Anti-Flash Performance**:
  - Eliminate loading flashes on `/gallery` with `x-cloak`, fixed-aspect containers, and curated public photo display.
- **Tech Day Event UI Overhaul & Dynamic Date Status**:
  - Dynamically calculate event status badges based on `event_date` (never hardcoded: "Upcoming Flagship" when in the future, "Past Festival Highlights / Concluded" when in the past).
  - Elevate Tech Day show page typography, edition switcher pills, and SEO schema.
- **Public Page Redesign for `/donate` and `/get-involved`**:
  - Remove Flutterwave from public donate page while elevating GTBank transfer with 1-click copy and Givebutter U.S. giving.
  - Redesign `/get-involved` into a balanced, centered layout with distinct pathway cards for volunteers, partners, and sponsors.

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

### Milestones 1–11 (Shipped, Features 1–35)
Features 1–35 fully completed, tested, and shipped in production.

### Milestone 12: Production UI & Experience Polish, Media Visibility Control & Brand Corrections (Active Target, Features 36–38)
- 36. **Ijero Tech Day brand correction, Media visibility controls & universal picker overhaul**:
  - Correct all naming from "Innovation and Tech Day" to "Ijero Tech Day" across templates, seeders, and routes.
  - Add public gallery visibility toggles and active "Used on Website" / "Displayed in Gallery" indicators in the Media Hub.
  - Ensure the universal media picker loads and displays all library photos across all years and collections.
- 37. **Public Gallery anti-flash performance & Tech Day event UI overhaul with dynamic date status & SEO**:
  - Eliminate image loading flashes on `/gallery` with `x-cloak`, fixed-aspect containers, and curated public photo display.
  - Dynamically calculate event status ("Upcoming Flagship" vs "Past Festival Highlights") from `event_date`.
  - Elevate the Tech Day event show page with festival typography, edition switcher polish, and full SEO metadata.
- 38. **Public page UI redesign for `/donate` (Flutterwave removal) and `/get-involved` (balanced, centered layout)**:
  - Remove Flutterwave from the public donate page while elevating GTBank transfer with 1-click copy and Givebutter U.S. giving.
  - Redesign `/get-involved` into a balanced, centered layout with distinct pathway cards for volunteers, partners, and sponsors.

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

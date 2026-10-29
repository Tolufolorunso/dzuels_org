# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Ijero Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–12 (Completed & Shipped in Production, Features 1–38)
The foundation's platform has been successfully built, hardened, and deployed to live production (`https://dzf.dzuelsfoundation.org/`):
- **Milestones 1–10 (Shipped):** Core Web Platform, Brand Identity, MariaDB root SQL dump, Media Hub, Resilient GD image pipeline (strict <= 180 KB WebP), Givebutter U.S. donations, Universal Media Picker, and Gemini Multimodal AI Assistant.
- **Milestone 11 (Shipped):** Media Hub multi-criteria sorting and year filters, Tech Day separation from `/programmes`, curated 132-photo ingestion from `final/`, multi-year event showcase (2024, 2025, 2026), full 1,854-photo gallery archive ingestion, refreshed `dzuels_database.sql` (25 tables, 4,434 rows), and launch readiness check (0 errors).
- **Milestone 12 (Shipped):** Ijero Tech Day brand alignment, media gallery visibility toggles, anti-flash public gallery performance (`x-cloak`, fixed 4:3 aspect containers), dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`), Flutterwave removal from `/donate` with elevated GTBank 1-click copy and Givebutter U.S. giving, and balanced centered intake layout on `/get-involved`.

### Milestone 13: Giving Stewardship, Tech Day Event UI Centering & Pages CMS Parity (Active Target)
- **Giving Channel Correction & "Why Donate to Our Cause"**:
  - Remove all unverified Domiciliary account numbers (USD, GBP, EUR) and SWIFT code (`GTBINGLA`) from `/donate`.
  - Replace Channel 3 in the giving grid with an authoritative "Why Donate to Our Cause" stewardship card (100% direct learner impact, zero administrative deductions, CAC legal standing, verified student track record, and transparent donor updates).
- **Tech Day Event Show Page Centering (`/events/ijero-tech-day-[year]`)**:
  - Eliminate the left-heavy layout bias.
  - Center the hero typography, kicker badges, 4-stat festival metric bar, edition switcher pills, and countdown timer (`max-w-4xl mx-auto text-center`).
  - Center the wide 16:9 banner container (`max-w-5xl mx-auto`).
  - Redesign narrative, agenda timeline, authentic photo gallery, milestone celebration summary, and sponsor cards into a balanced, centered layout.
- **Pages CMS Public Content Parity, Visibility Controls & Section Reordering**:
  - Add `is_active` (boolean, default: `true`) to `page_sections` via migration.
  - Add one-click visibility toggle switches (`Visible on Public Page` / `Hidden`) and sort controls (up/down and sort order inputs) in `/admin/pages/{page}`.
  - Align `PageSection` seeders and Blade templates across `/about`, `/donate`, `/programmes`, `/contact`, and `/get-involved` with the exact living content public visitors see, supporting dynamic editing, reordering, and section toggling with graceful fallbacks.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving and local GTBank transfer).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships through balanced pathways.
- **Event participants & schools**: Attending Ijero Tech Day, reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, AI configuration and API keys, role permissions, global site settings, media library collections, deletions, and database exports.
  - **Editor / Content Manager**: Writing and refining living page copy across all public pages, reordering and toggling sections, and selecting photos with the media picker and AI assistant.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and partner enquiries.

## 3. Features & Milestones

### Milestones 1–12 (Shipped, Features 1–38)
Features 1–38 fully completed, tested, and shipped in production.

### Milestone 13: Giving Stewardship, Tech Day Event UI Centering & Pages CMS Parity (Active Target, Features 39–40)
- 39. **Giving channel correction (remove mock wires, add "Why Donate") & Tech Day event show page centered UI overhaul**:
  - Purge mock Domiciliary wire accounts and Swift code from `/donate`, replacing them with an authoritative "Why Donate to Our Cause" card highlighting 100% direct learner impact, zero administrative deductions, CAC legal standing, and tangible outcomes.
  - Redesign the Tech Day event show page (`/events/ijero-tech-day-[year]`) from its left-flushed layout into a centered, balanced festival experience with centered hero metrics, edition switcher pills, countdown timer, and balanced narrative and milestones cards.
- 40. **Pages CMS overhaul: dynamic public content parity, visibility toggles & section reordering**:
  - Add `is_active` to `page_sections` with admin toggle switches and move up/down sort controls.
  - Synchronize CMS sections and seeders across `/about`, `/donate`, `/programmes`, `/contact`, and `/get-involved` with the exact living content the public sees, enabling dynamic editing, reordering, and section show/hide.

## 4. Data - What are we storing?

- **settings**: `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, is_public, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, title, caption, alt_text, source_type, gallery_photo_id, is_public, timestamps.
- **events**: id, title, slug, summary, description, event_date, end_date, venue, theme, objective, banner_image, schedule, is_registration_open, is_active, sort_order, timestamps.
- **page_sections**: id, page_key, section_key, headline, subheadline, body_text, image_path, button_text, button_url, meta_data, sort_order, is_active, timestamps.
- **programmes**, **partners**, **users**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**, **partner_enquiries**.

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

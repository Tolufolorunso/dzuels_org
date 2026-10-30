# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash 5d783cfb8b8cd73aa52f89650db452fa03caf0e04a505723797fa51cb8811e2a -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (founded June 2015, registered 2015) providing education support, literacy resources, digital skills acquisition for indigent children and youth, and social welfare relief in Ijero-Ekiti and surrounding communities.

The web application was rebuilt from the ground up to establish an authentic, high-performance, mobile-first platform for donors, volunteers, event attendees, and beneficiaries.

- **Milestones 1–10 (Completed & Shipped):** Core Platform, Layout, CMS, Authentication, SEO, Image Optimization, Famoyinka Library, Central Media Hub, Dynamic Collections, Givebutter U.S. donations, and Gemini Multimodal AI Assistant.
- **Milestone 11 (Completed & Shipped):** Events Architecture, Tech Day Flagship Showcase & Media Hub Overhaul across Features 33–35: multi-criteria sorting and year filter chips (`2024`, `2025`, `2026`) in Media Hub, Tech Day separation from `/programmes` with 301 redirects, multi-year showcase for 2024 (Inaugural), 2025 (2nd Edition), and 2026 (AI Empowerment Edition), full ingestion and WebP optimization ($\le 180\text{ KB}$) of 1,854 photos from `gallery/` and 132 photos from `final/`, production database SQL dump refresh (`dzuels_database.sql` with 25 tables and 4,434 rows), and deployment manifest synchronization.
- **Milestone 12 (Completed & Shipped):** Production UI & Experience Polish, Media Visibility Control & Brand Corrections across Features 36–38: Ijero Tech Day brand alignment, media gallery visibility toggles, anti-flash public gallery performance (`x-cloak`, fixed 4:3 aspect containers), dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`), Flutterwave removal from `/donate` with elevated GTBank 1-click transfer and Givebutter U.S. giving, and balanced centered intake layout on `/get-involved`.
- **Milestone 13 (Completed & Shipped):** Giving channel correction (purged mock wire accounts, added "Why Donate" stewardship card), Tech Day event show page centered layout overhaul, and Pages CMS full content parity across all public pages with visibility toggles and section reordering (Features 39–40).
- **Milestone 14 (Completed & Shipped):** Historical accuracy corrections (foundation established computer lab and library at Dzuels Foundation in Ijero-Ekiti benefiting 500+ students, trained 30 teachers; did not equip public schools) and 4 exact institutional copy replacements; new `Transformational leadership (TRANSCOMM)` programme; Pages CMS modular component architecture with inline text styling tags (`[accent]`, `[crimson]`, `[mint]`, `[bold]`) and 1-click editor buttons; Tech Day event show page redesign aligned with Homepage UI structure; and Central Media Hub deep EXIF metadata (capture timestamp, color space, file properties), pagination, sorting, and interactive zoom controls (Features 41–44).
- **Milestone 15: Unified Central Media Architecture, Event & Programme Photo Galleries, High-Capacity Media Hub & Stewardship Truthfulness (Active Target, Features 45–47):** Unify all site imagery through the Central Media Hub with universal upload optimization ($\le 180\text{ KB}$ WebP) and registration in `media_items`; expand Media Hub pagination to 80–500 photos per page with bulk tools and full calendar date editing (`taken_at`: Day, Month, Year); overhaul gallery album photo management to default to the central picker; add multi-photo gallery management to events and programmes with public lightbox viewing; dynamically align the Homepage ITD showcase to reflect completed event status with highlights; and conduct a complete site-wide purge of "Zero Administrative Deductions" and "equips public school computer labs".

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, GTBank direct transfer details with 1-click copy, Givebutter U.S. diaspora giving, and clear stewardship guarantees.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries via structured, balanced intake pathways.
- **Event Participants & Schools:** Students and educators attending Ijero Tech Day (ITD), reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, Parents & Students:** Community members accessing literacy resources, activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, AI configuration and Gemini API keys, role permissions, global settings (logo, multi-phone lines, Givebutter link, hours/timezone), media library collections, bulk photo operations, deletions, and database exports.
  - **Editor / Content Manager:** Accessing the Central Media Library to toggle gallery visibility, curate collections, upload and organize photos, update living page content, manage event/programme photo galleries, toggle and reorder page sections, manage programmes, events, sponsors, partner logos, and generate copy with the Gemini AI assistant.
  - **Support / Viewer:** Reading incoming contact enquiries, volunteer submissions, and partner proposals.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- Multi-role staff access at `/admin` (Super Admin, Editor, Support); no public user registrations.
- Form submissions collect personal data handled in strict compliance with the foundation's privacy policy.
- Online payments are offloaded to Givebutter hosted checkout; bank transfers use direct NGN accounts. No payment card data is processed on-server.

## Features

### Milestones 1–14 Features (Completed & Shipped)

1–44. Core Platform, Layout, CMS, Authentication, SEO, Image Optimization, Famoyinka Library, Media Hub, Dynamic Collections, Gemini AI Assistant, Tech Day Flagship Showcase, Giving Channels, Modular Pages CMS, Institutional Facts, and Deep EXIF Metadata. *(Completed & Shipped)*

### Milestone 15 Features (Active Target)

45. **Central Media Hub unification, high-capacity pagination (80–500), bulk management & calendar date metadata** - Expand Media Hub (`/admin/media`) pagination selector to `80`, `150`, `250`, and `500` photos per page (default: `80`); add full calendar capture date editing (`taken_at`: Day, Month, Year) in photo edit modals, keeping `year` in sync automatically and enabling manual correction of misdated photos; implement multi-select bulk operations (bulk assign to albums/collections, bulk assign to events or programmes, bulk toggle public gallery visibility, bulk update date/year, and bulk delete); and enforce the universal pipeline rule that every photo uploaded anywhere on the platform is automatically optimized ($\le 180\text{ KB}$ WebP) and immediately registered into `media_items`.
46. **Universal photo picker integration for Gallery Albums, Events & Programmes with public lightbox showcase** - Overhaul `/admin/gallery/{album}/photos` so the default interface selects existing photos from the Central Media Hub, with direct uploading as a secondary option; add multi-photo gallery management to `/admin/events/{id}/edit` and `/admin/programmes/{id}/edit` powered by the central media picker and direct-to-hub upload fallback; and render attached event and programme photos on public `/events/{slug}` and `/programmes/{slug}` pages in a responsive photo grid with the existing interactive lightbox viewer.
47. **Homepage Flagship Event status dynamic alignment & site-wide institutional copy corrections** - Redesign Homepage Section 8 to eliminate hardcoded upcoming copy and dynamically reflect the event's completed status ("Recent Flagship Event • Highlights") with active links to the event showcase and gallery; purge "Zero Administrative Deductions" and false claims about the Board funding all admin overhead from `/donate`, replacing with grounded stewardship transparency; and perform a comprehensive site-wide purge replacing all lingering claims of "equips public school computer labs" across views, controllers, seeders, and SQL dumps with the verified fact that Dzuels Foundation equipped its own computer lab and library at Dzuels Foundation in Ijero-Ekiti (500+ students benefited) and urgently needs support to expand.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Donor receipts and recurring giving**: Automated receipts and recurring gifts.

## Data model

### User
- `id` (bigint, PK), `name` (string), `email` (string, unique), `password` (string), `role` (super_admin, editor, support), `is_active` (boolean), `timestamps`

### MediaItem / Unified Asset Catalog
- `id` (bigint, PK), `filename` (string), `file_path` (string), `file_size` (integer), `mime_type` (string), `width` (integer, nullable), `height` (integer, nullable), `taken_at` (timestamp, nullable), `color_space` (string, nullable), `title` (string, nullable), `caption` (text, nullable), `alt_text` (text, nullable), `source_type` (string), `year` (integer, nullable), `is_public` (boolean), `gallery_album_id` (bigint, FK, nullable), `gallery_photo_id` (bigint, FK, nullable), `timestamps`

### Event, EventMedia & EventSponsor
- `events`: `id`, `title`, `slug`, `summary`, `description`, `objective`, `event_date`, `venue`, `banner_image`, `schedule` (json), `is_registration_open`, `sort_order`, `is_active`, `timestamps`
- `event_media` / event photos: `id`, `event_id` (FK), `media_item_id` (FK), `sort_order` (integer), `timestamps`
- `event_sponsors`: `id`, `event_id` (FK), `name`, `logo_path`, `tier`, `website_url`, `sort_order`, `timestamps`

### Programme & ProgrammeMedia
- `programmes`: `id` (bigint, PK), `title` (string), `slug` (string, unique), `summary` (text), `body` (longText), `cover_image` (string, nullable), `partner_name` (string, nullable), `sort_order` (integer), `is_featured` (boolean), `timestamps`
- `programme_media` / programme photos: `id`, `programme_id` (FK), `media_item_id` (FK), `sort_order` (integer), `timestamps`

### Page & PageSection (Dynamic CMS)
- `id` (bigint, PK), `page_key` (string), `section_key` (string), `headline` (string, nullable), `subheadline` (string, nullable), `body_text` (text, nullable), `image_path` (string, nullable), `button_text` (string, nullable), `button_url` (string, nullable), `meta_data` (json, nullable), `sort_order` (integer), `is_active` (boolean), `timestamps`

### GalleryAlbum & GalleryPhoto
- `gallery_albums`: `id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `timestamps`
- `gallery_photos`: `id`, `gallery_album_id` (FK), `media_item_id` (FK, nullable), `image_path`, `caption`, `alt_text`, `year` (integer, nullable), `sort_order`, `is_public` (boolean), `timestamps`

### Setting
- `id` (bigint, PK), `key` (string, unique) - `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `contact_working_hours`, `contact_timezone`, impact numbers, banking, registration, `value` (text, nullable), `type` (text, integer, json, boolean), `timestamps`

### TeamMember & Partner
- `team_members`: `id`, `name`, `role`, `group` (trustee, management, advisor), `field`, `bio`, `photo`, `linkedin_url`, `sort_order`, `is_active`, `timestamps`
- `partners`: `id`, `name`, `logo`, `website_url`, `sort_order`, `is_active`, `timestamps`

### ContactMessage, VolunteerApplication & PartnerEnquiry
- `contact_messages`: `id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `is_read`, `timestamps`
- `volunteer_applications`: `id`, `full_name`, `email`, `phone`, `age_band`, `work_status`, `skills`, `status`, `timestamps`
- `partner_enquiries`: `id`, `organization_name`, `contact_name`, `email`, `phone`, `partnership_type`, `message`, `status`, `timestamps`

## Tech stack

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+ in development and production (`server161.web-hosting.com`), Eloquent ORM.
- **AI Integration:** Google Gemini REST API via native Laravel `Http` client (`GeminiService`), zero external dependencies, multimodal vision support.
- **Image Optimization & Upload Pipeline:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies. Strict iterative optimization loop enforcing <= 180 KB WebP output with universal Media Hub registration.
- **Admin Panel:** Tailored administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js for interactive widgets, lightbox, multi-photo bulk upload, central media manager, media picker modal, and AI preview popover.

## Deployment & Verification

- **Production Server:** Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- **Root Routing:** Root document execution via universal `index.php` and `.htaccess`.
- **Database Backups:** Automated `php artisan db:export-sql` generating root `dzuels_database.sql` for 1-click phpMyAdmin import.
- **Sync Tracking:** `cpanel_changed_files.txt` manifest tracking all modified files for Core FTP deployment.

# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash 9a930524019c6969c960e93de222f72639e9992c1c395ce66f751bd287a397e4 -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

Dzuels Educational Foundation is a Nigerian charity (founded June 2015, registered 2015) providing education support, literacy resources, digital skills acquisition for indigent children and youth, and social welfare relief in Ijero-Ekiti and surrounding communities.

The web application was rebuilt from the ground up to establish an authentic, high-performance, mobile-first platform for donors, volunteers, event attendees, and beneficiaries.

- **Milestones 1–10 (Completed & Shipped):** Overhauled platform across 32 features: WCAG 2.2 AA accessibility, responsive design, brand asset suite, ITD overhaul, server-rendered lightbox, WordPress-style Page CMS, multi-tier RBAC (`super_admin`, `editor`, `support`), MySQL export tooling, hotline updates, hero overlays, dynamic contact CMS, dynamic partners strip, resilient media pipeline with GD fallback, multi-photo bulk uploader, dynamic site logo in settings, Givebutter U.S. donations, authentic 6 Impact Causes CMS, $\le 180\text{ KB}$ WebP optimization engine, dynamic 4-photo Famoyinka Library CMS, Get Involved overhaul, universal media picker `<x-admin.media-picker>`, Central Media Hub (`/admin/media`), dynamic media collections, multimodal Gemini AI content assistant, server compression, and full Schema.org structured data suite.
- **Milestone 11 (Completed & Shipped):** Events Architecture, Tech Day Flagship Showcase & Media Hub Overhaul across Features 33–35: multi-criteria sorting and year filter chips (`2024`, `2025`, `2026`) in Media Hub, Tech Day separation from `/programmes` with 301 permanent redirects, multi-year showcase for 2024 (Inaugural), 2025 (2nd Edition), and 2026 (AI Empowerment Edition), full ingestion and WebP optimization ($\le 180\text{ KB}$) of 1,854 photos from `gallery/` and 132 photos from `final/`, production database SQL dump refresh (`dzuels_database.sql` with 25 tables and 4,434 rows), and deployment manifest synchronization.
- **Milestone 12 (Completed & Shipped):** Production UI & Experience Polish, Media Visibility Control & Brand Corrections across Features 36–38: Ijero Tech Day brand alignment, media gallery visibility toggles, anti-flash public gallery performance (`x-cloak`, fixed 4:3 aspect containers), dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`), Flutterwave removal from `/donate` with elevated GTBank 1-click transfer and Givebutter U.S. giving, and balanced centered intake layout on `/get-involved`.
- **Milestone 13: Giving Stewardship, Tech Day Event UI Centering & Pages CMS Parity (Active Target):** Purging mock Domiciliary wire accounts and Swift code from `/donate`, replacing them with an authoritative "Why Donate to Our Cause" stewardship card; redesigning `/events/ijero-tech-day-[year]` from its left-flushed layout into a centered, balanced festival experience; and overhauling Pages CMS with `is_active` section show/hide toggles, move up/down sort controls, and full content synchronization across `/about`, `/donate`, `/programmes`, `/contact`, and `/get-involved`.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, GTBank direct transfer details with 1-click copy, Givebutter U.S. diaspora giving, and clear stewardship guarantees.
- **Volunteers & Partners:** Apply for mentorship or submit corporate partnership inquiries via structured, balanced intake pathways.
- **Event Participants & Schools:** Students and educators attending Ijero Tech Day (ITD), reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, Parents & Students:** Community members accessing literacy resources, activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Role-Based Admin Users):**
  - **Super Admin:** Full platform control, managing staff accounts, AI configuration and Gemini API keys, role permissions, global settings (logo, multi-phone lines, Givebutter link, hours/timezone), media library collections, deletions, and database exports.
  - **Editor / Content Manager:** Accessing the Central Media Library to toggle gallery visibility, curate collections, upload and organize photos, update living page content, toggle and reorder page sections, manage programmes, events, sponsors, partner logos, and generate copy with the Gemini AI assistant.
  - **Support / Viewer:** Reading incoming contact enquiries, volunteer submissions, and partner proposals.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- Multi-role staff access at `/admin` (Super Admin, Editor, Support); no public user registrations.
- Form submissions collect personal data handled in strict compliance with the foundation's privacy policy.
- Online payments are offloaded to Givebutter hosted checkout; bank transfers use direct NGN accounts. No payment card data is processed on-server.

## Features

### Milestones 1–10 Features (Completed & Shipped)

1–32. Core Platform, Layout, CMS, Authentication, SEO, Image Optimization, Famoyinka Library, Media Hub, Dynamic Collections, and Gemini AI Assistant. *(Completed & Shipped)*

### Milestone 11 Features (Completed & Shipped)

33. **Media Hub sorting, year filtering & universal photo picker overhaul** - Multi-criteria sorting and quick year chips (`2024`, `2025`, `2026`) in Media Hub and modal picker. *(Completed & Shipped)*
34. **Tech Day event architecture, multi-year showcase (2024, 2025, 2026) & programme separation** - Separation of Tech Day from `/programmes` with 301 redirects, multi-year festival edition switching, and curated 132-photo ingestion from `final/`. *(Completed & Shipped)*
35. **Media integration, production SQL database export & launch verification** - Full 1,854-photo ingestion from `gallery/`, brand asset compression ($\le 180\text{ KB}$ WebP), fresh `dzuels_database.sql` export (25 tables, 4,434 rows), and launch readiness verification (0 failures). *(Completed & Shipped)*

### Milestone 12 Features (Completed & Shipped)

36. **Ijero Tech Day brand correction, Media visibility controls & universal picker overhaul** - Brand naming alignment from "Innovation and Tech Day" to "Ijero Tech Day" (ITD); Media Hub public gallery visibility toggles and active "Used on Website" / "Displayed in Gallery" indicators; and universal media picker availability across all library photos. *(Completed & Shipped)*
37. **Public Gallery anti-flash performance & Tech Day event UI overhaul with dynamic date status & SEO** - Elimination of photo loading flashes on `/gallery` with `x-cloak` and curated display; dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`); and festival typography with rich Schema.org Event JSON-LD. *(Completed & Shipped)*
38. **Public page UI redesign for `/donate` (Flutterwave removal) and `/get-involved` (balanced, centered layout)** - Flutterwave removal from `/donate` while elevating GTBank 1-click transfer and Givebutter U.S. giving in a 4-channel grid; and redesign of `/get-involved` into a balanced, centered layout with distinct pathway cards. *(Completed & Shipped)*

### Milestone 13 Features (Active Target)

39. **Giving channel correction (remove mock wires, add "Why Donate") & Tech Day event show page centered UI overhaul** - Purge mock Domiciliary wire accounts and Swift code from `/donate`, replacing them with an authoritative "Why Donate to Our Cause" card highlighting 100% direct learner impact, zero administrative deductions, CAC legal standing, and tangible outcomes; and redesign the Tech Day event show page (`/events/ijero-tech-day-[year]`) from its left-flushed layout into a centered, balanced festival experience with centered hero metrics, edition switcher pills, countdown timer, and balanced narrative and milestones cards.
40. **Pages CMS overhaul: dynamic public content parity, visibility toggles & section reordering** - Add `is_active` to `page_sections` with admin toggle switches and move up/down sort controls; and synchronize CMS sections and seeders across `/about`, `/donate`, `/programmes`, `/contact`, and `/get-involved` with the exact living content the public sees, enabling dynamic editing, reordering, and section show/hide.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Donor receipts and recurring giving**: Automated receipts and recurring gifts.

## Data model

### User
- `id` (bigint, PK), `name` (string), `email` (string, unique), `password` (string), `role` (super_admin, editor, support), `is_active` (boolean), `timestamps`

### MediaItem / Unified Asset Catalog
- `id` (bigint, PK), `filename` (string), `file_path` (string), `file_size` (integer), `mime_type` (string), `width` (integer, nullable), `height` (integer, nullable), `title` (string, nullable), `caption` (text, nullable), `alt_text` (text, nullable), `source_type` (string), `year` (integer, nullable), `is_public` (boolean), `gallery_album_id` (bigint, FK, nullable), `gallery_photo_id` (bigint, FK, nullable), `timestamps`

### Page & PageSection (Dynamic CMS)
- `id` (bigint, PK), `page_key` (string), `section_key` (string), `headline` (string, nullable), `subheadline` (string, nullable), `body_text` (text, nullable), `image_path` (string, nullable), `button_text` (string, nullable), `button_url` (string, nullable), `meta_data` (json, nullable), `sort_order` (integer), `is_active` (boolean), `timestamps`

### Event & EventSponsor
- `events`: `id`, `title`, `slug`, `summary`, `description`, `objective`, `event_date`, `venue`, `banner_image`, `schedule` (json), `is_registration_open`, `sort_order`, `is_active`, `timestamps`
- `event_sponsors`: `id`, `event_id` (FK), `name`, `logo_path`, `tier`, `website_url`, `sort_order`, `timestamps`

### Setting
- `id` (bigint, PK), `key` (string, unique) - `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `contact_working_hours`, `contact_timezone`, impact numbers, banking, registration, `value` (text, nullable), `type` (text, integer, json, boolean), `timestamps`

### Programme
- `id` (bigint, PK), `title` (string), `slug` (string, unique), `summary` (text), `body` (longText), `cover_image` (string, nullable), `partner_name` (string, nullable), `sort_order` (integer), `is_featured` (boolean), `timestamps`

### GalleryAlbum & GalleryPhoto
- `gallery_albums`: `id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `timestamps`
- `gallery_photos`: `id`, `gallery_album_id` (FK), `image_path`, `caption`, `alt_text`, `year` (integer, nullable), `sort_order`, `is_public` (boolean), `timestamps`

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
- **Image Optimization & Upload Pipeline:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies. Strict iterative optimization loop enforcing <= 180 KB WebP output.
- **Admin Panel:** Tailored administrative dashboard at `/admin` with role-based authorization (Super Admin, Editor, Support).
- **Frontend Templates:** Blade server-side templates with modular components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full`.
- **JavaScript:** Lightweight Alpine.js for interactive widgets, lightbox, multi-photo bulk upload, central media manager, media picker modal, and AI preview popover.

## Deployment & Verification

- **Production Server:** Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- **Root Routing:** Root document execution via universal `index.php` and `.htaccess`.
- **Database Backups:** Automated `php artisan db:export-sql` generating root `dzuels_database.sql` for 1-click phpMyAdmin import.
- **Sync Tracking:** `cpanel_changed_files.txt` manifest tracking all modified files for Core FTP deployment.

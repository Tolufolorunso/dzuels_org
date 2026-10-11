# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), a Reading Competition, and a Spelling Bee.

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

### Milestone 3: Production Server Readiness & Launch Refinements (Active Target)
Finalizing production readiness for live deployment on Namecheap cPanel (`server161.web-hosting.com` running MariaDB):
- **MariaDB / MySQL Architecture**: Transition `.env` and `.env.example` to default to MariaDB/MySQL. Provide `dzuels_database.sql` directly in the project root folder for 1-click import into phpMyAdmin on `server161.web-hosting.com`.
- **Dynamic Database-Driven Content**: Ensure all layout elements, contact numbers, impact statistics, and addresses pull dynamically from the database (`settings` and `page_sections` tables) with zero hardcoded values.
- **Hotline & Contact Update**: Completely replace old telephone `+234 803 388 9576` with `+234 904 249 3883` across all seeders, database records, views, and error pages.
- **Giving Channels Refinement**: Hide the Diaspora & International Wire transfers channel on `/donate` for now, keeping focus on Flutterwave online donations and direct GTBank Naira transfers.
- **Contextual Hero Section Imagery**: Enrich About Us, Programmes, and Contact page heroes with rich, contextual background imagery and maroon gradient overlays.
- **Spelling Bee Programme Image Fix**: Replace the incorrect `home_1.webp` with an authentic Spelling Bee championship photo (`front/images/gallery/optimized/spelling-01.webp`).

## 2. Users - Who is this for?

- **Prospective donors**: In Nigeria and the diaspora, seeking transparency and quick donation methods.
- **Volunteers & Partners**: Submitting applications and corporate partnership inquiries.
- **Event participants & schools**: Attending ITD 2026 and browsing schedules, objectives, and sponsors.
- **Beneficiaries & parents**: Accessing educational resources, reading competitions, and visual archives.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Full platform control, managing staff users, access permissions, site settings, and database exports.
  - **Editor / Content Manager**: Updating page content (WordPress-style), programmes, events, sponsors, and gallery albums.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event attendee records.

## 3. Features & Milestones

### Milestone 1: Core Web Platform (Shipped, Features 1–17)
- Public layout, brand theme, and dark mode engine.
- Admin dashboard, dynamic site settings, and impact counters.
- Home, About, Programmes, Events, Gallery, Donate, Get Involved, Contact, News, and Newsletter signup.
- Site responsiveness overhaul and modern admin UI.
- Comprehensive SEO foundation, privacy policy, and branded error pages.
- WCAG 2.2 AA accessibility compliance, automated `app:launch-check`, and production runbook.

### Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment (Shipped, Features 18–20)
- 18. Brand assets, ITD 2026 event overhaul, and gallery optimization.
- 19. Site-wide page content management, user access control, and password recovery.
- 20. MySQL database export and cPanel phpMyAdmin migration suite.

### Milestone 3: Production Server Readiness & Launch Refinements (Features 21+)
- 21. **Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements**:
  - Configure `.env` and `.env.example` for local and production MariaDB/MySQL (`server161.web-hosting.com`).
  - Provide root `dzuels_database.sql` for 1-click phpMyAdmin import.
  - Ensure all layout and contact data is strictly driven from database settings.
  - Replace phone number with `+234 904 249 3883` site-wide.
  - Hide Diaspora & International Wire on `/donate`.
  - Add contextual background imagery to About, Programmes, and Contact hero sections.
  - Fix Spelling Bee cover photo to authentic contest image (`front/images/gallery/optimized/spelling-01.webp`).

## 4. Data - What are we storing?

- **users**: id, name, email, password, role (`super_admin`, `editor`, `support`), is_active, timestamps.
- **password_reset_tokens**: email, token, created_at.
- **page_sections**: page key, section key, headline, subheadline, body_text, image_path, button_text, button_url, meta_data (JSON), sort_order.
- **event_sponsors**: id, event_id, name, logo_path, tier, website_url, sort_order.
- **settings**: key/value pairs for impact numbers, contacts (`contact_phone_1` = `+234 904 249 3883`), banking, registration, Flutterwave links.
- **programmes**, **events**, **gallery_albums**, **gallery_photos**, **team_members**, **partners**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 13 on PHP 8.3+, MariaDB 10.3+ / MySQL 8.0+ in development and production (`server161.web-hosting.com`).
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (robust inline/modular initialization).
- **Authentication & RBAC:** Session guard with role-based policies and password reset brokers.
- **Export Tooling:** Custom SQL dump generator supporting MariaDB/MySQL syntax (`CREATE TABLE`, batch `INSERT INTO` with `utf8mb4_unicode_ci`).

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3+.
- Domain: `dzuelsfoundation.org`.
- Database imported via cPanel phpMyAdmin using root `dzuels_database.sql`.
- HTTPS secured via AutoSSL / Let's Encrypt.
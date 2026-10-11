# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), a Reading Competition, and a Spelling Bee.

### Milestone 1 (Completed & Shipped)
The foundation's web presence was rebuilt into a modern, fast, accessible, SEO-optimized Laravel application with verified WCAG 2.2 AA compliance, brand color tokens, dynamic settings, team/partner/programme showcases, payment details, contact forms, and security safeguards across 17 completed features.

### Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment
To make the platform completely independent, production-ready on shared cPanel hosting, and self-manageable by non-technical staff:
- **Brand Identity & Favicons**: Complete the brand asset suite (multi-size `favicon.ico`, PNG touch icons, webmanifest) using the foundation logo.
- **ITD 2026 Refinements & Historical Separation**: Update the flagship event page for October 3, 2026 with the backdrop extracted from the official PDF, the new AI empowerment objective, remove SeedDev and public registration forms, support 3 dynamic sponsors with flexible admin controls, and distinguish historical ITD editions.
- **Gallery Stability & Polish**: Fix script loading and gallery rendering issues, ensuring photos are server-rendered with responsive masonry, filter chips, and a working keyboard lightbox.
- **WordPress-Style Page Content Management**: Allow staff to edit text, headlines, vision/mission, values, and section imagery across all public pages directly from `/admin`.
- **Role-Based Access Control & Password Recovery**: Allow Super Admins to create and manage staff accounts with granular access levels (Super Admin, Editor/Content Manager, Support/Viewer), alongside secure forgotten password email resets and in-dashboard password updates.
- **cPanel phpMyAdmin Database Migration**: Provide an automated database export tool producing a clean, MySQL/MariaDB-compliant `.sql` file ready for 1-click import into cPanel phpMyAdmin, along with a deployment runbook.

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

### Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment (Features 18–20)
- **18. Brand assets, ITD 2026 event overhaul, and gallery optimization**:
  - Full favicon suite generated from `logo.png` (`favicon.ico`, `favicon-16x16.png`, `favicon-32x32.png`, `apple-touch-icon.png`, `android-chrome` icons, `site.webmanifest`).
  - ITD 2026 updated with extracted 1390×1132 backdrop from PDF, date Saturday, October 3, 2026, objective *"Empowering children with AI tools to learn smarter, think creatively, and build the future"*, SeedDev removed for 2026, public registration form removed, 3 dynamic sponsors with extensible management, and past events clearly categorized.
  - Gallery page fixed, server-rendered with responsive masonry grid, filter chips, and keyboard lightbox.
- **19. Site-wide page content management, user access control, and password recovery**:
  - WordPress-style visual page editor in `/admin/pages` for Home, About, Programmes, Contact, and Donate.
  - Role-based user management (Super Admin, Editor, Support/Viewer) with granular route authorization.
  - Forgotten password reset workflow via signed email tokens and in-dashboard password updates.
- **20. MySQL database export and cPanel phpMyAdmin migration suite**:
  - Automated `php artisan db:export-sql` Artisan command generating a MySQL/MariaDB-compliant `.sql` dump.
  - Downloadable/exportable database dump ready for phpMyAdmin on cPanel.
  - Comprehensive cPanel phpMyAdmin import and `.env` setup guide.

## 4. Data - What are we storing?

- **users**: id, name, email, password, role (`super_admin`, `editor`, `support`), is_active, timestamps.
- **password_reset_tokens**: email, token, created_at.
- **pages** & **page_sections**: page key, section key, headline, subheadline, body_text, image_path, button_text, button_url, meta_data (JSON), sort_order.
- **event_sponsors**: id, event_id, name, logo_path, tier, website_url, sort_order.
- **settings**: key/value pairs for impact numbers, contacts, banking, registration, Flutterwave links.
- **programmes**, **events**, **gallery_albums**, **gallery_photos**, **team_members**, **partners**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 13 on PHP 8.3+, SQLite (development) and MySQL/MariaDB (cPanel production).
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (robust inline/modular initialization).
- **Authentication & RBAC:** Session guard with role-based policies and password reset brokers.
- **Export Tooling:** Custom SQL dump generator supporting MySQL syntax (`CREATE TABLE`, `INSERT INTO` with `utf8mb4_unicode_ci`).

## 6. Deployment - cPanel & Production

- Hosted on cPanel VPS / shared hosting with PHP 8.3+.
- Domain: `dzuelsfoundation.org`.
- Database imported via cPanel phpMyAdmin.
- HTTPS secured via AutoSSL / Let's Encrypt.
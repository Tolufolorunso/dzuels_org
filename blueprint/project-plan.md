# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Innovation and Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–9 (Completed & Shipped in Production, Features 1–31)
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

### Milestone 10: Gemini AI Content Assistant & Multimodal Admin Suite (Active Target)
- **Native Gemini Service Integration**:
  - Build `GeminiService` using Laravel's native `Http` client with zero external package dependencies.
  - Connect to the official Google GenAI REST API (`https://generativelanguage.googleapis.com/v1beta/models/...`).
  - Support text generation and multimodal vision analysis (passing base64 `inline_data` for photos).
  - Inject foundation context into the system prompt: Nigerian educational charity, youth literacy and tech empowerment, inspiring and professional tone, and British/Commonwealth English spelling (e.g., *programme, organise, colour*).
- **Admin Settings Management & Live Connection Test**:
  - Add an **AI & Gemini Integration** configuration card in Site Settings (`/admin/settings`).
  - Configurable `gemini_api_key` in the `settings` database table, falling back to `.env` (`GEMINI_API_KEY`).
  - Configurable `gemini_model` dropdown selector (defaulting to fast multimodal models: `gemini-1.5-flash`, `gemini-2.0-flash`, `gemini-1.5-pro`, or custom).
  - Include an interactive **"Test Connection"** button in Settings that verifies the API key against Google's API and returns immediate visual status.
- **Context-Aware Backend API**:
  - Dedicated endpoint `POST /admin/ai/generate` protected by admin auth middleware and rate limiting.
  - Accepts field payload: `field_name`, `field_label`, `page_context`, `section_context`, `sibling_data` (e.g. existing title/summary), `prompt_tweak` (optional custom instruction), and optional `image_url` / `image_path`.
- **Reusable Blade / Alpine UI Component (`<x-admin.ai-button>`)**:
  - Elegant sparkle icon (✨) attaching cleanly beside inputs and textareas without breaking native form submissions or browser validation.
  - Inline floating preview popover showing:
    - Animated skeleton/loading state while generating.
    - Generated suggestion display with 1-click **Insert** (populates/replaces field).
    - **Regenerate** button for alternative variations.
    - Optional **Prompt Tweak** input (e.g. "Make it shorter", "Focus on Ijero-Ekiti", "Include a call to action").
    - **Cancel** / dismiss button.
- **Multimodal Visual Inspection for Media & Photos**:
  - Automatically analyze images attached to sections or in the Media Hub / Gallery to generate descriptive alt text, engaging photo captions, and image-accurate section summaries.
- **Universal Rollout Across Dashboard**:
  - Roll out across CMS Page Sections (Home, About, Contact, Privacy), Site Settings, Programmes, Events, Team Bios, and the Central Media Hub / Gallery.
- **Production Core FTP Alignment**:
  - Maintain exact upload tracking in `cpanel_changed_files.txt` for fast, zero-friction uploads via Core FTP to `/home/dzuefjel/dzuels_org`.

## 2. Users - Who is this for?

- **Prospective donors**: Seeking transparency, impact evidence, and seamless donation channels (including U.S. Givebutter diaspora giving).
- **Volunteers & Partners**: Submitting applications and exploring organizational partnerships.
- **Event participants & schools**: Attending ITD 2026, reading competitions, spelling bees, essay contests, and girls in tech initiatives.
- **Beneficiaries, parents & students**: Accessing educational literacy, community activities, and the Famoyinka Memorial Children's Library.
- **Foundation Staff (Admin Users)**:
  - **Super Admin**: Managing staff accounts, AI configuration and API keys, role permissions, global site settings, media library collections, deletions, and database exports.
  - **Editor / Content Manager**: Writing and refining page copy, programme descriptions, event agendas, and photo metadata with the AI writing assistant.
  - **Support / Viewer**: Viewing incoming contact messages, volunteer applications, and event registrations.

## 3. Features & Milestones

### Milestones 1–9 (Shipped, Features 1–31)
Features 1–31 fully completed, tested, and shipped in production.

### Milestone 10 (Active Target, Feature 32)
- 32. **Gemini AI content assistant, multimodal image analysis & universal admin input integration**:
  - Native `GeminiService` with Google GenAI REST API integration, British English foundation system prompt, and multimodal vision support.
  - Site Settings AI card with `gemini_api_key`, model selector, and live connection test.
  - Secure `POST /admin/ai/generate` endpoint passing page, section, sibling fields, and optional image context.
  - Reusable `<x-admin.ai-button>` with Alpine floating popover, loading state, suggestion preview, prompt tweak input, and Insert/Regenerate/Cancel controls.
  - Universal rollout across Page Sections CMS, Site Settings, Programmes, Events, Team, and Central Media Hub / Gallery photo metadata.
  - Core FTP upload manifest tracking in `cpanel_changed_files.txt`.

## 4. Data - What are we storing?

- **settings**: `gemini_api_key`, `gemini_model`, `site_logo`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, title, caption, alt_text, source_type, gallery_photo_id, timestamps.
- **page_sections**, **programmes**, **partners**, **users**, **events**, **event_sponsors**, **team_members**, **contact_messages**, **volunteer_applications**.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 12 on PHP 8.3 / 8.4, MariaDB 10.3+ / MySQL 8.0+.
- **AI Integration:** Google Gemini REST API via native Laravel `Http` client (zero heavy packages, multimodal vision).
- **Image Processing:** Native PHP GD (`imagewebp`, `imagecopyresampled`) with zero external package dependencies.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js (modals, gallery masonry, media picker, AI preview popover).
- **Deployment Tracking:** Root `cpanel_changed_files.txt` manifest tracking all modified files for Core FTP uploads to `/home/dzuefjel/dzuels_org`.

## 6. Deployment - cPanel & Production

- Hosted on Namecheap cPanel (`server161.web-hosting.com`) running MariaDB with PHP 8.3/8.4 under `/home/dzuefjel/dzuels_org`.
- Supports root document root execution via universal `index.php` and `.htaccess`.
- Database exportable via `php artisan db:export-sql` and root `dzuels_database.sql`.
- File synchronization facilitated by `cpanel_changed_files.txt` formatted for Core FTP.
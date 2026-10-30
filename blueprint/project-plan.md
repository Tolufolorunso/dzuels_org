# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), plus yearly flagship events such as Ijero Tech Day (ITD), an Essay Competition, a Reading Competition, a Spelling Bee, and Girls in Tech Day, alongside the Famoyinka Memorial Children's Library and WDADA Community Outreach.

### Milestones 1–13 (Completed & Shipped in Production, Features 1–40)
The foundation's platform has been successfully built, hardened, and deployed to live production (`https://dzf.dzuelsfoundation.org/`):
- **Milestones 1–10 (Shipped):** Core Web Platform, Brand Identity, MariaDB root SQL dump, Media Hub, Resilient GD image pipeline (strict <= 180 KB WebP), Givebutter U.S. donations, Universal Media Picker, and Gemini Multimodal AI Assistant.
- **Milestone 11 (Shipped):** Media Hub multi-criteria sorting and year filters, Tech Day separation from `/programmes`, curated 132-photo ingestion from `final/`, multi-year event showcase (2024, 2025, 2026), full 1,854-photo gallery archive ingestion, refreshed `dzuels_database.sql` (25 tables, 4,434 rows), and launch readiness check (0 errors).
- **Milestone 12 (Shipped):** Ijero Tech Day brand alignment, media gallery visibility toggles, anti-flash public gallery performance (`x-cloak`, fixed 4:3 aspect containers), dynamic event date status computation (`is_past`, `is_future`, `is_today`, `status_label`), Flutterwave removal from `/donate` with elevated GTBank 1-click copy and Givebutter U.S. giving, and balanced centered intake layout on `/get-involved`.
- **Milestone 13 (Shipped):** Giving channel correction (purged mock wire accounts, added "Why Donate" stewardship card), Tech Day event show page centered layout overhaul, and Pages CMS full content parity across all public pages with visibility toggles and section reordering.

### Milestone 14: Institutional Copy Corrections, Pages CMS Modular Overhaul, Tech Day Event Redesign & Deep Media Hub Metadata (Active Target, Features 41–44)
- **Institutional Fact Corrections, Copy Updates & Transformational Leadership (TRANSCOMM) Programme (Feature 41)**:
  - Correct historical facts: Dzuels Foundation did not equip public schools; it established a well-equipped computer laboratory and resourceful library at Dzuels Foundation in Ijero-Ekiti (over 500 students benefited), and trained 30 teachers.
  - Apply exact institutional copy replacements across seeders and public views:
    1. `"Holistic support for youth & community development"` -> `"Programs that Bridge the Opportunity Divide"`
    2. `"We combine direct education sponsorship with skills acquisition, advocacy, family stability, transformative leadership, and community health."` -> `"From digital hubs in Ijero-Ekiti to annual literacy contest, and much more in between, our causes are designed to uplift communities from the ground up."`
    3. `"Establishing modern computer laboratories and solar-powered learning facilities in public schools across Ijero-Ekiti, equipping over 165 students with practical coding and computer skills."` -> `"Establishing a well-equipped computer laboratory and a resourceful library at Dzuels Foundation, providing children with access to practical computer training, coding skills, reading materials, and a supportive learning environment. To date, over 500 students have benefited from these learning opportunities."`
    4. `"Whether through corporate sponsorship, volunteer mentoring, or financial donations, your partnership empowers the next generation."` -> `"Whether through individual, family or corporate sponsorship, volunteer mentorship, financial donations or legacy giving, your partnership empowers the next generation."`
  - Catalogue and seed new programme: `Transformational leadership (TRANSCOMM)`.
  - Update verified baseline metrics: 500+ students benefited, 30 teachers trained.
- **Pages CMS Modular Component Overhaul & Universal Inline Text Styling (Feature 42)**:
  - Architectural overhaul of Pages CMS editor (`/admin/pages/{page}`) based on real-world page archetypes (e.g. `/about` with Hero, Story with 3 stats & floating badge, 6 Approach Pillars, Famoyinka Library with 4 feature bullets, Governance section intros, and CTA banner).
  - Use `page_sections.meta_data` JSON for structured component-specific sub-items (Pillars array, Library feature bullets, Story stat callouts) with dedicated admin item repeaters/sub-forms.
  - Implement a lightweight, secure inline text styling syntax (`[accent]text[/accent]`, `[crimson]text[/crimson]`, `[mint]text[/mint]`, `[bold]text[/bold]`, `[italic]text[/italic]`, `[underline]text[/underline]`) powered by a safe Blade/PHP parser (`format_cms_text()`), equipped with 1-click styling helper buttons in the CMS editor.
  - Connect all public page Blade templates to parse and render styled CMS text.
- **Ijero Tech Day Event Show Page Redesign (Homepage UI Layout Alignment) (Feature 43)**:
  - Redesign `/events/ijero-tech-day-[year]` to mirror the visual structure, balance, and rhythm of the Homepage UI.
  - Full-bleed rich hero with dark burgundy/navy gradient, decorative pattern overlay, dynamic status pill ("Upcoming Flagship" vs "Past Festival"), and edition switcher pills (`2024`, `2025`, `2026`).
  - Floating 4-metric banner matching homepage style (`-mt-12 relative z-20 mx-auto max-w-6xl shadow-xl rounded-2xl bg-white border border-stone-200 p-6 grid grid-cols-2 lg:grid-cols-4 gap-6`).
  - 2-column balanced story & media section (narrative and focus areas on one side, curated photography with offset border accent on the other, mirroring the homepage Who We Are section).
  - 3-column milestone celebration card (mirrors DRNICER values / Impact Causes layout on homepage).
  - Full-width interactive agenda & schedule timeline with clear stage/track styling.
  - Full-bleed radial maroon CTA section ("Partner with ITD" / "Register for Tech Day") matching homepage CTA.
- **Central Media Hub Deep Metadata, Pagination, Sorting & Zoom Controls (Feature 44)**:
  - Database migration: Add `taken_at` (timestamp, nullable) and `color_space` (string, nullable, e.g. "sRGB", "Adobe RGB") to `media_items` table.
  - EXIF extractor in `MediaLibraryController` and upload pipeline: Extract exact capture timestamp (`taken_at`), color space, file type (JPEG, PNG, RAW/DNG, WebP), file size, and pixel dimensions ($W \times H$). Retroactively backfill existing media items from disk.
  - Display comprehensive metadata on photo cards and via an interactive inspector modal in `/admin/media`.
  - Configurable per-page pagination selector: `50`, `80`, `100`, `120`, `150` photos per page (default: `50`).
  - Advanced multi-column sorting: By capture date/time, upload date/time, file size, dimensions, and title.
  - Grid zoom / sizing controls: Interactive thumbnail resizing (compact, normal, large, extra-large) to inspect images clearly.

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

### Milestones 1–13 (Shipped, Features 1–40)
Features 1–40 fully completed, tested, and shipped in production.

### Milestone 14: Institutional Copy Corrections, Pages CMS Modular Overhaul, Tech Day Event Redesign & Deep Media Hub Metadata (Active Target, Features 41–44)
- 41. **Institutional fact corrections, living copy updates & Transformational Leadership (TRANSCOMM) programme**:
  - Correct historical records across seeders and public templates: establish that Dzuels Foundation did not equip public schools, but rather established a computer lab and library at Dzuels Foundation (500+ students benefited) and trained 30 teachers.
  - Apply the 4 exact institutional copy updates across public views and seeders.
  - Add and seed new programme: `Transformational leadership (TRANSCOMM)`.
  - Synchronize baseline metrics across `SettingSeeder`, `ImpactStatsService`, and public statistics.
- 42. **Pages CMS modular component architecture & universal inline text styling**:
  - Overhaul `/admin/pages/{page}` based on real page archetypes (e.g. `/about` with Hero, Story with 3 stats & floating badge, 6 Approach Pillars, Famoyinka Library with 4 feature bullets, Governance section intros, and CTA banner).
  - Store structured component sub-items in `page_sections.meta_data` JSON with dedicated admin repeater inputs.
  - Implement safe inline text styling tags (`[accent]text[/accent]`, `[crimson]text[/crimson]`, `[mint]text[/mint]`, `[bold]text[/bold]`, etc.) parsed via helper `format_cms_text()` with 1-click styling helper buttons in the CMS editor.
  - Update public page Blade views to parse and render styled CMS text.
- 43. **Ijero Tech Day event show page redesign (Homepage UI layout alignment)**:
  - Redesign `/events/ijero-tech-day-[year]` using the Homepage UI as design sample.
  - Full-bleed rich hero with dark burgundy/navy gradient, decorative pattern overlay, dynamic status pill ("Upcoming Flagship" vs "Past Festival"), and edition switcher pills (`2024`, `2025`, `2026`).
  - Floating 4-metric banner matching homepage style (`-mt-12 relative z-20 mx-auto max-w-6xl shadow-xl rounded-2xl bg-white border border-stone-200 p-6 grid grid-cols-2 lg:grid-cols-4 gap-6`).
  - 2-column balanced story & media section (narrative and focus areas on one side, curated photography with offset border accent on the other, mirroring the homepage Who We Are section).
  - 3-column milestone celebration card (mirrors DRNICER values / Impact Causes layout on homepage).
  - Full-width interactive agenda & schedule timeline with clear stage/track styling.
  - Full-bleed radial maroon CTA section ("Partner with ITD" / "Register for Tech Day") matching homepage CTA.
- 44. **Central Media Hub deep metadata, pagination, sorting & zoom controls**:
  - Add `taken_at` (timestamp, nullable) and `color_space` (string, nullable) to `media_items` table via migration.
  - Extract exact capture timestamp (`taken_at`), color space (sRGB, Adobe RGB), file type (JPEG, PNG, RAW/DNG, WebP), file size, and pixel dimensions ($W \times H$) from EXIF data.
  - Backfill existing media files with extracted EXIF metadata.
  - Display comprehensive photo metadata on photo cards and via an interactive inspector modal in `/admin/media`.
  - Provide configurable per-page pagination selector: `50`, `80`, `100`, `120`, `150` photos per page (default: `50`).
  - Multi-criteria sorting: capture date/time (newest/oldest), upload date/time, file size, dimensions, and title.
  - Interactive photo grid zoom controls (compact, normal, large, extra-large) to inspect images clearly.

## 4. Data - What are we storing?

- **settings**: `gemini_api_key`, `gemini_model`, `site_logo`, `google_site_verification`, `givebutter_url`, `contact_phone_1` through `contact_phone_5`, `office_hours`, `office_timezone`, impact numbers, banking, and registration.
- **gallery_albums / collections**: id, title, slug, kicker, description, cover_image, sort_order, is_active, timestamps.
- **gallery_photos**: id, gallery_album_id, image_path, caption, alt_text, year, sort_order, is_public, timestamps.
- **media_items**: id, filename, file_path, file_size, mime_type, width, height, taken_at, color_space, title, caption, alt_text, source_type, gallery_photo_id, is_public, timestamps.
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

# Build Plan

## MVP (Completed & Shipped)

- [x] 1. **Public layout and brand theme** - every page shares header, utility bar, footer, brand tokens, flash-free dark mode
- [x] 2. **Admin login and site settings** - staff sign in at `/admin` and edit impact numbers, contacts, social links, banking
- [x] 3. **Home page** - hero with Donate/Volunteer buttons, impact stats, who we are, vision/mission, DRNICER values, causes, partners
- [x] 4. **Team and partners** - staff manage trustees, management, advisors and partner logos
- [x] 5. **About page** - story, approach, leadership profiles with LinkedIn, partners and CTA
- [x] 6. **Programmes** - programmes catalog and detail views, including Ijero-Ekiti Digital Literacy
- [x] 7. **Events & Ijero Tech Day** - upcoming events, ITD celebration landing page, schedule highlights, sponsor tiering
- [x] 8. **Gallery** - 5 collections, 125 pre-optimized WebP photos, filter chips, masonry grid, lightbox
- [x] 9. **Donate page** - Flutterwave online giving, bank transfer details with copy buttons, trust FAQs
- [x] 10. **Volunteer and partner form** - Get Involved intake forms with spam protection and email notifications
- [x] 11. **Contact page** - contact form, hotlines, office details, and lazy-loaded map
- [x] 12. **News** - staff article publishing engine (dormant in UI)
- [x] 13. **Newsletter signup** - footer subscriber capture with CSV export (dormant in UI)
- [x] 14. **Site refinement & ITD 2026 overhaul** - mobile responsiveness pass (zero horizontal scroll) and modern admin UI
- [x] 15. **SEO foundation** - dynamic meta tags, Open Graph, Twitter cards, structured data, sitemap, robots.txt
- [x] 16. **Privacy policy and error pages** - readable privacy policy, branded 404 and 500 pages
- [x] 17. **Accessibility and launch readiness** - WCAG 2.2 AA contrast, visible focus, skip links, and `app:launch-check`

## Milestone 2: Polish, Dynamic CMS, Access Control & cPanel Deployment (Completed & Shipped)

- [x] 18. **Brand assets, ITD 2026 event overhaul and gallery optimization** - full favicon suite, ITD 2026 overhaul, server-rendered lightbox
- [x] 19. **Site-wide page content management, user access control and password recovery** - visual page content editor in `/admin`, multi-tier RBAC, password reset flows
- [x] 20. **MySQL database export and cPanel phpMyAdmin migration suite** - automated `php artisan db:export-sql` for 1-click phpMyAdmin import

## Milestone 3: Production Server Readiness & Launch Refinements (Completed & Shipped)

- [x] 21. **Production MariaDB configuration, root SQL dump, phone hotline update and hero imagery refinements** - MariaDB `.env` configuration, root `dzuels_database.sql`, phone hotline update to `+234 904 249 3883`, hero imagery overlays, Spelling Bee photo fixes

## Milestone 4: Dynamic Media Hub, Gallery Overhaul, Famoyinka Memorial Library & Global Admin Controls (Completed & Shipped)

- [x] 22. **Automated image optimization, global media picker and gallery collection/photo management** - native GD WebP conversion, media picker modal, gallery collection CRUD, standardized year labels
- [x] 23. **Famoyinka Memorial Children's Library programme integration and curated visual outreach** - dedicated public programme, dedicated gallery album, seeded authentic children's action photos
- [x] 24. **Site-wide multi-phone settings, dynamic contact CMS and dynamic partners strip** - up to 5 contact hotlines, 100% dynamic `/contact` page with timezone, dynamic partner logo strip on Home and About

## Milestone 5: Production Launch Hardening, Resilient Media Pipeline, Givebutter Diaspora Donations & 6 Impact Causes CMS (Completed & Shipped)

- [x] 25. **Resilient image optimization pipeline, upload fallback & gallery bulk uploader** - GD function scoping, direct-save fallback, multi-file bulk gallery uploader
- [x] 26. **Dynamic site logo in settings, Givebutter U.S. diaspora donations & 6 Impact Causes CMS** - dynamic site logo upload, Givebutter U.S. giving integration, authentic 6 Key Impact Causes on Home
- [x] 27. **Production release suite, updated MariaDB database export & launch verification** - refreshed `dzuels_database.sql`, updated cPanel upload manifest, verified production release

## Milestone 6: Production Refinements, Image Optimizer Hardening (<= 180 KB WebP), Dynamic Library Photos & Get Involved UI Overhaul (Completed & Shipped)

- [x] 28. **High-performance WebP image optimizer (<= 180 KB ceiling), full unclipped causes display, dynamic Famoyinka Library photos in CMS & Get Involved UI overhaul** - strict <= 180 KB WebP ceiling with original file deletion, retroactive optimization of legacy uploads, full unclipped causes graphic on Home, 4-photo dynamic Famoyinka Library CMS, Get Involved overhaul with tab-synchronized sidebar

## Milestone 7: Universal Gallery Media Picker & Centralized Cover Photo Optimizer (Completed & Shipped)

- [x] 29. **Universal gallery media picker and centralized cover photo optimization** - `<x-admin.media-picker>` integrated across Gallery Album, Programme, News, and Team forms; centralized cover photo optimization strictly enforcing <= 180 KB WebP format; dual-input controller and Form Request validators

## Milestone 8: Unified Central Media Hub, Global Asset Management & cPanel Public Path Hardening (Completed & Shipped)

- [x] 30. **Unified Central Media Hub, global asset management & cPanel public web path resolution** - bind `$app->usePublicPath(__DIR__)` in `public/index.php` and verify directory creation with correct permissions so uploaded media on cPanel saves directly to the active web folder and serves without 404s; create a top-level **Media Library** dashboard at `/admin/media` with an admin navbar tab; present a unified visual grid showcasing all foundation media (both gallery collection photos and general site/CMS assets) with multi-criteria search by photo name/caption/filename, date uploaded filters (date picker & quick ranges), sort controls (newest, oldest, A-Z), category tabs (*All Media*, *Gallery Collections*, *Site Assets*), file size badges (<= 180 KB WebP), dimensions, and copyable public URLs; add direct drag-and-drop WebP uploading into the central hub; and connect `<x-admin.media-picker>` across all CMS editors to the unified media catalog with the same search and date filters.

## Milestone 9: Dynamic Media Collections, Authentic Photo Integration & cPanel Root Alignment (Completed & Shipped)

- [x] 31. **Dynamic media collections/categories, authentic photo curation from picture archive, database SQL export & cPanel root architecture alignment** - unify Media Categories with `GalleryAlbum` so categories in the Central Media Hub (`/admin/media`) and `<x-admin.media-picker>` are dynamic, with a "+ New Collection" creation modal and optional public gallery toggle; curate, optimize (<= 180 KB WebP), and seed authentic photos from `picture/` across Essay Competition, Girls Day, ITD, Reading/Library, Spelling Bee, WDADA, and General Community; update CMS heroes, causes, and page sections with real imagery and catalog them in `MediaItem`; delete the temporary `picture/` folder; add root deployment configuration (`index.php`, `.htaccess`) supporting direct execution in `/home/dzuefjel/dzuels_org` without breaking local development; and export a fresh `dzuels_database.sql` for 1-click phpMyAdmin import.

## Milestone 10: Gemini AI Content Assistant & Multimodal Admin Suite (Completed & Shipped)

- [x] 32. **Gemini AI content assistant, multimodal image analysis & universal admin input integration** - create native `GeminiService` calling the Google GenAI REST API using Laravel's `Http` client (zero heavy packages); add `gemini_api_key` and model selector in Site Settings (`/admin/settings`) with an interactive "Test Connection" button, falling back to `.env`; implement secure endpoint `POST /admin/ai/generate` enforcing British English and injecting page, section, field label, and sibling context; build reusable `<x-admin.ai-button>` with an Alpine.js floating popover offering loading feedback, suggestion preview, prompt tweak input, and `Insert`/`Regenerate`/`Cancel` controls; support multimodal vision inspection for photos to generate accurate alt text, captions, and section copy; deploy across CMS Page Sections, Site Settings, Programmes, Events, Team, and Central Media Hub / Gallery; and track all modified files in `cpanel_changed_files.txt` for Core FTP deployment.

## Milestone 11: Events Architecture, Tech Day Flagship Showcase & Media Hub Overhaul

- [x] 33. **Media Hub sorting, year filtering & universal photo picker overhaul** - add multi-criteria sorting (by Year, Event/Collection, Newest/Oldest, and A–Z) with quick year filter chips (`2024`, `2025`, `2026`) in the Central Media Hub (`/admin/media`) and modal picker (`<x-admin.media-picker>`); ingest, optimize (<= 180 KB WebP), and catalog authentic photo archives from `gallery/` into structured collections (`reading`, `spelling-bee`, `general-community`).
- [x] 34. **Tech Day event architecture, multi-year showcase (2024, 2025, 2026) & programme separation** - separate Tech Day from `/programmes` to highlight it exclusively as the foundation's premier annual flagship event under `/events`; purge legacy techday imagery from storage and database; ingest, optimize (<= 180 KB WebP), and catalog authentic photos from `final/techday2024`, `final/techday2025`, and `final/techday2026`; build a multi-year Tech Day event showcase with edition switcher and authentic photo archives.
- [x] 35. **Media integration, production SQL database export & launch verification** - wire new authentic photography into event cards, page heroes, and impact highlights; run full launch checks and verify automated test suite; export fresh root `dzuels_database.sql` for 1-click phpMyAdmin cPanel import; and log all modified files in `cpanel_changed_files.txt` for Core FTP deployment.

## Milestone 12: Production UI & Experience Polish, Media Visibility Control & Brand Corrections

- [x] 36. **Ijero Tech Day brand correction, Media visibility controls & universal picker overhaul** - correct all naming from "Innovation and Tech Day" to "Ijero Tech Day" across templates, seeders, and routes; add public gallery visibility toggles and active "Used on Website" / "Displayed in Gallery" indicators in the Media Hub; and ensure the universal media picker loads and displays all library photos across all years and collections.
- [x] 37. **Public Gallery anti-flash performance & Tech Day event UI overhaul with dynamic date status & SEO** - eliminate image loading flashes on `/gallery` with `x-cloak`, fixed-aspect containers, and curated public photo display; dynamically calculate event status ("Upcoming Flagship" vs "Past Festival Highlights") from `event_date`; and elevate the Tech Day event show page with festival typography, edition switcher polish, and full SEO metadata.
- [x] 38. **Public page UI redesign for `/donate` (Flutterwave removal) and `/get-involved` (balanced, centered layout)** - remove Flutterwave from the public donate page while elevating GTBank transfer with 1-click copy and Givebutter U.S. giving; and redesign `/get-involved` into a balanced, centered layout with distinct pathway cards for volunteers, partners, and sponsors.

## Milestone 13: Giving Stewardship, Tech Day Event UI Centering & Pages CMS Parity

- [x] 39. **Giving channel correction (remove mock wires, add "Why Donate") & Tech Day event show page centered UI overhaul** - purge mock Domiciliary wire accounts and Swift code from `/donate`, replacing them with an authoritative "Why Donate to Our Cause" card highlighting 100% direct learner impact, zero administrative deductions, CAC legal standing, and tangible outcomes; and redesign the Tech Day event show page (`/events/ijero-tech-day-[year]`) from its left-flushed layout into a centered, balanced festival experience with centered hero metrics, edition switcher pills, countdown timer, and balanced narrative and milestones cards.
- [x] 40. **Pages CMS overhaul: dynamic public content parity, visibility toggles & section reordering** - add `is_active` to `page_sections` with admin toggle switches and move up/down sort controls; and synchronize CMS sections and seeders across `/about`, `/donate`, `/programmes`, `/contact`, and `/get-involved` with the exact living content the public sees, enabling dynamic editing, reordering, and section show/hide.

## Deferred Ideas (Not Scheduled as Features)

*(Preserved for future reference, completely removed from the active feature roadmap)*
- **Annual reports and downloads**: staff upload PDFs (annual reports, project updates) that appear on a public Reports page
- **Impact stories and testimonials**: short beneficiary and partner quotes (with written permission) shown on Home and programme pages
- **Donor receipts and recurring giving**: automatic thank-you emails with receipts, and monthly donation plans

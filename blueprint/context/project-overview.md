# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash e692595ab747a7b59349cbe92a01427053824280b4bf1593fba029bfaa4b104d -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application powering education support, digital literacy, and community welfare in Nigeria.

## Problem

The original Dzuels website failed to communicate the foundation's 10-year track record and real community impact. Pages lacked individual SEO, impact counters displayed unstyled zeroes, programmes lacked dedicated shareable URLs, the featured Ijero-Ekiti initiative linked to an external PDF, and donating was buried. In addition, viewport overflow causing horizontal scrolling hurt mobile usability, and the admin dashboard required a more professional, usable layout for foundation staff.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, and GTBank transfer details.
- **Volunteers (Students to Retirees):** Look for active causes and a simple application form.
- **Partners & Funders (NGOs, Tech Initiatives, SeedDev):** Require institutional credibility, governance details, and programme outcomes.
- **Beneficiaries & Communities (Ekiti State):** Seek access to digital skills, competition details, and literacy assistance.
- **Event Participants & Parents:** 300 students and parents attending ITD 2026 and celebrating 22 cohort graduates.
- **Foundation Staff (1-3 Admins):** Non-technical editors managing events, photo galleries, team profiles, and form submissions via `/admin`.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- 1 to 3 trusted administrators accessing `/admin` using role-based authentication; no public user accounts.
- Form submissions collect personal data (contact messages, volunteer applications, event registrations) handled in compliance with privacy guidelines.
- Online payments are offloaded completely to Flutterwave's hosted checkout; no payment card data is processed on-server.
- Non-requirements: public user registration, member logins, on-site card processing, events ticketing ecommerce, active news/newsletter publishing for now.

## Features

### MVP Features (in build order)

1. **Public layout and brand theme** - Shared header, utility bar, footer, Poppins and Great Vibes fonts, brand maroon/cream tokens, light mode default with flash-free dark mode toggle. *(Completed)*
2. **Admin login and site settings** - Secure `/admin` sign-in allowing staff to update impact numbers (165 ICT students, 591 literacy students, 15 schools supported), contact info, social links, and bank details without code. *(Completed)*
3. **Home page** - Impact stats strip, mission, vision, 7-tile DRNICER values band, our causes (`cause.png` / `cause.webp`), featured project card (`dzuelsCollage.webp`), upcoming event teaser, and partner logos. *(Completed)*
4. **Team and partners** - Management of Trustees, Management, Advisory Board with LinkedIn links, and partner organizations. *(Completed)*
5. **About page** - 10-year story, holistic child approach, Board of Trustees, Management team, Advisory Board, and partner showcase. *(Completed)*
6. **Programmes** - Admin-managed programme catalog with index (`/programmes`) and dedicated detail pages (`/programmes/{slug}`), starting with the Ijero-Ekiti Digital Infrastructure and Literacy project. *(Completed)*
7. **Events & Ijero Tech Day** - Dedicated upcoming event page for the Ijero Tech Day & 10th Anniversary Celebration, featuring schedule highlights, attendee registration form, and past highlights gallery. *(Completed)*
8. **Gallery** - 5 collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) powered by 125 pre-optimized WebP photos, with filter chips, lazy-loaded masonry grid, and keyboard lightbox. *(Completed)*
9. **Donate page** - 1-click Flutterwave giving, GTBank NGN transfer card with copy buttons, international wire details, in-kind guidelines, and FAQ. *(Completed)*
10. **Volunteer and partner form** - Get Involved page (`/get-involved`) with volunteer application and partner enquiry forms, honeypot spam protection, rate limiting, and staff notifications. *(Completed)*
11. **Contact page** - Contact form, phone hotlines, office address, and responsive Google Maps embed for Ijero-Ekiti headquarters. *(Completed)*
12. **News** - Staff news publishing and article views. *(Completed backend & models; dormant in UI)*
13. **Newsletter signup** - Footer subscriber form with admin export and unsubscribe flow. *(Completed backend & models; dormant in UI)*
14. **Site refinement & ITD 2026 overhaul** - Hide News and Newsletter across public views and admin tabs (dormant for now); full mobile/tablet responsiveness pass eliminating all horizontal scrolling across all screens (320px–1024px) using `max-w-full`, `%` units and fluid constraints; redesign `/admin` into a modern, professional, usable dashboard layout; update the live event to ITD 2026 (Oct 17, 2026 at AAoJ Memorial Learning Center: "Learning Today, Creating Tomorrow", 300 participants in 10 batches of 30, inside/garden rotation) with a reusable annual structure.
15. **SEO foundation** - Unique titles, meta descriptions, canonical URLs, Open Graph and Twitter tags, Organization and Breadcrumb structured data, auto-generated `sitemap.xml`, `robots.txt`, and silent 301 redirect from `/partner` to `/get-involved`.
16. **Privacy policy and error pages** - Readable privacy policy page linked from the footer and forms, and friendly 404 and 500 pages with links back to Home and Donate.
17. **Accessibility and launch readiness** - WCAG 2.2 AA contrast verified in light and dark mode, visible focus and keyboard navigation, responsive images, Lighthouse performance checks, live server checks, domain pointing and Google Search Console submission.

### Deferred Ideas (Non-Roadmap)

- **Annual reports and downloads**: PDF uploads on a public Reports page.
- **Impact stories and testimonials**: Beneficiary and partner quotes on Home and programmes.
- **Diaspora donations**: Direct USD/GBP donation channels.
- **Donor receipts and recurring giving**: Automated receipts and recurring gifts.

## Data model

### User
- `id` (bigint, PK)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (string: admin, editor)
- `timestamps`

### Setting
- `id` (bigint, PK)
- `key` (string, unique) - e.g. `stat_ict_students`, `stat_literacy_students`, `stat_schools_supported`, `contact_phone_1`, `contact_email`, `bank_account_number`
- `value` (text, nullable)
- `type` (string: text, integer, json, boolean)
- `timestamps`

### Programme
- `id` (bigint, PK)
- `title` (string)
- `slug` (string, unique)
- `summary` (text)
- `body` (longText)
- `cover_image` (string, nullable)
- `partner_name` (string, nullable)
- `gallery_album_id` (foreignId, nullable, references `gallery_albums.id`)
- `is_featured` (boolean, default false)
- `sort_order` (integer, default 0)
- `seo_title` (string, nullable)
- `seo_description` (text, nullable)
- `timestamps`

### Event
- `id` (bigint, PK)
- `title` (string) - e.g. "Ijero Tech-Day 2026 (ITD 2026)"
- `slug` (string, unique)
- `summary` (text)
- `description` (longText)
- `event_date` (dateTime, nullable) - Oct 17, 2026
- `venue` (string, nullable) - AAoJ Memorial Learning Center, Ijero-Ekiti
- `banner_image` (string, nullable)
- `schedule` (json, nullable) - batch rotation, inside practical, garden stations
- `is_registration_open` (boolean, default true)
- `sort_order` (integer, default 0)
- `seo_title` (string, nullable)
- `seo_description` (text, nullable)
- `timestamps`

### EventRegistration
- `id` (bigint, PK)
- `event_id` (foreignId, references `events.id`, onDelete cascade)
- `full_name` (string)
- `email` (string)
- `phone` (string)
- `attendee_type` (string, nullable) - student, teacher, guest, cohort_graduate
- `batch_number` (string, nullable)
- `notes` (text, nullable)
- `timestamps`

### Post *(Dormant)*
- `id` (bigint, PK)
- `title` (string)
- `slug` (string, unique)
- `excerpt` (text)
- `body` (longText)
- `cover_image` (string, nullable)
- `programme_id` (foreignId, nullable, references `programmes.id`)
- `status` (string: draft, published)
- `published_at` (dateTime, nullable)
- `seo_title` (string, nullable)
- `seo_description` (text, nullable)
- `timestamps`

### GalleryAlbum
- `id` (bigint, PK)
- `title` (string) - e.g. "ITD 2024", "ITD 2025", "Reading Competition", "Spelling Bee", "General"
- `slug` (string, unique)
- `kicker` (string, nullable)
- `description` (text, nullable)
- `cover_image` (string, nullable)
- `sort_order` (integer, default 0)
- `timestamps`

### GalleryPhoto
- `id` (bigint, PK)
- `gallery_album_id` (foreignId, references `gallery_albums.id`, onDelete cascade)
- `image_path` (string) - WebP image file path
- `caption` (string, nullable)
- `alt_text` (string, nullable)
- `sort_order` (integer, default 0)
- `timestamps`

### TeamMember
- `id` (bigint, PK)
- `name` (string)
- `role` (string)
- `group` (string: trustee, management, advisor)
- `field` (string, nullable)
- `bio` (text, nullable)
- `photo` (string, nullable)
- `linkedin_url` (string, nullable)
- `sort_order` (integer, default 0)
- `is_active` (boolean, default true)
- `timestamps`

### Partner
- `id` (bigint, PK)
- `name` (string)
- `logo` (string)
- `website_url` (string, nullable)
- `sort_order` (integer, default 0)
- `is_active` (boolean, default true)
- `timestamps`

### ContactMessage
- `id` (bigint, PK)
- `first_name` (string)
- `last_name` (string)
- `email` (string)
- `phone` (string, nullable)
- `subject` (string, nullable)
- `message` (text)
- `is_read` (boolean, default false)
- `timestamps`

### VolunteerApplication
- `id` (bigint, PK)
- `full_name` (string)
- `email` (string)
- `phone` (string)
- `age_band` (string: below_18, 18_to_55, above_55)
- `work_status` (string: employed, self_employed, unemployed, student)
- `skills` (text, nullable)
- `status` (string: pending, reviewed, contacted)
- `timestamps`

### NewsletterSubscriber *(Dormant)*
- `id` (bigint, PK)
- `email` (string, unique)
- `subscribed_at` (dateTime)
- `unsubscribed_at` (dateTime, nullable)
- `unsubscribe_token` (string, unique)
- `timestamps`

## Tech stack

- **Backend:** Laravel 13 on PHP 8.3+, Eloquent ORM.
- **Database:** SQLite for local development; MySQL/MariaDB in production.
- **Admin Panel:** Tailored administrative UI at `/admin` built with modern, accessible, professional Blade components and responsive layouts.
- **Frontend Templates:** Blade server-side templates with modular Blade components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens. Strict zero-horizontal-overflow rules with `max-w-full` and percentage constraints.
- **JavaScript:** Lightweight Alpine.js for interactive widgets (mobile menu, theme toggle, lightbox, copy buttons).
- **Typography:** Self-hosted Poppins (body & headings) and Great Vibes (accent script).
- **Media Optimization:** Pre-optimized WebP assets in `public/front/images/` and `public/front/images/gallery/optimized/`.
- **Payments:** Hosted external Flutterwave checkout link + GTBank manual transfer details.
- **Testing & Quality:** PHPUnit 12 (`php artisan test`) and Laravel Pint code style formatting.

## Monetization

Non-profit charity donations. The platform does not sell products or show advertisements. Giving flows through:
1. Flutterwave hosted e-payment checkout (`https://flutterwave.com/pay/j0byxgjs0nma`).
2. GTBank Plc. direct bank transfers (NGN Account `0450072850`).
3. In-kind and international giving coordination via email.

## UI/UX

- **Design Tokens:**
  - Maroon Heading: `#6A0406`
  - Crimson Primary: `#B4070A`
  - Warm Cream: `#F9DEC9`
  - Soft Mint Accent: `#56E39F`
  - Body Text: `#3B4456`
  - Dark Navy Text: `#242F40`
  - Page Background (Light default): `#FFFFFF`
  - Section Alternate: `#FFF5ED`
  - Dark Mode Background: `#140607`
  - Dark Mode Surface: `#241012`
  - Footer Background: `#2B0203`
- **Core Public Routes:**
  - `/` - Homepage (hero, impact stats, who we are, vision/mission, DRNICER values, causes, featured project, event teaser, programmes, partners, CTA).
  - `/about` - About Us (story, trustees, management, advisory board, partners).
  - `/programmes` - Programmes Index.
  - `/programmes/{slug}` - Programme Detail Page (e.g. `/programmes/ijero-digital-literacy`).
  - `/events` & `/events/{slug}` - ITD 2026 landing page with registration form.
  - `/gallery` - Photo Gallery (5 collections, filter chips, masonry grid, lightbox modal).
  - `/donate` - Giving page (Flutterwave button, GTBank card with copy buttons, trust badges).
  - `/get-involved` - Volunteer application form and partner enquiry.
  - `/contact` - Contact details, enquiry form, and Google Maps embed.
  - `/privacy-policy` - Privacy terms.
  - `/admin` - Professional administrative dashboard.

## Deployment

- **Target Hosting:** VPS (Hetzner / DigitalOcean) managed via Laravel Forge/Ploi, or cPanel with PHP 8.3+ support.
- **Web Root:** `public/` directory.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`, `php artisan migrate --force`, `php artisan storage:link`, `php artisan optimize`.
- **Domain:** `dzuelsfoundation.org` with Let's Encrypt SSL and HTTP-to-HTTPS redirect.
- **Background Tasks:** Laravel scheduler (cron) and database queue driver for form notification emails.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`.

## Open questions

*(None. All core requirements, impact statistics, leadership rosters, events, gallery assets, and brand tokens have been reconciled and verified.)*

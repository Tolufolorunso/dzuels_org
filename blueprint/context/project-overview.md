# Dzuels Educational Foundation - Project Overview

<!-- blueprint:source-hash ea485a0a35d4f2d9aa873a927ed59594b4954a3f8ba1f402877ab76800d414af -->

> Rebuild of dzuelsfoundation.org as a modern, high-trust, mobile-first Laravel web application with a Filament admin panel, powering education support, digital literacy, and community welfare in Nigeria.

## Problem

The current Dzuels website fails to communicate the foundation's 10-year track record and real community impact. Pages lack individual SEO, impact counters display unstyled zeroes, programmes lack dedicated shareable URLs, the featured Ijero-Ekiti initiative links to an external PDF, and donating is buried. Hardcoded content prevents non-technical staff from publishing updates or managing photo galleries.

## Users

- **Donors (Nigeria & Diaspora/USA):** Seek transparency, verified impact numbers, quick Flutterwave online giving, and GTBank transfer details.
- **Volunteers (Students to Retirees):** Look for active causes and a simple application form.
- **Partners & Funders (NGOs, Tech Initiatives, SeedDev):** Require institutional credibility, governance details, and programme outcomes.
- **Beneficiaries & Communities (Ekiti State):** Seek access to digital skills, competition details, and literacy assistance.
- **Foundation Staff (1-3 Admins):** Non-technical editors managing news posts, gallery albums, team profiles, and form submissions via `/admin`.

## Usage model

- Public, internet-facing web application optimized for mobile browsers on Nigerian networks.
- 1 to 3 trusted administrators accessing `/admin` using role-based authentication; no public user accounts.
- Form submissions collect personal data (contact messages, volunteer applications, newsletter emails) handled in compliance with privacy guidelines.
- Online payments are offloaded completely to Flutterwave's hosted checkout; no payment card data is processed on-server.
- Non-requirements: public user registration, member logins, on-site card processing, events ticketing ecommerce.

## Features

### MVP Features (in build order)

1. **Public layout and brand theme** - Shared header, utility bar, footer, Poppins and Great Vibes fonts, brand maroon/cream tokens, light mode default with flash-free dark mode toggle.
2. **Admin login and site settings** - Secure `/admin` sign-in allowing staff to update impact numbers (165 ICT students, 591 literacy students, 15 schools supported), contact info, social links, and bank details without code.
3. **Home page** - Impact stats strip, mission, vision, 7-tile DRNICER values band, our causes (`cause.png` / `cause.webp`), featured project card (`dzuelsCollage.webp`), upcoming event teaser, and partner logos.
4. **Team and partners** - Management of Trustees, Management, Advisory Board with LinkedIn links, and partner organizations.
5. **About page** - 10-year story, holistic child approach, Board of Trustees, Management team, Advisory Board, and partner showcase.
6. **Programmes** - Admin-managed programme catalog with index (`/programmes`) and dedicated detail pages (`/programmes/{slug}`), starting with the Ijero-Ekiti Digital Infrastructure and Literacy project.
7. **Events & Ijero Tech Day** - Dedicated upcoming event page for the Ijero Tech Day & 10th Anniversary Celebration, featuring schedule highlights, attendee registration form, and past highlights gallery.
8. **Gallery** - 5 collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) powered by 125 pre-optimized WebP photos, with filter chips, lazy-loaded masonry grid, and keyboard lightbox.
9. **Donate page** - 1-click Flutterwave giving, GTBank NGN transfer card with copy buttons, international wire details, in-kind guidelines, and FAQ.
10. **Volunteer and partner form** - Get Involved page (`/get-involved`) with volunteer application and partner enquiry forms, honeypot spam protection, rate limiting, and staff notifications.
11. **Contact page** - Contact form, phone hotlines, office address, and responsive Google Maps embed for Ijero-Ekiti headquarters.
12. **News** - Admin news publisher with cover photos, public article index, detail views with social share buttons, and homepage latest-posts integration.
13. **Newsletter signup** - Footer subscription form storing validated emails with admin CSV export and unsubscribe link.
14. **SEO foundation** - Unique titles, meta descriptions, Open Graph/Twitter preview cards, JSON-LD structured data (Organization, Breadcrumbs, Article), auto-generated `sitemap.xml`, and 301 redirects from old URLs.
15. **Privacy policy and error pages** - Transparent privacy policy page, accessible 404 and 500 error templates.
16. **Accessibility and performance pass** - WCAG 2.2 AA contrast compliance, visible focus states, reduced-motion preferences, responsive images, and Lighthouse 90+ mobile score.
17. **Content migration and launch** - Final content and asset migration, live server verification, SSL configuration, and Search Console submission.

### Post-MVP Features

18. **Annual reports and downloads** - Downloadable PDF reports and updates.
19. **Impact stories and testimonials** - Beneficiary quotes and partner stories.
20. **Diaspora donations** - Dedicated USD/GBP direct payment gateways.
21. **Donor receipts and recurring giving** - Automated tax receipts and recurring gift subscriptions.

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
- `title` (string) - e.g. "Ijero Tech Day & 10th Anniversary Celebration"
- `slug` (string, unique)
- `summary` (text)
- `description` (longText)
- `event_date` (dateTime, nullable)
- `venue` (string, nullable)
- `banner_image` (string, nullable)
- `schedule` (json, nullable) - array of time, title, speaker
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
- `attendee_type` (string, nullable)
- `notes` (text, nullable)
- `timestamps`

### Post
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
- `kicker` (string, nullable) - e.g. "Innovation and Tech Day", "Everyday impact"
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
- `role` (string) - e.g. "Trustee", "Founder", "Country Manager (USA)"
- `group` (string: trustee, management, advisor)
- `field` (string, nullable) - for advisors (e.g. "Education Services", "Medical Services")
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

### NewsletterSubscriber
- `id` (bigint, PK)
- `email` (string, unique)
- `subscribed_at` (dateTime)
- `unsubscribed_at` (dateTime, nullable)
- `timestamps`

## Tech stack

- **Backend:** Laravel 13 on PHP 8.3+, Eloquent ORM.
- **Database:** SQLite for local development; MySQL/MariaDB in production.
- **Admin Panel:** Filament admin panel mounted at `/admin`.
- **Frontend Templates:** Blade server-side templates with modular Blade components.
- **Styling:** Tailwind CSS v4 (`@tailwindcss/vite`) using CSS tokens.
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
  - `/events` & `/events/{slug}` - Ijero Tech Day & events landing page with registration form.
  - `/gallery` - Photo Gallery (5 collections, filter chips, masonry grid, lightbox modal).
  - `/donate` - Giving page (Flutterwave button, GTBank card with copy buttons, trust badges).
  - `/get-involved` - Volunteer application form and partner enquiry.
  - `/contact` - Contact details, enquiry form, and Google Maps embed.
  - `/privacy-policy` - Privacy terms.
  - `/admin` - Secure Filament administrative dashboard.

## Deployment

- **Target Hosting:** VPS (Hetzner / DigitalOcean) managed via Laravel Forge/Ploi, or cPanel with PHP 8.3+ support.
- **Web Root:** `public/` directory.
- **Build Sequence:** `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`, `php artisan migrate --force`, `php artisan storage:link`, `php artisan optimize`.
- **Domain:** `dzuelsfoundation.org` with Let's Encrypt SSL and HTTP-to-HTTPS redirect.
- **Background Tasks:** Laravel scheduler (cron) and database queue driver for form notification emails.
- **Environment:** `APP_ENV=production`, `APP_DEBUG=false`.

## Open questions

*(None. All core requirements, impact statistics, leadership rosters, events, gallery assets, and brand tokens have been reconciled and verified.)*

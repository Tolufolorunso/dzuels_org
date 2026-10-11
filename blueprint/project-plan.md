# Project Plan

Dzuels Educational Foundation website rebuild (https://dzuelsfoundation.org), built with Laravel.

## 1. Problem - What problem are we solving?

Dzuels Educational Foundation is a Nigerian charity, founded on 22 June 2015 and registered the same year. It provides education support and skills acquisition for indigent Nigerian children and youth, creates awareness of social vices and mitigating measures, and feeds poor families, among other social responsibilities. Its current work includes free literacy support and digital skills training for teachers and students in Ijero-Ekiti (Ekiti State, Southwest Nigeria), in partnership with the Information Technology Seed Development Initiative (SeedDev), plus yearly events such as Innovation and Tech Day (ITD), a Reading Competition and a Spelling Bee.

The current website works but does not do justice to the organisation:

- Every page has a generic title ("Dzuels Foundation") and there is no per-page SEO, so the foundation is hard to find in search.
- The three impact counters on the homepage show "0" in the page text and depend on animation; the real figures need to be confirmed and made editable.
- Donating is hidden on the Get Involved page instead of being one click from every page.
- Programmes (Innovation and Tech Day, Reading Competition, Spelling Bee, Ijero-Ekiti digital literacy) have no pages of their own, so they cannot rank or be shared.
- The featured project links to a Google Drive PDF instead of a real page.
- The advisory board is a plain text list with no photos, and the About page is thin for an organisation with a board, management team and advisors.
- Content is hard-coded, so staff cannot update the site themselves.
- Horizontal scrolling and layout overflow on mobile screens hurt user experience.
- The admin dashboard needs a clean, modern, professional layout that staff enjoy using.

Goal: a professional, fast, good-looking, SEO-friendly website that builds trust and turns visitors into donors, volunteers and partners, and that non-technical staff can keep up to date. Keep the existing brand (same colours, same fonts, same logo).

## 2. Users - Who is this for?

- **Prospective donors** in Nigeria and the diaspora (the foundation has a USA country manager), mostly on mobile phones and sometimes on slow connections.
- **Volunteers**: students, employed and self-employed people, retirees. The current volunteer form already asks age band (under 18, 18 to 55, above 55) and work status (employed, self-employed, unemployed, student).
- **Partners and funders**: companies, NGOs, schools and grant-makers who will look for credibility (registration, board, partners, programme results).
- **Beneficiary communities**: schools, parents and community leaders in Ekiti who want to know what the foundation offers and how to reach it.
- **Event participants and parents**: students attending ITD 2026, teachers, and parents of cohort graduates.
- **Search visitors** who find the site by searching for programmes or topics (for example free ICT training in Ijero-Ekiti, spelling bee Ekiti, education charity Nigeria).
- **Foundation staff (admin users)**: one to three non-technical editors who manage events, gallery photos, update team/partner lists, and read messages.

## 3. Features - What does the MVP need?

**Public site (all pages responsive, zero horizontal scrolling, light mode by default with a dark mode toggle):**

- Home page that explains the mission in seconds and pushes Donate, Volunteer and Partner.
- About page: story, approach, Board of Trustees, Management, Advisory Board, partners.
- Programmes: an index page plus one page per programme (Ijero-Ekiti Digital Infrastructure and Literacy, Innovation and Tech Day, Reading Competition, Spelling Bee).
- Gallery: five collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) with filter chips, lazy-loaded masonry grid and a keyboard-friendly lightbox, using pre-optimized WebP photos.
- Events: dedicated event page for **Ijero Tech-Day 2026 (ITD 2026)** on Saturday, October 17, 2026, at AAoJ Memorial Learning Center. Theme: *"Learning Today, Creating Tomorrow"*. Features objective, 300 participant / 10-batch schedule, activity stations (Inside AI workshop + Garden Image Booth / Quiz), and attendee registration form. Built with an extensible, reusable architecture for future annual editions.
- Donate page: online donation (Flutterwave link), bank transfer details with copy buttons, and other donations (contact to arrange pick-up or delivery).
- Get Involved page: volunteer application form, donate shortcut, partner enquiry.
- Contact page: form, address, phone numbers, email and a lazy-loaded Google map.
- Privacy policy page, custom 404 and 500 pages.

**Dormant modules (code and database ready, hidden from UI for now):**
- News (hidden from navigation, home, and admin).
- Newsletter signup (hidden from footer and admin).

**Admin panel (for staff):**

- Secure login.
- Professional, modern dashboard layout with dedicated sidebar navigation, clear KPI summary cards, and clean typography.
- Manage events (create, edit, toggle registration, view registrations).
- Manage gallery albums and photos (bulk upload, captions, ordering).
- Manage team members (trustees, management, advisors), partners, and programmes.
- Edit site settings without code: impact numbers, contact details, social links, donation links, bank details, registration number.
- Inbox for contact messages and volunteer applications.

**SEO & Redirects:**

- Unique title and meta description on every page.
- Canonical URLs, Open Graph and Twitter card tags.
- JSON-LD structured data: Organization sitewide, BreadcrumbList on inner pages.
- Auto-generated `sitemap.xml` and `robots.txt`.
- Silent 301 redirect from old `/partner` to `/get-involved` so legacy links and bookmarks never hit a 404.

**Not in the MVP:** online card payments handled on our own site, user accounts for the public, shop, member portal, active news or newsletter.

## 4. Data - What are we storing?

- **users** - admin and editor accounts (name, email, password, role).
- **settings** - key/value pairs for verified impact numbers (165 ICT students, 591 literacy students, 15 schools supported), contact details, social links, donation links, bank details, registration number.
- **programmes** - title, slug, short summary, body, cover image, optional partner name, sort order, featured flag.
- **events** - title, slug, summary, description, event date, venue, banner image, schedule details, registration enabled flag, sort order (supports ITD 2026 and future recurring annual editions).
- **event_registrations** - event_id, full_name, email, phone, school/category, batch, created_at.
- **gallery_albums** - title, slug, short description, kicker label, cover image, sort order.
- **gallery_photos** - album, image (stored with WebP conversions), caption, alt text, sort order.
- **team_members** - name, role, group (trustee, management, advisor), field, short bio, photo, LinkedIn URL, sort order.
- **partners** - name, logo, website URL, sort order.
- **contact_messages** - first name, last name, email, phone, subject, message, read flag, created_at.
- **volunteer_applications** - full name, email, phone, age band, work status, skills, created_at, status.
- **posts** & **newsletter_subscribers** - existing schemas preserved in database for future activation.

## 5. Tech - What stack are we using?

- **Backend:** Laravel 13 on PHP 8.3+, SQLite/MySQL.
- **Frontend:** Blade templates, Tailwind CSS v4, Alpine.js. Strict viewport containment: `max-w-full`, percentage and fluid units, no horizontal scrolling.
- **Admin:** Custom tailored Blade & Tailwind administrative UI at `/admin`, styled with professional SaaS dashboard aesthetics.
- **SEO:** Reusable `<x-seo>` component, auto-sitemap, JSON-LD schema.
- **Fonts:** Poppins and Great Vibes, self-hosted `.woff2`.
- **Code style & tests:** Laravel Pint, 100% test pass rate.

## 6. Deferred Ideas (Non-Roadmap)

The following items are preserved for potential future exploration but are not scheduled as active features:
- Annual reports and downloads (PDF uploads on public page)
- Impact stories and testimonials (beneficiary quotes)
- Diaspora donations (USD/GBP payment links)
- Donor receipts and recurring giving

## 7. UI/UX - What should it look and feel like?

### 7.1 Design principles
- **Warm, credible, human.** Real photos of learners and events lead the design; maroon and cream give a warm, established feel.
- **Zero horizontal scrolling.** Every screen size from 320px mobile to wide desktop must fit snugly without horizontal scrollbars.
- **Light by default.** Always opens in light mode; optional dark mode toggle with persistent state.
- **Professional Admin Dashboard.** Modern layout with high-contrast navigation, clean cards, readable data tables, and intuitive controls.

### 7.2 Colour tokens
- Brand colors preserved: Maroon `#6A0406`, Accent Crimson `#B4070A`, Soft Cream `#F9DEC9`, Mint highlight `#56E39F`.

## 8. Deployment - Where and how will it ship?

- VPS or modern cloud host running PHP 8.3+.
- Domain: `dzuelsfoundation.org`.
- HTTPS enabled with automated Let's Encrypt certificates.
- At launch: verify zero horizontal overflow on mobile devices, test all contact/volunteer/event registration forms, confirm silent redirects, and submit sitemap to Google Search Console.
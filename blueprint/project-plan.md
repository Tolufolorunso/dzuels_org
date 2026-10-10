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
- There is no news or stories section, so the site never changes and gives donors no fresh proof of work.
- The advisory board is a plain text list with no photos, and the About page is thin for an organisation with a board, management team and advisors.
- Content is hard-coded, so staff cannot update the site themselves.

Goal: a professional, fast, good-looking, SEO-friendly website that builds trust and turns visitors into donors, volunteers and partners, and that non-technical staff can keep up to date. Keep the existing brand (same colours, same fonts, same logo).

## 2. Users - Who is this for?

- **Prospective donors** in Nigeria and the diaspora (the foundation has a USA country manager), mostly on mobile phones and sometimes on slow connections.
- **Volunteers**: students, employed and self-employed people, retirees. The current volunteer form already asks age band (under 18, 18 to 55, above 55) and work status (employed, self-employed, unemployed, student).
- **Partners and funders**: companies, NGOs, schools and grant-makers who will look for credibility (registration, board, partners, programme results).
- **Beneficiary communities**: schools, parents and community leaders in Ekiti who want to know what the foundation offers and how to reach it.
- **Search visitors** who find the site by searching for programmes or topics (for example free ICT training in Ijero-Ekiti, spelling bee Ekiti, education charity Nigeria).
- **Foundation staff (admin users)**: one to three non-technical editors who post news, manage photos, update the team list and read messages.

## 3. Features - What does the MVP need?

**Public site (all pages responsive, light mode by default with a dark mode toggle):**

- Home page that explains the mission in seconds and pushes Donate, Volunteer and Partner.
- About page: story, approach, Board of Trustees, Management, Advisory Board, partners.
- Programmes: an index page plus one page per programme (Ijero-Ekiti Digital Infrastructure and Literacy, Innovation and Tech Day, Reading Competition, Spelling Bee). The Ijero-Ekiti project replaces the old Drive-PDF link.
- News: an index and article pages for updates, event recaps and stories, with share buttons.
- Gallery: five collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) with filter chips, lazy-loaded masonry grid and a keyboard-friendly lightbox, using pre-optimized WebP photos.
- Events: dedicated event page for the upcoming Innovation and Tech Day (ITD / 10th Anniversary), featuring event details, schedule highlights, past photo strip, and an attendee registration form.
- Donate page: online donation (Flutterwave link), bank transfer details with copy buttons, and other donations (contact to arrange pick-up or delivery).
- Get Involved page: volunteer application form, donate shortcut, partner enquiry.
- Contact page: form, address, phone numbers, email and a map.
- Newsletter signup in the footer.
- Privacy policy page, custom 404 page.

**Admin panel (for staff):**

- Secure login.
- Create, edit and publish news posts with a cover image.
- Manage gallery albums and photos (bulk upload, captions and alt text, ordering).
- Manage team members (trustees, management, advisors), partners and programmes.
- Edit site settings without code: impact numbers, contact details, social links, donation links, bank details, registration number.
- Inbox for contact messages, volunteer applications and newsletter subscribers, with CSV export.

**SEO (a core requirement, not an extra):**

- Unique title and meta description on every page, editable for news posts and programmes.
- Canonical URLs, Open Graph and Twitter card tags (share images look right on WhatsApp, Facebook and X).
- JSON-LD structured data: Organization (NGO) sitewide, BreadcrumbList on inner pages, Article on news posts.
- Auto-generated `sitemap.xml` and a `robots.txt`.
- 301 redirects from old URLs (the old `/partner` page moves to `/get-involved`; `/about`, `/contact` and `/gallery` keep their URLs).
- Clean semantic HTML (one `h1` per page, logical heading order), descriptive image alt text, fast loading images.
- Google Search Console and analytics set up at launch.

**Spam and safety on forms:** honeypot field, rate limiting, server-side validation, email notification to info@dzuelsfoundation.org.

**Not in the MVP:** online card payments handled on our own site, user accounts for the public, events calendar, multiple languages, shop, member portal.

## 4. Data - What are we storing?

- **users** - admin and editor accounts (name, email, password, role).
- **settings** - key/value pairs for verified impact numbers (165 Ekiti students in ICT training, 591 Ekiti students in literacy intervention, 15 schools supported - manually editable in admin dashboard or optionally synced from an external API), contact details, social links, donation links, bank details, registration number.
- **programmes** - title, slug, short summary, body, cover image, optional partner name, optional link to a gallery album, sort order, featured flag, SEO title and description.
- **events** - title, slug, summary, description, event date, venue, banner image, schedule details, registration enabled flag, sort order.
- **event_registrations** - event_id, full_name, email, phone, created_at.
- **posts** (news) - title, slug, excerpt, body, cover image, published_at, status (draft or published), optional programme link, SEO title and description.
- **gallery_albums** - title, slug, short description, kicker label, cover image, sort order.
- **gallery_photos** - album, image (stored with WebP conversions), caption, alt text, sort order.
- **team_members** - name, role, group (trustee, management, advisor), field (for advisors, such as Education Services or Medical Services), short bio, photo, LinkedIn URL, sort order.
- **partners** - name, logo, website URL, sort order.
- **contact_messages** - first name, last name, email, phone, subject, message, read flag, created_at.
- **volunteer_applications** - full name, email, phone, age band, work status, skills, created_at, status.
- **partner_enquiries** (or a type flag on contact messages) - organisation, contact person, email, message.
- **newsletter_subscribers** - email, subscribed_at, unsubscribed_at.

Personal data (names, emails, phone numbers, ages of volunteers) is stored only to respond to the person; the privacy policy page must say so. Photos of children need consent (see section 7.9).

## 5. Tech - What stack are we using?

- **Backend:** latest stable Laravel on PHP 8.3 or newer, MySQL (or MariaDB).
- **Frontend:** Blade templates and Blade components, Tailwind CSS (v4) through Vite, Alpine.js for small interactions (mobile menu, theme toggle, lightbox, tabs, copy buttons). No heavy JavaScript framework.
- **Admin:** Filament admin panel at `/admin` so staff can manage content without code.
- **Media:** Spatie Laravel Media Library (or equivalent) to generate WebP versions and responsive sizes from uploaded photos.
- **SEO:** a reusable `<x-seo>` Blade component for meta tags, canonical and Open Graph; `spatie/laravel-sitemap` for the sitemap; JSON-LD generated in Blade.
- **Forms and mail:** Laravel Form Requests for validation, Laravel Mail with SMTP (the foundation's mailbox or a transactional provider), notifications queued with the database queue driver.
- **Fonts:** Poppins and Great Vibes, self-hosted as `.woff2` (for example through the Fontsource npm packages) with `font-display: swap`. Self-hosting keeps pages fast for visitors on slow connections and avoids third-party requests.
- **Payments:** no payment code on our server. Online donations go to the existing Flutterwave hosted payment link.
- **Testing:** Pest feature tests for form submission, validation, spam protection, sitemap and redirects.
- **Code style:** Laravel Pint.

Tailwind dark mode is class-based (add the `dark` class on `<html>`), using `@custom-variant dark (&:where(.dark, .dark *));` in the Tailwind v4 stylesheet. All colours are defined as CSS variables (design tokens below) so the theme switches by changing variables only.

## 6. Monetize - How will it make money?

It is a charity site, so no ads and no selling. Success means donations, volunteer sign-ups and partnership enquiries. Donations go through the existing Flutterwave payment link and the GTBank account (details on the Donate page). Later options, not in the MVP: recurring donation plans, a diaspora donation option in USD or GBP, and donor receipts.

## 7. UI/UX - What should it look and feel like?

### 7.1 Design principles

- **Warm, credible, human.** Real photos of learners and events lead the design; maroon and cream give a warm, established feel rather than a generic charity template.
- **One clear action per section.** The two primary actions are Donate and Volunteer, visible in the header, the hero, mid-page and the footer.
- **Calm and spacious.** Generous white space, large type, rounded cards, subtle motion.
- **Mobile first.** Most visitors are on phones. Design at 375px first, then scale up.
- **Light by default.** The site always opens in light mode; dark mode is an optional choice for the visitor.

### 7.2 Typography

| Role | Font | Weight | Notes |
|---|---|---|---|
| Body and UI | Poppins | 400, 500 | 16px base on mobile, 17px from 1024px, line-height 1.7 |
| Headings | Poppins | 600, 700 | tight line-height 1.15, letter-spacing -0.01em |
| Eyebrow labels | Poppins | 600 | 12 to 13px, uppercase, letter-spacing 0.18em, colour primary |
| Accent script | Great Vibes | 400 | one or two words inside a heading only, at least 1.15 times the heading size, never in body text, navigation or buttons |

Type scale (fluid, using `clamp`):

- H1: `clamp(2.3rem, 5.5vw, 4.2rem)`, weight 700 (hero), inner-page H1 `clamp(2rem, 4.6vw, 3.4rem)`
- H2: `clamp(1.8rem, 3.6vw, 2.8rem)`, weight 700
- H3: 1.3rem, weight 600
- Lead paragraph: 1.15rem, muted text colour, max width 62 characters
- Small and captions: 0.85 to 0.95rem

Example of the accent script in use: "Addressing poverty through quality *education* and skills acquisition" with the word "education" in Great Vibes. Use it on at most one headline per section, and only on the homepage hero, section intros and the final call to action.

### 7.3 Colour tokens

Brand colours come from the existing site and must not change.

**Light mode (default):**

| Token | Hex | Used for |
|---|---|---|
| `--bg` | `#FFFFFF` | page background |
| `--bg-alt` | `#FFF5ED` | alternating sections, cards on white |
| `--surface` | `#FFFFFF` | cards, form panels |
| `--border` | `#ECE4DC` | card and input borders |
| `--heading` | `#6A0406` | all headings (brand dark maroon) |
| `--text` | `#3B4456` | body text |
| `--text-strong` | `#242F40` | strong text, nav links |
| `--text-muted` | `#6B7385` | secondary text, captions |
| `--primary` | `#B4070A` | links, eyebrow labels, key accents |
| `--primary-strong` | `#6A0406` | buttons, hover states |
| `--accent-cream` | `#F9DEC9` | soft highlights, outlines, badges |
| `--accent-mint` | `#56E39F` | check icons, small highlights (never text on white) |
| `--footer-bg` | `#2B0203` | footer |
| `--success` | `#1F8E11` | success messages |
| `--danger` | `#D92D20` | error messages (always with an icon and text) |

**Dark mode:**

| Token | Hex | Used for |
|---|---|---|
| `--bg` | `#140607` | page background |
| `--bg-alt` | `#1C0A0B` | alternating sections |
| `--surface` | `#241012` | cards, form panels |
| `--border` | `#4A2A2C` | borders |
| `--heading` | `#F9DEC9` | headings (brand cream) |
| `--text` | `#F1E6E0` | body text |
| `--text-strong` | `#FFFFFF` | strong text, nav links |
| `--text-muted` | `#C4ADAA` | secondary text |
| `--primary` | `#FF9B97` | links and eyebrow labels (lighter so they read on dark) |
| `--primary-strong` | `#B4070A` | buttons (white text), hover `#D1141A` |
| `--accent-cream` | `#F9DEC9` | highlights, outline buttons |
| `--accent-mint` | `#56E39F` | check icons, focus ring, highlights (text is allowed on dark) |
| `--footer-bg` | `#0E0405` | footer |

Rules:

- Buttons: filled buttons use `--primary-strong` with white text in light mode; in dark mode use `#B4070A` with white text. Outline buttons use `--heading` colour for text and border.
- Hero and page-hero banners use the same dark maroon gradient in both modes (`#6A0406` to `#B4070A` overlays on photos), so they never flip.
- Mint is a highlight colour only: never use it as text or a thin line on white.
- Check every text and background pair for WCAG AA contrast (4.5:1 for body text, 3:1 for large text and icons) in both themes before launch. Re-verify the hex values above with a contrast checker; adjust the dark `--primary` or `--text-muted` if any pair fails.
- Gradients: `linear-gradient(120deg, #6A0406, #B4070A)` for call-to-action banners; `linear-gradient(100deg, rgba(106,4,6,.94), rgba(106,4,6,.78) 45%, rgba(36,47,64,.45))` over hero photos.

### 7.4 Light and dark mode behaviour

- The site is **always light on first visit**, regardless of the visitor's device setting (explicit decision).
- A sun/moon toggle button sits in the header (also inside the mobile menu). It switches the `dark` class on `<html>` and saves the choice in `localStorage`.
- A tiny inline script in `<head>` applies the saved choice before the page paints, to avoid a flash of the wrong theme.
- Photos are not inverted. In dark mode, add a very light overlay (`rgba(0,0,0,.15)`) to bright photos if they glare.
- Logo: use `logo.png` in the header. If it does not read on dark backgrounds, place it on a white rounded badge in dark mode (as in the footer). Decide after seeing the real logo.
- Set `<meta name="color-scheme" content="light dark">` and `theme-color` to `#6A0406`.

### 7.5 Layout, spacing and shape

- Container width 1180px, 20px side gutters on mobile.
- Section padding `clamp(64px, 9vw, 112px)` top and bottom.
- Breakpoints: 600px, 860px, 1000px, 1280px.
- Radius: cards 18px, large banners and feature blocks 26 to 30px, buttons and chips fully round (999px), inputs 12px.
- Shadows: soft maroon-tinted, for example `0 18px 50px rgba(106,4,6,.12)` for raised cards; none in dark mode (use borders instead).
- Icons: one outline icon set (Lucide or Heroicons) as inline SVG, 1.8px stroke, replacing the old PNG icons (location, phone, mail, donate, package).

### 7.6 Core components

- **Utility bar** (above header, desktop only): phone, email, social icons (Instagram, X/Twitter, Facebook). Dark maroon background.
- **Header:** sticky, white (dark surface in dark mode), blurred background. Logo at 52px height on the left. Links: Home, About, Programmes, News, Gallery, Get Involved, Contact. On the right: theme toggle and a filled **Donate** button. Active link gets a cream pill background. On mobile, a hamburger opens a full-width slide-down menu with the Donate button at the bottom.
- **Buttons:** pill-shaped, 600 weight, 14px by 32px padding. Variants: filled, outline, light (white on maroon), ghost (outline on maroon). Hover lifts 2px and inverts colours.
- **Cards:** white surface, 1px border, 18px radius, hover lifts 6px.
- **Stat strip:** three large numbers in a white card overlapping the hero bottom edge, counting up when scrolled into view (respects reduced motion).
- **Section header:** eyebrow label, H2 (optionally with one script word), lead paragraph.
- **Forms:** visible labels above fields, 13px by 16px padding, maroon focus ring (mint ring in dark mode), inline error text with an icon, a success panel after submitting, a hidden honeypot field.
- **CTA banner:** maroon gradient, large headline, one primary and one light button, subtle cream circle decoration.
- **Footer:** deep maroon background in both modes. Four columns: logo (in white badge) and one-line mission; quick links; programmes; contact plus newsletter signup. Bottom line: "© 2026 Dzuels Educational Foundation. All rights reserved." plus Privacy Policy link.
- **Toasts and alerts:** success (green with check), error (red with icon), never colour alone.

### 7.7 Motion and imagery

- Scroll reveal: fade and rise 28px over 0.8s, once per element. Disabled under `prefers-reduced-motion`.
- Image hover: slow 5% zoom on programme and gallery cards.
- Use real photographs of the foundation's work only (no stock photos). Hero images at least 2000px wide, content images at least 1200px, all exported to WebP with a JPEG fallback only if needed.
- Every image has descriptive alt text; decorative images use empty alt text.
- Below the fold, images use `loading="lazy"` with explicit width and height to prevent layout shift.

### 7.8 Page by page

For every page: set a unique title (under 60 characters), meta description (under 160 characters), canonical URL, Open Graph image and breadcrumb schema.

**Home (`/`)**

SEO title: "Dzuels Educational Foundation | Education and Skills for Nigerian Children". Description: a one-sentence summary of the mission and the Ijero-Ekiti work.

Sections in order:

1. **Hero.** Full-width photo (`home_1.webp` or an ITD photo) with the maroon gradient. Eyebrow "Dzuels Educational Foundation". H1 "Addressing poverty through quality education and skills acquisition" with "education" in Great Vibes script. One supporting sentence. Buttons: Donate (light) and Become a volunteer (ghost).
2. **Impact stats** overlapping the hero:
   - **165** Ekiti students in ICT training
   - **591** Ekiti students in literacy intervention
   - **15** Schools supported
   Values managed in the admin dashboard (manually editable with support for external API feed).
3. **Who we are.** `about_1.webp` in a frame with a cream outline offset behind it, short intro text, link to About.
4. **Vision and Mission.** Two cards with the `vision.png` and `mission.png` icons (or matching line icons). Vision: "To alleviate poverty through quality education". Mission: "To make quality education accessible to every child within reach".
5. **Values band** (dark maroon background): the seven values that spell DRNICER: Discipline, Responsibility, No-Excuse, Integrity, Compassion, Excellence, Respect. A large cream letter above each word, seven tiles on desktop, two columns on mobile.
6. **Our causes.** Heading "We provide education support and skills acquisition for indigent Nigerian children and youth", `cause.png` / `cause.webp`, and a two-column check-list: paying school fees, providing educational materials, equipping non-profit schools, mentoring beneficiaries, transformational leadership training, feeding families through widows, enabling health services, and creating awareness of social vices.
7. **Featured project.** Large card with `dzuelsCollage.webp` (`dzuelsCollage.jpg`). Tag "Featured project". Title "Digital Infrastructure and Literacy in Ijero-Ekiti". Text: free literacy support and digital skills for teachers and students in the Ijero-Ekiti community, in partnership with SeedDev. Button to the programme page; secondary link to download the project update PDF.
8. **Programmes teaser.** Four tall photo cards (Innovation and Tech Day, Reading Competition, Spelling Bee, Ijero-Ekiti Digital Literacy) linking to each programme page.
9. **Upcoming Event banner / highlight:** Teaser card for the upcoming Ijero Tech Day / 10th Anniversary with date, venue badge, and registration CTA.
10. **Latest news.** Three most recent posts; the section is hidden until at least one post is published.
11. **Partners strip.** Greyscale logos that turn to colour on hover (`intinternational.png`, `helpmeetaneed.png`, `SAA.jpg`, `ascend.jpg`).
12. **Final CTA.** "You too can get involved. Volunteer, donate or partner with us to join our mission." Buttons: Get involved and Donate.

**About (`/about`)**

SEO title: "About Dzuels Educational Foundation | Our Story, Board and Team".

1. Page hero (maroon): eyebrow "About us", H1 "Transforming lives page by page".
2. **Our story.** Founded 22 June 2015 and registered the same year; purpose and activities as in the existing "More About Dzuels Foundation" text; the integrated approach to the total well-being of the child (school fees, educational materials, equipping non-profit schools, mentoring, leadership training, feeding families through widows, health services). Photo `about_1.webp`. Button to Contact.
3. **Vision, mission and values** (reuse the homepage components).
4. **Board of Trustees.** Round portrait cards with cream ring: Anna John-Yakubu, Kate Ojiogo, Olawale Dada, Bolaji Agbelusi (Founder). Photos `anna.jpg`, `kate.jpg`, `olawale.jpg`, `bolaji.jpg`.
5. **Management.** Mrs. Fadekemi Humphries, Country Manager (USA); Ms. Michelle Sarumi, Country Manager (Nigeria). Photos `mgt_fadekemi.webp`, `mgt_michelle.png`.
6. **Advisory Board.** Grid of cards: name, field (small uppercase label in primary colour), profession, LinkedIn link. People: Mrs. Fola Oloyede (Education Services), Solape Adesuyi (Corporate Legal Services), Mrs. Toyin Idowu (Education Services), Dr. Eyitayo Abejide (Medical Services), Dr. Olukemi Alegi (Medical Services), Mr. Odunayo Ayegbusi (Project Management, Energy Solutions), Ms. Kofoworola Tubi (Architectural Services), Mr. David Jayeola (ICT Services), Mr. Tolu Talabi (ICT Services). Optional: ask each advisor for a photo.
7. **Partners.** Logo row with the partners shown on the current About page, including ASCEND Education Foundation (link to https://ascendedufoundation.org/).
8. CTA banner.

**Programmes index (`/programmes`)** and **programme page (`/programmes/{slug}`)**

SEO title pattern: "{Programme name} | Dzuels Educational Foundation".

- Index: hero, intro paragraph, grid of four large programme cards (photo, title, two-line summary, "Learn more").
- Detail page: hero with cover photo, short summary, "What we do", "Who it is for", "Partners" (for example SeedDev on the Ijero-Ekiti project), photo strip from the linked gallery album, related news, CTA (Donate and Volunteer).
- Copy for each programme must come from the foundation; the current site only has a short description of the Ijero-Ekiti project and the gallery blurbs. Reuse these blurbs as starting text.

**Events (`/events` and `/events/{slug}`) - e.g. Ijero Tech Day**

SEO title: "Ijero Tech Day & 10th Anniversary | Dzuels Educational Foundation".

1. **Hero & Banner:** Optimized event banner (`techday.webp`), event title "Tech Day & 10th Anniversary Celebration", subtext "Celebrating 10 years of purpose & impact".
2. **Overview:** "Join us as we celebrate 10 years of impact, innovation, and transformation at the Dzuels Foundation Tech-Day in Ijero-Ekiti. A day of learning, networking, and celebrating purpose-driven tech."
3. **Event Schedule & Highlights:** Schedule overview, speakers, lab workshops, student demonstrations, and award sessions.
4. **Registration Form:** Attendee registration card (Full Name, Email Address, Phone Number, Attendee Category), with submissions saved to the admin inbox with email notifications.
5. **Past Event Gallery:** Curated photo grid from past ITD events.

**News (`/news` and `/news/{slug}`)**

SEO title: "News and Updates | Dzuels Educational Foundation".

- Index: hero, optional category chips, responsive grid of post cards (cover image, date, title, excerpt), pagination.
- Article: cover image, title, date, reading time, body (headings, images, quotes), share buttons (WhatsApp, X, Facebook, copy link), related posts, CTA. Article JSON-LD and Open Graph image come from the cover image.
- Launch with at least three posts (for example recaps of ITD 2025, the Reading Competition and the Spelling Bee).

**Gallery (`/gallery`)**

SEO title: "Photo Gallery | Dzuels Educational Foundation".

1. Hero: eyebrow "Dzuels Foundation Gallery", H1 "Real moments from the work we do." with two buttons (View highlights, Browse collections).
2. Filter chips: All, ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General.
3. One section per collection with its kicker label, title and blurb:
   - Innovation and Tech Day, ITD 2024: talks, hands-on demonstrations, award moments, group portraits.
   - Tech-Day 2025, ITD 2025: welcome moments, computer lab sessions, learner engagement, recognition, team celebrations.
   - Literacy and curiosity, Reading Competition: confident readers, prize-giving, school visits.
   - Confidence in words, Spelling Bee: classroom focus, written rounds, panel moments, winners.
   - Everyday impact, General: classes, mentoring, library support, community visits, practical learning, outdoor activities.
4. Masonry grid (about 25 photos per collection today), lazy-loaded thumbnails, click opens a lightbox with previous, next, close, counter, keyboard arrows and Escape.
5. Closing CTA "See the work in motion" with a Get Involved button.

**Donate (`/donate`)**

SEO title: "Donate | Support Education for Nigerian Children". 

1. Hero: H1 "Give a child the gift of education".
2. **Online donation:** one large button to the Flutterwave link (https://flutterwave.com/pay/j0byxgjs0nma), text "Secure e-payment by Flutterwave".
3. **Bank transfer:** a card with account name Dzuels Educational Foundation, bank GTBank Plc., NGN account number 0450072850, each with a copy-to-clipboard button. A note: "Cash or cheque payable to Dzuels Educational Foundation."
4. **Other donations:** books, equipment and supplies, contact info@dzuelsfoundation.org to arrange pick-up or delivery.
5. **Where your gift goes:** the causes list (school fees, learning materials, equipping schools, mentoring, leadership training, feeding families, health services). Do not invent percentages or amounts.
6. **Trust block:** registered 2015, board of trustees link, partners strip.
7. Short FAQ (can I get a receipt, can I donate from abroad, can I give in kind). Answers must come from the foundation.

**Get Involved (`/get-involved`)**

SEO title: "Volunteer and Partner With Us | Dzuels Educational Foundation".

1. Hero: eyebrow "Partner with us", H1 "Get involved today".
2. Three option cards: Volunteer, Donate, Partner, each with an icon and a short line.
3. **Volunteer form:** Full name, Email, Phone, Age (Under 18 years, 18 to 55 years, Above 55 years), Work status (Employed, Self-employed, Unemployed, Student), Specific skills (textarea), submit button. Success panel after sending.
4. **Partner enquiry:** short form or email button (organisation, contact person, email, how you would like to partner).
5. Shortcut to the Donate page.

**Contact (`/contact`)**

SEO title: "Contact Dzuels Educational Foundation | Ijero-Ekiti, Nigeria".

1. Hero: eyebrow "Get in touch", H1 "We are always available to speak with you."
2. Two-column layout: contact form (First name, Last name, Email, Phone, Subject, Message) and three info cards: Visit us (12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria), Call us (+234 707 664 6780, +234 904 249 3883; tap to call), Email us (info@dzuelsfoundation.org).
3. Map: Google Maps embed for 12 Doherty St, Ijero, Ekiti (lazy-loaded iframe, or a click-to-load placeholder to protect page speed).
4. CTA banner.

**Privacy Policy (`/privacy-policy`):** plain, readable page covering what is collected (contact, volunteer and newsletter forms, analytics), why, who can see it, retention, how to ask for deletion, and contact email.

**404:** friendly page with large cream "404", a short message, and buttons back to Home and Donate.

### 7.9 Assets and Image Optimization

All production assets are placed and pre-optimized in `public/front/images/`:

- **Brand:** `logo.png` (header), `logo.jpg` (footer), and favicon assets.
- **Hero & Site Imagery (WebP Optimized):**
  - `home_1.webp` (67KB, converted from `home_1.jpg`)
  - `about_1.webp` (58KB, converted from `about_1.jpg`)
  - `cause.png` and `cause.webp` (52KB, converted from `cause.jpg`)
  - `dzuelsCollage.webp` (406KB, converted from 1MB `dzuelsCollage.jpg`)
  - `techday.webp` (993KB, converted from 20.8MB `techday.png`)
  - `mgt_fadekemi.webp` (592KB, converted from 2MB `mgt_fadekemi.jpeg`)
  - `mgt_michelle.png`, `anna.jpg`, `kate.jpg`, `olawale.jpg`, `bolaji.jpg`
- **Gallery Collections (125 Pre-optimized WebP Photos):**
  Located in `public/front/images/gallery/optimized/`, with all images under 200KB:
  - `itd-2024-01.webp` to `itd-2024-25.webp` (25 photos)
  - `itd-2025-01.webp` to `itd-2025-25.webp` (25 photos)
  - `reading-01.webp` to `reading-25.webp` (25 photos)
  - `spelling-01.webp` to `spelling-25.webp` (25 photos)
  - `general-01.webp` to `general-25.webp` (25 photos)
  *(Note: The root `gallery/` folder containing raw multi-megabyte camera photos is safely deprecated and can be removed).*
- **Design Mockup Prompts:**
  Comprehensive Stitch and AI design prompts are maintained in `blueprint/design-prompt.md`.
- **Consent:** confirm the foundation has permission to publish photos of children. Do not publish children's full names without consent, and remove any photo on request.
- **Share image:** a 1200 by 630 px Open Graph image (logo plus a strong photo), created for the site.

### 7.10 Accessibility and performance targets

- WCAG 2.2 AA in both themes: contrast, visible focus (maroon outline in light mode, mint in dark mode), keyboard access to the menu, lightbox and forms, a skip-to-content link, labelled form fields, error text linked to fields, 44px minimum touch targets.
- Respect `prefers-reduced-motion` for animations and counters.
- Lighthouse 90 or higher in Performance, Accessibility, Best Practices and SEO on mobile for Home, About and Gallery.
- Largest Contentful Paint under 2.5s on a typical 4G connection; homepage under about 1.5MB total weight.
- Use responsive images (`srcset`), WebP, lazy loading, preloaded primary fonts and caching headers for static assets.

## 8. Deployment - Where and how will it ship?

Hosting is not decided yet and needs the foundation's confirmation. Recommended options:

- **A small VPS** (for example DigitalOcean or Hetzner) managed with Laravel Forge or Ploi, or set up manually with Nginx, PHP-FPM and MySQL. Best performance and control.
- **cPanel or shared hosting** is acceptable if it supports PHP 8.3 or newer. The web root must point to Laravel's `public` directory.

Keep the domain `dzuelsfoundation.org` and point DNS to the new host at launch. Use HTTPS (free Let's Encrypt certificate) and redirect HTTP to HTTPS and `www` to the main domain (or the reverse, picked once and kept).

Deploy steps: pull the code, `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`, `php artisan migrate --force`, `php artisan storage:link`, then `php artisan optimize`. Set up the scheduler (cron) and a queue worker, or run queued mail on the `sync` driver if the host does not allow workers.

Production needs: environment file with `APP_ENV=production` and `APP_DEBUG=false`, a mail account for form notifications, daily database and uploads backups, error monitoring (email or a free service), and uptime monitoring.

At launch: import all content and photos, test every form, confirm the 301 redirects (`/partner` to `/get-involved`), submit `sitemap.xml` in Google Search Console, verify Open Graph previews on WhatsApp, and run Lighthouse on the live site.

## 9. Usage model and constraints (optional)

- Public, internet-facing website for a registered non-profit charity.
- Mostly mobile visitors in Nigeria plus diaspora visitors (including the USA); some on slow or limited connections.
- Low to moderate traffic, with spikes around events and campaigns.
- One to three trusted staff users log into the admin panel; there are no public user accounts and no multi-tenancy.
- The site stores personal data from forms (names, emails, phone numbers, volunteer age band and work status), so it needs a clear privacy policy and secure handling.
- Online payments are handled entirely by Flutterwave's hosted page and bank transfer; no card data touches the site.
- Explicit non-requirements: multiple languages, an events calendar, a shop, member accounts, on-site card processing.
- Unknown and to be confirmed by the foundation: real impact numbers, registration number, hosting choice and budget, who owns the domain and hosting accounts, and who will write news posts.
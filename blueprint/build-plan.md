# Build Plan

## MVP

- [x] 1. **Public layout and brand theme** - every page shares the header (logo, nav, Donate button, theme toggle), utility bar and footer, using Poppins and Great Vibes and the brand colour tokens, light mode by default with a saved dark mode toggle that never flashes the wrong theme
- [x] 2. **Admin login and site settings** - staff sign in at `/admin` and edit impact numbers (165 ICT students, 591 literacy students, 15 schools supported - with manual entry or API feed), contact details, social links, Flutterwave link, bank details and registration number without touching code
- [x] 3. **Home page** - hero with Donate and Volunteer buttons, impact stats, who we are, vision and mission, DRNICER values band, our causes (`cause.png` / `cause.webp`), featured project (`dzuelsCollage.webp`), upcoming event teaser, partners strip and final call to action
- [x] 4. **Team and partners** - staff can add, edit, order and hide trustees, management, advisors and partner logos in the admin panel
- [x] 5. **About page** - story, approach, Board of Trustees, Management, Advisory Board with LinkedIn links, partners and call to action, powered by the team and partner records
- [x] 6. **Programmes** - staff manage programmes in the admin panel; visitors see a programmes index and a page for each programme, including the Ijero-Ekiti Digital Infrastructure and Literacy project that replaces the old Drive PDF link
- [x] 7. **Events & Ijero Tech Day** - staff manage events in the admin panel; visitors see upcoming events starting with the Ijero Tech Day & 10th Anniversary Celebration, featuring schedule highlights, attendee registration form (saved to admin inbox) and event highlights
- [x] 8. **Gallery** - staff manage albums; visitors browse five collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) powered by the 125 pre-optimized WebP photos in `public/front/images/gallery/optimized/`, with filter chips, lazy-loaded masonry grid and a keyboard-friendly lightbox
- [x] 9. **Donate page** - one-click online donation through the Flutterwave link, bank transfer details with copy buttons, other-donations contact, where-your-gift-goes section and a short FAQ
- [x] 10. **Volunteer and partner form** - Get Involved page where visitors submit a volunteer application (name, email, phone, age band, work status, skills) or a partner enquiry, saved for staff, emailed to info@dzuelsfoundation.org, protected by a honeypot and rate limiting
- [x] 11. **Contact page** - contact form (first name, last name, email, phone, subject, message), address, phone numbers, email and a lazy-loaded Google map, with messages saved in an admin inbox and emailed to staff
- [x] 12. **News** - staff write and publish posts with a cover image in the admin panel; visitors see a news index, article pages with share buttons (WhatsApp, X, Facebook, copy link) and related posts, and the homepage shows the latest three
- [x] 13. **Newsletter signup** - footer form that stores subscribers, prevents duplicates, lets staff export the list as CSV and gives subscribers an unsubscribe link
- [x] 14. **Site refinement & ITD 2026 overhaul** - hide News and Newsletter across public views and admin tabs (dormant for now); full mobile/tablet responsiveness pass eliminating all horizontal scrolling across all screens (320px–1024px) using `max-w-full`, `%` units and fluid constraints; redesign `/admin` into a modern, professional, usable dashboard layout; update the live event to ITD 2026 (Oct 17, 2026 at AAoJ Memorial Learning Center: "Learning Today, Creating Tomorrow", 300 participants in 10 batches of 30, inside/garden rotation) with a reusable annual structure
- [x] 15. **SEO foundation** - every page has a unique title, meta description, canonical URL, Open Graph and Twitter tags, Organization and Breadcrumb structured data, auto-generated `sitemap.xml`, `robots.txt`, and silent 301 redirect from `/partner` to `/get-involved`
- [ ] 16. **Privacy policy and error pages** - readable privacy policy page linked from the footer and forms, and friendly 404 and 500 pages with links back to Home and Donate
- [ ] 17. **Accessibility and launch readiness** - WCAG 2.2 AA contrast verified in light and dark mode, visible focus and keyboard navigation, responsive images, Lighthouse performance checks, live server checks, domain pointing and Google Search Console submission

## Deferred Ideas (Not Scheduled as Features)

*(Preserved for future reference, completely removed from the feature roadmap)*
- **Annual reports and downloads**: staff upload PDFs (annual reports, project updates) that appear on a public Reports page
- **Impact stories and testimonials**: short beneficiary and partner quotes (with written permission) shown on Home and programme pages
- **Diaspora donations**: donation option for USD or GBP givers alongside the Naira options
- **Donor receipts and recurring giving**: automatic thank-you emails with receipts, and monthly donation plans
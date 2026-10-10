# Build Plan

## MVP

- [x] 1. **Public layout and brand theme** - every page shares the header (logo, nav, Donate button, theme toggle), utility bar and footer, using Poppins and Great Vibes and the brand colour tokens, light mode by default with a saved dark mode toggle that never flashes the wrong theme
- [ ] 2. **Admin login and site settings** - staff sign in at `/admin` and edit impact numbers (165 ICT students, 591 literacy students, 15 schools supported - with manual entry or API feed), contact details, social links, Flutterwave link, bank details and registration number without touching code
- [ ] 3. **Home page** - hero with Donate and Volunteer buttons, impact stats, who we are, vision and mission, DRNICER values band, our causes (`cause.png` / `cause.webp`), featured project (`dzuelsCollage.webp`), upcoming event teaser, partners strip and final call to action
- [ ] 4. **Team and partners** - staff can add, edit, order and hide trustees, management, advisors and partner logos in the admin panel
- [ ] 5. **About page** - story, approach, Board of Trustees, Management, Advisory Board with LinkedIn links, partners and call to action, powered by the team and partner records
- [ ] 6. **Programmes** - staff manage programmes in the admin panel; visitors see a programmes index and a page for each programme, including the Ijero-Ekiti Digital Infrastructure and Literacy project that replaces the old Drive PDF link
- [ ] 7. **Events & Ijero Tech Day** - staff manage events in the admin panel; visitors see upcoming events starting with the Ijero Tech Day & 10th Anniversary Celebration, featuring schedule highlights, attendee registration form (saved to admin inbox) and event highlights
- [ ] 8. **Gallery** - staff manage albums; visitors browse five collections (ITD 2024, ITD 2025, Reading Competition, Spelling Bee, General) powered by the 125 pre-optimized WebP photos in `public/front/images/gallery/optimized/`, with filter chips, lazy-loaded masonry grid and a keyboard-friendly lightbox
- [ ] 9. **Donate page** - one-click online donation through the Flutterwave link, bank transfer details with copy buttons, other-donations contact, where-your-gift-goes section and a short FAQ
- [ ] 10. **Volunteer and partner form** - Get Involved page where visitors submit a volunteer application (name, email, phone, age band, work status, skills) or a partner enquiry, saved for staff, emailed to info@dzuelsfoundation.org, protected by a honeypot and rate limiting
- [ ] 11. **Contact page** - contact form (first name, last name, email, phone, subject, message), address, phone numbers, email and a lazy-loaded Google map, with messages saved in an admin inbox and emailed to staff
- [ ] 12. **News** - staff write and publish posts with a cover image in the admin panel; visitors see a news index, article pages with share buttons (WhatsApp, X, Facebook, copy link) and related posts, and the homepage shows the latest three
- [ ] 13. **Newsletter signup** - footer form that stores subscribers, prevents duplicates, lets staff export the list as CSV and gives subscribers an unsubscribe link
- [ ] 14. **SEO foundation** - every page has a unique title, meta description, canonical URL, Open Graph and Twitter tags, plus Organization, Breadcrumb and Article structured data, an auto-generated `sitemap.xml`, `robots.txt` and 301 redirects from old URLs (`/partner` to `/get-involved`)
- [ ] 15. **Privacy policy and error pages** - readable privacy policy page linked from the footer and every form, and friendly 404 and 500 pages with links back to Home and Donate
- [ ] 16. **Accessibility and performance pass** - WCAG 2.2 AA contrast checked in light and dark mode, visible focus and full keyboard use, reduced-motion support, responsive images and preloaded fonts, and Lighthouse 90 or higher on mobile for Home, About, Events and Gallery
- [ ] 17. **Content migration and launch** - all current text, team photos, partner logos and gallery photos loaded from optimized assets, forms and redirects tested on the live server, HTTPS and mail working, backups and uptime monitoring on, domain pointed to the new site, sitemap submitted to Google Search Console

## Post-MVP

- [ ] 18. **Annual reports and downloads** - staff upload PDFs (annual reports, project updates) that appear on a public Reports page
- [ ] 19. **Impact stories and testimonials** - short beneficiary and partner quotes (with written permission) shown on Home and programme pages
- [ ] 20. **Diaspora donations** - donation option for USD or GBP givers alongside the Naira options
- [ ] 21. **Donor receipts and recurring giving** - automatic thank-you emails with receipts, and monthly donation plans
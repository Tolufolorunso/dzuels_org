# Feature: Famoyinka Memorial Children's Library programme integration and curated visual outreach

**From build-plan:** feature 23
**Build attempt:** 1

**Branch:** feature/famoyinka-memorial-childrens-library-programme-integration-and-curated-visual-outreach

## Goal

Integrate the **Famoyinka Memorial Children's Library** as a permanent, first-class pillar of Dzuels Educational Foundation across the web application by:
1. Adding a dedicated public programme page at `/programmes/famoyinka-memorial-library` detailing its free book loans, weekend study circles, creative arts and crafts, transformational leadership games, and educational excursions in Ijero-Ekiti.
2. Establishing a dedicated "Famoyinka Memorial Library" gallery collection with authentic action photography of children (book borrowing, creative crafts, outdoor leadership challenges, Ikogosi excursion, and reading circles).
3. Pruning repetitive historical gallery photos (specifically duplicate Reading Competition entries) to eliminate visual clutter and ensure every displayed photo is distinct.
4. Prominently showcasing the Famoyinka Memorial Children's Library on both the Home (`/`) and About (`/about`) pages with rich storytelling, action imagery, and direct cross-links to its dedicated programme page and gallery collection.
5. Enabling staff to manage the library programme and its gallery photos seamlessly in `/admin` with the reusable media picker.

## In scope

- **Programme Database & Seeder:**
  - Seed `famoyinka-memorial-library` in `database/seeders/ProgrammeSeeder.php` with rich educational narrative, values-based mission, and authentic cover photo.
  - Set `is_featured = true` and `sort_order = 1` so it is prominently displayed in programme catalogs.
- **Dedicated Public Programme Page (`/programmes/famoyinka-memorial-library`):**
  - Verify detail view renders with crisp typography, breadcrumbs, partner attribution, and a "Explore Library Photo Gallery" action linking directly to the library gallery collection.
  - Add SEO metadata for search engines and social sharing.
- **Dedicated Gallery Collection & Action Photography Seeding:**
  - Create `Famoyinka Memorial Library` album in `database/seeders/GallerySeeder.php` (slug: `famoyinka-library`, kicker: `Children's Literacy & Creative Hub`, sort order: 1).
  - Prepare and optimize authentic action images to modern WebP format:
    - `famoyinka-01.webp`: Student selecting fiction books from children's library shelf (from `library_1.jpg`).
    - `famoyinka-02.webp`: Transformational leadership games & outdoor teamwork (from `running.jpg`).
    - `famoyinka-03.webp`: Creative arts & crafts workshop with colorful paintings.
    - `famoyinka-04.webp`: Ikogosi Warm Springs educational excursion with students exploring nature.
    - `famoyinka-05.webp`: Attentive storybook reading circle (from `classroom.jpg`).
    - `famoyinka-06.webp`: Student study circle & digital literacy lab (from `cohort_1.jpg`).
    - `famoyinka-07.webp`: Smiling pupils engaged in community peer learning (from `what.jpg`).
    - `famoyinka-08.webp`: Foundation education and values curriculum (from `STEAM.jpg`).
  - Seed each photo with descriptive captions, alt text, and standardized year labels (`2024`/`2025`).
- **Pruning Repetitive Gallery Photos:**
  - Update `database/seeders/GallerySeeder.php` to prune redundant duplicate Reading Competition photos down to the top distinct entries.
  - Delete duplicate photo records from `gallery_photos` for clean database state.
- **Prominent Showcases on Home and About Pages:**
  - Update `resources/views/home.blade.php`: Add a dedicated library highlight feature banner with action photography, core pillars (Book Loans, Creative Crafts, Leadership Games, Excursions), and direct link to `/programmes/famoyinka-memorial-library`.
  - Update `resources/views/about.blade.php`: Feature the Famoyinka Memorial Children's Library within the 10-year holistic child development story and community pillars, highlighting the sanctuary in Ijero-Ekiti.
- **Admin Media Library Integration:**
  - Ensure the new library photos appear immediately in `<x-admin.media-picker>` across all admin forms.
- **Deployment Manifest:**
  - Log all modified/created files in `cpanel_changed_files.txt`.

## Out of scope

- Multi-phone site settings and contact page CMS overhaul (reserved for Feature 24).
- Dynamic partners strip on Home and About pages (reserved for Feature 24).
- Physical library catalog borrowing database or book reservation software (the website provides public outreach and educational documentation).

## Build loop

- `workflow.stepReview: "feature"` (one review packet after all steps are complete).
- `workflow.checkpointCommits: "disabled"`.
- Run tests (`php artisan test`) and lint checks (`php vendor/bin/pint --test`) after implementation.

## Build steps

- [x] 1. **Optimize action photography assets & prepare WebP files** - convert `library_1.jpg`, `running.jpg`, `classroom.jpg`, `cohort_1.jpg`, `what.jpg`, `STEAM.jpg`, excursion, and crafts photos into optimized WebP assets under `public/front/images/gallery/optimized/` (`famoyinka-01.webp` through `famoyinka-08.webp`) using `ImageOptimizerService`.
  *Done when:* All 8 `famoyinka-*.webp` images exist in `public/front/images/gallery/optimized/`, are valid WebP files, and are under 150KB each.

- [x] 2. **Seed Famoyinka Memorial Library programme** - update `database/seeders/ProgrammeSeeder.php` to include `famoyinka-memorial-library` with rich descriptive narrative, key pillars, authentic cover image (`famoyinka-01.webp`), and `sort_order = 1`; run seeder.
  *Done when:* `Programme::where('slug', 'famoyinka-memorial-library')->exists()` is true and `/programmes/famoyinka-memorial-library` returns HTTP 200 with the library details.

- [x] 3. **Seed dedicated gallery collection and prune duplicate photos** - update `database/seeders/GallerySeeder.php` to add the `Famoyinka Memorial Library` album (`famoyinka-library`) with its 8 action photos; prune duplicate photos from `reading-competition` to eliminate repetition; re-seed gallery data.
  *Done when:* `GalleryAlbum::where('slug', 'famoyinka-library')->first()->photos()->count()` equals 8, and the Reading Competition collection has no duplicate photos.

- [x] 4. **Enhance public programme detail view with gallery cross-link** - update `resources/views/programmes/show.blade.php` to include a dedicated "Explore Photo Gallery" action and preview linking directly to `/gallery?album=famoyinka-library` when viewing the Famoyinka Memorial Children's Library.
  *Done when:* Visiting `/programmes/famoyinka-memorial-library` displays a prominent link to the library gallery collection that filters to the library album.

- [x] 5. **Prominently feature the library on Home page** - update `resources/views/home.blade.php` to introduce a dedicated Famoyinka Memorial Children's Library highlight section or enhanced featured banner with action imagery, 4 core pillars (Free Book Loans, Creative Crafts & Art, Leadership Games, Educational Excursions), and a call to action leading to the programme page.
  *Done when:* Visiting `/` displays the Famoyinka Memorial Children's Library highlight with responsive styling and link to `/programmes/famoyinka-memorial-library`.

- [x] 6. **Feature the library on About page** - update `resources/views/about.blade.php` to incorporate the Famoyinka Memorial Children's Library into the foundation's 10-year holistic child development narrative and community impact section with a link to the programme.
  *Done when:* Visiting `/about` showcases the library as a permanent community sanctuary in Ijero-Ekiti with direct cross-linking.

- [x] 7. **Automated tests and deployment tracking** - write feature tests in `tests/Feature/ProgrammePublicTest.php` and `tests/Feature/GalleryPublicTest.php` verifying the library programme, gallery album, public responses, and cross-links; run `php artisan test` and `php vendor/bin/pint --test`; record all modified files in `cpanel_changed_files.txt`.
  *Done when:* Full test suite passes 100%, Pint passes with 0 violations, and `cpanel_changed_files.txt` lists all updated and new files.

## Files / areas

- `database/seeders/ProgrammeSeeder.php` - Famoyinka Memorial Library programme seeding
- `database/seeders/GallerySeeder.php` - Famoyinka Library album, action photos seeding, and duplicate pruning
- `public/front/images/gallery/optimized/famoyinka-*.webp` - action photography assets
- `resources/views/programmes/show.blade.php` - programme detail view with gallery cross-link
- `resources/views/home.blade.php` - Home page library highlight section
- `resources/views/about.blade.php` - About page community sanctuary showcase
- `tests/Feature/ProgrammePublicTest.php` - feature tests for library programme page
- `tests/Feature/GalleryPublicTest.php` - feature tests for library gallery album
- `cpanel_changed_files.txt` - deployment file tracking

## Data / contracts

- `programmes` record:
  - `title`: "Famoyinka Memorial Children's Library"
  - `slug`: "famoyinka-memorial-library"
  - `summary`: "A vibrant children's reading sanctuary and community creative hub in Ijero-Ekiti offering free book loans, weekend study circles, creative arts and crafts, transformational leadership games, and educational excursions."
  - `cover_image`: "front/images/gallery/optimized/famoyinka-01.webp"
  - `is_featured`: true, `is_active`: true, `sort_order`: 1
- `gallery_albums` record:
  - `title`: "Famoyinka Memorial Library"
  - `slug`: "famoyinka-library"
  - `kicker`: "Children's Literacy & Creative Hub"
  - `cover_image`: "front/images/gallery/optimized/famoyinka-01.webp"
  - `sort_order`: 1, `is_active`: true
- `gallery_photos`:
  - 8 action photos mapped to `famoyinka-library` with standardized captions, alt text, and year labels (`2024`/`2025`).

## Testing

- Feature tests: `php artisan test --filter=ProgrammePublicTest`
- Feature tests: `php artisan test --filter=GalleryPublicTest`
- Full suite: `php artisan test`
- Code formatting: `php vendor/bin/pint --test`

## Notes for the AI

- Use `ImageOptimizerService` to produce clean `.webp` assets with aspect ratio preserved.
- When pruning duplicate reading photos, ensure that existing references don't break.
- Ensure all new UI additions maintain WCAG 2.2 AA contrast with the foundation's maroon and cream palette, supporting both light and dark mode.
- Maintain `cpanel_changed_files.txt` accurately.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10510,"specSha256":"53f7b999f3104cdb52b651b2bcd8686488045965c0bed47b6fb4370148ba0ae2","branch":"refs/heads/feature/famoyinka-memorial-childrens-library-programme-integration-and-curated-visual-outreach","head":"d164173aad5f57f840d5db19ae8d90089e883fb9","baseRef":"refs/heads/main","baseCommit":"d164173aad5f57f840d5db19ae8d90089e883fb9","sourceTree":"f269c460eadd2774cd0792dc633c8db8f2528e6a","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Famoyinka Memorial Children's Library Programme Detail**:
   - Visit `/programmes/famoyinka-memorial-library`.
   - Verify the page header displays "Famoyinka Memorial Children's Library", featured initiative tag, and rich summary.
   - Confirm the authentic cover photo (`famoyinka-01.webp`) renders crisply.
   - Scroll through the initiative narrative detailing free book lending, weekend study circles, creative arts/crafts, transformational leadership games, and Ikogosi excursions.
   - Verify the "Explore Photo Gallery" preview card displays with action photography and links to `/gallery?album=famoyinka-library`.
   - Verify the sidebar features the "Visual Outreach" card linking to the photo gallery.

2. **Dedicated Gallery Album & Curated Photo Archive**:
   - Visit `/gallery?album=famoyinka-library` or `/gallery/famoyinka-library`.
   - Verify the album header displays "Famoyinka Memorial Library" and kicker "Children's Literacy & Creative Hub".
   - Confirm all 8 authentic action photos render in the masonry grid with year labels (`2024`/`2025`).
   - Click each photo to test the lightbox modal, verifying descriptive captions and clean navigation.
   - Filter to the "Reading Competition" album and verify that duplicate photos have been pruned down to 8 distinct shots.

3. **Home Page Feature Showcase**:
   - Visit `/`.
   - Observe Section 7: "Famoyinka Memorial Children's Library".
   - Verify the 4 core pillars: Free Book Loans, Creative Arts & Crafts, Leadership & Teamwork, and Educational Excursions.
   - Verify the 4-photo action mosaic and click "Explore Library Programme" to confirm seamless routing to `/programmes/famoyinka-memorial-library`.

4. **About Page Heritage Story & Sanctuary Showcase**:
   - Visit `/about`.
   - In Section 2 ("Our Heritage & Purpose"), verify the library is mentioned as a cornerstone of the foundation's 10-year mission with direct link.
   - Scroll to Section 3b ("Permanent Community Hub") and verify the dedicated sanctuary showcase highlighting the Ijero-Ekiti center with action photo and cross-links.

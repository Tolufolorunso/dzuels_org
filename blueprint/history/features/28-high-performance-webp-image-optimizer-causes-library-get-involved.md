# Feature: High-performance WebP image optimizer (<= 180 KB ceiling), full unclipped causes display, dynamic Famoyinka Library photos in CMS & Get Involved UI overhaul

**From build-plan:** feature 28
**Build attempt:** 1
**Branch:** feature/28-image-optimizer-causes-library-get-involved
**Status:** verified

## Goal

Harden the media pipeline so every image uploaded anywhere in the admin dashboard is automatically converted to WebP format, constrained to a strict maximum size ceiling of 180 KB (184,320 bytes) while retaining visual sharpness, and deletes uncompressed originals; retroactively optimize legacy uploaded images; display the official 6 Impact Causes graphic unclipped on the homepage; empower staff to customize all 4 action photos and labels in the Famoyinka Memorial Children's Library section from the admin dashboard; and overhaul `/get-involved` with tab-synchronized sidebar content, Tailwind v4 linear gradients, and responsive sticky alignment.

## In scope

- **Image Optimizer Hardening:**
  - Update `ImageOptimizerService` to enforce a hard file size ceiling of 180 KB (184,320 bytes).
  - Iterative quality downscaling (82 down to 40 in 5-unit steps) and dimension downscaling (10% increments) until the file size is strictly <= 180 KB.
  - Strictly encode output as `.webp`.
  - Automatically unlink/delete original source files after successful optimization.
  - Unify all dashboard image upload routes through `ImageOptimizerService` (`GalleryAlbumController`, `MediaController`, `PageController`, `ProgrammeController`, `TeamMemberController`, `PartnerController`).
  - Retroactively convert existing images in `public/uploads/` (including 3 legacy `.jpg` files) to WebP <= 180 KB, delete the `.jpg` originals, and update database records.
- **Homepage 6 Impact Causes Graphic:**
  - In `resources/views/home.blade.php`, remove fixed height cropping and `object-cover`.
  - Use `w-full h-auto max-h-[580px] object-contain` with natural aspect ratio so the entire graphic is 100% visible edge to edge.
  - Remove/reposition the bottom overlay pill badge that obscures text on the graphic.
- **Dynamic Famoyinka Memorial Children's Library Section in CMS:**
  - Add `famoyinka_library` section in `database/seeders/PageSectionSeeder.php` with `meta_data['photos']` array storing 4 photos and labels.
  - Update `PageController::updateSection` validation and storage for `meta_data.photos`.
  - Add a dedicated 4-photo editor in `resources/views/admin/pages/edit.blade.php` under `/admin/pages/home` with `<x-admin.media-picker>` and caption inputs.
  - Render the 4 photos and labels dynamically in `resources/views/home.blade.php` with reliable fallbacks to default WebP assets.
- **Get Involved Page Layout & Tab-Synchronized Sidebar Overhaul:**
  - In `resources/views/get-involved/index.blade.php`, dynamically adapt the right column content based on `activeTab` (`volunteer` vs `partner`).
  - Provide dedicated Corporate & Institutional Partnership cards when `activeTab === 'partner'` (Partnership pathways, CSR metrics, co-branding, hotline and email).
  - Convert Tailwind v3 `bg-gradient-to-br` to Tailwind v4 `bg-linear-to-br`.
  - Apply sticky positioning (`lg:sticky lg:top-28 lg:self-start`) and balanced spacing to prevent layout overflow.
- **Verification & Testing:**
  - Automated unit and feature tests covering <= 180 KB WebP optimization, original deletion, CMS library photo persistence, and public page rendering.

## Out of scope

- External cloud storage (e.g. AWS S3); uploads remain local in `public/uploads` for cPanel compatibility.
- Third-party image processing SaaS or external binaries (strictly native PHP GD).
- Changes to other public pages or payment gateway integrations.

## Build loop

- `workflow.stepReview: "feature"`: Small implementation steps proceed continuously, with one comprehensive review packet presented upon completion.
- `workflow.checkpointCommits: "disabled"`: Clean commits created at feature completion.
- Execute Verify command (`php artisan test` and `php vendor/bin/pint --test`) across all steps.

## Build steps

- [x] 1. **Image Optimizer Hardening (<= 180 KB WebP Ceiling, Original Cleanup & Retroactive Conversion)**: Harden `ImageOptimizerService` with iterative quality and dimension reduction to strictly enforce `<= 180 KB` output size, WebP format, and original file deletion. Connect all admin controllers (`GalleryAlbumController`, `MediaController`, `PageController`, `ProgrammeController`, `TeamMemberController`). Retro-optimize legacy uploads in `public/uploads/` to WebP <= 180 KB, delete `.jpg` originals, and update database paths. Add unit tests in `tests/Unit/ImageOptimizerServiceTest.php`.
  - *Done when:* An uploaded image (regardless of initial size/format) outputs as `.webp` strictly <= 184,320 bytes, the source file is deleted, existing uploads are <= 180 KB WebP, and `php artisan test --filter=ImageOptimizerServiceTest` passes.

- [x] 2. **Homepage 6 Impact Causes Banner Display (Unclipped & Natural Aspect Ratio)**: Update `resources/views/home.blade.php` in the 6 Impact Causes section to replace `object-cover` and fixed height classes with `object-contain` and natural aspect ratio (`w-full h-auto max-h-[580px]`). Remove or reposition the obscuring bottom overlay badge so the entire graphic is 100% visible and unclipped.
  - *Done when:* Viewing the homepage shows the complete `cause.webp` banner without any cropped edges or obscured text across mobile, tablet, and desktop screens.

- [x] 3. **Dynamic 4-Photo Famoyinka Memorial Children's Library CMS**: Add `famoyinka_library` section in `database/seeders/PageSectionSeeder.php` with 4 default photos and labels. Update `PageController` to validate and save `meta_data.photos`. Add a 4-photo media picker and caption editor in `resources/views/admin/pages/edit.blade.php` under `/admin/pages/home`. Update `resources/views/home.blade.php` to dynamically render the 4 photos and labels with fallbacks.
  - *Done when:* An admin can edit all 4 library photos and labels at `/admin/pages/home`, changes persist and immediately render on the homepage, and fallbacks render gracefully if unset.

- [x] 4. **Get Involved Page Layout & Tab-Synchronized Responsive Sidebar Overhaul**: Overhaul `resources/views/get-involved/index.blade.php` so the right sidebar dynamically switches between volunteer information (when `activeTab === 'volunteer'`) and corporate partnership details/metrics/hotline (when `activeTab === 'partner'`). Convert Tailwind v3 gradients to Tailwind v4 `bg-linear-to-br`, and apply sticky alignment (`lg:sticky lg:top-28 lg:self-start`).
  - *Done when:* Switching between "Volunteer" and "Partner With Us" tabs displays tailored, contextually matching sidebar content that fits seamlessly without layout overflow or awkward whitespace on all screen sizes.

- [x] 5. **Full Regression Verification & Code Style Formatting**: Run the complete test suite (`php artisan test`) including new and existing feature tests, verify `php vendor/bin/pint --test`, and update `cpanel_changed_files.txt`.
  - *Done when:* All 259+ tests pass, code styling passes Pint with zero errors, and all changed files are recorded in `cpanel_changed_files.txt`.

## Files / areas

- `app/Services/ImageOptimizerService.php` - Iterative <= 180 KB WebP optimization, GD functions, source file deletion.
- `app/Http/Controllers/Admin/GalleryAlbumController.php` - Bulk and single gallery upload via optimizer.
- `app/Http/Controllers/Admin/MediaController.php` - Media picker upload via optimizer.
- `app/Http/Controllers/Admin/PageController.php` - Page section image upload & `famoyinka_library` photo metadata validation.
- `app/Http/Controllers/Admin/ProgrammeController.php` - Programme cover image upload via optimizer.
- `app/Http/Controllers/Admin/TeamMemberController.php` - Team photo upload via optimizer.
- `database/seeders/PageSectionSeeder.php` - Seed `famoyinka_library` page section with 4 photos and captions.
- `resources/views/home.blade.php` - Unclipped causes image display and dynamic 4-photo library mosaic with fallbacks.
- `resources/views/admin/pages/edit.blade.php` - 4-photo media picker and label editor for Famoyinka library section on `/admin/pages/home`.
- `resources/views/get-involved/index.blade.php` - Tab-synchronized responsive sidebar, Tailwind v4 linear gradients, sticky alignment.
- `tests/Unit/ImageOptimizerServiceTest.php` - Unit test for <= 180 KB WebP optimization and source cleanup.
- `tests/Feature/PageContentTest.php` - Tests for dynamic library photos CMS and public rendering.
- `tests/Feature/GetInvolvedPageTest.php` - Tests for get-involved page tab structure and partner sidebar.
- `cpanel_changed_files.txt` - Deployment manifest update.

## Data / contracts

- `page_sections` table:
  - `page_key`: `'home'`
  - `section_key`: `'famoyinka_library'`
  - `headline`: `'Famoyinka Memorial Children\'s Library'`
  - `subheadline`: `'Permanent Literacy Sanctuary'`
  - `body_text`: Description text
  - `meta_data`: `{"photos": [{"image": "...", "label": "Free Book Loans", "alt": "..."}, ...]}`
- `ImageOptimizerService::optimize($file, $dir, $maxW, $maxH, $initialQuality, $maxBytes = 184320)`:
  - Return: relative path to stored `.webp` file.
  - Guaranteed: `filesize <= 184320`, extension `.webp`, original source deleted.

## Testing

- Unit tests: `tests/Unit/ImageOptimizerServiceTest.php` verifying:
  - Large JPG/PNG images are converted to WebP.
  - Resulting file size is strictly <= 180 KB (184,320 bytes).
  - Source uncompressed file is deleted.
- Feature tests:
  - `tests/Feature/PageContentTest.php`: Admin can update the 4 library photos and captions, which render on the homepage.
  - `tests/Feature/PartnerPublicTest.php` & `tests/Feature/VolunteerApplicationTest.php`: Tab switching and partner layout integrity.
- Full regression: `php artisan test` (all 259+ tests passing).
- Code style: `php vendor/bin/pint --test`.

## Notes for the AI

- Do NOT add external packages or binaries (ImageMagick, Spatie, etc.). Strictly use native PHP GD.
- When creating WebP files via GD, always handle true-color canvas and alpha channel correctly.
- Ensure all responsive image attributes (`loading="lazy"`, `decoding="async"`) remain intact.
- Follow Tailwind CSS v4 conventions: use `bg-linear-to-br` instead of `bg-gradient-to-br`.
- Fallbacks in Blade must be rock-solid: if `$sections['famoyinka_library']` or `meta_data['photos']` is null or empty, fall back seamlessly to `famoyinka-01.webp` through `04.webp`.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10573,"specSha256":"7704778e24001f6892cadbba063b599b11868feb0eb59383bbb448241b4ef6af","branch":"refs/heads/feature/28-image-optimizer-causes-library-get-involved","head":"d3278f53d7abf6de74f9d561495cbdbbfff400f9","baseRef":"refs/heads/main","baseCommit":"d3278f53d7abf6de74f9d561495cbdbbfff400f9","sourceTree":"fe69b5de85ea7cfef86430f692d0bc6b74a2adc1","landing":"local-merge","absentOptional":[]} -->

## Findings

_No findings were recorded for this feature._

## Manual try guide

1. **Verify Full Unclipped 6 Impact Causes Graphic**:
   - Navigate to `http://localhost:8000` and scroll down to the "Our 6 Impact Causes" section.
   - Confirm that the `cause.webp` graphic renders cleanly without cropped borders, fixed-height cutoff, or obscuring overlay pill badges on mobile, tablet, and desktop viewports.

2. **Verify Famoyinka Library 4-Photo Dynamic CMS**:
   - Access the admin panel at `http://localhost:8000/admin/pages/home`.
   - Scroll to the "Famoyinka Memorial Children's Library" section and customize photos 1 through 4, action labels, and alt attributes using the media picker.
   - Save the section and reload `http://localhost:8000` to confirm that all 4 photos render dynamically with active links.
   - If unset, confirm that fallback images (`famoyinka-01.webp` to `04.webp`) render seamlessly.

3. **Verify /get-involved Dynamic Tab-Synchronized Sidebar**:
   - Visit `http://localhost:8000/get-involved`.
   - Toggle between the "Volunteer Application" and "Partner With Us" tabs.
   - Verify that the right sidebar switches smoothly:
     - Under "Volunteer", volunteer impact copy and donation options appear.
     - Under "Partner With Us", corporate partnership pathways, verified metrics (165+ ICT students, 591+ literacy learners, 15 schools, 10+ years), and direct Secretariat inquiry details appear.
   - Confirm sticky alignment (`lg:sticky lg:top-28`) and Tailwind v4 linear gradients display without layout distortion.

4. **Verify <= 180 KB WebP Image Optimizer & Original File Purging**:
   - Upload any image through `/admin/gallery` or the media picker modal.
   - Verify that the resulting output file in `public/uploads/` is converted strictly to `.webp` and its size does not exceed 180 KB (184,320 bytes).
   - Verify that the uncompressed original uploaded file is permanently removed from storage immediately after conversion.

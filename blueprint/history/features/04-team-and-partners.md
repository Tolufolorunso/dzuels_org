# Feature: Team and partners

**From build-plan:** feature 4
**Build attempt:** 1
**Branch:** feature/team-and-partners

## Goal

Provide foundation administrators with a complete, secure management interface in `/admin` to add, edit, order, hide/show, and delete team members (Board of Trustees, Management, and Advisory Board with LinkedIn profiles) and partner organizations (with logos and website URLs). Seed verified foundation leadership and partner records.

## Design reference

- Admin UI styling: Tailwind CSS v4 using `<x-admin-layout>` in `resources/views/components/admin-layout.blade.php`
- Legacy leadership roster and photos: `scratch/about.html` and `public/front/images/` (`anna.jpg`, `kate.jpg`, `olawale.jpg`, `bolaji.jpg`, `mgt_fadekemi.webp`, `mgt_michelle.png`, `ascend.jpg`, `SAA.jpg`, `helpmeetaneed.png`, `intinternational.png`)

## In scope

1. **Database Schema & Eloquent Models:**
   - Migration `create_team_members_table`:
     - `id` (bigint PK)
     - `name` (string)
     - `role` (string)
     - `group` (string: `trustee`, `management`, `advisor`)
     - `field` (string, nullable - advisory domain such as Education Services, Medical Services, ICT Services)
     - `bio` (text, nullable)
     - `photo` (string, nullable)
     - `linkedin_url` (string, nullable)
     - `sort_order` (integer, default 0)
     - `is_active` (boolean, default true)
     - `timestamps`
   - Migration `create_partners_table`:
     - `id` (bigint PK)
     - `name` (string)
     - `logo` (string)
     - `website_url` (string, nullable)
     - `sort_order` (integer, default 0)
     - `is_active` (boolean, default true)
     - `timestamps`
   - Model `App\Models\TeamMember` with scopes (`active()`, `ordered()`, `byGroup(string $group)`), fillable attributes, and type casting.
   - Model `App\Models\Partner` with scopes (`active()`, `ordered()`), fillable attributes, and type casting.
   - Seeders `TeamMemberSeeder` and `PartnerSeeder` populating existing foundation trustees, managers, advisors, and partner organizations.

2. **Admin Controllers & Request Validation:**
   - `TeamMemberController`: CRUD operations (`index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleActive`).
   - `PartnerController`: CRUD operations (`index`, `create`, `store`, `edit`, `update`, `destroy`, `toggleActive`).
   - Form requests: `StoreTeamMemberRequest`, `UpdateTeamMemberRequest`, `StorePartnerRequest`, `UpdatePartnerRequest` validating inputs, image file formats, and URLs.
   - Secure file upload handling storing photos in `public/uploads/team` and logos in `public/uploads/partners` (or public disk).

3. **Admin Blade Views:**
   - Navigation: Add "Team Members" and "Partners" tabs to `<x-admin-layout>`.
   - Team Management views:
     - `resources/views/admin/team/index.blade.php`: Group-filtered or tabbed view showing photo avatar, name, role, group badge, sort order, active toggle, and edit/delete actions.
     - `resources/views/admin/team/create.blade.php` and `edit.blade.php`: Form with group selector, photo file input with preview, name, role, field, bio, LinkedIn URL, sort order, and active checkbox.
   - Partner Management views:
     - `resources/views/admin/partners/index.blade.php`: Data list showing logo preview, name, website link, sort order, active toggle, and actions.
     - `resources/views/admin/partners/create.blade.php` and `edit.blade.php`: Form with logo upload, name, website URL, sort order, and active checkbox.

4. **Public Homepage Partner Strip Integration:**
   - Update `HomeController` and `resources/views/home.blade.php` to fetch active ordered partners from `Partner::active()->ordered()->get()`, seamlessly falling back to the 4 verified default partner logos if none are in the database.

5. **Automated Feature Testing:**
   - `tests/Feature/Admin/TeamMemberManagementTest.php` testing authorization, list viewing, creation with photo, updating, toggling visibility, ordering, and deletion.
   - `tests/Feature/Admin/PartnerManagementTest.php` testing authorization, list viewing, creation with logo, updating, toggling visibility, and deletion.

## Out of scope

- Full public About page layout and leadership display (reserved for Feature 5: About page).
- Programme-partner relationship linkages (reserved for Feature 6: Programmes).

## Build loop

Configured with `workflow.stepReview: "feature"` and `workflow.checkpointCommits: "disabled"`. Implementation steps 1 through 5 will execute in sequence, ending with a comprehensive feature-level review packet before `/complete`.

## Build steps

- [x] 1. **Migrations, Models & Seeders** - Create migrations for `team_members` and `partners` tables, generate `TeamMember` and `Partner` Eloquent models with fillable attributes, casting, and scopes, and build `TeamMemberSeeder` and `PartnerSeeder` with historical data.
  - _Done when:_ `php artisan migrate` succeeds, seeders execute cleanly via `php artisan db:seed --class=TeamMemberSeeder` and `php artisan db:seed --class=PartnerSeeder`, and model queries return expected records.

- [x] 2. **Form Requests & Admin Controllers** - Create Form Request classes (`StoreTeamMemberRequest`, `UpdateTeamMemberRequest`, `StorePartnerRequest`, `UpdatePartnerRequest`) and resource controllers `TeamMemberController` and `PartnerController` handling validation, photo/logo upload, ordering, and visibility toggling.
  - _Done when:_ Admin routes are registered under `routes/web.php` and controller actions handle valid and invalid payloads properly.

- [x] 3. **Admin Blade Views & Navigation** - Build admin management views for team members (`admin/team/index`, `create`, `edit`) and partners (`admin/partners/index`, `create`, `edit`), and update `<x-admin-layout>` navigation tabs.
  - _Done when:_ Authenticated administrators can browse, create, edit, reorder, toggle, and delete team members and partner logos from the `/admin` interface.

- [x] 4. **Homepage Dynamic Partner Integration** - Update `HomeController` to pass active `Partner` records to `home.blade.php`, dynamically rendering active partner logos with fallback to static verified assets if the database table is empty.
  - _Done when:_ Homepage renders dynamically seeded partner records and existing tests in `HomepageTest` pass.

- [x] 5. **Feature Tests & Quality Verification** - Write feature test suites `TeamMemberManagementTest` and `PartnerManagementTest`, run Pint formatting check, and execute full test suite.
  - _Done when:_ `php artisan test` passes 100% green with all new assertions and `php vendor/bin/pint --test` reports zero violations.

## Files / areas

- `database/migrations/*_create_team_members_table.php` (new)
- `database/migrations/*_create_partners_table.php` (new)
- `app/Models/TeamMember.php` (new)
- `app/Models/Partner.php` (new)
- `database/seeders/TeamMemberSeeder.php` (new)
- `database/seeders/PartnerSeeder.php` (new)
- `database/seeders/DatabaseSeeder.php` (modified)
- `app/Http/Requests/Admin/StoreTeamMemberRequest.php` (new)
- `app/Http/Requests/Admin/UpdateTeamMemberRequest.php` (new)
- `app/Http/Requests/Admin/StorePartnerRequest.php` (new)
- `app/Http/Requests/Admin/UpdatePartnerRequest.php` (new)
- `app/Http/Controllers/Admin/TeamMemberController.php` (new)
- `app/Http/Controllers/Admin/PartnerController.php` (new)
- `resources/views/components/admin-layout.blade.php` (modified)
- `resources/views/admin/team/index.blade.php` (new)
- `resources/views/admin/team/create.blade.php` (new)
- `resources/views/admin/team/edit.blade.php` (new)
- `resources/views/admin/partners/index.blade.php` (new)
- `resources/views/admin/partners/create.blade.php` (new)
- `resources/views/admin/partners/edit.blade.php` (new)
- `routes/web.php` (modified)
- `app/Http/Controllers/HomeController.php` (modified)
- `resources/views/home.blade.php` (modified)
- `tests/Feature/Admin/TeamMemberManagementTest.php` (new)
- `tests/Feature/Admin/PartnerManagementTest.php` (new)

## Data / contracts

- `TeamMember` model contract:
  - `name`: string, required
  - `role`: string, required
  - `group`: string (`trustee`, `management`, `advisor`), required
  - `field`: string, nullable
  - `bio`: text, nullable
  - `photo`: string, nullable
  - `linkedin_url`: string, nullable
  - `sort_order`: int, default 0
  - `is_active`: bool, default true

- `Partner` model contract:
  - `name`: string, required
  - `logo`: string, required (or image file)
  - `website_url`: string, nullable
  - `sort_order`: int, default 0
  - `is_active`: bool, default true

## Testing

- Feature tests in `tests/Feature/Admin/TeamMemberManagementTest.php` and `tests/Feature/Admin/PartnerManagementTest.php`.
- Full suite verification: `php artisan test`.
- Code styling: `php vendor/bin/pint --test`.
- Asset compilation: `npm run build`.

## Notes for the AI

- Use `<x-admin-layout>` for all admin views.
- Handle image upload gracefully using `Storage::disk('public')` or `file->store('...')`, while allowing seeded records to reference existing assets in `front/images/...`.
- Provide clean flash messaging upon store/update/delete.
- Keep table actions accessible and clearly labeled.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":9167,"specSha256":"9bf6739875397d5a857be8e1af24a9b2c83032eae39329b9bfc996d3a338b49d","branch":"refs/heads/feature/team-and-partners","head":"77ede88f9995cdf03ec3c5e94a6421c571ac211f","baseRef":"refs/heads/main","baseCommit":"77ede88f9995cdf03ec3c5e94a6421c571ac211f","sourceTree":"18a1110fdbb63f65af60bbd245f0bac332c2fc4e","landing":"local-merge","absentOptional":[]} -->

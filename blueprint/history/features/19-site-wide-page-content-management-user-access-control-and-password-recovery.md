# Feature: Site-wide page content management, user access control and password recovery

**From build-plan:** feature 19
**Build attempt:** 1
**Branch:** feature/site-wide-page-content-management-user-access-control-and-password-recovery
**Status:** verified

## Goal
Empower foundation staff to manage all public web page content (headlines, text copy, and section images across Home, About, Programmes, Contact, Donate, and Get Involved) directly from an intuitive WordPress-style visual CMS in `/admin/pages`; implement multi-tier role-based access control (Super Admin, Editor, Support) so administrators can create and manage staff accounts with appropriate privileges; and provide comprehensive password security including forgotten-password recovery email links and in-dashboard password updates.

## In scope
1. **PageSection Data Model & Default Seeding**:
   - Migration for `page_sections` table: `id`, `page_key`, `section_key`, `headline`, `subheadline`, `body_text`, `image_path`, `button_text`, `button_url`, `meta_data`, `sort_order`, `timestamps`.
   - `PageSection` model with fast retrieval helpers (`PageSection::forPage('home')`, `PageSection::getSection('home', 'hero')`).
   - Comprehensive `PageSectionSeeder` pre-populating default content for all public pages (Home, About, Programmes, Contact, Donate, Get Involved) so that existing site copy and media are immediately manageable without blank slates.
2. **Public Page Integration**:
   - Integration in controllers (`HomeController`, `AboutController`, `ProgrammeController`, `ContactController`, `DonateController`, `GetInvolvedController`) and public Blade templates.
   - Dynamic rendering of headlines, subheadlines, body copy, and section images with graceful fallbacks to default text/assets when fields are empty.
3. **Admin Visual CMS Page Editor (`/admin/pages`)**:
   - Page overview listing at `/admin/pages` showing all manageable pages with status badges and section counts.
   - Dedicated page section editor at `/admin/pages/{page_key}` with tabbed or accordion sections for editing headline, subheadline, body copy, button labels/URLs, and uploading or replacing section images.
   - File upload validation (JPEG, PNG, WebP up to 5MB) storing uploaded section media cleanly in `public/front/images/cms/` or storage with public asset URLs.
4. **Role-Based Access Control (RBAC)**:
   - Three structured staff roles:
     - `super_admin`: Full administrative privileges (user management, global settings, CMS, database access).
     - `editor`: Content management (CMS page editing, events, programmes, gallery, team, partners). Restricted from user management and system settings.
     - `support`: Read-only access to community inquiries, contact messages, volunteer applications, and event registrations.
   - `User` model role helper methods (`isSuperAdmin()`, `isEditor()`, `isSupport()`, `canManageUsers()`).
   - Authorization middleware restricting `/admin/users` and `/admin/settings` to Super Admins.
5. **Staff User Management (`/admin/users`)**:
   - User list showing name, email, role badge, active status, and actions.
   - Create new staff account with name, email, role selection, and temporary/initial password.
   - Edit existing staff account details and roles.
   - Toggle user active status.
   - Delete staff account with guards preventing self-deletion and removal of the last Super Admin.
6. **Password Management & Forgotten Password Recovery**:
   - **Forgot Password Workflow**:
     - "Forgot your password?" link on `/admin/login`.
     - Request reset link page at `/admin/forgot-password`.
     - Dispatch reset link token via Laravel password broker.
     - Reset password form at `/admin/reset-password/{token}` to enter email and new confirmed password.
   - **In-Dashboard Profile & Password Update**:
     - Profile view at `/admin/profile` allowing logged-in staff to update their profile and change their password (with current password verification).
7. **Admin Navigation Overhaul**:
   - Modern, responsive administrative navbar in `resources/views/admin/layout.blade.php` linking to Dashboard, Pages CMS, Site Settings, Events, Programmes, Gallery, Team, Partners, Inquiries, Messages, and Users (filtered by user role).
   - Quick action shortcuts in `resources/views/admin/dashboard.blade.php`.
8. **Automated Testing & Code Quality**:
   - Feature tests covering CMS section updates, image uploads, public page rendering, role authorization boundaries, user CRUD, password reset tokens, and profile password changes.
   - 100% passing tests via `php artisan test` and Pint code formatting.

## Out of scope
- Arbitrary code execution or raw HTML/PHP injection in the CMS editor (safe structured text, markdown/newlines, and validated media only).
- Public front-end user registration (the foundation portal is staff-only).
- Feature 20 database SQL export (`php artisan db:export-sql`), which is isolated to the next feature in the build plan.

## Build loop
- Review cadence: feature-level (`workflow.stepReview: "feature"`, `workflow.checkpointCommits: "disabled"`).
- Automated tests run after each implementation step with `php artisan test`.
- Final Pint formatting and full suite verification before completion.

## Build steps
- [x] 1. **PageSection Migration, Model & Seeders**
  - Create database migration `create_page_sections_table`.
  - Create `App\Models\PageSection` model with fillable attributes, JSON casting, and fast retrieval helper methods.
  - Create `PageSectionSeeder` with complete baseline content for Home, About, Programmes, Contact, Donate, and Get Involved.
  - Run migrations and seed data.
  - *Done when:* `php artisan test` passes and `PageSection::forPage('home')` returns populated section records.

- [x] 2. **Public Page CMS Integration**
  - Connect `HomeController`, `AboutController`, `ProgrammeController`, `ContactController`, `DonateController`, and `GetInvolvedController` to load page sections.
  - Update public Blade templates to display dynamic headlines, body copy, and section images with robust fallbacks.
  - Create feature test `tests/Feature/PublicPageContentRenderingTest.php` asserting dynamic content updates reflect on public pages.
  - *Done when:* Updating a record in `page_sections` changes the text/image on the corresponding public page.

- [x] 3. **Admin Visual CMS Editor (`/admin/pages`)**
  - Create `App\Http\Controllers\Admin\PageController` with `index`, `edit`, and `updateSection` methods.
  - Create Blade views `resources/views/admin/pages/index.blade.php` and `resources/views/admin/pages/edit.blade.php` with section forms, image uploads, and live previews.
  - Register `/admin/pages` routes in `routes/web.php`.
  - Create feature test `tests/Feature/PageContentManagementTest.php` testing page section updates, image upload storage, and validation.
  - *Done when:* Staff can navigate to `/admin/pages`, edit any section or upload an image, and see immediate changes on the public site.

- [x] 4. **Role-Based Access Control & User Management (`/admin/users`)**
  - Update `User` model with roles (`super_admin`, `editor`, `support`), role helper methods, and migrate existing admin user to `super_admin`.
  - Create `EnsureSuperAdmin` middleware and register alias in `bootstrap/app.php`.
  - Create `App\Http\Controllers\Admin\UserController` (`index`, `create`, `store`, `edit`, `update`, `toggleActive`, `destroy`).
  - Create Blade views `resources/views/admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php`.
  - Register `/admin/users` routes protected by Super Admin middleware.
  - Create feature test `tests/Feature/UserAccessControlTest.php` asserting role boundaries (editors blocked from user management, super admins allowed, self-deletion prevented).
  - *Done when:* Super Admin can create, edit, toggle, and delete staff accounts with assigned roles, while unauthorized roles are denied.

- [x] 5. **Password Management & Forgotten Password Recovery**
  - Create `App\Http\Controllers\Admin\ForgotPasswordController` and `App\Http\Controllers\Admin\ResetPasswordController` for email reset links and token resets.
  - Create `App\Http\Controllers\Admin\ProfileController` for in-dashboard password changes with current password validation.
  - Create Blade views `resources/views/admin/auth/forgot-password.blade.php`, `resources/views/admin/auth/reset-password.blade.php`, and `resources/views/admin/profile/password.blade.php`.
  - Add "Forgot your password?" link on `/admin/login` and Profile/Password link in admin layout.
  - Create feature test `tests/Feature/PasswordRecoveryTest.php` testing link requests, token verification, password resetting, and profile password updates.
  - *Done when:* Staff can request password reset links, reset passwords via tokens, and change their own password from the dashboard.

- [x] 6. **Admin Navigation Overhaul & Final Verification**
  - Update `resources/views/admin/layout.blade.php` with comprehensive responsive navigation bar (Dashboard, Pages CMS, Settings, Events, Programmes, Gallery, Team, Partners, Inquiries, Messages, Users) filtered by role.
  - Update `resources/views/admin/dashboard.blade.php` quick actions and role display.
  - Run full test suite `php artisan test` and Pint code formatting `php vendor/bin/pint`.
  - *Done when:* All tests pass with 0 failures, Pint reports no style violations, and admin navigation is fully responsive and role-aware.

## Files / areas
- `database/migrations/*_create_page_sections_table.php` - CMS sections schema
- `app/Models/PageSection.php` - CMS section model & helpers
- `database/seeders/PageSectionSeeder.php` - Baseline content for public pages
- `database/seeders/DatabaseSeeder.php` - Register `PageSectionSeeder`
- `app/Models/User.php` - Role helpers & casts
- `app/Http/Middleware/EnsureSuperAdmin.php` - Super admin role middleware
- `bootstrap/app.php` - Middleware alias registration
- `app/Http/Controllers/HomeController.php` & public controllers - Public page CMS injection
- `resources/views/home.blade.php`, `about.blade.php`, etc. - Public templates dynamic content
- `app/Http/Controllers/Admin/PageController.php` - CMS editor controller
- `resources/views/admin/pages/index.blade.php` & `edit.blade.php` - CMS editor views
- `app/Http/Controllers/Admin/UserController.php` - Staff user management controller
- `resources/views/admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php` - User management views
- `app/Http/Controllers/Admin/ForgotPasswordController.php` - Reset link controller
- `app/Http/Controllers/Admin/ResetPasswordController.php` - Password reset controller
- `app/Http/Controllers/Admin/ProfileController.php` - In-dashboard profile & password controller
- `resources/views/admin/auth/forgot-password.blade.php` & `reset-password.blade.php` - Password recovery views
- `resources/views/admin/profile/password.blade.php` - Profile password change view
- `resources/views/admin/layout.blade.php` - Admin layout & responsive navigation
- `resources/views/admin/dashboard.blade.php` - Dashboard quick actions
- `routes/web.php` - Routes for CMS, Users, Password recovery, Profile
- `tests/Feature/PageContentManagementTest.php` - CMS feature tests
- `tests/Feature/UserAccessControlTest.php` - RBAC feature tests
- `tests/Feature/PasswordRecoveryTest.php` - Password recovery feature tests

## Data / contracts
- **PageSection Schema**:
  - `page_key`: string (indexed)
  - `section_key`: string (indexed)
  - `headline`: string, nullable
  - `subheadline`: string, nullable
  - `body_text`: text, nullable
  - `image_path`: string, nullable
  - `button_text`: string, nullable
  - `button_url`: string, nullable
  - `meta_data`: json, nullable
  - `sort_order`: integer, default 0
- **User Roles**:
  - `super_admin`: Full access including `/admin/users` and `/admin/settings`.
  - `editor`: Access to `/admin/pages`, `/admin/events`, `/admin/programmes`, `/admin/gallery`, `/admin/team`, `/admin/partners`.
  - `support`: Access to `/admin/messages`, `/admin/inquiries`, `/admin/events/*/registrations`.
- **Password Reset**:
  - Standard Laravel `password_reset_tokens` table with 60-minute token expiry.
  - Minimum password length: 8 characters.

## Testing
- Automated testing via `php artisan test`.
- New feature test classes:
  - `tests/Feature/PageContentManagementTest.php`
  - `tests/Feature/UserAccessControlTest.php`
  - `tests/Feature/PasswordRecoveryTest.php`
- Code formatting via `php vendor/bin/pint --test`.

## Notes for the AI
- Maintain zero AI attribution in Git commits and pull requests.
- Ensure all public pages gracefully fall back to default text if a `PageSection` record or field is missing or empty.
- Keep passwords securely hashed using Laravel's default bcrypt hasher.
- Preserve existing admin user credentials while upgrading their role to `super_admin`.
- When uploading section images, ensure they are stored in `public/front/images/cms/` or disk with unique filenames to prevent overwrite collisions.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":13050,"specSha256":"512938f6fdfdc8d39d3a0a9d53101d45a27b682ad2ae8f9b0a51e728998165bf","branch":"refs/heads/feature/site-wide-page-content-management-user-access-control-and-password-recovery","head":"0190cca61f87be38ddb50188a1a2bee451efcae7","baseRef":"refs/heads/main","baseCommit":"0190cca61f87be38ddb50188a1a2bee451efcae7","sourceTree":"899853a402a4a8f9cafe70437c90c3ad19846a15","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Visual CMS Page Content Editor**:
   - Sign in to the administration portal at `http://localhost:8000/admin/login` as Super Admin (`tolufolorunso@yahoo.com`).
   - Navigate to **Pages CMS** at `http://localhost:8000/admin/pages`.
   - Select **Home Page** (or About, Programmes, Contact, Donate, Get Involved) and click **Edit Content**.
   - Modify a section headline or body text (e.g. Hero headline) and click **Save Section Content**.
   - Open `http://localhost:8000/` in another tab to confirm the updated headline renders immediately.
   - In the CMS editor, upload a valid image (JPEG/PNG/WebP under 5MB) for a section and verify it displays properly on the public page.

2. **Verify Multi-Tier Role-Based Access Control**:
   - In the admin sidebar, click **Staff Accounts** at `http://localhost:8000/admin/users`.
   - Click **Add Staff User** and create a new staff account with Role: **Content Editor** (`editor`).
   - Verify the staff table shows the newly created user with active status and an Editor badge.
   - Log out and log in using the newly created Editor credentials.
   - Confirm the Editor can navigate to **Pages CMS**, **Events**, **Programmes**, **Gallery**, **Team**, and **Partners**.
   - Attempt to directly access `http://localhost:8000/admin/users` and `http://localhost:8000/admin/settings`: confirm access is blocked with 403 Forbidden.

3. **Verify Password Recovery & Profile Security**:
   - Visit `http://localhost:8000/admin/login` and click **Forgot your password?**.
   - Enter your email address and submit the request.
   - Log in and visit **My Profile** at `http://localhost:8000/admin/profile`.
   - Change your account password by entering your current password and a new confirmed password.
   - Log out and sign in using your new password to verify credentials.

# Feature: Gemini AI Content Assistant, Multimodal Image Analysis & Universal Admin Input Integration

**From build-plan:** feature 32
**Build attempt:** 1
**Branch:** feature/gemini-ai-content-assistant-multimodal-image-analysis-universal-admin-input-integration

## Goal

Integrate Google Gemini into the Dzuels Educational Foundation admin dashboard to assist staff in generating and refining text across all CMS page sections, site settings, programmes, events, team bios, and media collections. Provide context-aware suggestions, prompt tweaks, and multimodal photo inspection for accurate alt text and captions, with zero heavy package dependencies, British English defaults, and Core FTP deployment tracking.

## In scope

- **Native Gemini Service Layer (`GeminiService`)**:
  - Connect to Google GenAI REST API (`https://generativelanguage.googleapis.com/v1beta/models/...`) using native Laravel `Http` client.
  - Support text generation and multimodal vision analysis (passing base64 `inline_data` for photos).
  - Enforce British/Commonwealth English spelling (e.g., *programme, organise, colour*) and Dzuels Foundation charity context in the system prompt.
  - Built-in `testConnection()` method to verify credentials and report model connectivity.
- **Admin Settings Management & Live Connection Test**:
  - Configurable `gemini_api_key` and `gemini_model` in the `settings` database table, falling back to `.env` (`GEMINI_API_KEY`).
  - Dropdown selector for models (defaulting to fast multimodal models: `gemini-1.5-flash`, `gemini-2.0-flash`, `gemini-1.5-pro`, or custom).
  - Interactive "Test Connection" button in `/admin/settings` providing instant visual status.
- **Context-Aware Backend API (`POST /admin/ai/generate`)**:
  - Secure endpoint protected by admin auth middleware and rate limiting.
  - Accepts field payload: `field_name`, `field_label`, `page_context`, `section_context`, `sibling_data` (e.g. existing title/summary), `prompt_tweak` (optional custom instruction), and optional `image_path`.
  - Intelligently constructs prompt combining field purpose, surrounding context, and visual image data.
- **Reusable Blade / Alpine UI Component (`<x-admin.ai-button>`)**:
  - Elegant sparkle icon (✨) attaching cleanly beside inputs and textareas.
  - Inline floating preview popover showing:
    - Animated skeleton/loading state while generating.
    - Generated suggestion preview with 1-click **Insert** (populates/replaces field).
    - **Regenerate** button for alternative variations.
    - Optional **Prompt Tweak** input (e.g. "Make it shorter", "Focus on Ijero-Ekiti", "Include a call to action").
    - **Cancel** / dismiss button.
- **Universal Rollout Across Dashboard**:
  - CMS Page Sections (Home, About, Contact, Privacy) with section image context.
  - Site Settings (mission, vision, who_we_are, values, footer).
  - Programmes (title, summary, body).
  - Events (title, theme, summary, description, venue).
  - Team Members (bio, role description).
  - Central Media Hub & Gallery Photos (captions, alt text, titles using multimodal vision).
- **Production Core FTP Manifest**:
  - Track all created and modified files in `cpanel_changed_files.txt` for 1-click Core FTP upload to `/home/dzuefjel/dzuels_org`.

## Out of scope

- Direct automated overwriting without user preview (always requires user to review and click "Insert").
- Public-facing visitor AI chat or user-facing bots (admin content generation only).
- Generating or altering image pixel data (text generation and image understanding only).
- Heavy external vendor SDKs (using native Laravel `Http` client).

## Build loop

- Step review: `feature` (one review packet after all steps are completed).
- Checkpoint commits: `disabled`.
- Testing: Run `php artisan test` and `php vendor/bin/pint --test` after each step.

## Build steps

- [x] 1. **Gemini Configuration, Model Settings & Service Layer**
  - Seed default settings `gemini_api_key` (null) and `gemini_model` (`gemini-1.5-flash`).
  - Create `App\Services\GeminiService` with `generateText()`, `buildSystemPrompt()`, `encodeImage()`, and `testConnection()`.
  - Done when: Unit and feature tests verify `GeminiService` connects to the Google GenAI REST API, formats multimodal payloads, extracts text, and handles error states.
- [x] 2. **Admin Settings AI Card & Live Connection Test**
  - Update `SettingController` to validate and persist `gemini_api_key` and `gemini_model`.
  - Add `POST /admin/settings/test-gemini` endpoint returning connection status.
  - Update `resources/views/admin/settings.blade.php` with an "AI Writing Assistant (Google Gemini)" card, key input, model dropdown, and "Test Connection" button.
  - Done when: Admins can configure Gemini credentials in `/admin/settings` and test the connection with instant feedback.
- [x] 3. **Context-Aware AI Generation API Endpoint**
  - Create Form Request `GenerateAiContentRequest` validating `field_name`, `field_label`, `page_context`, `section_context`, `sibling_data`, `prompt_tweak`, and `image_path`.
  - Create Controller `AiContentController` handling `POST /admin/ai/generate` with rate limiting and error handling.
  - Register route `POST /admin/ai/generate` under admin middleware.
  - Done when: Automated feature tests verify the endpoint validates requests, passes context to `GeminiService`, and returns JSON responses.
- [x] 4. **Reusable Blade/Alpine AI Sparkle Button & Floating Popover**
  - Create `resources/views/components/admin/ai-button.blade.php` with Alpine.js popover component.
  - Include loading skeleton, generated text display, prompt tweak input, and Insert/Regenerate/Cancel action buttons.
  - Done when: The component can be mounted on inputs and textareas, displaying the popover, fetching suggestions, and inserting text into target fields.
- [x] 5. **Universal Integration Across Admin CMS & Media Forms**
  - Add `<x-admin.ai-button>` to CMS Page Sections editor with section image awareness.
  - Add to Site Settings text inputs and textareas.
  - Add to Programme, Event, and Team Member forms.
  - Add to Central Media Hub and Gallery Photo editors for automated multimodal caption and alt text generation.
  - Done when: All admin forms feature the AI sparkle icon and successfully generate relevant copy for their inputs.
- [x] 6. **Verification, Core FTP Manifest & Production Alignment**
  - Run full test suite (`php artisan test`).
  - Run code formatting check (`php vendor/bin/pint --test`).
  - Run launch check (`php artisan app:launch-check`).
  - Export refreshed MariaDB dump (`php artisan db:export-sql`).
  - Update `cpanel_changed_files.txt` for Core FTP deployment.
  - Done when: All tests pass, code is Pint-clean, launch check is green, and Core FTP manifest is up to date.

## Files / areas

- `app/Services/GeminiService.php` (new)
- `app/Http/Controllers/Admin/AiContentController.php` (new)
- `app/Http/Requests/Admin/GenerateAiContentRequest.php` (new)
- `app/Http/Controllers/Admin/SettingController.php` (updated)
- `database/seeders/SettingSeeder.php` (updated)
- `routes/web.php` (updated)
- `resources/views/components/admin/ai-button.blade.php` (new)
- `resources/views/admin/settings.blade.php` (updated)
- `resources/views/admin/pages/edit.blade.php` (updated)
- `resources/views/admin/programmes/create.blade.php` & `edit.blade.php` (updated)
- `resources/views/admin/events/create.blade.php` & `edit.blade.php` (updated)
- `resources/views/admin/team/create.blade.php` & `edit.blade.php` (updated)
- `resources/views/admin/gallery/photos.blade.php` & `resources/views/admin/media/index.blade.php` (updated)
- `tests/Unit/GeminiServiceTest.php` (new)
- `tests/Feature/Admin/AiContentGenerationTest.php` (new)
- `cpanel_changed_files.txt` (updated)

## Data / contracts

- `settings` table keys:
  - `gemini_api_key`: string, nullable
  - `gemini_model`: string, default `gemini-1.5-flash`
- `POST /admin/settings/test-gemini` -> JSON `{ success: bool, message: string }`
- `POST /admin/ai/generate` -> JSON `{ success: bool, text?: string, error?: string }`

## Testing

- Unit tests for `GeminiService` (mocking Google REST API requests and responses, verifying system prompt, multimodal image base64 formatting, error handling).
- Feature tests for `POST /admin/settings/test-gemini` and `POST /admin/ai/generate` (authentication, validation, rate limiting, and successful response generation).
- Feature tests for admin views ensuring `<x-admin.ai-button>` renders on forms.
- Full suite verification: `php artisan test` (target: 305+ tests passing).

## Notes for the AI

- Use native Laravel `Http` client (`Illuminate\Support\Facades\Http`) — do NOT install any third-party Composer packages.
- When encoding images for multimodal vision, read the file using `File::get()` from `public_path($imagePath)`, check mime type, and pass as base64 string in `inline_data`.
- Keep the popover UI clean, lightweight, and matching the admin panel's aesthetic (warm cream accents, subtle borders, crisp typography).
- Keep `cpanel_changed_files.txt` updated with every modified file.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":9110,"specSha256":"8882bbaa59030282d243a7db4c9c0c905e1b7b9c28ead38db0e6458a90f30293","branch":"refs/heads/feature/gemini-ai-content-assistant-multimodal-image-analysis-universal-admin-input-integration","head":"d1fdf42a9df77db6575532f5c0f69da6fe4107aa","baseRef":"refs/heads/main","baseCommit":"d1fdf42a9df77db6575532f5c0f69da6fe4107aa","sourceTree":"d5a3dc6d42f97147d9ce626d9e3c98625f7a06f4","landing":"local-merge","absentOptional":[]} -->

## Findings

_No resolved findings to archive for this feature. (Open advisory finding F-07 remains in live ledger)._

## Manual try guide

1. **Verify Gemini API Configuration & Interactive Connection Test**:
   - Log into the Admin Operations Portal at `http://localhost:8000/admin/login`.
   - Navigate to **Site Settings** (`/admin/settings`) and scroll to **Section 6: AI Writing Assistant (Google Gemini)**.
   - Enter a Gemini API Key (or leave blank to fallback to `GEMINI_API_KEY` from `.env`).
   - Select a model (e.g. `gemini-1.5-flash` or `gemini-2.0-flash`).
   - Click the **"Test Connection"** button: verify that a green success pill appears reporting successful connection and latency (or clean error message if the key is invalid).
   - Save settings and confirm credentials persist.

2. **Verify Universal AI Sparkle Button & Floating Popover**:
   - Navigate to **Programmes > New Programme** (`/admin/programmes/create`).
   - Observe the sparkle icon button (**✨ AI Write**) next to the Title field.
   - Click the sparkle icon: verify the elegant floating popover opens immediately.
   - Type a prompt tweak (e.g. `Focus on STEM and coding in Ijero-Ekiti`) and press Enter or click the send button.
   - Observe the loading skeleton, followed by the generated British English copy (e.g. *programme*, *organise*).
   - Click **"Insert into Field"**: verify the text populates into the Title input field and triggers input/change events.
   - Repeat on the Summary and Body fields to verify sibling context resolution.

3. **Verify Multimodal Photo Analysis for Alt Text & Captions**:
   - Navigate to the **Central Media Hub** (`/admin/media`) or **Gallery Photos** (`/admin/gallery/albums/{id}/photos`).
   - Click to edit any existing photo thumbnail.
   - Click the sparkle icon next to **Photo Caption** or **Alt Text**.
   - Notice that the backend loads the authentic photo file bytes, encodes them into base64 `inline_data`, and sends them to Gemini Vision.
   - Verify that the generated alt text or caption accurately describes the contents of the image with charity-appropriate British English framing.

4. **Verify Universal Form Coverage Across Dashboard**:
   - Verify AI sparkle buttons on:
     - **CMS Page Sections** (`/admin/pages/home/edit` - headline, subheadline, body text, button text)
     - **Events** (`/admin/events/create` and `edit` - title, venue, theme, summary, description)
     - **Team** (`/admin/team/create` and `edit` - role, bio)
     - **News Articles** (`/admin/news/create` and `edit` - title, excerpt, body, SEO fields)
     - **Partners** (`/admin/partners/create` and `edit` - organization name)

5. **Verify Full Automated Test Suite & Launch Readiness**:
   - Run `php artisan test` to confirm all 323 tests pass.
   - Run `php vendor/bin/pint --test` to confirm code formatting is clean.
   - Run `php artisan app:launch-check` to verify all 14 launch criteria are green.

6. **Verify cPanel Upload Manifest**:
   - Inspect `cpanel_changed_files.txt` to confirm all 31 Feature 32 files are listed for Core FTP upload to `/home/dzuefjel/dzuels_org`.

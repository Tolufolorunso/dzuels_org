# Findings

> **Generated file.** The findings ledger: review findings raised by `/audit`
> against the work in progress, each with a durable ID, severity (P0-P3), and
> status. `/implement` marks repaired findings `fixed`, a later `/audit` pass
> moves them to `closed`, and `/complete` refuses to land while any P0 or P1
> finding is `open` or `fixed`, then archives resolved findings with the work
> and resets this file.

### F-07 [P2] open - Automated coverage does not exercise real oversized multipart requests

**File:** tests/Feature/Admin/MediaLibraryTest.php:167-194; tests/Feature/GalleryBulkUploadTest.php:39-72; tests/Unit/ImageOptimizerServiceTest.php:42-116
**Found:** 2026-10-15 by /audit (scope: full; lens: tests)
**Why it matters:** The existing upload tests use `UploadedFile::fake()` or mock the optimizer, so they verify controller wiring but not PHP's `UPLOAD_ERR_INI_SIZE`, request truncation, the 20-file runtime limit, structured 422 responses, or the optimizer's fallback ceiling. All 284 tests can pass while production uploads fail before Laravel executes.
**Suggested fix:** Add focused coverage for Laravel validation and error rendering, plus a documented SAPI-level smoke test using a real multipart request above and below the configured limits. Keep the test fixture and runtime limits aligned with the chosen upload contract.
**Resolution:**

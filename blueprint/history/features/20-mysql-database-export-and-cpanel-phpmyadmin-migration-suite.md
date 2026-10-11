# Feature: MySQL database export and cPanel phpMyAdmin migration suite

**From build-plan:** feature 20
**Build attempt:** 1
**Branch:** feature/mysql-database-export-and-cpanel-phpmyadmin-migration-suite
**Status:** verified

## Goal

Provide an automated Artisan command `php artisan db:export-sql` (with optional `--output=` path option, defaulting to `database/dumps/dzuels_database.sql`) that exports the application's entire database into a clean, standalone MySQL/MariaDB 5.7+ / 8.0+ compatible `.sql` file with table schemas, primary/foreign keys, indices, and all seeded/persisted data, ready for 1-click import in cPanel phpMyAdmin. Additionally, provide an authoritative production deployment guide in `docs/cpanel-deployment-guide.md` covering MySQL database and user setup, phpMyAdmin import, file upload, `.env` production configuration, storage symlink, file permissions, and post-deployment verification.

## In scope

- Artisan command `php artisan db:export-sql` with `--output=` option (defaulting to `database/dumps/dzuels_database.sql`).
- Schema definition generator for MySQL:
  - Header with UTF-8 encoding declarations, disabling foreign key checks (`SET FOREIGN_KEY_CHECKS=0;`), SQL mode settings, and character set declarations (`utf8mb4`).
  - Table drop and create statements for all application tables:
    `migrations`, `users`, `password_reset_tokens`, `page_sections`, `events`, `event_sponsors`, `event_registrations`, `programmes`, `gallery_albums`, `gallery_photos`, `team_members`, `partners`, `contact_messages`, `volunteer_applications`, `partner_enquiries`, `posts`, `newsletter_subscribers`, `settings`, `sessions`, `jobs`, `job_batches`, `failed_jobs`, `cache`, `cache_locks`.
  - Proper MySQL types, backtick escaping (`` `table` ``, `` `column` ``), `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
  - Primary keys, unique keys, indexes, auto_increment attributes, and foreign keys.
- Data export generator:
  - Iterates through tables in topological order (parent tables before dependent child tables) to ensure clean foreign key integrity.
  - Chunked querying of all rows.
  - SQL escaping of values: integers/floats formatted cleanly, nulls as `NULL`, booleans as `1`/`0`, strings/JSON properly escaped with `addslashes` or PDO quote.
  - Formatted batch `INSERT INTO \`table\` (\`col1\`, \`col2\`, ...) VALUES (...), (...);`.
  - Footer re-enabling foreign key checks (`SET FOREIGN_KEY_CHECKS=1;`).
- Directory creation: automatically creates directory `database/dumps/` if it does not exist.
- Verification feedback: console output detailing dumped tables, row counts, output file path, and file size in KB/MB.
- Comprehensive deployment documentation: `docs/cpanel-deployment-guide.md`:
  - cPanel MySQL Database Wizard (creating database and user, assigning all privileges).
  - phpMyAdmin step-by-step import instructions.
  - Application file packaging & upload (excluding node_modules, tests, .git, etc.).
  - Production `.env` setup guide (`APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=mysql`, `DB_HOST=localhost`, `DB_DATABASE=...`, `DB_USERNAME=...`, `DB_PASSWORD=...`).
  - Symlink handling in cPanel (`storage/app/public` to `public_html/storage`).
  - Permissions and optimization (`php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`).
- Integration with pre-launch health checks: updating `app:launch-check` to verify that `database/dumps/dzuels_database.sql` can be generated or exists when checked, or documenting its export availability.
- Automated tests:
  - `tests/Feature/DatabaseExportCommandTest.php` testing `php artisan db:export-sql`, custom output path, file existence, SQL file content (presence of MySQL directives, `SET FOREIGN_KEY_CHECKS=0`, `CREATE TABLE \`users\``, `INSERT INTO \`users\``, `ENGINE=InnoDB`, `SET FOREIGN_KEY_CHECKS=1`), all application tables included, and proper return code `0`.

## Out of scope

- Modifying local development `.env` default connection away from SQLite (local remains SQLite for fast zero-configuration testing and dev).
- Deploying directly or connecting remotely to a live cPanel server (migration suite creates the tooling and artifacts; deployment is performed by the system administrator using the provided guide).
- Non-MySQL database engines (PostgreSQL, Oracle, SQL Server).
- Installing third-party heavy packages or composer packages when standard Laravel facades and PDO capabilities provide full fidelity.

## Build loop

- Step review cadence: `workflow.stepReview: "feature"` (one review packet after all steps are complete).
- Checkpoint commits: `workflow.checkpointCommits: "disabled"` (no intermediate commits; `/complete` creates the single feature commit).
- Quality gates: `regular.audit: "manual"`, `regular.independentReview: "when-sensitive"`, `regular.check: "manual"`.

## Build steps

- [x] **Step 1: MySQL Schema and DDL Generator**
  - Create `app/Services/DatabaseExportService.php` responsible for extracting table structure from the active database connection or Laravel schema, converting columns, primary keys, indices, and foreign keys into clean, standard MySQL DDL (`DROP TABLE IF EXISTS \`table\`;`, `CREATE TABLE \`table\` (...) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;`).
  - Ensure table generation order places independent parent tables before dependent child tables (e.g. `events` before `event_sponsors`).
  - Done when: Exporter generates valid MySQL DDL strings for all application tables with backticks, proper MySQL types, and constraint definitions.
- [x] **Step 2: Table Data Dumper & Artisan Command**
  - Implement data dumping logic in `DatabaseExportService`: chunked row retrieval, value quoting/escaping (`NULL`, numbers, strings, JSON, booleans), batch `INSERT INTO` construction, and foreign key toggle wrapping (`SET FOREIGN_KEY_CHECKS=0;` ... `SET FOREIGN_KEY_CHECKS=1;`).
  - Create Artisan command `app/Console/Commands/ExportDatabaseSql.php` (`php artisan db:export-sql`), accepting optional `--output=` option (defaulting to `database/dumps/dzuels_database.sql`), ensuring target folder exists, streaming/writing SQL to disk, and reporting table-by-table row counts and final dump statistics to the console.
  - Done when: Running `php artisan db:export-sql` outputs `database/dumps/dzuels_database.sql` containing valid MySQL schema and data inserts for all populated tables (users, settings, page_sections, programmes, events, gallery, team members, etc.) and exits with status 0.
- [x] **Step 3: Step-by-Step cPanel Deployment Guide**
  - Author comprehensive guide in `docs/cpanel-deployment-guide.md` with detailed, user-friendly instructions tailored to cPanel shared hosting (Namecheap, cPanel, Whogohost, etc.).
  - Cover:
    1. Exporting the database with `php artisan db:export-sql`.
    2. Creating the MySQL database & database user via cPanel MySQL Database Wizard.
    3. Importing `dzuels_database.sql` via phpMyAdmin (1-click import tab, encoding verification).
    4. Packaging and uploading the codebase to cPanel File Manager (subfolder or public_html structure).
    5. Setting up `.env` for production (`DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1` or `localhost`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://dzuelsfoundation.org`).
    6. Configuring storage symlink and folder permissions (`chmod 755` on storage & bootstrap/cache).
    7. Running production optimizations and testing admin login.
  - Done when: `docs/cpanel-deployment-guide.md` is complete, readable, formatted cleanly in Markdown, and accessible to non-technical or semi-technical hosting administrators.
- [x] **Step 4: Automated Testing & Code Style Verification**
  - Create feature test `tests/Feature/DatabaseExportCommandTest.php` with assertions:
    - Command runs successfully (`$this->artisan('db:export-sql')->assertSuccessful()`).
    - Default file `database/dumps/dzuels_database.sql` is created.
    - Custom output option `--output=custom/test_dump.sql` works properly.
    - Exported SQL file contains essential MySQL directives (`FOREIGN_KEY_CHECKS`, `utf8mb4`, `CREATE TABLE \`users\``, `INSERT INTO \`users\``, `CREATE TABLE \`settings\``, etc.).
    - Empty and populated tables are handled gracefully.
  - Format all modified/new PHP files with `php vendor/bin/pint`.
  - Run full test suite with `php artisan test` to confirm all existing and new tests pass.
  - Done when: `tests/Feature/DatabaseExportCommandTest.php` passes with 100% assertions green, Pint reports no style violations, and `php artisan test` exits with code 0.

## Files / areas

- `app/Services/DatabaseExportService.php` (new MySQL export service)
- `app/Console/Commands/ExportDatabaseSql.php` (new Artisan command `db:export-sql`)
- `docs/cpanel-deployment-guide.md` (new comprehensive cPanel migration guide)
- `tests/Feature/DatabaseExportCommandTest.php` (new test suite)
- `database/dumps/.gitignore` (keep dumps directory tracked while allowing gitignore configuration if desired)

## Data / contracts

- Command: `php artisan db:export-sql {--output= : Custom path for the exported SQL file}`
- Default dump file: `database/dumps/dzuels_database.sql`
- SQL Dialect: MySQL 5.7+ / 8.0+ & MariaDB 10.3+ compatible.
- Encoding: `utf8mb4` with collation `utf8mb4_unicode_ci`.
- Table engine: `ENGINE=InnoDB`.
- Safety headers/footers:
  ```sql
  SET FOREIGN_KEY_CHECKS=0;
  SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
  SET time_zone = "+00:00";
  ...
  SET FOREIGN_KEY_CHECKS=1;
  ```

## Testing

- Test runner: `php artisan test`
- Feature test: `tests/Feature/DatabaseExportCommandTest.php`
- Assertions to cover:
  - Command exit status 0
  - Dump file creation at default and custom paths
  - Presence of MySQL schema definitions for core tables
  - Presence of row insert data for seeded models
  - Clean error handling if invalid paths or write errors occur

## Notes for the AI

- Keep SQLite as local connection; do not switch `.env` to MySQL.
- Do not require an active MySQL server to generate the dump. The export service must inspect the existing connection schema and synthesize valid MySQL syntax without external binaries.
- Ensure all foreign keys, nullable fields, default values, and column types (strings, texts, bigints, timestamps, json) map accurately to MySQL types.
- Follow proportional engineering: write clean, robust PHP without external Composer dependencies.
- Zero AI attribution trailers in commits or code comments.


<!-- blueprint:completion {"schemaVersion":2,"specBytes":10559,"specSha256":"9d7a3dbceb44432715cb8916a9e890a65cd52601cd2d81547528dab9b9132a3f","branch":"refs/heads/feature/mysql-database-export-and-cpanel-phpmyadmin-migration-suite","head":"e9652fe7265b21ea3ed726198ac9f8743418a5c6","baseRef":"refs/heads/main","baseCommit":"e9652fe7265b21ea3ed726198ac9f8743418a5c6","sourceTree":"107499c3f9fbfeb5fb1e92a4654bffa8273f4d04","landing":"local-merge","absentOptional":[]} -->

## Manual try guide

1. **Verify Automated Database Export**:
   - Run `php artisan db:export-sql` from the terminal.
   - Verify console table output displaying 24 tables and 221 total rows dumped.
   - Confirm target file `database/dumps/dzuels_database.sql` is created/updated (size ~74 KB).

2. **Verify Custom Destination Path**:
   - Run `php artisan db:export-sql --output=database/dumps/custom_test.sql`.
   - Confirm custom destination file is created successfully.
   - Delete test file after verification.

3. **Inspect Exported MySQL DDL & DML Syntax**:
   - Open `database/dumps/dzuels_database.sql`.
   - Confirm MySQL directives: `SET FOREIGN_KEY_CHECKS=0;`, `SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";`, `START TRANSACTION;`, `SET NAMES utf8mb4;`.
   - Verify table structures: `DROP TABLE IF EXISTS `table`;`, `CREATE TABLE `table` (...) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;`.
   - Verify table data: `LOCK TABLES`, batch `INSERT INTO `table` (...) VALUES (...);`, `UNLOCK TABLES;`.
   - Confirm footer: `COMMIT;`, `SET FOREIGN_KEY_CHECKS=1;`.

4. **Verify cPanel Production Deployment Guide**:
   - Open `docs/cpanel-deployment-guide.md`.
   - Verify all 10 sections: prerequisites, export steps, MySQL Database Wizard, phpMyAdmin 1-click import, directory architecture (`dzuels_app/` and `public_html/`), production `.env` setup, storage symlinks, file permissions (`chmod 755`), launch verification, and troubleshooting FAQ.

5. **Run Pre-Launch Readiness & Test Verification**:
   - Run `php artisan app:launch-check` and verify status: **READY FOR LAUNCH (0 failures, 0 warnings)**.
   - Run `php artisan test` and confirm all 222 tests pass green.

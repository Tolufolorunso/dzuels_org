# Coding Standards

> Project conventions for Dzuels. Tuned for Laravel, PHP 8.3+, Blade,
> Tailwind CSS v4, and PHPUnit.

## PHP & Laravel

- PHP 8.3+ with explicit types for properties, method parameters, and return values
- Follow Laravel naming conventions and PSR-12, formatted with Laravel Pint
- Keep controllers focused; delegate heavy business logic to actions or service classes
- Use Form Request classes for request validation rather than inline controller validation
- Follow standard Laravel directory conventions:
  - Controllers: `app/Http/Controllers/`
  - Requests: `app/Http/Requests/`
  - Models: `app/Models/`
  - Blade templates: `resources/views/`
  - Routes: `routes/web.php` and `routes/console.php`
  - Migrations: `database/migrations/`

## Views & Templating

- Use Blade templates for server-rendered HTML (`resources/views/`)
- Extract reusable layout pieces into Blade components (`resources/views/components/`)
- Use semantic HTML elements and accessible markup
- Keep complex view logic inside view models, presenters, or controller data preparation

## Styling & Frontend Assets

- Tailwind CSS v4 for all styling
- CSS-first configuration via `@import "tailwindcss";` and `@theme` directives in `resources/css/app.css`
- No inline styles; use semantic utility combinations
- Vite manages asset building (`vite.config.js` with `laravel-vite-plugin`)

## Database & Models

- Eloquent ORM for all database operations
- SQLite default (`database/database.sqlite`), configured in `.env`
- Always use Laravel migrations (`php artisan make:migration`, `php artisan migrate`) for schema changes
- Define explicit `$fillable` or `$guarded` attributes and property type casting on Eloquent models
- Prevent N+1 query problems by using eager loading (`with(...)`) when querying relationships

## Testing

A test suite is configured with PHPUnit 12 and Laravel's test runner (`php artisan test`).
Because a `test` command is declared in `AGENTS.md`, **tests are an active gate for logic-bearing steps**,
not an optional extra; the loop verifies logic with automated test assertions alongside build checks.

- **What to test (the scope rule):** pure logic and backend behavior where a wrong answer is possible:
  validators, models, controllers, calculation routines, authorization rules.
- **What not to test:** static UI layout and design nuances. Verify those with browser evidence and screenshots.
- **The gate:** a build step that adds in-scope logic must include passing tests in the same reviewable diff.
  The project's test command (`php artisan test`) must be green before step approval, checkpoint commits, and completion.
- Feature tests live in `tests/Feature/` (HTTP endpoints, database integration).
- Unit tests live in `tests/Unit/` (isolated classes, calculations).
- Use `Illuminate\Foundation\Testing\RefreshDatabase` for database tests.
- Run tests via `php artisan test`.

## Browser Verification

For UI and integration behavior, prefer real browser evidence over reading the
code and assuming it works.

- Browser automation is separately opt-in through `/tests browser`. That setup
  reuses a compatible runner or prefers Playwright for supported projects, then
  documents the exact command as `Browser tests` in `AGENTS.md`.
- When `Browser tests` is declared, add focused coverage for stable behavioral
  done-whens when it is proportionate, and run the documented command during
  `/check`. Do not assume it proves visual fidelity, real authenticated-profile
  behavior, browser chrome, or another claim the test does not observe.
- If no Browser tests command is declared, do not add a runner silently in the
  middle of an unrelated feature. Use the available dev server, browser
  screenshots, build output, API output, or manual evidence instead.
- Browser tests are not part of the default Verify command or CI unless the user
  separately chooses that slower gate.
- Browser evidence is especially important for flows that click, type, submit,
  navigate, download files, render complex layouts, or depend on client-side
  state.

## Code Quality

- No commented-out code unless specified
- No unused imports or variables
- Keep functions under 50 lines when possible

## Comments

Write code that explains itself; comment only what the code cannot say.
Over-commenting is a common AI tell, so resist it.

- Comment the **why**, not the **what**. Delete any comment that restates the code.
- No banner/header blocks, section dividers, or step-by-step narration of obvious
  code. A file does not need a comment announcing each region.
- A comment earns its place only when it captures something the code can't: a
  non-obvious decision, a gotcha or workaround, why a value is what it is, or a
  link to a spec or issue.
- Prefer self-documenting names and small functions over explanatory comments.
- Keep doc comments minimal: a one-line purpose on an exported type or function is
  plenty; don't write JSDoc that just repeats the signature.
- When in doubt, leave the comment out.

## Writing

- No em dashes (U+2014) in generated content: docs, comments, commit messages,
  READMEs, specs. They read as AI-generated.
- Use a hyphen for `term - description` separators; rephrase prose with commas,
  parentheses, or a colon. Avoid en dashes and the ellipsis character too.

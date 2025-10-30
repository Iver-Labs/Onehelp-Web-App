# Copilot / AI agent instructions — one_help-main

These notes help an AI coding agent become productive quickly in this Laravel API repository.

1) Quick summary
- This is a Laravel (PHP 8.2, Laravel 12) backend API project (routes defined in `routes/api.php`).
- Frontend tooling uses Vite + Tailwind (see `package.json`, `resources/`).

2) Quick start (PowerShell)
```powershell
# one-time setup (composer.json "setup" script does something similar)
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php artisan migrate
npm install
npm run build

# dev mode (starts server, queue listener, vite etc.)
composer run dev

# run tests (phpunit config uses in-memory sqlite)
composer test
```

3) Big-picture architecture
- API-focused Laravel app. Public entrypoint is `public/index.php` and routes are split into `routes/web.php` (simple root JSON) and `routes/api.php` (all main resources).
- Controllers live in `app/Http/Controllers/` and use standard RESTful `apiResource` routes for: `users`, `volunteers`, `organizations`, `events`, `registrations`, `skills`, `attendances`, `notifications`, `feedbacks`, `verifications`.
- Models are in `app/Models/`. Example: `User` uses a non-standard primary key `user_id` and `public $timestamps = false` — watch for these when querying or writing migrations.
- Providers are standard; `AppServiceProvider` currently has no bootstrap logic.

4) Project-specific conventions and patterns (do not assume defaults)
- Primary key naming: `User` uses `protected $primaryKey = 'user_id'` and timestamps are disabled. Check other models' `$primaryKey` and `$timestamps` before altering queries.
- Eloquent relationships are used (e.g., `User::volunteer()`, `User::organization()`, `User::notifications()`); follow existing relationship naming and foreign key conventions in `app/Models/`.
- Routes: `routes/api.php` uses grouped imports and `Route::apiResource()` for standard CRUD. Custom routes exist (example: `POST /api/events/{event}/register`).

5) Tests and CI notes
- `phpunit.xml` is configured to run tests with an in-memory sqlite DB (no DB file needed). Use `composer test` or `php artisan test`.
- When adding DB-level changes, update migrations in `database/migrations/` and add a corresponding seeder if needed (`database/seeders/`).

6) Useful files to inspect for context
- `composer.json` — project PHP requirements and helpful scripts (see `setup` and `dev`).
- `package.json` — vite / frontend scripts.
- `routes/api.php` — canonical list of API endpoints and examples (see commented examples and `apiResource` usage).
- `app/Models/User.php` — example of nonstandard PK/timestamps and relationships.
- `phpunit.xml` — test runtime environment (sqlite in-memory, env overrides).

7) Typical tasks & examples for an AI to perform
- Add a new API endpoint: update `routes/api.php`, create controller action in `app/Http/Controllers/`, add route test in `tests/Feature/` and, if DB-backed, a migration in `database/migrations/`.
- Fix a model bug: check `$primaryKey`, `$fillable`, `$casts` and update migration if schema mismatch; run existing tests.
- Implement a custom route (example present: `/api/events/{event}/register`) — mimic pattern used in `EventRegistrationController`.

8) Where to be careful / common pitfalls
- Do NOT assume `id` is primary key — check `$primaryKey` per model.
- Some models set `$timestamps = false` — do not write code that expects `created_at/updated_at` unless present.
- Tests rely on sqlite in-memory; seeding and migrations must be compatible with sqlite for CI tests.

If anything in these notes is ambiguous or you want more examples (example controller, a failing test, or a specific workflow), tell me which area to expand and I will update this file.

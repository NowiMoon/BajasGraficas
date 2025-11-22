Project: BajasGraficas — Copilot instructions for in-repo coding agents

Summary
- Laravel 11 PHP web app (PHP 8.2) with a Vite + Tailwind/Bootstrap frontend. A small Flask microservice (resources/Python_API/app.py) is used for fuzzy normalization.

Quick dev commands (Windows / PowerShell)
- PHP backend: composer install; copy .env from .env.example; php artisan key:generate; php artisan migrate
- Frontend: npm install; npm run dev (or npm run build for production). The project also defines `composer dev` which runs concurrent server/queue/vite tasks.
- Tests: php artisan test (or vendor/bin/phpunit). See `phpunit.xml` for test settings.
- Python microservice: run the Flask app in `resources/Python_API/app.py` (install `flask` and `fuzzywuzzy` in a venv, run `python app.py`), it listens on 127.0.0.1:5000 and must be running for normalization endpoints.

Architecture notes & important files
- Backend framework: `app/` (controllers: `app/Http/Controllers`, middleware: `app/Http/Middleware`, models under `app/`).
- Routes: `routes/web.php` — role-gated routes and names used by views and controllers.
- Views: Blade templates under `resources/views` (e.g. `resources/views/formulario.blade.php`, `resources/views/auth/*`).
- Frontend build: Vite outputs manifest at `public/build/manifest.json`. Entry points: `resources/js/app.js`, `resources/sass/app.scss`.
- Python microservice: `resources/Python_API/app.py` — endpoints: `/normalizar/materia`, `/normalizar/escuela`, `/normalizar/trabajos`, and `/normalizar/datos`.

Project-specific conventions & gotchas
- Role system: `App\\Models\\User.php` defines integer constants (ADMIN=1, COORDINADOR=2, TRABAJADOR=3) and a `hasRole()` helper. Middleware files (`app/Http/Middleware/*Middleware.php`) check the `user_type` field. Note: some route checks call `Auth::user()->hasRole('admin')` (string) while middleware checks `user_type` (integer) — investigate before refactoring (potential inconsistency).
- Normalization flow: controllers call the Python microservice via Guzzle. Example: `App\\Http\\Controllers\\MateriaController::normalizar_datos()` posts to `http://127.0.0.1:5000/...` and `App\\Http\\Controllers\\RecibirJsonController::recibirJson()` orchestrates batch normalization and storage (`storage/json/...`). Keep the Python service running when testing/importing data.
- File storage: JSON intermediate files are stored using Laravel `Storage` (see `RecibirJsonController`). Tests and local imports depend on these files being present under storage/app/json.
- Packages and scripts: check `composer.json` and `package.json` for custom scripts (e.g., `composer dev` uses `concurrently` to start multiple processes). Use those scripts when available to match how original devs run the app.

How to make safe changes quickly
- Adding routes/controllers: follow existing naming and middleware; see `routes/web.php` and `app/Http/Controllers/*` for examples.
- Adding model fields: migrations live in `database/migrations`. Update migration + model (in `app/`) and run `php artisan migrate`.
- Calling the normalizer: prefer calling `App\\Http\\Controllers\\MateriaController::normalizar_datos()` or the `/normalizar/*` endpoints. Tests/mock the microservice or run the Python app locally.

Where to look first when debugging
- Backend errors: `storage/logs/laravel.log` and `Log::info()` usages (controllers use `Log::info`/`Log::error`).
- HTTP integrations: `app/Http/Controllers/MateriaController.php` (Guzzle config and error handling) and `resources/Python_API/app.py` (expected JSON shape).
- Frontend static issues: `public/build/manifest.json` and `resources/js` / `resources/sass` entry files.

Small examples (copy/paste)
- Run everything (recommended local dev):
  - composer install
  - copy `.env.example` to `.env` and set DB
  - php artisan key:generate
  - php artisan migrate
  - npm install && npm run dev
  - python -m venv .venv; .\\.venv\\Scripts\\Activate.ps1; pip install flask fuzzywuzzy
  - (in Python_API) python app.py

When in doubt
- Read `routes/web.php` and the controller used by the failing route. Follow the chain: route -> controller -> model/service -> storage.
- If working on normalization, run the Flask service locally and replay a small JSON file from `storage/app/json`.

If this file missed something or you want examples expanded, tell me which area to flesh out (dev setup, tests, normalization, or role auth) and I'll iterate.

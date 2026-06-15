# KIWI GROUP Humble Graduation Trip

Laravel full-stack website for the KIWI GROUP Humble Graduation Trip.

## Stack

- Laravel 12
- Blade
- Tailwind CSS
- Alpine.js
- MySQL / MariaDB
- Git

Laravel 12 is used as the practical Cloudways-friendly baseline. The code is structured so it can be upgraded to Laravel 13 when the target Cloudways PHP runtime supports it comfortably.

## First Version

- Public homepage at `/`
- Animated KIWI HUMBLE burger mark
- Title: `KIWI GROUP Humble Graduation Trip`
- Date: `2027/5/29`
- Frontend countdown timer
- Login at `/login`
- Admin dashboard at `/admin`
- Admin-only access using `users.is_admin`

## Local Setup

This workspace may not include PHP or Composer. On a machine with PHP 8.2+ and Composer:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

## Admin Account

Set these in `.env` before seeding:

```env
ADMIN_NAME="KIWI Admin"
ADMIN_EMAIL="your-admin-email@example.com"
ADMIN_PASSWORD="replace-with-a-long-password"
```

Then run:

```bash
php artisan migrate:fresh --seed
```

## Cloudways

See [docs/deployment-cloudways.md](docs/deployment-cloudways.md).

Important Cloudways notes:

- Web root should point to `public`.
- Do not commit `.env`.
- Keep `APP_TIMEZONE=Asia/Taipei`.
- Run `npm run build` before production deployment.

## Animation Assets

The homepage animation uses separated SVG assets in:

```text
public/assets/loader/
```

The source animation package remains in:

```text
assets/
demo/
exports/
src/
```

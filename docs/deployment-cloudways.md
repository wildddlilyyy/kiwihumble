# Cloudways Deployment

## First Deploy

1. Create a new PHP application on Cloudways.
2. Point the application web root to `public`.
3. Pull this repository into the application folder. The repository includes the
   production Vite bundle in `public/build`, so the deployed CSS and JavaScript
   match the version tested locally.
4. Create `.env` from `.env.example` and fill production values.
5. Run:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Run `npm run build` whenever frontend Blade classes, Tailwind configuration, or
assets change, then commit the updated `public/build` files before pulling the
release on Cloudways. This keeps the hashed CSS filename and Laravel's Vite
manifest in sync with the application code.

## Admin Account

Set these before running the seeder:

```env
ADMIN_NAME="KIWI Admin"
ADMIN_EMAIL="your-admin-email@example.com"
ADMIN_PASSWORD="replace-with-a-long-password"
```

## Notes

- Never commit `.env`.
- Keep `APP_TIMEZONE=Asia/Taipei`.
- The first public page is `/`.
- The admin dashboard is `/admin`.

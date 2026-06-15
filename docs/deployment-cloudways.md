# Cloudways Deployment

## First Deploy

1. Create a new PHP application on Cloudways.
2. Point the application web root to `public`.
3. Pull this repository into the application folder.
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

#!/bin/sh
set -e

# Run database migrations on every deploy
echo "==> Running database migrations..."
php artisan migrate --force

# Clear and cache config for production
echo "==> Caching config & routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

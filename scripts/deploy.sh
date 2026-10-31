#!/usr/bin/env bash
set -e

echo "Optimizing Laravel performance..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running Supabase database migrations..."
php artisan migrate --force

echo "Launching Apache and Laravel queue worker under Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
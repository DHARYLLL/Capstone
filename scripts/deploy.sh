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

echo "Launching Web Server..."
apache2-foreground
#!/bin/sh
set -e

# Run optimizations at runtime
echo "Running optimizations..."
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache..."
exec apache2-foreground

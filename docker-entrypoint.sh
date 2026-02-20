#!/bin/sh
set -e

# Run migrations first
echo "Running migrations..."
php artisan migrate --force

# Now run optimizations at runtime
echo "Running optimizations..."
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground

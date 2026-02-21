#!/bin/sh
set -e

# FORCE Laravel to use PostgreSQL and clear any old cached config
echo "Initial cleanup..."
export DB_CONNECTION=pgsql
php artisan config:clear || true
php artisan cache:clear || true

if [ -n "$APP_URL" ] && ! echo "$APP_URL" | grep -q "://"; then
    export APP_URL="https://$APP_URL"
    echo "Fixed APP_URL: $APP_URL"
fi

echo "Forcing DB_CONNECTION: $DB_CONNECTION"

# Wait for DB to wake up
echo "Waiting for database connection..."
max_retries=10
count=0
while [ $count -lt $max_retries ]; do
    if php artisan db:show > /dev/null 2>&1 || php artisan migrate:status > /dev/null 2>&1; then
        echo "Database is ready!"
        break
    fi
    echo "Database not ready yet... waiting 5s (Attempt $((count+1))/$max_retries)"
    sleep 5
    count=$((count+1))
done

php -d memory_limit=-1 artisan migrate --force

# Now run optimizations at runtime
echo "Running optimizations..."
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mark as installed if database is ready
touch /var/www/html/storage/installed

# Ensure permissions are correct for Apache after Artisan might have created files as root
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground

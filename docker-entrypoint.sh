#!/bin/sh
set -e

# Wait for database to be available
echo "Waiting for database to become available..."
for i in $(seq 1 60); do
    if php -r "new PDO('mysql:host=\$DB_HOST;dbname=\$DB_DATABASE', \$_ENV['DB_USERNAME'], \$_ENV['DB_PASSWORD']);" 2>/dev/null; then
        echo "Database is available!"
        break
    fi
    echo "Waiting... (\$i/60)"
    sleep 2
done

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force 2>&1

# Optimize Laravel
php artisan optimize:clear 2>&1

# Start the Laravel HTTP server
echo "Starting Laravel application..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-80}
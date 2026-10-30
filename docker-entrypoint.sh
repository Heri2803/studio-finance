#!/bin/bash
set -e

# Configure Apache port based on Render $PORT env variable (defaults to 80 if not set)
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf

# Ensure .env file exists from .env.example
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Setup SQLite if MySQL credentials are not provided
if [ -z "$DB_HOST" ] || [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "Configuring SQLite database..."
    export DB_CONNECTION=sqlite
    export DB_DATABASE=/var/www/html/database/database.sqlite
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
    chmod -R 775 /var/www/html/database
    chmod 666 /var/www/html/database/database.sqlite

    # Update .env to reflect sqlite
    sed -i "s/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/g" /var/www/html/.env
    sed -i "s/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/g" /var/www/html/.env
fi

# Generate APP_KEY if missing
if ! grep -q "APP_KEY=base64:" /var/www/html/.env && [ -z "$APP_KEY" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# Ensure storage directories exist and are writable
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear cached configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run database migrations and seeders
echo "Running migrations and seeders..."
php artisan migrate --force
php artisan db:seed --force

# Crucial: Ensure web server (www-data) owns database directory & sqlite file for CRUD writes
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

echo "StudioFinance is ready! Starting Apache on port $PORT..."
exec apache2-foreground

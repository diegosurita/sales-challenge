#!/usr/bin/env bash
set -e

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "No .env file found. Copying .env.example to .env..."
    cp .env.example .env
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "No APP_KEY found. Generating application key..."
    php artisan key:generate
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate

# Run seeders to populate the database with sample data
if [ "$(php artisan tinker --execute="echo \Module\Auth\Infrastructure\Persistence\Eloquent\Models\User::count();")" -eq 0 ]; then
    echo "No users found in the database. Running seeders..."
    php artisan db:seed
fi

# Build assets using Vite
echo "Building assets with Vite..."
npm install
npm run build

echo "All set! Starting the application..."
/usr/local/bin/start-container

exec "$@"
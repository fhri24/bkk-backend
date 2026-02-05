#!/bin/bash
set -e

echo "Initializing Laravel application in Docker..."

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! nc -z db 3306; do
  sleep 1
done

echo "Database is ready!"

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
  if [ -f .env.docker ]; then
    cp .env.docker .env
    echo "Created .env from .env.docker"
  else
    cp .env.example .env
    echo "Created .env from .env.example"
  fi
fi

# Generate application key if not set
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2)" ]; then
  echo "Generating application key..."
  php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed database (optional - uncomment if needed)
# echo "Seeding database..."
# php artisan db:seed

# Create storage symlink
php artisan storage:link || true

# Clear caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel application initialized successfully!"
echo "Application is running at http://localhost"

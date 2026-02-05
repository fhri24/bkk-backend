#!/bin/bash
set -e

echo "Initializing Laravel application..."

# Wait for database to be ready
echo "Waiting for database..."
MAX_TRIES=30
COUNT=0
while ! nc -z db 3306; do
  COUNT=$((COUNT+1))
  if [ $COUNT -ge $MAX_TRIES ]; then
    echo "Database connection timeout after ${MAX_TRIES} attempts"
    exit 1
  fi
  echo "Database not ready yet... waiting (${COUNT}/${MAX_TRIES})"
  sleep 2
done
echo "Database is ready!"

# Ensure proper permissions for storage and cache
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

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

# Generate application key if not set or empty
if ! grep -q "^APP_KEY=.\+" .env 2>/dev/null; then
  echo "Generating application key..."
  php artisan key:generate --force
  echo "Application key generated"
fi

# Run migrations automatically
echo "Running migrations..."
php artisan migrate --force 2>/dev/null || echo "Migrations already up to date or failed (this is normal on first run)"

# Create storage symlink
php artisan storage:link 2>/dev/null || echo "Storage link already exists"

echo "Laravel application initialized successfully!"

# Execute the CMD from Dockerfile
exec "$@"

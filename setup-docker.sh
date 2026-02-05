#!/bin/bash

# Setup script for Docker environment

set -e

echo "================================"
echo "Laravel Docker Environment Setup"
echo "================================"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "[ERROR] Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if docker-compose or podman-compose is installed
if command -v docker-compose &> /dev/null; then
    DOCKER_CMD="docker-compose"
    echo "[OK] Docker Compose found"
elif command -v podman-compose &> /dev/null; then
    DOCKER_CMD="podman-compose"
    echo "[OK] Podman Compose found"
else
    echo "[ERROR] Neither docker-compose nor podman-compose is installed."
    exit 1
fi

echo ""

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    if [ -f .env.docker ]; then
        cp .env.docker .env
        echo "[OK] .env created from .env.docker"
    else
        cp .env.example .env
        echo "[OK] .env created from .env.example"
    fi
else
    echo "[OK] .env already exists"
fi

echo ""
echo "Building Docker image..."
$DOCKER_CMD build

echo ""
echo "Starting containers..."
$DOCKER_CMD up -d

echo ""
echo "Waiting for services to be ready..."
sleep 10

echo ""
echo "Generating application key..."
$DOCKER_CMD exec app php artisan key:generate 2>/dev/null || true

echo ""
echo "Running database migrations..."
$DOCKER_CMD exec app php artisan migrate --force

echo ""
echo "Creating storage link..."
$DOCKER_CMD exec app php artisan storage:link 2>/dev/null || true

echo ""
echo "Clearing caches..."
$DOCKER_CMD exec app php artisan cache:clear
$DOCKER_CMD exec app php artisan config:clear

echo ""
echo "Setup complete!"
echo ""
echo "================================"
echo "Application is ready!"
echo "================================"
echo ""
echo "Access points:"
echo "  Web:      http://localhost"
echo "  MySQL:    localhost:3306 (user: laravel, password: secret)"
echo "  Redis:    localhost:6379"
echo ""
echo "Useful commands:"
echo "  View logs:         $DOCKER_CMD logs -f"
echo "  Run artisan:       $DOCKER_CMD exec app php artisan"
echo "  Open bash:         $DOCKER_CMD exec app bash"
echo "  Access MySQL:      $DOCKER_CMD exec db mysql -u laravel -p laravel"
echo ""
echo "For more information, see DOCKER.md"
echo ""

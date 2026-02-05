#!/bin/bash

# Docker Helper Scripts for Laravel

set -e

DOCKER_CMD=${DOCKER_CMD:-docker-compose}

case "$1" in
    "start")
        echo "Starting Docker containers..."
        $DOCKER_CMD up -d
        echo "Containers started. Waiting for services..."
        sleep 5
        echo "Services are running"
        ;;

    "stop")
        echo "Stopping Docker containers..."
        $DOCKER_CMD down
        echo "Containers stopped"
        ;;

    "restart")
        echo "Restarting Docker containers..."
        $DOCKER_CMD restart
        echo "Containers restarted"
        ;;

    "logs")
        shift
        $DOCKER_CMD logs -f "$@"
        ;;

    "exec")
        shift
        $DOCKER_CMD exec app "$@"
        ;;

    "artisan")
        shift
        $DOCKER_CMD exec app php artisan "$@"
        ;;

    "tinker")
        $DOCKER_CMD exec app php artisan tinker
        ;;

    "bash")
        $DOCKER_CMD exec app bash
        ;;

    "build")
        echo "Building Docker image..."
        $DOCKER_CMD build --no-cache .
        echo "Build complete"
        ;;

    "init")
        echo "Initializing Laravel application..."
        $DOCKER_CMD exec app php artisan key:generate 2>/dev/null || true
        $DOCKER_CMD exec app php artisan migrate --force
        $DOCKER_CMD exec app php artisan storage:link 2>/dev/null || true
        $DOCKER_CMD exec app php artisan cache:clear
        $DOCKER_CMD exec app php artisan config:cache
        echo "Initialization complete"
        ;;

    "migrate")
        $DOCKER_CMD exec app php artisan migrate "$@"
        ;;

    "seed")
        $DOCKER_CMD exec app php artisan db:seed "$@"
        ;;

    "test")
        $DOCKER_CMD exec app php artisan test "$@"
        ;;

    "fresh")
        echo "Running fresh migration..."
        $DOCKER_CMD exec app php artisan migrate:fresh --seed "$@"
        echo "Fresh migration complete"
        ;;

    "cache-clear")
        echo "Clearing all caches..."
        $DOCKER_CMD exec app php artisan cache:clear
        $DOCKER_CMD exec app php artisan config:clear
        $DOCKER_CMD exec app php artisan route:clear
        $DOCKER_CMD exec app php artisan view:clear
        echo "Caches cleared"
        ;;

    "cache-build")
        echo "Building all caches..."
        $DOCKER_CMD exec app php artisan config:cache
        $DOCKER_CMD exec app php artisan route:cache
        $DOCKER_CMD exec app php artisan view:cache
        echo "Caches built"
        ;;

    "ps")
        $DOCKER_CMD ps
        ;;

    "status")
        echo "=== Container Status ==="
        $DOCKER_CMD ps
        echo ""
        echo "=== Service Health ==="
        echo "Checking database..."
        $DOCKER_CMD exec db mysqladmin ping -h localhost >/dev/null 2>&1 && echo "[OK] Database is healthy" || echo "[FAIL] Database is not responding"
        echo "Checking app..."
        $DOCKER_CMD exec app php -v >/dev/null 2>&1 && echo "[OK] App is healthy" || echo "[FAIL] App is not responding"
        ;;

    "destroy")
        echo "WARNING: This will delete all containers and volumes!"
        read -p "Are you sure? (yes/no): " confirm
        if [ "$confirm" = "yes" ]; then
            $DOCKER_CMD down -v
            echo "All containers and volumes removed"
        else
            echo "Cancelled"
        fi
        ;;

    "help"|*)
        cat << 'EOF'
Laravel Docker Helper Script

Usage: ./docker-help.sh [command] [options]

Commands:
  start              Start all containers
  stop               Stop all containers
  restart            Restart all containers
  rebuild            Rebuild Docker image
  init               Initialize Laravel (key, migrate, cache)

  artisan [cmd]      Run artisan command (e.g., artisan migrate)
  migrate [opts]     Run database migrations
  seed [table]       Seed the database
  fresh [opts]       Run fresh migration with seeding
  tinker             Start Laravel tinker shell
  test [path]        Run tests

  bash               Open bash in app container
  exec [cmd]         Execute command in app container

  logs [service]     Show container logs (e.g., logs app, logs db)
  ps                 Show running containers
  status             Show service health status

  cache-clear        Clear all caches
  cache-build        Build all caches (config, routes, views)

  destroy            Remove all containers and volumes (CAUTION!)
  help               Show this help message

Examples:
  ./docker-help.sh start
  ./docker-help.sh artisan migrate
  ./docker-help.sh logs app
  ./docker-help.sh bash
  ./docker-help.sh test --filter=ExampleTest
  ./docker-help.sh cache-clear

Environment:
  Set DOCKER_CMD=podman-compose to use Podman instead of Docker
  Example: DOCKER_CMD=podman-compose ./docker-help.sh start

EOF
        ;;
esac

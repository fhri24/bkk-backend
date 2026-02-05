.PHONY: help start stop restart build rebuild init up down logs ps bash exec artisan tinker test fresh migrate seed \
        migrate-rollback cache-clear cache-build health-check status destroy

DC := docker-compose
EXEC := $(DC) exec -T
APP := $(EXEC) app

help: ## Show this help message
	@echo "Laravel Docker Commands"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Container Management
start: ## Start all containers
	@$(DC) up -d
	@echo "✓ Containers started"

stop: ## Stop all containers
	@$(DC) down
	@echo "✓ Containers stopped"

restart: ## Restart all containers
	@$(DC) restart
	@echo "✓ Containers restarted"

build: ## Build Docker image
	@$(DC) build

rebuild: ## Rebuild Docker image without cache
	@$(DC) build --no-cache

up: ## Start containers in foreground
	@$(DC) up

down: ## Stop and remove containers
	@$(DC) down

ps: ## Show running containers
	@$(DC) ps

logs: ## Show container logs
	@$(DC) logs -f

# Application Commands
init: ## Initialize Laravel (key, migrate, cache)
	@echo "Initializing Laravel..."
	@$(APP) php artisan key:generate 2>/dev/null || true
	@$(APP) php artisan migrate --force
	@$(APP) php artisan storage:link 2>/dev/null || true
	@$(APP) php artisan cache:clear
	@$(APP) php artisan config:cache
	@echo "✓ Initialization complete"

bash: ## Open bash in app container
	@$(EXEC) app bash

exec: ## Execute arbitrary command: make exec cmd="your-command"
	@$(APP) $(cmd)

artisan: ## Run artisan command: make artisan cmd="migrate"
	@$(APP) php artisan $(cmd)

tinker: ## Start Laravel tinker shell
	@$(EXEC) app php artisan tinker

# Database Commands
migrate: ## Run database migrations
	@$(APP) php artisan migrate

migrate-rollback: ## Rollback migrations
	@$(APP) php artisan migrate:rollback

seed: ## Seed the database: make seed table="TableSeeder"
	@$(APP) php artisan db:seed $(if $(table),--class=Database\\Seeders\\$(table))

fresh: ## Run fresh migration with seeding
	@$(APP) php artisan migrate:fresh --seed

# Testing
test: ## Run tests: make test path="tests/Feature"
	@$(APP) php artisan test $(path)

# Cache Management
cache-clear: ## Clear all caches
	@echo "Clearing caches..."
	@$(APP) php artisan cache:clear
	@$(APP) php artisan config:clear
	@$(APP) php artisan route:clear
	@$(APP) php artisan view:clear
	@echo "✓ Caches cleared"

cache-build: ## Build all caches
	@echo "Building caches..."
	@$(APP) php artisan config:cache
	@$(APP) php artisan route:cache
	@$(APP) php artisan view:cache
	@echo "✓ Caches built"

# Database Access
mysql: ## Open MySQL CLI
	@$(DC) exec db mysql -u laravel -plaravel laravel

redis: ## Open Redis CLI
	@$(DC) exec redis redis-cli

# Health & Status
status: ## Show service health status
	@echo "=== Container Status ==="
	@$(DC) ps
	@echo ""
	@echo "=== Service Health ==="
	@echo -n "Database: "
	@$(DC) exec db mysqladmin ping -h localhost >/dev/null 2>&1 && echo "✓ healthy" || echo "✗ not responding"
	@echo -n "Redis: "
	@$(DC) exec redis redis-cli ping >/dev/null 2>&1 && echo "✓ healthy" || echo "✗ not responding"
	@echo -n "App: "
	@$(DC) exec app php -v >/dev/null 2>&1 && echo "✓ healthy" || echo "✗ not responding"

health-check: status ## Alias for status

# Cleanup
destroy: ## Remove all containers and volumes (DESTRUCTIVE!)
	@echo "⚠️  WARNING: This will delete all containers and volumes!"
	@read -p "Are you sure? (type 'yes' to confirm): " confirm && \
	if [ "$$confirm" = "yes" ]; then \
		$(DC) down -v && echo "✓ All containers and volumes removed"; \
	else \
		echo "Cancelled"; \
	fi

clean: stop ## Stop and remove containers (keep volumes)
	@echo "✓ Containers removed"

# Development Utilities
watch-logs: ## Watch logs in real-time: make watch-logs service="app"
	@$(DC) logs -f $(service)

composer-install: ## Install Composer dependencies
	@$(APP) composer install

composer-update: ## Update Composer dependencies
	@$(APP) composer update

npm-install: ## Install NPM dependencies
	@$(APP) npm install

npm-build: ## Build frontend assets
	@$(APP) npm run build

npm-dev: ## Watch frontend assets in development
	@$(DC) exec app npm run dev

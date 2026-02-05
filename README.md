# BKK Backend

Laravel 12 backend application with Docker/Podman support.

## Requirements

- PHP 8.2+ (Docker images use PHP 8.3)
- Docker 20.10+ and Docker Compose 2.0+, OR
- Podman 4.0+ and Podman Compose 1.0+
- Git

## Quick Start

### Using Docker

```bash
# Copy environment file
cp .env.docker .env

# Start containers
docker-compose up -d

# Initialize application
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate

# Access application
open http://localhost
```

### Using Podman

```bash
# Copy environment file
cp .env.docker .env

# Start containers
podman-compose up -d

# Initialize application
podman-compose exec app php artisan key:generate
podman-compose exec app php artisan migrate

# Access application
open http://localhost
```

### Automated Setup

Run the setup script for automatic initialization:

```bash
./setup-docker.sh
```

## Services

| Service | Port | Description |
|---------|------|-------------|
| Nginx | 80 | Web server |
| PHP-FPM | 9000 | Application runtime |
| MySQL | 3306 | Database |

## Environment Configuration

The project includes environment templates:

- `.env.docker` - Development configuration with Docker service names
- `.env.production.example` - Production configuration template

Key environment variables:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Development Commands

### Using Make

```bash
make start              # Start containers
make stop               # Stop containers
make bash               # Access application shell
make artisan cmd="..."  # Run artisan command
make migrate            # Run migrations
make test               # Run tests
make logs               # View logs
```

### Using Docker Compose

```bash
# Container management
docker-compose up -d
docker-compose down
docker-compose ps
docker-compose logs -f

# Application commands
docker-compose exec app bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan test

# Database access
docker-compose exec db mysql -u laravel -p laravel
```

### Using Podman Compose

Replace `docker-compose` with `podman-compose` in all commands above.

## Database

### MySQL Access

```bash
# Using Docker
docker-compose exec db mysql -u laravel -p

# Using Podman
podman-compose exec db mysql -u laravel -p
```

Default password: `secret`

### Migrations

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback

# Fresh migration with seeding
docker-compose exec app php artisan migrate:fresh --seed
```

## Testing

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test
docker-compose exec app php artisan test --filter=TestName

# Using make
make test
```

## Debugging

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db

# Using make
make logs
```

### Access Container Shell

```bash
# Application container
docker-compose exec app bash

# Run Laravel Tinker
docker-compose exec app php artisan tinker

# Using make
make bash
make tinker
```

### Check Service Health

```bash
# Container status
docker-compose ps

# Health check
make status
```

## Production Deployment

Use the production compose file:

```bash
docker-compose -f docker-compose.prod.yml up -d
```

Before deploying:

1. Copy and configure `.env.production.example` to `.env`
2. Set strong passwords for all services
3. Generate APP_KEY: `docker-compose exec app php artisan key:generate`
4. Configure SSL certificates in `docker/nginx/ssl/`
5. Update `docker/nginx/conf.d/default.conf` with your domain
6. Run migrations: `docker-compose exec app php artisan migrate --force`
7. Cache configuration: `docker-compose exec app php artisan config:cache`

See `DEPLOYMENT.md` for complete production setup guide.

## Project Structure

```
.
├── app/                    # Application code
├── config/                 # Configuration files
├── database/               # Migrations and seeders
├── docker/                 # Docker configuration files
│   ├── nginx/             # Nginx configuration
│   ├── php/               # PHP configuration
│   ├── mysql/             # MySQL configuration
│   └── supervisor/        # Process manager
├── public/                # Web root
├── resources/             # Views and assets
├── routes/                # Route definitions
├── storage/               # File storage
├── tests/                 # Test files
├── Dockerfile             # Development Docker image
├── Dockerfile.prod        # Production Docker image
├── docker-compose.yml     # Development services
└── docker-compose.prod.yml # Production services
```

## Cache Management

```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Build caches (production)
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Using make
make cache-clear
make cache-build
```

## Troubleshooting

### Port Already in Use

Edit `docker-compose.yml` and change the port mapping:

```yaml
nginx:
  ports:
    - "8080:80"
```

### Permission Errors

```bash
docker-compose exec app chown -R www-data:www-data /app/storage
docker-compose exec app chmod -R 775 /app/storage /app/bootstrap/cache
```

### Database Connection Failed

Wait for database to be ready:

```bash
sleep 10
docker-compose exec app php artisan migrate
```

### Reset Everything

```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

## Additional Documentation

- `QUICKSTART.md` - Quick setup guide
- `DOCKER.md` - Complete Docker documentation
- `DEPLOYMENT.md` - Production deployment guide
- `PODMAN.md` - Podman-specific setup
- `MAKEFILE_REFERENCE.md` - All make commands

## Support

For issues or questions, check the documentation files or review container logs:

```bash
docker-compose logs -f
```

## License

This project is licensed under the MIT License.

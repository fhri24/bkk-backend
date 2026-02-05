# Stage 1: Build stage
FROM php:8.3-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    curl \
    git \
    libzip-dev \
    sqlite-dev \
    mysql-client \
    postgresql-client \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    zip \
    bcmath \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies and build assets
RUN npm install && npm run build

# Copy application code
COPY . .

# Generate application key if not exists
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Create necessary directories with proper permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /app

# Stage 2: Runtime stage
FROM php:8.3-fpm-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    curl \
    git \
    libzip \
    sqlite-libs \
    mysql-client \
    postgresql-client \
    supervisor \
    bash

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    zip \
    bcmath \
    opcache

# Install opcache configuration
RUN docker-php-ext-enable opcache
COPY --chown=www-data:www-data docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY --chown=www-data:www-data docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application from builder
COPY --from=builder --chown=www-data:www-data /app /app

# Copy PHP-FPM configuration
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/conf.d/ /etc/supervisor/conf.d/

# Create necessary directories
RUN mkdir -p /var/log/supervisor \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:9000/ping || exit 1

# Run PHP-FPM
CMD ["/usr/sbin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

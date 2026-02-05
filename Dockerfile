# Single stage Dockerfile for Laravel 12 development
FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions in one layer
RUN apk add --no-cache \
    curl \
    git \
    libzip \
    libzip-dev \
    mysql-client \
    bash \
    nodejs \
    npm \
    build-base \
    netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql zip bcmath opcache \
    && docker-php-ext-enable opcache \
    && apk del --no-cache build-base libzip-dev \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP configuration
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy PHP-FPM configuration
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /app

# Copy application
COPY --chown=www-data:www-data . .

# Install dependencies during build (will be overridden by volume mount in dev)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build \
    && mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 777 storage bootstrap/cache

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port
EXPOSE 9000

# Use entrypoint to handle initialization
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Run PHP-FPM
CMD ["php-fpm"]

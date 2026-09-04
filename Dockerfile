FROM php:8.2-fpm-alpine AS laravel-build

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    bash \
    openssl \
    ca-certificates \
    zip \
    unzip \
    gnupg \
    linux-headers \
    libpng-dev \
    libjpeg-dev \
    freetype-dev \
    oniguriri-dev \
    libxml2-dev \
    icu-dev \
    argon2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install dependencies (production)
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && rm -rf /root/.composer/cache

# Generate application key if not present
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# ---------------------------------------------------------
# Production stage - non-root user
# ---------------------------------------------------------
FROM php:8.2-fpm-alpine AS laravel-production

# Install system dependencies (leaner)
RUN apk add --no-cache \
    openssl \
    ca-certificates \
    zip \
    unzip \
    git \
    bash

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Create non-root user
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

# Copy compiled application from build stage
COPY --from=laravel-build /var/www /var/www

# Set working directory
WORKDIR /var/www

# Copy .env (production values expected)
COPY .env /var/www/.env

# Fix ownership for non-root user
RUN chown -R appuser:appgroup /var/www/storage /var/www/bootstrap/cache

# PHP-FPM production tuning
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

# Expose port 9000 and start FPM
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -f http://localhost:9000/status 2>/dev/null || exit 1

USER appuser

CMD ["php-fpm"]
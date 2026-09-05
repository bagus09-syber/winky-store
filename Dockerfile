FROM php:8.2-fpm-alpine

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
    libpng-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    zlib-dev \
    argon2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application code (without .env - DockHosting provides env vars at runtime)
COPY . /var/www

# Install dependencies (production only)
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && rm -rf /root/.composer/cache

# Create non-root user
RUN addgroup -S appgroup && adduser -S appuser -G appgroup \
    && chown -R appuser:appgroup /var/www/storage /var/www/bootstrap/cache

# Expose port for DockHosting (environment variable PORT will override default 80)
EXPOSE 80

# Health check HTTP endpoint
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -f http://localhost:${PORT:-80}/status 2>/dev/null || exit 1 \
    || exit 1

# Run Laravel HTTP server
# PORT from DockHosting environment variable, fallback to 80
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-80}
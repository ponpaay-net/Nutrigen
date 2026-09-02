# ===== Stage 1: Composer deps =====
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --ignore-platform-reqs

# ===== Stage 2: Node assets =====
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ===== Stage 3: Runtime — PHP 8.3 CLI =====
FROM php:8.3-cli-alpine AS app

# System + PHP extensions needed by Laravel (dikirim via composer.json ext-*)
RUN apk add --no-cache git unzip icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql bcmath intl mbstring zip

COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
COPY . .

# Prepare Laravel runtime
RUN php artisan storage:link \
    && composer dump-autoload --optimize \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# Migrate (idempotent, --force non-interactive) lalu jalankan server
CMD ["sh", "-c", "php artisan migrate --force --no-interaction || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]

# ===== Stage 1: Frontend assets (npm build) =====
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ===== Stage 2: Runtime — PHP 8.3 CLI =====
FROM php:8.3-cli-alpine
WORKDIR /app

# System deps + PHP extensions yang dibutuhkan Laravel
RUN apk add --no-cache git unzip icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql bcmath intl mbstring zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Source app (vendor & public/build di-exclude via .dockerignore)
COPY . .

# Frontend hasil build (dari stage assets)
COPY --from=assets /app/public/build ./public/build

# Pasang dependensi PHP + generate autoload + link storage
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && php artisan storage:link \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# Migrate (idempotent) lalu start server pada $PORT yang diinject Railway
CMD ["sh", "-c", "php artisan migrate --force --no-interaction || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]

# Stage 1: PHP-FPM
FROM php:8.2-fpm AS php

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    zip unzip git curl libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Stage 2: Nginx + PHP-FPM
FROM nginx:alpine

COPY --from=php /var/www/html /var/www/html
COPY --from=php /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

# Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Script de inicio combinado
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080
CMD ["/start.sh"]

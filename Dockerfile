FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    zip unzip git curl libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Configurar PHP-FPM para Render
RUN echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.d/zz-render.conf
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-render.conf

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Script de inicio
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080
CMD ["/start.sh"]

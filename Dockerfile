FROM php:8.2-fpm

# 1. Instalar Nginx y dependencias
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 3. Configurar directorios
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/nginx \
    && touch /var/log/nginx/access.log \
    /var/log/nginx/error.log \
    && chown -R www-data:www-data /var/log/nginx

WORKDIR /var/www/html

# 4. Copiar aplicación
COPY . .

# 5. Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 6. Configuración PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# 7. Configuración Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 8. Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "php artisan optimize && php-fpm -D && nginx -g 'daemon off;'"]

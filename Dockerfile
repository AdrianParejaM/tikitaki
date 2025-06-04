FROM php:8.2-fpm

# 1. Instalar dependencias
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configurar directorios
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/nginx \
    && touch /var/log/nginx/access.log \
    /var/log/nginx/error.log

# 3. Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Limpieza radical inicial
RUN rm -rf /var/www/html/*

# 5. Copiar solo lo necesario para composer
COPY composer.json composer.lock ./

# 6. Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. Copiar el resto de la aplicación
COPY . .

# 8. Limpieza exhaustiva post-copia
RUN rm -rf bootstrap/cache/* \
    && rm -rf storage/framework/cache/* \
    && rm -rf storage/framework/views/* \
    && rm -rf storage/framework/sessions/*

# 9. Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 10. Configurar Nginx y PHP-FPM
COPY docker/nginx.conf /etc/nginx/nginx.conf
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

EXPOSE 8080

# 11. Comando de inicio seguro
CMD ["sh", "-c", "composer dump-autoload && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan config:cache && php artisan route:cache && php-fpm -D && nginx -g 'daemon off;'"]

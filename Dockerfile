# Stage 1 - Builder para dependencias
FROM php:8.2-cli as builder

WORKDIR /var/www/html

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copiar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Stage 2 - Imagen final
FROM php:8.2-fpm

# Instalar Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiar aplicación
COPY --from=builder /var/www/html /var/www/html

# Configuración de PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# Copiar configuración Nginx
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/ && \
    rm -f /etc/nginx/sites-enabled/default

# Permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Configurar logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

CMD ["sh", "-c", "php artisan optimize && php-fpm && nginx -g 'daemon off;'"]

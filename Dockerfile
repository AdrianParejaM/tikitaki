# Stage 1 - Builder para dependencias
FROM php:8.2-cli as builder

WORKDIR /var/www/html

# 1. Instalar dependencias
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 3. Copiar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 4. Copiar aplicación
COPY . .

# Stage 2 - Imagen final
FROM php:8.2-fpm

# 5. Instalar Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    && mkdir -p /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/log/nginx \
    && touch /var/log/nginx/access.log \
    /var/log/nginx/error.log \
    && chown -R www-data:www-data /var/log/nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 6. Copiar aplicación
COPY --from=builder /var/www/html /var/www/html

# 7. Configuración PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# 8. Configuración Nginx (versión simplificada)
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# 9. Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log && php-fpm -D && nginx -g 'daemon off;'"]

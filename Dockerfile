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

# 3. Copiar solo lo necesario para composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 4. Copiar el resto de la aplicación
COPY . .

# Stage 2 - Imagen final
FROM php:8.2-fpm

# 5. Instalar Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 6. Copiar aplicación desde el builder
COPY --from=builder /var/www/html /var/www/html

# 7. Configuración de PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# 8. Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# 9. Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 10. Configurar logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# 11. Comando de inicio CORREGIDO (sin artisan optimize)
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]

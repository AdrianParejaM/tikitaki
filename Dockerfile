# Stage 1 - Builder para dependencias
FROM php:8.2-cli as builder

WORKDIR /var/www/html

# 1. Instalar dependencias del sistema
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

# Stage 2 - Imagen final
FROM php:8.2-fpm

# 4. Instalar Nginx y crear estructura de directorios
RUN apt-get update && apt-get install -y \
    nginx \
    && mkdir -p /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /etc/nginx/sites-available \
    /etc/nginx/sites-enabled \
    && rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 5. Copiar aplicación desde el builder
COPY --from=builder /var/www/html /var/www/html

# 6. Configuración de PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# 7. Configuración de Nginx (versión simplificada)
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 8. Configurar permisos (versión robusta)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 9. Configurar logs
RUN touch /var/log/nginx/access.log \
    /var/log/nginx/error.log \
    && chown -R www-data:www-data /var/log/nginx \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# 10. Comando de inicio
CMD ["sh", "-c", "php-fpm && nginx -g 'daemon off;'"]

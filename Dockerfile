# Usar imagen base de PHP con FPM
FROM php:8.2-fpm

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configurar directorios básicos
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/nginx \
    && touch /var/log/nginx/access.log \
    /var/log/nginx/error.log

# 3. Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copiar solo lo necesario para composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 5. Copiar el resto de la aplicación
COPY . .

# 6. Configuración de PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# 7. Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# 8. Configurar permisos (sin ejecutar comandos artisan durante el build)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && rm -rf bootstrap/cache/*

# 9. Puerto expuesto
EXPOSE 8080

# 10. Script de inicio
COPY docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
CMD ["start"]

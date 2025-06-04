# Stage 1 - Instalación de dependencias
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

# 3. Copiar solo lo necesario para composer (optimización de caché)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Stage 2 - Imagen final de producción
FROM php:8.2-fpm

# 4. Instalar Nginx y dependencias mínimas
RUN apt-get update && apt-get install -y \
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 5. Copiar aplicación desde el builder
COPY --from=builder /var/www/html /var/www/html
COPY --from=builder /usr/bin/composer /usr/bin/composer

# 6. Configuración de PHP-FPM para Render
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.start_servers = 4" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_spare_servers = 6" >> /usr/local/etc/php-fpm.d/zz-render.conf

# 7. Configuración de Nginx (archivo externo)
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/ && \
    rm -f /etc/nginx/sites-enabled/default.conf

# 8. Configurar permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# 9. Configurar logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# 10. Comando de inicio optimizado
CMD sh -c "php artisan optimize:clear && php artisan optimize && php-fpm && nginx -g 'daemon off;'"

# Dockerfile optimizado para Laravel + Nginx en Render
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    zip unzip git curl libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar PHP-FPM para Render
RUN echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_children = 50" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.start_servers = 5" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.min_spare_servers = 5" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_spare_servers = 10" >> /usr/local/etc/php-fpm.d/zz-render.conf

# Configuración de supervisor
COPY docker/supervisor.conf /etc/supervisor/conf.d/laravel.conf

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configurar el entorno
WORKDIR /var/www/html

# Copiar solo lo necesario para composer (optimización de caché Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copiar el resto de la aplicación
COPY . .

# Configurar permisos y optimizar
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache && \
    php artisan optimize:clear && \
    php artisan optimize && \
    mkdir -p /var/log/nginx /var/log/supervisor && \
    touch /var/log/nginx/access.log /var/log/nginx/error.log /var/log/supervisor/supervisord.log && \
    chown -R www-data:www-data /var/log/nginx /var/log/supervisor && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    ln -sf /dev/stdout /var/log/supervisor/supervisord.log

# Configuración de Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default && \
    rm /etc/nginx/sites-enabled/default.conf && \
    echo "daemon off;" >> /etc/nginx/nginx.conf

# Puerto expuesto
EXPOSE 8080

# Comando de inicio usando supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]

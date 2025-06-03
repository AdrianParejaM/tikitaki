FROM php:8.2-fpm

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev \
    zip unzip git curl libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configurar PHP-FPM para Render
RUN echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-render.conf

# 3. Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 4. Configurar el entorno
WORKDIR /var/www/html

# 5. Copiar solo lo necesario para composer (optimización de caché)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 6. Copiar el resto de la aplicación
COPY . .

# 7. Configurar permisos (sin ejecutar artisan durante el build)
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# 8. Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default && \
    rm -f /etc/nginx/sites-enabled/default.conf && \
    echo "daemon off;" >> /etc/nginx/nginx.conf

# 9. Configurar logs
RUN mkdir -p /var/log/nginx && \
    touch /var/log/nginx/access.log /var/log/nginx/error.log && \
    chown -R www-data:www-data /var/log/nginx && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# 10. Comando de inicio optimizado
CMD sh -c "php artisan optimize && php-fpm -D && nginx -g 'daemon off;'"

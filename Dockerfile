# Usamos la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar PHP-FPM para Render
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.start_servers = 4" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/zz-render.conf && \
    echo "pm.max_spare_servers = 6" >> /usr/local/etc/php-fpm.d/zz-render.conf

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configurar el entorno de trabajo
WORKDIR /var/www/html

# Copiar solo lo necesario para composer (optimización de caché)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copiar el resto de la aplicación
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Configuración simplificada de Nginx
RUN rm /etc/nginx/sites-enabled/default && \
    echo "\
server { \n\
    listen 8080; \n\
    server_name _; \n\
    root /var/www/html/public; \n\
    \n\
    index index.php index.html; \n\
    \n\
    location / { \n\
        try_files \$uri \$uri/ /index.php?\$query_string; \n\
    } \n\
    \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_index index.php; \n\
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \n\
        include fastcgi_params; \n\
    } \n\
    \n\
    location ~ /\.ht { \n\
        deny all; \n\
    } \n\
}" > /etc/nginx/sites-available/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Configurar logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# Script de inicio optimizado
CMD sh -c "php artisan optimize && php-fpm && nginx -g 'daemon off;'"

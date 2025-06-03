# Stage 1 - Instalación de dependencias
FROM php:8.2-cli as builder

WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copiar solo lo necesario para composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Stage 2 - Construcción de la aplicación
FROM builder as app

# Copiar el resto de la aplicación
COPY . .

# Limpieza y optimización (sin usar artisan durante el build)
RUN rm -rf bootstrap/cache/*.php && \
    composer dump-autoload --optimize

# Stage 3 - Imagen final de producción
FROM php:8.2-fpm

# Instalar dependencias mínimas
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar desde el builder
COPY --from=app /var/www/html /var/www/html

# Configurar PHP-FPM
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

# Configuración mínima de Nginx
RUN echo "\
server { \
    listen 8080; \
    server_name _; \
    root /var/www/html/public; \
    index index.php index.html; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}" > /etc/nginx/sites-available/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Configurar logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 8080

# Comando de inicio optimizado (las optimizaciones se hacen en runtime)
CMD sh -c "php artisan optimize:clear && php artisan optimize && php-fpm && nginx -g 'daemon off;'"

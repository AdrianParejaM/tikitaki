# Usar imagen base oficial de PHP 8.2 con FPM
FROM php:8.2-fpm

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Configurar directorios básicos
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/nginx \
    && touch /var/log/nginx/access.log \
    /var/log/nginx/error.log

# 3. Configurar Composer (ejecutando como usuario no-root)
RUN adduser --disabled-password --gecos '' deployuser \
    && mkdir -p /home/deployuser/.composer \
    && chown -R deployuser:deployuser /home/deployuser

# 4. Instalar Composer como usuario no-root
USER deployuser
COPY --from=composer:2.5 /usr/bin/composer /usr/local/bin/composer
USER root

WORKDIR /var/www/html

# 5. Limpieza radical inicial
RUN rm -rf /var/www/html/* \
    && rm -rf /tmp/*

# 6. Copiar solo lo necesario para composer
COPY --chown=deployuser:deployuser composer.json composer.lock ./

# 7. Instalar dependencias como usuario no-root
USER deployuser
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-plugins
USER root

# 8. Copiar el resto de la aplicación
COPY --chown=deployuser:deployuser . .

# 9. Limpieza exhaustiva post-copia
RUN rm -rf bootstrap/cache/* \
    && rm -rf storage/framework/cache/* \
    && rm -rf storage/framework/views/* \
    && rm -rf storage/framework/sessions/*

# 10. Configurar permisos
RUN chown -R deployuser:deployuser /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 11. Configurar Nginx y PHP-FPM
COPY docker/nginx.conf /etc/nginx/nginx.conf
RUN echo "listen = 9000" > /usr/local/etc/php-fpm.d/zz-render.conf

EXPOSE 8080

# 12. Script de inicio seguro
COPY --chown=deployuser:deployuser docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
CMD ["start"]

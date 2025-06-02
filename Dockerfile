# Dockerfile para Laravel en producción
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instalar Composer (versión específica para estabilidad)
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar solo lo necesario para composer (optimización de capas Docker)
COPY composer.json composer.lock ./

# Instalar dependencias PHP (sin dev y optimizando autoloader)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copiar el resto de la aplicación
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto y comando
EXPOSE 9000
CMD ["php-fpm"]

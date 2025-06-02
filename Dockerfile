# Dockerfile para Laravel
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
    libmcrypt-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
WORKDIR /var/www
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Generar clave
RUN php artisan key:generate

# Puerto y comando
EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=8080

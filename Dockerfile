FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git libonig-dev libpng-dev libxml2-dev libcurl4-openssl-dev libssl-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd soap curl

RUN a2enmod rewrite

COPY . /var/www/html

WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

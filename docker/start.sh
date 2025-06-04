#!/bin/bash

# Limpiar cachés
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reconstruir cachés para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar servicios
php-fpm -D
nginx -g 'daemon off;'

#!/bin/bash

# 1. Limpieza total de cachés
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# 2. Reconstruir autoloader
composer dump-autoload --optimize

# 3. Limpiar cachés Laravel
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Reconstruir cachés optimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Iniciar servicios
php-fpm -D
nginx -g 'daemon off;'

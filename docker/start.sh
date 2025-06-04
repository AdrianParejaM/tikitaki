#!/bin/bash

# Limpieza radical de caché
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# Reconstruir autoloader
composer dump-autoload

# Configuración inicial
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones
php artisan migrate --force

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar servicios
php-fpm -D
nginx -g 'daemon off;'

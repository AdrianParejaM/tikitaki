#!/bin/bash

# Esperar a que la base de datos esté lista (solo para PostgreSQL)
while ! pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -d $DB_DATABASE -t 1; do
    sleep 1
done

# Ejecutar migraciones
php artisan migrate --force

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reconstruir cachés
php artisan config:cache
php artisan route:cache

# Iniciar servicios
php-fpm -D
nginx -g 'daemon off;'

#!/bin/sh
# Iniciar PHP-FPM en modo foreground
php-fpm -D -R
# Iniciar Nginx en modo foreground
nginx -g "daemon off;"

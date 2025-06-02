#!/bin/sh
# Inicia PHP-FPM en segundo plano
php-fpm -D
# Inicia Nginx en primer plano
nginx -g 'daemon off;'

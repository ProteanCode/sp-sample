#!/bin/sh

chown -R www-data:www-data /var/www/html/bootstrap/cache;
chown -R www-data:www-data /var/www/html/storage;

php-fpm;

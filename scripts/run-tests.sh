#!/bin/sh

cd /var/www/html;

php artisan migrate:refresh --seed --env=testing
php artisan test $@

FROM php:8.2-fpm-alpine3.19

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS

# https://github.com/composer/docker/issues/30
RUN apk add unzip

RUN pecl install pcov && docker-php-ext-enable pcov

# COMPOSER INSTALLATION
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk del -f .build-deps

COPY ./entrypoint.sh /entrypoint.sh

EXPOSE 9003

ENTRYPOINT /entrypoint.sh


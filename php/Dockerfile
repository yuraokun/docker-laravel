FROM php:8.0-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql && \
    apk add --update nodejs npm 

COPY --from=composer /usr/bin/composer /usr/bin/composer
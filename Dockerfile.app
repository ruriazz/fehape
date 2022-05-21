FROM php:7.4-fpm-alpine3.15

RUN apk update && apk add bash

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY ./src /var/www

EXPOSE 9000
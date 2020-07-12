FROM php:7.2-cli-alpine

MAINTAINER jamiea31@gmail.com

RUN apk update && apk upgrade
RUN apk add \
    autoconf \
    g++ \
    make \
    unzip \
    libsodium \
    libsodium-dev \
    libzip-dev
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install \
    bcmath \
    pdo \
    pdo_mysql \
    sodium \
    zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /app
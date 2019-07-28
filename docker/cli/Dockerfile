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

ARG USERNAME_LOCAL
ARG UID_LOCAL
ARG GID_LOCAL
RUN addgroup --gid $GID_LOCAL $USERNAME_LOCAL
RUN adduser --ingroup $USERNAME_LOCAL --no-create-home --disabled-password --uid $UID_LOCAL $USERNAME_LOCAL

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /app
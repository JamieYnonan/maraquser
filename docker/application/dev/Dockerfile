ARG IMAGE
FROM $IMAGE

MAINTAINER Jamie Ynonan <jamiea31@gmail.com>

RUN apk update && apk upgrade
RUN apk add --no-cache $PHPIZE_DEPS
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ARG XDEBUG_PORT
ARG XDEBUG_IDEKEY
ARG XDEBUG_HOST
RUN echo "xdebug.remote_enable=1" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"
RUN echo "xdebug.remote_port=$XDEBUG_PORT" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"
RUN echo "xdebug.remote_host=$XDEBUG_HOST" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"
RUN echo "xdebug.idekey=$XDEBUG_IDEKEY" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"

FROM php:7.2-fpm-alpine

MAINTAINER Jamie Ynonan <jamiea31@gmail.com>

RUN apk update && apk upgrade
RUN apk add nginx
RUN docker-php-ext-install bcmath json mbstring pdo pdo_mysql

ARG TZ
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ADD config/www.conf /usr/local/etc/php-fpm.d/www.conf
ADD config/nginx.conf /etc/nginx/nginx.conf

ADD config/*.sh /tmp
RUN chmod +x /tmp/runservices.sh

RUN mkdir -p /app
WORKDIR /app

EXPOSE 80

ENTRYPOINT /tmp/runservices.sh
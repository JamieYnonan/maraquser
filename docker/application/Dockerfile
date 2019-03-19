FROM php:7.2-fpm

MAINTAINER Jamie Ynonan <jamiea31@gmail.com>

RUN apt-get update
RUN apt-get install -y nginx
RUN docker-php-ext-install bcmath json mbstring pdo pdo_mysql

ARG USERNAME_LOCAL
ARG UID_LOCAL
ARG GID_LOCAL
RUN groupadd -g $GID_LOCAL $USERNAME_LOCAL
RUN useradd -u $UID_LOCAL -g $GID_LOCAL $USERNAME_LOCAL

ENV TZ=America/Lima
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ADD config/www.conf /usr/local/etc/php-fpm.d/www.conf
ADD config/nginx.conf /etc/nginx/nginx.conf

ADD config/*.sh /tmp
RUN chmod +x /tmp/runservices.sh

RUN mkdir -p /app
WORKDIR /app

EXPOSE 80

ENTRYPOINT /tmp/runservices.sh
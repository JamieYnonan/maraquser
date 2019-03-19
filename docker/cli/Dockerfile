FROM php:7.2-cli

MAINTAINER jamiea31@gmail.com

RUN apt-get update
RUN apt-get install -y unzip
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install bcmath pdo pdo_mysql

ARG USERNAME_LOCAL
ARG UID_LOCAL
ARG GID_LOCAL
RUN groupadd -g $GID_LOCAL $USERNAME_LOCAL
RUN useradd -u $UID_LOCAL -g $GID_LOCAL $USERNAME_LOCAL

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /app/
FROM php:7.4-fpm
WORKDIR "/var/www/"

ENV PHP_INI /usr/local/etc/php/php.ini

RUN apt-get update \
#    && apt-get install -y \
#            libzip-dev \
#            zip \
#    && docker-php-ext-install zip mbstring pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY php.ini ${PHP_INI}

EXPOSE 9000
CMD ["php-fpm", "-F"]

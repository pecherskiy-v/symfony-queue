FROM php:7.4-fpm
WORKDIR "/var/www/"

ENV PHP_INI /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get install -y \
        apt-utils \
        curl \
        pkg-config \
        icu-devtools \
        libicu-dev \
        libcurl4 \
        libcurl4-gnutls-dev \
        libssl-dev \
        libonig-dev \

    && docker-php-ext-install bcmath \
    && docker-php-ext-install mbstring \
    && docker-php-source delete \

    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY php.ini ${PHP_INI}

EXPOSE 9000
CMD ["php-fpm", "-F"]

FROM php:7.3-fpm-alpine
MAINTAINER Damien Carcel <damien.carcel@gmail.com>

# Install needed PHP extensions and related libraries
RUN apk add --no-cache \
        icu            \
        libintl        \
        libzip         \
        zlib           \
    && apk add --no-cache --virtual .build-deps \
       icu-dev        \
       libzip-dev     \
       zlib-dev       \
       $PHPIZE_DEPS   \
    && docker-php-ext-install -j$(nproc) \
        intl                             \
        opcache                          \
        pdo                              \
        pdo_mysql                        \
        zip                              \
    && pecl install apcu && docker-php-ext-enable apcu \
    && apk del .build-deps

# Configure PHPH
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY files/app.ini $PHP_INI_DIR/conf.d/

# Create volumes
RUN mkdir -p /srv/app/var && chmod 777 /srv/app/var

VOLUME /srv/app/var
VOLUME /srv/app

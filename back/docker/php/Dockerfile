FROM php:7.3-alpine
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
    && pecl install apcu xdebug-2.7.0beta1 && docker-php-ext-enable apcu xdebug \
    && apk del .build-deps

# Configure PHPH
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY files/app.ini $PHP_INI_DIR/conf.d/
COPY files/xdebug.ini $PHP_INI_DIR/conf.d/

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

# Expose port for PHP internal server
EXPOSE 8000

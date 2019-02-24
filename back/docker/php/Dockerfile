FROM php:7.3-alpine
MAINTAINER Damien Carcel <damien.carcel@gmail.com>

# Install needed PHP extensions and related libraries
RUN apk add --no-cache \
        icu            \
        git            \
        libintl        \
        libzip         \
        zlib           \
    && apk add --no-cache --virtual .build-deps \
       icu-dev                                  \
       libzip-dev                               \
       zlib-dev                                 \
       $PHPIZE_DEPS                             \
    && docker-php-ext-install -j$(nproc) \
        intl                             \
        opcache                          \
        pdo                              \
        pdo_mysql                        \
        zip                              \
    && pecl install xdebug-2.7.0RC2      \
    && docker-php-ext-enable xdebug      \
    && apk del .build-deps

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY files/app.ini $PHP_INI_DIR/conf.d/

# Configure XDEBUG
COPY files/xdebug.ini $PHP_INI_DIR/conf.d/

# Make XDEBUG activable at container start
COPY files/docker-php-entrypoint /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-entrypoint && chmod 666 /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

# Expose port for PHP internal server
EXPOSE 8000

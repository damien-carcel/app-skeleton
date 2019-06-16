#####################################
# PHP CLI image use for development #
#####################################

ARG BASE_IMAGE

FROM ${BASE_IMAGE} as base

# Install needed PHP extensions and related libraries
RUN apk add --no-cache                          \
        icu                                     \
        libintl                                 \
        libzip                                  \
        zlib                                    \
    && apk add --no-cache --virtual .build-deps \
       icu-dev                                  \
       libzip-dev                               \
       zlib-dev                                 \
       $PHPIZE_DEPS                             \
    && docker-php-ext-install -j$(nproc)        \
        intl                                    \
        opcache                                 \
        pdo                                     \
        pdo_mysql                               \
        zip                                     \
    && apk del .build-deps

FROM base as dev

# Install needed PHP extensions and related libraries
RUN apk add --no-cache                          \
        git                                     \
    && apk add --no-cache --virtual .build-deps \
       $PHPIZE_DEPS                             \
    && pecl install xdebug                      \
    && docker-php-ext-enable xdebug             \
    && apk del .build-deps

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini $PHP_INI_DIR/conf.d/skeleton.ini

# Configure XDEBUG
COPY docker/xdebug/xdebug.ini $PHP_INI_DIR/conf.d/

# Make XDEBUG activable at container start
COPY docker/xdebug/docker-php-entrypoint /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-entrypoint && chmod 666 /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

# Expose port for PHP internal server
EXPOSE 8000

##################################
# Intermediate image to prepare  #
# the application for production #
##################################

FROM dev as builder

ENV COMPOSER_CACHE_DIR=/tmp/composer/cache

WORKDIR /var/www/html
USER www-data
COPY --chown=www-data:www-data . .

RUN mkdir -p /tmp/composer/cache && \
    composer install --optimize-autoloader --no-interaction --no-scripts --prefer-dist

################################
# FPM image use for production #
# It contains the application  #
################################

FROM base as fpm

# Configure PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini $PHP_INI_DIR/conf.d/skeleton.ini

# Copy the application
WORKDIR /var/www/html
USER www-data
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html
RUN bin/console ca:c

##################################
# Nginx image use for production #
##################################

FROM nginx:alpine as api

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/upload.conf /etc/nginx/conf.d/upload.conf

# Copy the application
RUN mkdir -p /var/www/html/public
COPY --from=builder --chown=root:root /var/www/html/public /var/www/html/public

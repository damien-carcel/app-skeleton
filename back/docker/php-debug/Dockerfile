FROM skeleton/php:7.3
MAINTAINER Damien Carcel <damien.carcel@gmail.com>

# Install XDEBUG
RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS                         \
    && pecl install xdebug-2.7.0beta1        \
    && docker-php-ext-enable xdebug          \
    && apk del .build-deps

# Configure XDEBUG
COPY files/xdebug.ini $PHP_INI_DIR/conf.d/

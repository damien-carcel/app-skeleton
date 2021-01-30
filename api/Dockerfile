######################################
# Base image for common dependencies #
# between development and production #
######################################

FROM debian:buster-slim as base

ENV DEBIAN_FRONTEND=noninteractive\
    PHP_CONF_DATE_TIMEZONE=UTC \
    PHP_CONF_DISPLAY_ERRORS=0 \
    PHP_CONF_DISPLAY_STARTUP_ERRORS=0 \
    PHP_CONF_MAX_EXECUTION_TIME=60 \
    PHP_CONF_MAX_INPUT_VARS=1000 \
    PHP_CONF_MAX_POST_SIZE=40M \
    PHP_CONF_MEMORY_LIMIT=512M \
    PHP_CONF_ERROR_REPORTING=22527 \
    PHP_CONF_UPLOAD_LIMIT=40M \
    PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=0 \
    PHP_CONF_ZEND_ASSERTIONS=-1

RUN echo 'APT::Install-Recommends "0" ; APT::Install-Suggests "0" ;' > /etc/apt/apt.conf.d/01-no-recommended && \
    echo 'path-exclude=/usr/share/doc/*' > /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/groff/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/info/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/linda/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/lintian/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/locale/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/man/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    apt-get update && \
    apt-get --yes install apt-transport-https ca-certificates gpg gpg-agent wget && \
    echo 'deb https://packages.sury.org/php/ buster main' > /etc/apt/sources.list.d/sury.list && \
    wget -O sury.gpg https://packages.sury.org/php/apt.gpg && apt-key add sury.gpg && rm sury.gpg && \
    apt-get update && \
    apt-get --yes install \
        php8.0-apcu \
        php8.0-cli \
        php8.0-curl \
        php8.0-dom \
        php8.0-fpm \
        php8.0-intl \
        php8.0-mbstring \
        php8.0-mysql \
        php8.0-opcache \
        php8.0-pdo \
        php8.0-zip && \
    apt-get clean && \
    apt-get --yes autoremove --purge && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    ln -s /usr/sbin/php-fpm8.0 /usr/local/sbin/php-fpm && \
    mkdir /run/php/

COPY docker/php/skeleton.ini /etc/php/8.0/cli/conf.d/99-skeleton.ini
COPY docker/php/skeleton.ini /etc/php/8.0/fpm/conf.d/99-skeleton.ini
COPY docker/php/fpm.conf /etc/php/8.0/fpm/pool.d/zzz-skeleton.conf

######################################
# PHP CLI image used for development #
######################################

FROM base as dev

ENV XDEBUG_ENABLED=0

# Install Git and XDEBUG
RUN apt-get update && \
    apt-get --yes install \
        git \
        php8.0-xdebug \
        unzip && \
    apt-get clean && \
    apt-get --yes autoremove --purge && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configure XDEBUG and make XDEBUG activable at container start
COPY docker/php/xdebug.ini /etc/php/8.0/cli/conf.d/99-skeleton-xdebug.ini
COPY docker/php/xdebug.ini /etc/php/8.0/fpm/conf.d/99-skeleton-xdebug.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

#######################################
# Intermediate image used to prepare  #
# the application for production      #
#######################################

FROM dev as builder

ENV COMPOSER_CACHE_DIR=/tmp/composer/cache

WORKDIR /var/www/html

COPY . .
RUN mkdir -p /tmp/composer/cache && \
    composer install --optimize-autoloader --no-interaction --no-scripts --prefer-dist --no-dev

###############################
# Image used for production   #
# It contains the application #
###############################

FROM base as api-fpm

ENV APP_ENV=prod

COPY --from=builder /var/www/html /var/www/html
WORKDIR /var/www/html
RUN mkdir var && \
    bin/console ca:c && \
    chown -R www-data:www-data /var/www/html

###################################
# Nginx image used for production #
###################################

FROM nginx:alpine as api-nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/upload.conf /etc/nginx/conf.d/upload.conf

RUN mkdir -p /var/www/html/public
COPY --from=builder --chown=root:root /var/www/html/public /var/www/html/public

FROM php:7.3-fpm-alpine

# php extensions deps
RUN apk update -q && apk upgrade -q \
    && apk add \
    gd \
    libpng \
    curl libcurl \
    ca-certificates \
    libjpeg \
    libpng \
    icu \
    && apk add -q --virtual build-deps \
    build-base \
    icu-dev \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    jpeg-dev \
    gd-dev \
    curl-dev \
    && docker-php-ext-install gd intl pdo_mysql curl opcache \
    && apk del -q build-deps \
# apcu
    && docker-php-source extract \
    && apk add --no-cache --virtual .phpize-deps-configure $PHPIZE_DEPS \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apk del .phpize-deps-configure \
    && docker-php-source delete

COPY --from=composer:2.7.8 /usr/bin/composer /usr/local/bin/composer
COPY support/docker/folkprog-php/zz-docker.conf /usr/local/etc/php/conf.d/zz-docker.conf

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction

COPY ./ /app

STOPSIGNAL SIGTERM

EXPOSE 9000

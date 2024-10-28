FROM php:8.3-fpm-alpine

# php extensions deps
RUN apk update -q && apk upgrade -q \
    && apk add \
    gd \
    libpng \
    curl \
    libcurl \
    ca-certificates \
    libjpeg \
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
    && docker-php-ext-install gd pdo_mysql curl opcache \
    && apk del -q build-deps

COPY --from=composer:2.7.8 /usr/bin/composer /usr/local/bin/composer
COPY support/docker/blog-php/zz-docker.conf /usr/local/etc/php/conf.d/zz-docker.conf

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction

COPY ./ ./
COPY src/BlogBundle/src/Resources/public/build/manifest.json ./public/bundles/harentiusblog/build/manifest.json

STOPSIGNAL SIGTERM

EXPOSE 9000

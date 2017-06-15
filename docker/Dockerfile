FROM php:7.1.6-fpm-alpine

MAINTAINER Yaroslav Osypenko <yaroslav.osypenko@gmail.com>

ARG APCU_VERSION=5.1.8
ARG APCU_BC_VERSION=1.0.3

RUN apk update \
  && apk add --no-cache --virtual .build-php \
    $PHPIZE_DEPS \
  && pecl install apcu-$APCU_VERSION \
  && docker-php-ext-enable apcu \
  && pecl install apcu_bc-$APCU_BC_VERSION \
  && docker-php-ext-enable apc \
  && rm -f /usr/local/etc/php/conf.d/docker-php-ext-apc.ini \
  && rm -f /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini

COPY php/*.ini /usr/local/etc/php/conf.d/
COPY php/php.ini /usr/local/etc/php/php.ini

COPY scripts/start.sh /root/start.sh

CMD ["/root/start.sh"]

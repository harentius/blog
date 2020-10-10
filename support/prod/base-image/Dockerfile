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
    && docker-php-source delete \
# nginx install
    && apk update \
    && apk add nginx \
# forward request and error logs to docker log collector
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \
# supervisor install
    && apk add supervisor && mkdir -p /var/log/supervisor

COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/php/zz-fpm-docker.conf /usr/local/etc/php-fpm.d/zz-fpm-docker.conf
COPY config/php/zz-docker.conf /usr/local/etc/php/conf.d/zz-fpm-docker.conf
COPY config/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY config/supervisord.conf /etc/supervisord.conf

# Fix permissions after changing nginx user
RUN chown -Rf www-data:www-data /var/lib/nginx

STOPSIGNAL SIGTERM

EXPOSE 80

ENTRYPOINT ["/usr/bin/supervisord"]
CMD ["-n", "-c", "/etc/supervisord.conf"]

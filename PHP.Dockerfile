FROM php:8-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && \
    pecl channel-update pecl.php.net && \
    pecl install apcu && \
    docker-php-ext-enable apcu && \
    docker-php-source delete

RUN curl https://getcomposer.org/download/2.1.7/composer.phar --output /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /app

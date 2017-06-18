FROM alpine:edge

MAINTAINER Yaroslav Osypenko <yaroslav.osypenko@gmail.com>

ENV PHP_VERSION 7.1.6

# Update repositories
RUN apk update

# Inslalling BASH and make it default shell (for phpbrew shell)
RUN apk add bash \
    && rm /bin/sh && ln -s /bin/bash /bin/sh

# Update certificates to make work wget over https
RUN apk add ca-certificates wget \
    && update-ca-certificates

# Instal phpbrew requirements
# https://github.com/phpbrew/phpbrew/wiki/Requirement
RUN apk add \
    php7 php7-dev php7-cli php7-phar php7-json \
    g++ make \
    openssl-dev openssl \
    autoconf automake curl libxslt-dev re2c libxml2 libxml2-dev bison bzip2-dev readline-dev \
    freetype-dev freetype-dev libpng libpng-dev libjpeg-turbo-dev libjpeg jpeg-dev gd-dev libltdl \
    gettext gettext-dev \
    icu-dev libmcrypt libmcrypt-dev

# Install phpbrew
RUN mkdir -p /usr/bin \
    && wget -O /usr/bin/phpbrew https://github.com/phpbrew/phpbrew/raw/master/phpbrew && chmod +x /usr/bin/phpbrew

# Install php versin
RUN phpbrew init \
    && echo '[[ -e ~/.phpbrew/bashrc ]] && source ~/.phpbrew/bashrc' >> /root/.bashrc \
    && source ~/.phpbrew/bashrc \
    && phpbrew install $PHP_VERSION +default

# Inslall php extensions
RUN phpbrew ext install apcu

#COPY php/*.ini /usr/local/etc/php/conf.d/
#COPY php/php.ini /usr/local/etc/php/php.ini
#
#COPY scripts/start.sh /root/start.sh

#CMD ["/root/start.sh"]

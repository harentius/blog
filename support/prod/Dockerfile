FROM harentius/blog-bundle-base

COPY support/prod/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY ./ /app

WORKDIR /app

RUN bin/console assets:install --symlink \
    && bin/console cache:warmup --env=prod

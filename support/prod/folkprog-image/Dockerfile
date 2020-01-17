FROM node:13.6-alpine AS build

WORKDIR /app

COPY ./package.json ./package-lock.json ./
RUN npm install --production

COPY ./assets ./assets
COPY ./src/BlogBundle/src/Resources/js ./src/BlogBundle/src/Resources/js
COPY ./webpack.config.js ./webpack.config.js
RUN npm run build:folkprog


FROM harentius/blog-bundle-base

COPY ./support/prod/folkprog-image/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY ./ /app
COPY --from=build /app/public/build /app/public/build

WORKDIR /app

RUN bin/console assets:install --symlink \
    && bin/console cache:warmup --env=prod

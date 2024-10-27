FROM node:13.6-alpine AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install --production
COPY ./assets ./assets
COPY ./src/BlogBundle/src/Resources/js ./src/BlogBundle/src/Resources/js
COPY ./src/BlogBundle/src/Resources/css ./src/BlogBundle/src/Resources/css
COPY ./webpack.config.js ./webpack.config.js
RUN npm run build:blog

FROM harentius/blog-php:latest AS blog-php
RUN bin/console assets:install

FROM nginx:1.27
COPY --from=build /app/public/build /app/public/build
COPY --from=blog-php /app/public/bundles /app/public/bundles
COPY support/docker/blog-static/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY public/banners /app/public/banners
COPY public/favicon.ico /app/public/favicon.ico
COPY public/robots.txt /app/public/robots.txt
COPY public/google2a887e99218c2967.html /app/public/google2a887e99218c2967.html

WORKDIR /app

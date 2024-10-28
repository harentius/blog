FROM nginx:1.27
COPY src/BlogBundle/src/Resources/public/build /app/public/bundles/harentiusblog/build
COPY support/docker/blog-static/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY public/robots.txt /app/public/robots.txt

WORKDIR /app

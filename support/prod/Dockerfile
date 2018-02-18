FROM harentius/blog-bundle-base

ENV project_dir="/app"
ENV project_console="bin/console"

COPY support/prod/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY ./ "$project_dir"

WORKDIR "$project_dir"
RUN "$project_console" assets:install --symlink
RUN "$project_console" cache:warmup --env=prod

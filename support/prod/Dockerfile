FROM harentius/blog-bundle-base

ENV project_dir='/app'

COPY support/prod/nginx.vh.conf /etc/nginx/conf.d/default.conf
COPY ./ ${project_dir}

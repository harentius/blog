Repository for [https://folkprog.net/](https://folkprog.net/) website
=====================================================================

Installation
---------------

1) Build base image:
```bash
cd support/prod/base-image
docker build -f support/prod/base-image/Dockerfile -t harentius/blog-bundle-base:latest support/prod/base-image
docker push harentius/blog-bundle-base
```

2) Build Folkprog image:
```bash
docker build -f support/prod/folkprog-image/Dockerfile -t harentius/folkprog .
docker push harentius/folkprog:latest
```

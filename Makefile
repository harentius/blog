.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Dev
up: dump-manifest ## Up containers
	docker compose up -d && \
	docker compose exec php /bin/sh -c "composer install \
	&& bin/console cache:warmup"

build-db:
	docker compose exec php /bin/sh -c " \
	&& bin/console doctrine:schema:drop --force \
	&& bin/console doctrine:schema:create
	"

api-key:
	docker compose exec php /bin/sh -c " \
	&& bin/console harentius:blog-bundle:create-api-key \
	"

down: ## Down containers
	docker compose down

ssh: ## ssh to php container
	docker compose exec php /bin/sh

ssh-s: ## ssh to static container
	docker compose exec static /bin/bash

rector: ## Run rector
	docker compose exec php /bin/sh -c "composer rector"

dump-manifest:
	mkdir -p ./public/bundles/harentiusblog/build && \
	cp ./src/BlogBundle/src/Resources/public/build/manifest.json ./public/bundles/harentiusblog/build

ff: build-blog-assets build dump-manifest down up ## Build frontend und restart container

# Build
build: build-image-php build-image-static build-image-publisher ## Build all images

build-image-php: ## Build php image for blog
	docker build -f support/docker/blog-php/Dockerfile . -t harentius/blog-php:latest --platform=linux/amd64

build-image-static: ## Build static files image for blog
	docker build -f support/docker/blog-static/Dockerfile . -t harentius/blog-static:latest --platform=linux/amd64

build-image-publisher: ## Build publisher docker image
	docker build -f support/docker/publisher/Dockerfile . -t harentius/blog-publisher:latest --platform=linux/amd64

build-blog-assets: ## Build blog assets before commit
	docker run -it --rm -w /app -v $(PWD)/src/BlogBundle:/app node:13.6-alpine sh -c "npm install --production && ./node_modules/.bin/encore production"

# Publish
publish: ## Publish blog images to the docker hub
	docker push harentius/blog-php:latest && docker push harentius/blog-static:latest && docker push harentius/blog-publisher:latest

publish-blog-bundle: ## Publish blog-bundle to github
	git checkout develop && git subtree push -P src/BlogBundle git@github.com:harentius/blog-bundle.git develop

# make build-blog-assets && make build && make down && make up
# also need assets install from php app
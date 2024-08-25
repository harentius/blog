.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Dev
up: ## Up containers
	docker compose up -d && \
	docker compose exec php /bin/sh -c "composer install \
	&& bin/console cache:warmup"

down: ## Down containers
	docker compose down

ssh: ## ssh to php container
	docker compose exec php /bin/sh

# Build
build: ## Build all images
	make build-image-php && make build-image-static

build-image-php: ## build php image for folkprog
	docker build -f support/docker/folkprog-php/Dockerfile . -t harentius/folkprog-php:latest --platform=linux/amd64

build-image-static: ## build php image for folkprog
	docker build -f support/docker/folkprog-static/Dockerfile . -t harentius/folkprog-static:latest --platform=linux/amd64

build-frontend: ## Build Frontend
	docker run -it --rm -w /app -v $(PWD):/app node:13.6-alpine sh -c "npm install --production && npm run build:folkprog"

# Publish
publish: ## Publish images to the docker hub
	docker push harentius/folkprog-php:latest && docker push harentius/folkprog-static:latest

publish-blog-bundle: ## Publish blog bundle
	git checkout develop && git subtree push -P src/BlogBundle git@github.com:harentius/blog-bundle.git develop
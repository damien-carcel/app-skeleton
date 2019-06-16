# Build Docker images

.PHONY: pull-back
pull-back:
	cd $(CURDIR)/back && docker-compose pull --ignore-pull-failures

.PHONY: build-back-dev
build-back-dev: pull-back
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/php:7.3 --build-arg BASE_IMAGE="php:7.3-alpine" --target dev

.PHONY: build-back
build-back: build-back-dev
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/fpm:7.3 --build-arg BASE_IMAGE="php:7.3-fpm-alpine" --target fpm
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/api:latest --build-arg BASE_IMAGE="php:7.3-alpine" --target api

.PHONY: pull-front
pull-front:
	cd $(CURDIR)/front && docker-compose pull --ignore-pull-failures

.PHONY: build-front-dev
build-front-dev: pull-front
	cd $(CURDIR)/front && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/node:lts --target dev

.PHONY: build-front
build-front: build-front-dev
	cd $(CURDIR)/front && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/front:latest --build-arg API_BASE_URL_FOR_PRODUCTION="http://api.skeleton.docker.local" --target front

.PHONY: build-dev
build-dev: build-front-dev build-back-dev

.PHONY: build
build: build-front build-back

# Prepare and serve the application

.PHONY: dependencies-front
dependencies-front:
	cd $(CURDIR)/front && docker-compose run --rm node yarn install

.PHONY: dependencies-back
dependencies-back:
	cd $(CURDIR)/back && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: dependencies
dependencies: dependencies-back dependencies-front

.PHONY: mysql
mysql:
	cd $(CURDIR)/back && docker-compose up -d mysql
	sh $(CURDIR)/.circleci/back/wait_for_mysql.sh
	cd $(CURDIR)/back && docker-compose run --rm php bin/console doctrine:schema:update --force

.PHONY: serve-back
serve-back: mysql dependencies-back
	cd $(CURDIR)/back && docker-compose up -d api

.PHONY: develop-back
develop-back: mysql dependencies-back
	cd $(CURDIR)/back && docker-compose run --rm php bin/console server:run

.PHONY: debug-back
debug-back: mysql dependencies-back
	cd $(CURDIR)/back && docker-compose run --rm -e XDEBUG_ENABLED=1 php bin/console server:run

.PHONY: fake-api
fake-api: dependencies-front
	cd $(CURDIR)/front && docker-compose up -d fake-api

.PHONY: develop-front
develop-front: fake-api dependencies-front
	cd $(CURDIR)/front && API_BASE_URL=http://localhost:3000 yarn run webpack:serve

.PHONY: serve-front
serve-front: dependencies-front serve-back
	cd $(CURDIR)/front && docker-compose up -d front

# Clean the containers

.PHONY: down-back
down-back:
	cd $(CURDIR)/back && docker-compose down -v

.PHONY: down-front
down-front:
	cd $(CURDIR)/front && docker-compose down -v

.PHONY: down
down: down-back down-front

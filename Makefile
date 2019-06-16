# Build Docker images

.PHONY: pull-back
pull-back:
	cd $(CURDIR)/back && docker-compose pull --ignore-pull-failures

.PHONY: build-back-dev
build-back-dev: pull-back
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/php:7.3 --target dev

.PHONY: build-back
build-back: build-back-dev
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/fpm:7.3 --target fpm
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/api:latest --target api

.PHONY: pull-front
pull-front:
	cd $(CURDIR)/front && docker-compose pull --ignore-pull-failures

.PHONY: build-front-dev
build-front-dev: pull-front

.PHONY: build-front
build-front: build-front-dev

.PHONY: build-dev
build-dev: build-front-dev build-back-dev

.PHONY: build
build: build-front build-back

# Prepare and serve the API

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

# Clean the containers

.PHONY: down-back
down-back:
	cd $(CURDIR)/back && docker-compose down -v

.PHONY: down-front
down-front:
	cd $(CURDIR)/front && docker-compose down -v

.PHONY: down
down: down-back down-front

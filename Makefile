# Build Docker images

.PHONY: pull-api
pull-api:
	cd $(CURDIR)/api && docker-compose pull --ignore-pull-failures

.PHONY: build-api-dev
build-api-dev: pull-api
	cd $(CURDIR)/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/dev:latest --target dev

.PHONY: build-api-prod
build-api-prod: pull-api
	cd $(CURDIR)/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/prod:latest --target prod
	cd $(CURDIR)/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/api:latest --target api

.PHONY: pull-front
pull-front:
	cd $(CURDIR)/front && docker-compose pull --ignore-pull-failures

.PHONY: build-front-dev
build-front-dev: pull-front
	cd $(CURDIR)/front && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/node:lts --target dev

.PHONY: build-front-prod
build-front-prod: pull-front
	cd $(CURDIR)/front && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/front:latest --build-arg API_BASE_URL_FOR_PRODUCTION="http://api.skeleton.docker.local" --target front

.PHONY: build-dev
build-dev: build-api-dev build-front-dev

.PHONY: build-prod
build-prod: build-api-prod build-front-prod

.PHONY: build
build: build-dev build-prod

# Prepare the application dependencies

.PHONY: install-api-dependencies
install-api-dependencies:  build-api-dev
	cd $(CURDIR)/api && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: install-front-dependencies
install-front-dependencies: build-front-dev
	cd $(CURDIR)/front && docker-compose run --rm node yarn install

.PHONY: install-dependencies
install-dependencies: install-api-dependencies install-front-dependencies

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev
	cd $(CURDIR)/api && docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: update-front-dependencies
update-front-dependencies: build-front-dev
	cd $(CURDIR)/front && docker-compose run --rm node yarn upgrade-interactive --latest

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-front-dependencies

# Serve the applications

.PHONY: mysql
mysql: install-api-dependencies
	cd $(CURDIR)/api && docker-compose up -d mysql
	sh $(CURDIR)/api/docker/mysql/wait_for_it.sh
	cd $(CURDIR)/api && docker-compose run --rm php bin/console doctrine:schema:update --force

.PHONY: develop-api
develop-api: mysql
	cd $(CURDIR)/api && docker-compose run --rm --service-ports php bin/console server:run 0.0.0.0:8000

.PHONY: debug-api
debug-api: mysql
	cd $(CURDIR)/api && docker-compose run --rm --service-ports -e XDEBUG_ENABLED=1 php bin/console server:run 0.0.0.0:8000

.PHONY: serve-api
serve-api: build-api-prod mysql
	cd $(CURDIR)/api && docker-compose up -d api

.PHONY: fake-api
fake-api: install-front-dependencies
	cd $(CURDIR)/front && docker-compose up -d fake-api

.PHONY: develop-front
develop-front: fake-api install-front-dependencies
	cd $(CURDIR)/front && API_BASE_URL=http://localhost:3000 yarn webpack:serve

.PHONY: serve-front
serve-front: build-front-prod install-front-dependencies
	cd $(CURDIR)/front && docker-compose run --rm node yarn webpack:build
	cd $(CURDIR)/front && docker-compose up -d front

.PHONY: install
install: serve-api serve-front

# Clean the containers

.PHONY: down-api
down-api:
	cd $(CURDIR)/api && docker-compose down -v

.PHONY: down-front
down-front:
	cd $(CURDIR)/front && docker-compose down -v

.PHONY: down
down: down-api down-front

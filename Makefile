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

.PHONY: pull-client
pull-client:
	cd $(CURDIR)/client && docker-compose pull --ignore-pull-failures

.PHONY: build-client-dev
build-client-dev: pull-client
	cd $(CURDIR)/client && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/node:latest --target dev

.PHONY: build-client-prod
build-client-prod: pull-client
	cd $(CURDIR)/client && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/client:latest --build-arg API_BASE_URL_FOR_PRODUCTION="http://skeleton-api.docker.localhost" --target client

.PHONY: build-dev
build-dev: build-api-dev build-client-dev

.PHONY: build-prod
build-prod: build-api-prod build-client-prod

.PHONY: build
build: build-dev build-prod

# Prepare the application dependencies

.PHONY: install-api-dependencies
install-api-dependencies:  build-api-dev
	cd $(CURDIR)/api && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: install-client-dependencies
install-client-dependencies: build-client-dev
	cd $(CURDIR)/client && docker-compose run --rm node yarn install

.PHONY: install-dependencies
install-dependencies: install-api-dependencies install-client-dependencies

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev
	cd $(CURDIR)/api && docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: update-client-dependencies
update-client-dependencies: build-client-dev
	cd $(CURDIR)/client && docker-compose run --rm node yarn upgrade-interactive --latest

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-client-dependencies

# Serve the applications

.PHONY: mysql	# It should depends on "install-api-dependencies" because it uses PHP dev image, but this make the CI build this image twice
mysql:
	cd $(CURDIR)/api && docker-compose up -d mysql
	sh $(CURDIR)/api/docker/mysql/wait_for_it.sh
	cd $(CURDIR)/api && docker-compose run --rm php bin/console doctrine:schema:update --force

.PHONY: develop-api
develop-api: install-api-dependencies mysql
	cd $(CURDIR)/api && docker-compose run --rm --service-ports php bin/console server:run 0.0.0.0:8000

.PHONY: debug-api
debug-api: install-api-dependencies mysql
	cd $(CURDIR)/api && docker-compose run --rm --service-ports -e XDEBUG_ENABLED=1 php bin/console server:run 0.0.0.0:8000

.PHONY: serve-api
serve-api: build-api-prod install-api-dependencies mysql
	cd $(CURDIR)/api && docker-compose up -d api

.PHONY: fake-api
fake-api: install-client-dependencies
	cd $(CURDIR)/client && docker-compose up -d fake-api

.PHONY: develop-client
develop-client: fake-api install-client-dependencies
	cd $(CURDIR)/client && API_BASE_URL=http://localhost:3000 yarn webpack:serve

.PHONY: serve-client
serve-client: build-client-prod install-client-dependencies
	cd $(CURDIR)/client && docker-compose up -d client

.PHONY: install
install: serve-api serve-client

# Clean the containers

.PHONY: down-api
down-api:
	cd $(CURDIR)/api && docker-compose down -v

.PHONY: down-client
down-client:
	cd $(CURDIR)/client && docker-compose down -v

.PHONY: down
down: down-api down-client

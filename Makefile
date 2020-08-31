SHELL = bash

.PHONY: help
help:
	@echo "-----------------"
	@echo "- Main commands -"
	@echo "-----------------"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?#main# .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?#main# "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "----------------------"
	@echo "- Secondary commands -"
	@echo "----------------------"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help

# Environment Variables

APP_ENV ?= dev
DEBUG ?= 0
IO ?=
L ?= max
TL ?= 6

PHPMD_OUTPUT=ansi
PHPMD_RULESETS=cleancode,codesize,controversial,design,naming,unusedcode

# Build Docker images

.PHONY: pull
pull: ## Pull all Docker images used in docker-compose.yaml
	@docker-compose pull

.PHONY: build
build: pull ## Build all Docker images at once (API and client, development and production)
	@docker-compose build --pull

.PHONY: build-dev
build-dev: build-api-dev build-client-dev ## Build all development images (API and client)

.PHONY: build-api-dev
build-api-dev: pull ## Build API development image (carcel/skeleton/dev:php)
	@docker-compose build --pull php

.PHONY: build-client-dev
build-client-dev: pull ## Build client development image (carcel/skeleton/dev:node)
	@docker-compose build --pull node

.PHONY: build-prod
build-prod: build-api-prod build-client-prod ## Build all production images (API and client)

.PHONY: build-api-prod
build-api-prod: pull ## Build API production images (carcel/skeleton/api:fpm and carcel/skeleton/api:nginx)
	@docker-compose build --pull api fpm

.PHONY: build-client-prod
build-client-prod: pull ## Build client production image (carcel/skeleton/client:latest)
	@docker-compose build --pull client

# Prepare the application dependencies

api/composer.lock: api/composer.json
	@docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

api/vendor: api/composer.lock
	@docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

client/yarn.lock: client/package.json
	@docker-compose run --rm node yarn install

client/node_modules: client/yarn.lock
	@docker-compose run --rm node yarn install --frozen-lockfile --check-files

.PHONY: dependencies
dependencies: api/vendor client/node_modules ## Install API and client dependencies

.PHONY: update-api-dependencies
update-api-dependencies: ## Update API dependencies
	@docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction

.PHONY: update-client-dependencies
update-client-dependencies: ## Update client dependencies
	@docker-compose run --rm node yarn upgrade-interactive --latest
	@docker-compose run --rm node yarn upgrade

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-client-dependencies ## Update all dependencies at once (API and client)

# Serve the applications

.PHONY: proxy
proxy:
	@docker-compose up -d traefik

traefik/ssl/_wildcard.docker.localhost.pem:
	@cd ${CURDIR}/traefik/ssl && mkcert "*.docker.localhost"

.PHONY: cache
cache: api/vendor ## Clear the API (Symfony) cache
	@docker-compose run --rm php rm -rf var/cache/*
	@docker-compose run --rm -e APP_ENV=${APP_ENV} php bin/console cache:clear

.PHONY: mysql
mysql: api/vendor ## Setup the API database
	@docker-compose up -d mysql
	@sh ${CURDIR}/api/docker/mysql/wait_for_it.sh
	@docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: develop-api
develop-api: api/config/jwt/public.pem #main# Run the API using the PHP development server
	@echo "Starting the API in development mode"
	@echo "..."
	@make mysql
	@make cache
	@XDEBUG_ENABLED=${DEBUG} docker-compose up -d api-dev
	@echo "..."
	@echo "API is now running in development mode, you can access it through http://localhost:8000"

api/config/jwt:
	@mkdir -p api/config/jwt

api/config/jwt/public.pem: api/config/jwt
	@echo "Generating public and private keys for JWT tokens"
	@docker-compose run --rm php sh -c ' \
		jwt_passphrase=$$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''=''); \
		echo "$$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096; \
		echo "$$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout; \
	'

.PHONY: develop-client
develop-client: develop-api client/node_modules #main# Run the client using Vue CLI development server (hit CTRL+c to stop the server)
	@echo "Starting the Client in development mode"
	@echo "..."
	@docker-compose run --rm --service-ports node yarn serve

.PHONY: serve
serve: serve-api serve-client #main# Serve the whole application in production mode

.PHONY: serve-api
serve-api: traefik/ssl/_wildcard.docker.localhost.pem mysql build-api-prod ## Serve the API in production mode (nginx + PHP-FPM)
	@echo "Starting the API in production mode"
	@echo "..."
	@make proxy
	@docker-compose up -d api
	@echo "..."
	@echo "API is now running in production mode, you can access it through https://skeleton-api.docker.localhost"

.PHONY: serve-client
serve-client: traefik/ssl/_wildcard.docker.localhost.pem build-client-prod ## Serve the client in production mode (nginx serving static files)
	@echo "Starting the client in production mode"
	@echo "..."
	@make proxy
	@docker-compose up -d client
	@echo "..."
	@echo "Client is now running in production mode, you can access it through https://skeleton.docker.localhost"

.PHONY: down
down: #main# Stop the application and remove all containers, networks and volumes
	@docker-compose down -v

# Test the API

.PHONY: api-tests
api-tests: api/vendor #main# Execute all the API tests
	@echo "Lint the PHP code"
	@make lint-api-code
	@echo "Run PHP static analysis"
	@make analyse-api-code
	@echo "Check coupling violations between API code layers"
	@make api-coupling
	@echo "Execute API unit tests"
	@make api-unit-tests
	@echo "Execute API acceptance tests"
	@make api-acceptance-tests
	@echo "Execute API integration tests"
	@make mysql
	@make api-integration-tests
	@echo "Execute API end to end tests"
	@make api-end-to-end-tests

.PHONY: api-coding-standards
api-coding-standards: ## Check API coding style with PHP CS Fixer
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

.PHONY: sniff-api-code
sniff-api-code: ## Check API coding style with PHP Code Sniffer
	@docker-compose run --rm php vendor/bin/phpcs

.PHONY: lint-api-code
lint-api-code: api-coding-standards sniff-api-code ## Lint the PHP code using both PHP Code Sniffer and PHP CS Fixer

.PHONY: fix-api-code
fix-api-code: ## Attempt to fix the violations detected by PHP Code Sniffer and PHP CS Fixer
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php
	@docker-compose run --rm php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src: ## Run PHP static analysis on source folder
	@docker-compose run --rm php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests: ## Run PHP static analysis on tests folder
	@docker-compose run --rm php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api-code ## Run PHP static analysis the API code
analyse-api-code: analyse-api-src analyse-api-tests

.PHONY: api-coupling
api-coupling: ## Check coupling violations between API code layers
	@docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: api-unit-tests
api-unit-tests: ## Execute API unit tests (use "make api-unit-tests IO=path/to/test" to run a specific test)
	@docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite "Unit tests" ${IO}

.PHONY: api-acceptance-tests
api-acceptance-tests: ## Execute API acceptance tests (use "make api-acceptance-tests IO=path/to/test" to run a specific test)
	@docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=acceptance -o std --colors -f pretty ${IO}

.PHONY: api-integration-tests
api-integration-tests: ## Execute API integration tests (use "make api-integration-tests IO=path/to/test" to run a specific test)
	@docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite="Integration tests" ${IO}

.PHONY: api-end-to-end-tests
api-end-to-end-tests: api/config/jwt/public.pem ## Execute API end to end tests (use "make api-end-to-end-tests IO=path/to/test" to run a specific test)
	@docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty ${IO}

.PHONY: phpmd
phpmd: ## Run PHP Mess Detector on the API code
	@docker-compose run --rm php vendor/bin/phpmd src,tests ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: phpmetrics
phpmetrics: ## Run PHP Metrics on the API code
	@docker-compose run --rm php vendor/bin/phpmetrics --report-html=reports/phpmetrics .
	@xdg-open api/reports/phpmetrics/index.html

# Test the client

.PHONY: client-tests
client-tests: client/node_modules #main# Execute all the client tests
	@echo "Lint the stylesheets"
	@make stylelint
	@echo "Lint the TypeScript code"
	@make eslint
	@echo "Check for type errors"
	@make type-check-client
	@echo "Execute unit tests"
	@make client-unit-tests
	@echo "Execute end-to-end tests"
	@make serve
	@make client-end-to-end-tests IO="--headless"

.PHONY: stylelint
stylelint: ## Lint the LESS stylesheet code
	@docker-compose run --rm node yarn run -s stylelint

.PHONY: eslint
eslint: ## Lint the TypeScript code
	@docker-compose run --rm node yarn run -s lint

.PHONY: type-check-client
type-check-client: ## Check for type errors
	@docker-compose run --rm node yarn run type-check

.PHONY: client-unit-tests
client-unit-tests: ## Execute client unit tests (use "make client-unit-tests IO=path/to/test" to run a specific test)
	@docker-compose run --rm -e JEST_JUNIT_OUTPUT_DIR="./reports" -e JEST_JUNIT_OUTPUT_NAME="jest.xml" node yarn run test:unit ${IO}

.PHONY: client-end-to-end-tests
client-end-to-end-tests: ## Run end to end tests — use "make end-to-end IO=--headless" for headless mode and "make end-to-end IO=--headless -s path/to/test" to run a specific test (works only in headless mode)
	@docker-compose run --rm cypress yarn run test:e2e --url http://client/ ${IO}

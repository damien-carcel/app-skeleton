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
IO ?=
L ?= max
TL ?= 6

PHPMD_OUTPUT=ansi
PHPMD_RULESETS=cleancode,codesize,controversial,design,naming,unusedcode

# Build Docker images

.PHONY: pull
pull: ## Pull all Docker images used in docker-compose.yaml.
	@docker-compose pull

.PHONY: build
build: pull ## Build all Docker images at once (API and client, development and production).
	@docker-compose build --parallel --pull

.PHONY: build-dev
build-dev: build-api-dev build-client-dev ## Build all development images (API and client).

.PHONY: build-api-dev
build-api-dev: ## Build API development image (carcel/skeleton/dev:php).
	@docker-compose build --pull php

.PHONY: build-client-dev
build-client-dev: ## Build client development image (carcel/skeleton/dev:node).
	@docker-compose build --pull node

.PHONY: build-prod
build-prod: build-api-prod build-client-prod ## Build all production images (API and client).

.PHONY: build-api-prod
build-api-prod: ## Build API production images (carcel/skeleton/api:fpm and carcel/skeleton/api:nginx).
	@docker-compose build --parallel --pull api fpm

.PHONY: build-client-prod
build-client-prod: ## Build client production image (carcel/skeleton/client:latest).
	@docker-compose build --pull client

# Prepare the application dependencies

.PHONY: install-api-dependencies
install-api-dependencies: build-api-dev ## Install API dependencies.
	@docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

.PHONY: install-client-dependencies
install-client-dependencies: build-client-dev ## Install client dependencies.
ifeq ($(wildcard client/yarn.lock),)
	@echo "Install the Node modules according to package.json"
	@docker-compose run --rm node yarn install
endif
	@echo "Install the Node modules according to yarn.lock"
	@docker-compose run --rm node yarn install --frozen-lockfile --check-files

.PHONY: dependencies
dependencies: install-api-dependencies install-client-dependencies ## Install API and client dependencies.

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev ## Update API dependencies.
	@docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction

.PHONY: update-client-dependencies
update-client-dependencies: build-client-dev ## Update client dependencies.
	@docker-compose run --rm node yarn upgrade-interactive --latest
	@docker-compose run --rm node yarn upgrade

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-client-dependencies ## Update all dependencies at once (API and client).

# Serve the applications

.PHONY: proxy
proxy:
	@docker-compose up -d traefik

traefik/ssl/_wildcard.docker.localhost.pem:
	@cd ${CURDIR}/traefik/ssl && mkcert "*.docker.localhost"

.PHONY: cache
cache: install-api-dependencies ## Clear the API (Symfony) cache.
	@docker-compose run --rm php rm -rf var/cache/*
	@docker-compose run --rm -e APP_ENV=${APP_ENV} php bin/console cache:clear

.PHONY: mysql
mysql: install-api-dependencies ## Setup the API database.
	@docker-compose up -d mysql
	@sh ${CURDIR}/api/docker/mysql/wait_for_it.sh
	@docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: develop-api
develop-api: install-api-dependencies #main# Run the API using the PHP development server. Use "XDEBUG_MODE=debug make develop-api" to activate the debugger.
	@echo ""
	@echo "Starting the API in development mode"
	@echo ""
	@make mysql
	@make cache
	@docker-compose up -d api-dev
	@echo ""
	@echo "API is now running in development mode, you can access it through http://localhost:8000"
	@echo ""

.PHONY: develop-client
develop-client: develop-api install-client-dependencies #main# Run the client using Webpack development server (hit CTRL+c to stop the server).
	@echo ""
	@echo "Starting the Client in development mode"
	@echo ""
	@docker-compose run --rm --service-ports node yarn serve

.PHONY: serve
serve: serve-api serve-client #main# Serve the whole application in production mode.

.PHONY: serve-api
serve-api: traefik/ssl/_wildcard.docker.localhost.pem mysql build-api-prod ## Serve the API in production mode (nginx + PHP-FPM).
	@echo "Starting the API in production mode"
	@echo "..."
	@make proxy
	@docker-compose up -d api
	@echo "..."
	@echo "API is now running in production mode, you can access it through https://skeleton-api.docker.localhost"

.PHONY: serve-client
serve-client: traefik/ssl/_wildcard.docker.localhost.pem build-client-prod ## Serve the client in production mode (nginx serving static files).
	@echo "Starting the client in production mode"
	@echo "..."
	@make proxy
	@docker-compose up -d client
	@echo "..."
	@echo "Client is now running in production mode, you can access it through https://skeleton.docker.localhost"

.PHONY: down
down: #main# Stop the application and remove all containers, networks and volumes.
	@docker-compose down -v

# Usefull aliases

.PHONY: console
console: #main# Use the Symfony CLI. Example: "make console IO=debug:container"
	@docker-compose run --rm php bin/console ${IO}

# Test the API

.PHONY: api-tests
api-tests: install-api-dependencies #main# Execute all the API tests.
	@echo ""
	@echo "Lint the PHP code"
	@echo ""
	@make lint-api-code
	@echo ""
	@echo "Run PHP static analysis"
	@echo ""
	@make analyse-api-code
	@echo ""
	@echo "Check coupling violations between API code layers"
	@echo ""
	@make api-coupling
	@echo ""
	@echo "Execute API unit tests"
	@echo ""
	@make api-unit-tests
	@echo ""
	@echo "Execute API acceptance tests"
	@echo ""
	@make api-acceptance-tests
	@echo ""
	@echo "Execute API integration tests"
	@echo ""
	@make mysql
	@make api-integration-tests
	@echo ""
	@echo "Execute API end to end tests"
	@echo ""
	@make api-end-to-end-tests
	@echo ""
	@echo "All API tests were successfully executed"
	@echo ""

.PHONY: api-coding-standards
api-coding-standards: ## Check API coding style with PHP CS Fixer.
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php-cs-fixer.dist.php

.PHONY: sniff-api-code
sniff-api-code: ## Check API coding style with PHP Code Sniffer.
	@docker-compose run --rm php vendor/bin/phpcs

.PHONY: lint-api-code
lint-api-code: api-coding-standards sniff-api-code ## Lint the PHP code using both PHP Code Sniffer and PHP CS Fixer.

.PHONY: fix-api-code
fix-api-code: ## Attempt to fix the violations detected by PHP Code Sniffer and PHP CS Fixer.
	@docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php-cs-fixer.dist.php
	@docker-compose run --rm php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src: ## Run PHP static analysis on source folder.
	@docker-compose run --rm php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests: ## Run PHP static analysis on tests folder.
	@docker-compose run --rm php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api-code ## Run PHP static analysis the API code.
analyse-api-code: analyse-api-src analyse-api-tests

.PHONY: api-coupling
api-coupling: ## Check coupling violations between API code layers.
	@docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: api-unit-tests
api-unit-tests: ## Execute API unit tests (use "make api-unit-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-unit-tests" to activate the debugger.
	@docker-compose run --rm php vendor/bin/phpunit --testsuite "Unit tests" ${IO}

.PHONY: api-acceptance-tests
api-acceptance-tests: ## Execute API acceptance tests (use "make api-acceptance-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-acceptance-tests" to activate the debugger.
	@docker-compose run --rm php vendor/bin/behat --profile=acceptance -o std --colors -f pretty ${IO}

.PHONY: api-integration-tests
api-integration-tests: ## Execute API integration tests (use "make api-integration-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-integration-tests" to activate the debugger.
	@docker-compose run --rm php vendor/bin/phpunit --testsuite="Integration tests" ${IO}

.PHONY: api-end-to-end-tests
api-end-to-end-tests: ## Execute API end to end tests (use "make api-end-to-end-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-end-to-end-tests" to activate the debugger.
	@docker-compose run --rm php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty ${IO}

.PHONY: phpmd
phpmd: ## Run PHP Mess Detector on the API code.
	@docker-compose run --rm php vendor/bin/phpmd src,tests ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: phpmetrics
phpmetrics: ## Run PHP Metrics on the API code.
	@docker-compose run --rm php vendor/bin/phpmetrics --report-html=reports/phpmetrics .
	@xdg-open api/reports/phpmetrics/index.html

# Test the client

.PHONY: client-tests
client-tests: install-client-dependencies #main# Execute all the client tests.
	@echo ""
	@echo "Lint the stylesheets"
	@echo ""
	@make stylelint
	@echo ""
	@echo "Lint the TypeScript code"
	@echo ""
	@make eslint
	@echo ""
	@echo "Check for type errors"
	@echo ""
	@make type-check-client
	@echo ""
	@echo "Execute unit tests"
	@echo ""
	@make client-unit-tests
	@echo ""
	@echo "Execute end-to-end tests"
	@echo ""
	@make serve
	@make client-end-to-end-tests IO="--headless"
	@echo ""
	@echo "All client tests were successfully executed"
	@echo ""

.PHONY: stylelint
stylelint: ## Lint the LESS stylesheet code.
	@docker-compose run --rm node yarn -s stylelint

.PHONY: eslint
eslint: ## Lint the TypeScript code.
	@docker-compose run --rm node yarn -s lint

.PHONY: type-check-client
type-check-client: ## Check for type errors.
	@docker-compose run --rm node yarn type-check

.PHONY: client-unit-tests
client-unit-tests: ## Execute client unit tests (use "make client-unit-tests IO=path/to/test" to run a specific test).
	@docker-compose run --rm -e JEST_JUNIT_OUTPUT_DIR="./reports" -e JEST_JUNIT_OUTPUT_NAME="jest.xml" node yarn jest ${IO}

.PHONY: client-end-to-end-tests
client-end-to-end-tests: ## Run end to end tests — use "make end-to-end IO=--headless" for headless mode and "make end-to-end IO=--headless -s path/to/test" to run a specific test (works only in headless mode).
	@docker-compose run --rm cypress yarn cypress run ${IO}

.PHONY: open-cypress ## Open the Cypress UI.
open-cypress:
	@docker-compose run --rm cypress yarn cypress open

.PHONY: install-cypress ## Force the install of the Cypress binary.
install-cypress:
	@docker-compose run --rm node yarn cypress install

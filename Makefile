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

ifeq ($(CI),true)
	DC_RUN = docker-compose run --rm -T
else
	DC_RUN = docker-compose run --rm
endif

# Build Docker images

.PHONY: up
up: ## Pull all Docker images used in docker-compose.yaml.
	@docker-compose up -d --remove-orphans ${IO}

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
	@$(DC_RUN) php composer install --prefer-dist --optimize-autoloader --no-interaction

.PHONY: install-client-dependencies
install-client-dependencies: build-client-dev ## Install client dependencies.
ifeq ($(wildcard client/yarn.lock),)
	@echo "Install the Node modules according to package.json"
	@$(DC_RUN) node yarn install
endif
	@echo "Install the Node modules according to yarn.lock"
	@$(DC_RUN) node yarn install --frozen-lockfile --check-files

.PHONY: dependencies
dependencies: install-api-dependencies install-client-dependencies ## Install API and client dependencies.

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev ## Update API dependencies.
	@$(DC_RUN) php composer update --prefer-dist --optimize-autoloader --no-interaction

.PHONY: update-client-dependencies
update-client-dependencies: build-client-dev ## Update client dependencies.
	@$(DC_RUN) node yarn upgrade-interactive --latest
	@$(DC_RUN) node yarn upgrade

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-client-dependencies ## Update all dependencies at once (API and client).

# Serve the applications

.PHONY: proxy
proxy: traefik/ssl/_wildcard.docker.localhost.pem
	@make up IO=traefik

traefik/ssl/_wildcard.docker.localhost.pem:
	@cd ${CURDIR}/traefik/ssl && mkcert "*.docker.localhost"

.PHONY: cache
cache: install-api-dependencies ## Clear the API (Symfony) cache.
	@$(DC_RUN) php rm -rf var/cache/*
	@$(DC_RUN) -e APP_ENV=${APP_ENV} php bin/console cache:clear

.PHONY: database
database: install-api-dependencies ## Setup the API database.
	@make up IO=database
	@sh ${CURDIR}/api/docker/database/wait_for_it.sh
	@$(DC_RUN) php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

.PHONY: dev
dev: develop-api develop-client #main# Serve the whole application in development mode.
	@make proxy
	@echo "..."
	@echo "The application is now running in development mode, you can access it through http://skeleton.docker.localhost"

.PHONY: develop-api
develop-api: database ## Run the API using the PHP development server. Use "XDEBUG_MODE=debug make develop-api" to activate the debugger.
	@echo ""
	@echo "Starting the API in development mode"
	@echo ""
	@make cache
	@make up IO=api-dev

.PHONY: develop-client
develop-client: install-client-dependencies ## Run the client using Webpack development server (hit CTRL+c to stop the server).
	@echo ""
	@echo "Starting the Client in development mode"
	@echo ""
	@make up IO=client-dev

.PHONY: prod
prod: serve-api serve-client #main# Serve the whole application in production mode.
	@make proxy
	@echo "..."
	@echo "The application is now running in production mode, you can access it through https://skeleton.docker.localhost"

.PHONY: serve-api
serve-api: database build-api-prod ## Serve the API in production mode (nginx + PHP-FPM).
	@echo "Starting the API in production mode"
	@echo "..."
	@make up IO=api

.PHONY: serve-client
serve-client: build-client-prod ## Serve the client in production mode (nginx serving static files).
	@echo "Starting the client in production mode"
	@echo "..."
	@make up IO=client

.PHONY: down
down: #main# Stop the application and remove all containers, networks and volumes.
	@docker-compose down -v

# Usefull aliases

.PHONY: console
console: #main# Use the Symfony CLI. Example: "make console IO=debug:container"
	@$(DC_RUN) php bin/console ${IO}

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
	@echo "Run PHP Mess Detector on the API code"
	@echo ""
	@make phpmd
	@echo ""
	@echo "Execute API unit tests"
	@echo ""
	@make api-unit-tests
	@echo ""
	@echo "Execute \"in memory\" API acceptance tests"
	@echo ""
	@make api-acceptance-tests-in-memory
	@echo ""
	@echo "Execute API acceptance tests with I/O"
	@echo ""
	@make database
	@make api-acceptance-tests-with-io
	@echo ""
	@echo "Execute API integration tests"
	@echo ""
	@make api-integration-tests
	@echo ""
	@echo "All API tests were successfully executed"
	@echo ""

.PHONY: lint-api-code
lint-api-code: ## Check API coding style with PHP CS Fixer.
	@$(DC_RUN) php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php-cs-fixer.dist.php

.PHONY: fix-api-code
fix-api-code: ## Attempt to fix the violations detected by PHP Code Sniffer and PHP CS Fixer.
	@$(DC_RUN) php vendor/bin/php-cs-fixer fix -v --diff --config=.php-cs-fixer.dist.php
	@$(DC_RUN) php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src: ## Run PHP static analysis on source folder.
	@$(DC_RUN) php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests: ## Run PHP static analysis on tests folder.
	@$(DC_RUN) php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api-code ## Run PHP static analysis the API code.
analyse-api-code: analyse-api-src analyse-api-tests

.PHONY: api-coupling
api-coupling: ## Check coupling violations between API code layers.
	@$(DC_RUN) php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: phpmd
phpmd: ## Run PHP Mess Detector on the API code.
	@$(DC_RUN) php vendor/bin/phpmd src,tests --exclude *src/Kernel.php ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: api-unit-tests
api-unit-tests: ## Execute API unit tests (use "make api-unit-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-unit-tests" to activate the debugger.
	@$(DC_RUN) php vendor/bin/phpspec run ${IO}

.PHONY: api-unit-tests
describe: ## Create a phpspec unit test (use as follow: "make describe IO=namepace/with/slash/instead/of/antislash", then running "make api-unit-tests" will create the class corresponding to the test).
	@$(DC_RUN) php vendor/bin/phpspec describe ${IO}

.PHONY: api-acceptance-tests-in-memory
api-acceptance-tests-in-memory: ## Execute "in memory" API acceptance tests (use "make api-acceptance-tests-in-memory IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-acceptance-tests-in-memory" to activate the debugger.
	@$(DC_RUN) php vendor/bin/behat --profile=acceptance-in-memory -o std --colors -f pretty ${IO}

.PHONY: api-acceptance-tests-with-io
api-acceptance-tests-with-io: ## Execute API acceptance tests with I/O (use "make api-acceptance-tests-with-io IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-acceptance-tests-with-io" to activate the debugger.
	@$(DC_RUN) php vendor/bin/behat --profile=acceptance-with-io -o std --colors -f pretty ${IO}

.PHONY: api-integration-tests
api-integration-tests: ## Execute API integration tests (use "make api-integration-tests IO=path/to/test" to run a specific test). Use "XDEBUG_MODE=debug make api-integration-tests" to activate the debugger.
	@$(DC_RUN) php vendor/bin/behat --profile=integration -o std --colors -f pretty ${IO}

.PHONY: phpmetrics
phpmetrics: ## Run PHP Metrics on the API code.
	@$(DC_RUN) php vendor/bin/phpmetrics --report-html=reports/phpmetrics .
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
	@echo "All client tests were successfully executed"
	@echo ""

.PHONY: stylelint
stylelint: ## Lint the LESS stylesheet code.
	@$(DC_RUN) node yarn -s stylelint

.PHONY: eslint
eslint: ## Lint the TypeScript code.
	@$(DC_RUN) node yarn -s eslint

.PHONY: type-check-client
type-check-client: ## Check for type errors.
	@$(DC_RUN) node yarn type-check

.PHONY: client-unit-tests
client-unit-tests: ## Execute client unit tests (use "make client-unit-tests IO=path/to/test" to run a specific test).
	@$(DC_RUN) -e JEST_JUNIT_OUTPUT_DIR="./reports" -e JEST_JUNIT_OUTPUT_NAME="jest.xml" node yarn test ${IO}

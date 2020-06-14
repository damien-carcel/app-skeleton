SHELL = bash

# Environment Variables

APP_ENV ?= dev
DEBUG ?= 0
L ?= max
O ?=
TL ?= 6

PHPMD_OUTPUT=ansi
PHPMD_RULESETS=cleancode,codesize,controversial,design,naming,unusedcode

# Build Docker images

.PHONY: pull
pull:
	docker-compose pull

.PHONY: build-api-dev
build-api-dev: pull
	docker-compose build --pull php

.PHONY: build-api-prod
build-api-prod: pull
	docker-compose build --pull api fpm

.PHONY: build-client-dev
build-client-dev: pull
	docker-compose build --pull node

.PHONY: build-client-prod
build-client-prod: pull
	docker-compose build --pull client

.PHONY: build-dev
build-dev: build-api-dev build-client-dev

.PHONY: build-prod
build-prod: build-api-prod build-client-prod

.PHONY: build
build: pull
	docker-compose build --pull

# Prepare the application dependencies

.PHONY: update-vendor
update-vendor:
	docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction

.PHONY: update-node-modules
update-node-modules:
	docker-compose run --rm node yarn upgrade-interactive --latest
	docker-compose run --rm node yarn upgrade

.PHONY: update-dependencies
update-dependencies: update-vendor update-node-modules

api/composer.lock: api/composer.json
	docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

api/vendor: api/composer.lock
	docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

client/yarn.lock: client/package.json
	docker-compose run --rm node yarn install

client/node_modules: client/yarn.lock
	docker-compose run --rm node yarn install --frozen-lockfile --check-files

.PHONY: dependencies
dependencies: api/vendor client/node_modules

# Serve the applications

.PHONY: proxy
proxy:
	docker-compose up -d traefik

traefik/ssl/_wildcard.docker.localhost.pem:
	cd ${CURDIR}/traefik/ssl && mkcert "*.docker.localhost"

.PHONY: cache
cache: api/vendor
	docker-compose run --rm php rm -rf var/cache/*
	docker-compose run --rm -e APP_ENV=${APP_ENV} php bin/console cache:clear

.PHONY: mysql
mysql: api/vendor
	docker-compose up -d mysql
	sh ${CURDIR}/api/docker/mysql/wait_for_it.sh
	docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: develop-api
develop-api:
	$(MAKE) mysql
	XDEBUG_ENABLED=${DEBUG} docker-compose up -d api-dev

.PHONY: serve-api
serve-api: traefik/ssl/_wildcard.docker.localhost.pem mysql build-api-prod
	$(MAKE) proxy
	docker-compose up -d api

api/config/jwt:
	mkdir -p api/config/jwt

api/config/jwt/public.pem: api/config/jwt
	docker-compose run --rm php sh -c ' \
		jwt_passphrase=$$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''=''); \
		echo "$$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096; \
		echo "$$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout; \
	'

.PHONY: develop-client
develop-client: develop-api client/node_modules
	docker-compose run --rm --service-ports node yarn serve

.PHONY: serve-client
serve-client: traefik/ssl/_wildcard.docker.localhost.pem build-client-prod
	$(MAKE) proxy
	docker-compose up -d client

.PHONY: install
install: serve-api serve-client

# Clean the containers

.PHONY: down
down:
	docker-compose down -v

# Test the API

.PHONY: api-coding-standards
api-coding-standards:
	docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

.PHONY: sniff-api-code
sniff-api-code:
	docker-compose run --rm php vendor/bin/phpcs

.PHONY: lint-api-code
lint-api-code: api-coding-standards sniff-api-code

.PHONY: fix-api-code
fix-api-code:
	docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php
	docker-compose run --rm php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src:
	docker-compose run --rm php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests:
	docker-compose run --rm php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api-code
analyse-api-code: analyse-api-src analyse-api-tests

.PHONY: api-coupling
api-coupling:
	docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: api-unit-tests
api-unit-tests:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite "Unit tests" --log-junit tests/results/unit_tests.xml

.PHONY: api-acceptance-tests
api-acceptance-tests:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=acceptance -o std --colors -f pretty -f junit -o tests/results/acceptance

.PHONY: api-integration-tests
api-integration-tests:
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite="Integration tests" --log-junit tests/results/integration_tests.xml

.PHONY: api-e2e-tests
api-e2e-tests: api/config/jwt/public.pem
	docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty -f junit -o tests/results/e2e

.PHONY: api-tests
api-tests: api/vendor
	$(MAKE) lint-api-code
	$(MAKE) analyse-api-code
	$(MAKE) api-coupling
	$(MAKE) api-unit-tests
	$(MAKE) api-acceptance-tests
	$(MAKE) mysql
	$(MAKE) api-integration-tests
	$(MAKE) api-e2e-tests

.PHONY: phpmd
phpmd:
	docker-compose run --rm php vendor/bin/phpmd src,tests ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: phpmetrics
phpmetrics:
	docker-compose run --rm php vendor/bin/phpmetrics --report-html=report .
	xdg-open api/report/index.html

# Test the client

.PHONY: stylelint
stylelint:
	docker-compose run --rm node yarn run -s stylelint

.PHONY: eslint
eslint:
	docker-compose run --rm node yarn run -s lint

.PHONY: type-check-client
type-check-client:
	docker-compose run --rm node yarn run type-check

.PHONY: client-unit-tests
client-unit-tests:
	docker-compose run --rm -e JEST_JUNIT_OUTPUT_DIR="./reports" -e JEST_JUNIT_OUTPUT_NAME="jest.xml" node yarn run test:unit ${O}

.PHONY: client-e2e-tests
client-e2e-tests:
	docker-compose run --rm -e MOCHA_FILE="tests/reports/e2e.xml" cypress yarn run test:e2e --headless ${O}

.PHONY: client-e2e-tests-x11-sharing
client-e2e-tests-x11-sharing:
	docker-compose run --rm --entrypoint="cypress open --project ." -e DISPLAY -v "/tmp/.X11-unix:/tmp/.X11-unix" cypress yarn run test:e2e

.PHONY: client-tests
client-tests: client/node_modules
	$(MAKE) stylelint
	$(MAKE) eslint
	$(MAKE) type-check-client
	$(MAKE) client-unit-tests
	$(MAKE) install
	$(MAKE) client-e2e-tests

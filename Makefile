SHELL = bash

# Environment Variables

DEBUG ?= 0
L ?= max
TL ?= 6

PHPMD_OUTPUT=ansi
PHPMD_RULESETS=cleancode,codesize,controversial,design,naming,unusedcode

ESL_OUT ?=
JEST_OUT ?=
SL_OUT ?=

SILENT =
ifneq (${SL_OUT},)
SILENT = -s
endif

# Build Docker images

.PHONY: pull-api
pull-api:
	cd ${CURDIR}/api && docker-compose pull

.PHONY: build-api-dev
build-api-dev: pull-api
	cd ${CURDIR}/api && docker-compose build --pull php

.PHONY: build-api-prod
build-api-prod: pull-api
	cd ${CURDIR}/api && docker-compose build --pull api fpm

.PHONY: pull-client
pull-client:
	cd ${CURDIR}/client && docker-compose pull

.PHONY: build-client-dev
build-client-dev: pull-client
	cd ${CURDIR}/client && docker-compose build --pull node

.PHONY: build-client-prod
build-client-prod: pull-client
	cd ${CURDIR}/client && docker-compose build --pull client

.PHONY: build-dev
build-dev: build-api-dev build-client-dev

.PHONY: build-prod
build-prod: build-api-prod build-client-prod

.PHONY: build
build: build-dev build-prod

# Prepare the application dependencies

.PHONY: update-vendor
update-vendor:
	cd ${CURDIR}/api && touch composer.json
	$(MAKE) api/vendor

.PHONY: update-node-modules
update-node-modules:
	cd ${CURDIR}/client && docker-compose run --rm node yarn upgrade-interactive --latest

.PHONY: update-dependencies
update-dependencies: update-vendor update-node-modules

api/composer.lock: api/composer.json
	cd ${CURDIR}/api && docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction

api/vendor: api/composer.lock
	cd ${CURDIR}/api && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

client/yarn.lock: client/package.json
	cd ${CURDIR}/client && docker-compose run --rm node yarn install

client/node_modules: client/yarn.lock
	cd ${CURDIR}/client && docker-compose run --rm node yarn install --frozen-lockfile --check-files

.PHONY: dependencies
dependencies: api/vendor client/node_modules

# Serve the applications

.PHONY: mysql
mysql: api/vendor
	cd ${CURDIR}/api && docker-compose up -d mysql
	sh ${CURDIR}/api/docker/mysql/wait_for_it.sh
	cd ${CURDIR}/api && docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: develop-api
develop-api:
	$(MAKE) mysql
	cd ${CURDIR}/api && XDEBUG_ENABLED=${DEBUG} docker-compose up -d api-dev

.PHONY: serve-api
serve-api: mysql build-api-prod
	cd ${CURDIR}/api && docker-compose up -d api

api/config/jwt:
	mkdir -p api/config/jwt

api/config/jwt/public.pem: api/config/jwt
	cd ${CURDIR}/api && docker-compose run --rm php sh -c ' \
		jwt_passphrase=$$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''=''); \
		echo "$$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096; \
		echo "$$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout; \
	'

.PHONY: develop-client
develop-client: develop-api client/node_modules
	cd ${CURDIR}/client && docker-compose run --rm --service-ports node yarn serve

.PHONY: serve-client
serve-client: build-client-prod
	cd ${CURDIR}/client && docker-compose up -d client

.PHONY: install
install: serve-api serve-client

# Clean the containers

.PHONY: down-api
down-api:
	cd ${CURDIR}/api && docker-compose down -v

.PHONY: down-client
down-client:
	cd ${CURDIR}/client && docker-compose down -v

.PHONY: down
down: down-api down-client

# Test the API

.PHONY: api-coding-standards
api-coding-standards:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

.PHONY: sniff-api-code
sniff-api-code:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpcs

.PHONY: lint-api-code
lint-api-code: api-coding-standards sniff-api-code

.PHONY: fix-api-code
fix-api-code:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api-code
analyse-api-code: analyse-api-src analyse-api-tests

.PHONY: api-coupling
api-coupling:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: api-unit-tests
api-unit-tests:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite "Unit tests" --log-junit tests/results/unit_tests.xml

.PHONY: api-acceptance-tests
api-acceptance-tests:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=acceptance -o std --colors -f pretty -f junit -o tests/results/acceptance

.PHONY: api-integration-tests
api-integration-tests:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite="Integration tests" --log-junit tests/results/integration_tests.xml

.PHONY: api-e2e-tests
api-e2e-tests: api/config/jwt/public.pem
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty -f junit -o tests/results/e2e

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
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpmd src,tests ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: phpmetrics
phpmetrics:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpmetrics --report-html=report .
	cd ${CURDIR}/api && xdg-open report/index.html

# Test the client

.PHONY: stylelint
stylelint:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run ${SILENT} stylelint ${SL_OUT}

.PHONY: eslint
eslint:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run lint ${ESL_OUT}

.PHONY: fix-eslint
fix-eslint:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run lint --fix

.PHONY: type-check-client
type-check-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run type-check

.PHONY: client-unit-tests
client-unit-tests:
	cd ${CURDIR}/client && docker-compose run --rm -e JEST_JUNIT_OUTPUT_DIR="./reports" -e JEST_JUNIT_OUTPUT_NAME="jest.xml" node yarn run test:unit ${JEST_OUT}

.PHONY: client-e2e-tests-with-chrome
client-e2e-tests-with-chrome:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run test:e2e --env chrome

.PHONY: client-e2e-tests-with-firefox
client-e2e-tests-with-firefox:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run test:e2e --env firefox

.PHONY: client-e2e-tests
client-e2e-tests:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run test:e2e --env chrome,firefox

.PHONY: client-tests
client-tests: client/node_modules
	$(MAKE) stylelint
	$(MAKE) eslint
	$(MAKE) type-check-client
	$(MAKE) client-unit-tests
	$(MAKE) client-e2e-tests

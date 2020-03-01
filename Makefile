DEBUG=0
OUTPUT=
L=max
TL=6
PHPMD_OUTPUT=ansi
PHPMD_RULESETS=cleancode,codesize,controversial,design,naming,unusedcode

# Build Docker images

.PHONY: pull-api
pull-api:
	cd ${CURDIR}/api && docker-compose pull --ignore-pull-failures

.PHONY: build-api-dev
build-api-dev: pull-api
	cd ${CURDIR}/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/dev:php --target dev

.PHONY: build-api-prod
build-api-prod: pull-api
	cd ${CURDIR}/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/api:fpm --target prod
	cd ${CURDIR}/api && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/api:nginx --target api

.PHONY: pull-client
pull-client:
	cd ${CURDIR}/client && docker-compose pull --ignore-pull-failures

.PHONY: build-client-dev
build-client-dev: pull-client
	cd ${CURDIR}/client && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/dev:node --target dev

.PHONY: build-client-prod
build-client-prod: pull-client
	cd ${CURDIR}/client && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/client:latest --build-arg API_BASE_URL_FOR_PRODUCTION="https://skeleton-api.docker.localhost" --target client

.PHONY: build-dev
build-dev: build-api-dev build-client-dev

.PHONY: build-prod
build-prod: build-api-prod build-client-prod

.PHONY: build
build: build-dev build-prod

# Prepare the application dependencies

.PHONY: install-api-dependencies
install-api-dependencies:  build-api-dev
	cd ${CURDIR}/api && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction

.PHONY: install-client-dependencies
install-client-dependencies: build-client-dev
	cd ${CURDIR}/client && docker-compose run --rm node yarn install --frozen-lockfile --check-files

.PHONY: install-dependencies
install-dependencies: install-api-dependencies install-client-dependencies

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev
	cd ${CURDIR}/api && docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction

.PHONY: update-client-dependencies
update-client-dependencies: build-client-dev
	cd ${CURDIR}/client && docker-compose run --rm node yarn upgrade-interactive --latest

.PHONY: update-dependencies
update-dependencies: update-api-dependencies update-client-dependencies

# Serve the applications

.PHONY: mysql
mysql: install-api-dependencies
	cd ${CURDIR}/api && docker-compose up -d mysql
	sh ${CURDIR}/api/docker/mysql/wait_for_it.sh
	cd ${CURDIR}/api && docker-compose run --rm php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: develop-api
develop-api: mysql
	cd ${CURDIR}/api && XDEBUG_ENABLED=${DEBUG} docker-compose up -d api-dev

.PHONY: serve-api
serve-api: build-api-prod mysql
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
develop-client: develop-api install-client-dependencies
	cd ${CURDIR}/client && docker-compose run --rm --service-ports node yarn webpack:serve

.PHONY: serve-client
serve-client: install-client-dependencies build-client-prod
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

.PHONY: coding-standard-api
coding-standard-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

.PHONY: sniff-code-api
sniff-code-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpcs

.PHONY: lint-api
lint-api: coding-standard-api sniff-code-api

.PHONY: lint-fix-api
lint-fix-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpcbf

.PHONY: analyse-api-src
analyse-api-src:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpstan analyse -l ${L} src

.PHONY: analyse-api-tests
analyse-api-tests:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpstan analyse -l ${TL} tests

.PHONY: analyse-api
analyse-api: analyse-api-src analyse-api-tests

.PHONY: coupling-api
coupling-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: unit-api
unit-api:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite "Unit tests" --log-junit tests/results/unit_tests.xml

.PHONY: acceptance-api
acceptance-api:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=acceptance -o std --colors -f pretty -f junit -o tests/results/acceptance

.PHONY: integration-api
integration-api:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/phpunit --testsuite="Integration tests" --log-junit tests/results/integration_tests.xml

.PHONY: end-to-end-api
end-to-end-api:
	cd ${CURDIR}/api && docker-compose run --rm -e XDEBUG_ENABLED=${DEBUG} php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty -f junit -o tests/results/e2e

.PHONY: test-api
test-api: lint-api analyse-api coupling-api unit-api acceptance-api mysql integration-api end-to-end-api

.PHONY: phpmd
phpmd:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpmd src,tests ${PHPMD_OUTPUT} ${PHPMD_RULESETS}

.PHONY: phpmetrics
phpmetrics:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpmetrics --report-html=report .
	cd ${CURDIR}/api && xdg-open report/index.html

# Test the client

.PHONY: lint-client
lint-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run lint ${OUTPUT}

.PHONY: lint-fix-client
lint-fix-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run lint-fix

.PHONY: type-check-client
type-check-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run type-check

.PHONY: test-client
test-client: lint-client type-check-client

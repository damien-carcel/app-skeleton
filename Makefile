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
	cd ${CURDIR}/api && docker-compose run --rm php composer install --prefer-dist --optimize-autoloader --no-interaction --no-scripts

.PHONY: install-client-dependencies
install-client-dependencies: build-client-dev
	cd ${CURDIR}/client && docker-compose run --rm node yarn install

.PHONY: install-dependencies
install-dependencies: install-api-dependencies install-client-dependencies

.PHONY: update-api-dependencies
update-api-dependencies: build-api-dev
	cd ${CURDIR}/api && docker-compose run --rm php composer update --prefer-dist --optimize-autoloader --no-interaction --no-scripts

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
	cd ${CURDIR}/api && docker-compose run --rm php bin/console doctrine:schema:update --force

.PHONY: develop-api
develop-api: mysql
	cd ${CURDIR}/api && docker-compose run --rm --service-ports php bin/console server:run 0.0.0.0:8000

.PHONY: debug-api
debug-api: mysql
	cd ${CURDIR}/api && docker-compose run --rm --service-ports -e XDEBUG_ENABLED=1 php bin/console server:run 0.0.0.0:8000

.PHONY: serve-api
serve-api: build-api-prod mysql
	cd ${CURDIR}/api && docker-compose up -d api

.PHONY: fake-api
fake-api: install-client-dependencies
	cd ${CURDIR}/client && docker-compose up -d fake-api

.PHONY: develop-client
develop-client: fake-api install-client-dependencies
	cp ${CURDIR}/client/db.json.dist ${CURDIR}/client/db.json
	cd ${CURDIR}/client && docker-compose run --rm --service-ports node yarn webpack:serve

.PHONY: serve-client
serve-client: build-client-prod install-client-dependencies
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

.PHONY: check-style-api
check-style-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

.PHONY: fix-style-api
fix-style-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php

.PHONY: coupling-api
coupling-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/php-coupling-detector detect --config-file .php_cd.php

.PHONY: unit-api
unit-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpunit --testsuite "Unit tests" --log-junit tests/results/unit_tests.xml

.PHONY: acceptance-api
acceptance-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/behat --profile=acceptance -o std --colors -f pretty -f junit -o tests/results/acceptance

.PHONY: integration-api
integration-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/phpunit --testsuite="Integration tests" --log-junit tests/results/integration_tests.xml

.PHONY: end-to-end-api
end-to-end-api:
	cd ${CURDIR}/api && docker-compose run --rm php vendor/bin/behat --profile=end-to-end -o std --colors -f pretty -f junit -o tests/results/e2e

.PHONY: test-api
test-api: check-style-api coupling-api unit-api acceptance-api integration-api end-to-end-api

# Test the client

.PHONY: check-style-client
check-style-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run lint -t junit -o tests/results/lint.xml

.PHONY: type-check-client
type-check-client:
	cd ${CURDIR}/client && docker-compose run --rm node yarn run type-check

.PHONY: test-client
test-client: check-style-client type-check-client

up :
	docker-compose up -d

down :
	docker-compose down -v

yarn :
	docker-compose run --rm node yarn install

webpack :
	docker-compose run --rm node yarn build:dev

composer :
	docker-compose exec fpm composer update --prefer-dist --optimize-autoloader

schema :
	docker-compose exec fpm bin/console doctrine:schema:update --force

initialize : yarn webpack composer schema

lint :
	docker-compose run --rm node yarn lint

lint-fix :
	docker-compose run --rm node yarn lint-fix

phpcs :
	docker-compose exec fpm vendor/bin/phpcs -p --standard=PSR2 --extensions=php src tests/acceptance tests/integration tests/system

php-cs-fixer :
	docker-compose exec fpm vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

php-cs-fixer-fix :
	docker-compose exec fpm vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.php

unit :
	docker-compose exec fpm vendor/bin/phpspec run

integration :
	docker-compose exec fpm vendor/bin/behat --profile=integration

acceptance :
	docker-compose exec fpm vendor/bin/behat --profile=acceptance

system :
	docker-compose exec fpm vendor/bin/behat --profile=system

tests : lint phpcs php-cs-fixer unit integration acceptance system

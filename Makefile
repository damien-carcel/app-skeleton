up :
	docker-compose up -d

down :
	docker-compose down -v

composer :
	docker-compose exec fpm composer update --prefer-dist --optimize-autoloader

schema :
	docker-compose exec fpm bin/console doctrine:schema:update --force

initialize : composer schema

phpcs :
	docker-compose exec fpm vendor/bin/phpcs -p --standard=PSR2 --extensions=php src tests/acceptance tests/integration tests/system

php-cs-fixer :
	docker-compose exec fpm vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

unit :
	docker-compose exec fpm vendor/bin/phpspec run

integration :
	docker-compose exec fpm vendor/bin/behat --profile=integration

acceptance :
	docker-compose exec fpm vendor/bin/behat --profile=acceptance

system :
	docker-compose exec fpm vendor/bin/behat --profile=system

tests : phpcs php-cs-fixer unit integration acceptance system

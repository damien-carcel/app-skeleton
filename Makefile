up :
	docker-compose up -d

down :
	docker-compose down -v

composer :
	docker-compose exec fpm composer update --prefer-dist --optimize-autoloader

yarn :
	docker-compose run --rm node yarn install

schema :
	docker-compose exec fpm bin/console doctrine:schema:update --force

assets :
	docker-compose exec fpm bin/console assets:install --symlink --relative

webpack :
	docker-compose run node yarn run build:prod

initialize : composer yarn schema assets webpack

phpcs :
	docker-compose exec fpm vendor/bin/phpcs -p --standard=PSR2 --extensions=php src tests/acceptance tests/integration tests/system

php-cs-fixer :
	docker-compose exec fpm vendor/bin/php-cs-fixer fix --dry-run -v --diff --config=.php_cs.php

phpspec :
	docker-compose exec fpm vendor/bin/phpspec run

integration :
	docker-compose exec fpm vendor/bin/behat --profile=integration

acceptance :
	docker-compose exec fpm vendor/bin/behat --profile=acceptance

system :
	docker-compose exec fpm vendor/bin/behat --profile=system

tests : phpcs php-cs-fixer phpspec integration acceptance system

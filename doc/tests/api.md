# Testing the API

## Requirements

There is no need to fully install the application, you only need to [setup the database](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/api.md#setup-the-database) for integration and end-to-end tests.

## Introduction

The API is tested with various tools.

All the commands below are using `composer` scripts.
Look at the `composer.json` file to know what command is exactly launched.
You can run all the tests at once by launching:

```bash
$ docker-compose run --rm php composer tests
```

Here follows the complete list of those testing tools and what they are used for.
For each of them, configuration is already provided and ready to work.
 
## PHP coding style

The coding style is handled by `php-cs-fixer`. It is a tool to bot detect and fix coding standard violations.
Its configuration is defined in  the `.php_cs.php` file.

To use it, run the following command:
```bash
$ docker-compose run --rm php composer check-style
```

To fix detected issues:
```bash
$ docker-compose run --rm php composer fix-style
```

## Unit tests

Unit tests are managed through `PHPUnit`, a xUnit testing framework.
The configuration is defined in the `phpunit.xml.dist` file.

Unit tests are primarily used to tests the domain business.
They are also used to test DTOs and the fake adapters used in acceptance tests to replace the production ones.

To run them, use the following command:
```bash
$ docker-compose run --rm php composer unit
```

## Acceptance tests

These tests focus entirely on the application business (meaning the Application layer).
You should find no infrastructure here: no DB, no framework (meaning no requests, no web browser).


They are managed with `Behat`, a Story BDD framework. It is the PHP implementation if Cucumber.
All the necessary configuration is already present in the `behat.yaml` file.


They can run only with PHP, there is no need to start MySQL or the Symfony PHP server (or any other web server).

Launch them with the following command:
```bash
$ docker-compose run --rm php composer acceptance
```

## Integration tests

These tests are using PHPUnit, booting a Symfony kernel (unit tests don't).

They complete the acceptance tests by testing the infrastructure that was removed.
They are mostly used to test the production DB storage.

You'll need to have a MySQL database correctly configured to run them.
```bash
$ docker-compose run --rm php composer integration
```

### End to End tests

Those tests use the real API.  They are managed through `Behat`, like the acceptance tests.

Like for the integration tests, you'll need a MySQL database ready.

You can then run the tests with:
```bash
$ docker-compose run --rm php composer end-to-end
```

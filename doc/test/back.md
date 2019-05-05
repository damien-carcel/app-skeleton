# Testing the back-end application

## Requirements

There is no need to fully install the application, you only need to [setup the database](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back.md#setup-the-database) for integration and end-to-end tests.

## Introduction

The back-end application is extensively tested with various tools.

All the commands below are using `composer` scripts.
Look at the `composer.json` file to know what command is exactly launched.
You can run all the tests at once by launching:

```bash
$ docker-compose run --rm php composer tests
```

Here is the complete list of those testing tools and what they are used for.
For each of them, configuration is already provided and ready to work.
 
## PHP code sniffer

`phpcs` checks for coding standard violations. Its configuration is placed in the `phpcs.xml` file.
To use it, run the following command:
```bash
$ docker-compose run --rm php composer phpcs
```

## PHP Coding Standards Fixer

`php-cs-fixer` is another tool to detect coding standard violations. It can also fix the issues it detects.
It completes `phpcs` as they both don't detect exactly the same issues.
Its configuration is defined in two files:
- `.php_cs.php` for the code source and the tests code, except the specifications,
- `.php_cs.phpspec.php` for the specifications.

To use it, run the following command:
```bash
$ docker-compose run --rm php composer php-cs-fixer
```

To fix detected issues:
```bash
$ docker-compose run --rm php composer php-cs-fixer-fix
```
## phpspec

`phpspec` is a BDD tool used to write specifications. The configuration is defined in the `phpspec.yaml` file.

To use it, run the following command:
```bash
$ docker-compose run --rm php composer phpspec
```

## Behat

`Behat` is a Story BDD framework. It is the PHP implementation if Cucumber.
It is used to run acceptance, integration, and end-to-end tests.

All the necessary configuration is already present in the `behat.yaml` file.

### Acceptance tests

These tests focus on entirely on the business (meaning Domain and Application).
You should find no infrastructure here: no DB, no framework (meaning no requests, no web browser).

They can run only with PHP, there is no need to start MySQL or the Symfony PHP server (or any other web server).

Launch them with the following command:
```bash
$ docker-compose run --rm php composer acceptance
```

### Integration tests

These tests complete the acceptance tests by testing the infrastructure that was removed.
They are mostly used to test the real DB storage.

You'll need to have a MySQL database correctly configured to run them.
```bash
$ docker-compose run --rm php composer integration
```

### End to End tests

Those tests use the real application. Like for the integration tests, you'll need a MySQL database ready.

You can then run the tests with:
```bash
$ docker-compose run --rm php composer end-to-end
```

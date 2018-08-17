# Install the back-end application

## Requirements

Please read the [requirements for running Symfony](https://symfony.com/doc/current/reference/requirements.html).

The requirements checker tool is already provided as a development requirement, and the script that composer install is placed in `.gitignore`.

## Install

First, install the dependencies with composer:
```bash
$ composer update --prefer-dist --optimize-autoloader
```

Copy the `.env.dist` file as `.env` and configure the MySQL access by updating the `DATABASE_URL` environment variable.
Then update the schema of the MySQL database:
```bash
$ bin/console doctrine:schema:update --force
```

Finally, run the Symfony built-in server:
```bash
$ bin/console server:run
```

You can now access the application on [localhost:8000](http://localhost:8000).

## Testing

`phpcs` (PHP Code Sniffer) and `php-cs-fixer` (PHP Coding Standards Fixer) are used for static analysis, `phpspec` for unit tests, and `Behat` for acceptance, integration, and system (end to end) tests.

All the commands below are using `composer` scripts.
Look at the `composer.json` file to know what command is exactly launched.
You can run all the tests at once by launching:

```bash
$ composer tests
```

### PHP code sniffer

```bash
$ composer phpcs
```

### PHP Coding Standards Fixer

To detect issues in the code:
```bash
$ composer php-cs-fixer
```

To fix detected issues:
```bash
$ composer php-cs-fixer-fix
```
### phpspec

```bash
$ composer phpspec
```

### Behat

```bash
$ composer acceptance
$ composer integration
$ composer system
```

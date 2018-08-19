# Testing the back-end application

## Introductions

The back-end application is extensively tested with various tools.

All the commands below are using `composer` scripts.
Look at the `composer.json` file to know what command is exactly launched.
You can run all the tests at once by launching:

```bash
$ composer tests
```

Here is the complete list of those testing tools and what they are used for.
For each of them, configuration is already provided and ready to work.
 
## PHP code sniffer

`phpcs` checks for coding standard violations. To use it, run the following command:
```bash
$ composer phpcs
```

## PHP Coding Standards Fixer

`php-cs-fixer` is another tool to detect coding standard violations. It can also fix the issues it detects.
It completes `phpcs` as they both don't detect exactly the same issues.
Its configuration is defined in the `.php_cs.php` file.

To use it, run the following command:
```bash
$ composer php-cs-fixer
```

To fix detected issues:
```bash
$ composer php-cs-fixer-fix
```
## phpspec

`phpspec` is a BDD tool used to write specifications. The configuration is defined in the `phpspec.yaml` file.

To use it, run the following command:
```bash
$ composer phpspec
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
$ composer acceptance
```

### Integration tests

These tests complete the acceptance tests by testing the infrastructure that was removed.
They are mostly used to test the real DB storage.

You'll need to have a MySQL database correctly configured to run them.
```bash
$ composer integration
```

### End to End tests

Those tests use the real application. You'll need a MySQL database ready, but also the Symfony web server:
```bash
$ bin/console server:run
```

You also need a Chrome web browser (Google Chrome or Chromium) running in remote debug mode:
```bash
$ google-chrome-stable --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
```

Finally, the server URL will be different if you use the Symfony web server, or a production server like Apache or nginx, or Docker.
So the corresponding behat configuration is not present in the `behat.yaml` file.
You need to export it as an environment variable:
```bash
export BEHAT_PARAMS='{"extensions" : {"Behat\\MinkExtension" : {"base_url" : "http://localhost:8000"}}}'
```

You can now run the tests:
```bash
$ composer end-to-end
```

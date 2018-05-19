# The back-end application

This README only applied to the back-end part of the app-skeleton application.

## Requirements

## How to use it

First, install the dependencies with composer:
```bash
$ composer update
```

Then update the schema of the MySQL database:
```bash
$ bin/console doctrine:schema:update --force
```

Finally run the Symfony built-in server:
```bash
$ bin/console server:run
```

You can now access the application on [localhost:8000](http://localhost:8000).

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

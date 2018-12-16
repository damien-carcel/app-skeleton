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

You can now access the API on [localhost:8000](http://localhost:8000).

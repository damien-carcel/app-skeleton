# Install the back-end application using Docker

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

The installation procedure is mostly the same than for the [local installation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back/local.md#install),
except that every command is run into the `fpm` container. Also, you won't need to run the Symfony server. A `FPM` daemon and a `nginx` server are already setup.

## Start the containers

Copy the file `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`.
You may configure the nginx and MySQL output ports as you see fit.
Then start the containers by running:
```bash
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose up -d
```

## Install

First, install the dependencies with composer:
```bash
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose run --rm php composer update --prefer-dist --optimize-autoloader
```

Copy the `.env.dist` file as `.env` and configure the MySQL access by updating the `DATABASE_URL` environment variable.
Then update the schema of the MySQL database:
```bash
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose run --rm php bin/console doctrine:schema:update --force
```

## Run the application

You can either run the Symfony server (dev env, testing and developing only):
```bash
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose run --rm --service-ports php bin/console server:run 0.0.0.0:8000
```

Or you can launch the `nginx` container, that will launch automatically the `fpm` container too (production like conditions):
```bash
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose up -d nginx-back
```

You can now access the application on [localhost:8000](http://localhost:8000).

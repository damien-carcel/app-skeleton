# Run the back-end application using Docker

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

## Build the Docker images

```bash
$ docker-compose build --pull
```

## Install the dependencies

```bash
$ docker-compose run --rm php composer install --prefer-dist --optimize-autoloader
```

## Setup the database

Copy the file `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`.
You may configure the nginx and MySQL output ports as you see fit.
Then start the MySQL container by running:
```bash
$ docker-compose up -d mysql
```

Update the schema of the MySQL database:
```bash
$ docker-compose run --rm php bin/console doctrine:schema:update --force
```

## Serve the application

You can either run the Symfony server (dev env, testing and developing only):
```bash
$ docker-compose run --rm --service-ports php bin/console server:run 0.0.0.0:8000
```

Or you can launch the `nginx-back` container, that will launch automatically the `fpm` container too (production like conditions):
```bash
$ docker-compose up -d nginx-back
```

You can now access the API on [localhost:8000](http://localhost:8000) if you are using the Symfony server,
or [api.skeleton.docker.local](http://api.skeleton.docker.local) if you're using nginx+fpm alongside Traefik.

## Debugging the application

The `skeleton/php` image comes with XDebug installed and configured. It is by default deactivated.

To activate XDebug, set the environment variable `XDEBUG_ENABLED` to `1` in your `docker-compose.override.yaml` file.
The variable is set to `0` in the `docker-compose.override.yaml.dist` file example. 

Removing this environment variable (which is not present in the `docker-compose.yaml` file) will also keep XDebug deactivated.

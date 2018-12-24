# Run the back-end application using Docker

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

You also need to export the following environment variable:
```bash
export CURRENT_IDS="$(id -u):$(id -g)" 
```

## Install the dependencies

```bash
$ docker-compose run --rm php composer update --prefer-dist --optimize-autoloader
```

## Setup the database

Copy the file `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`.
You may configure the nginx and MySQL output ports as you see fit.
Then start the MySQL container by running:
```bash
$ docker-compose up -d mysql
```

Copy the `.env.dist` file as `.env` and configure the MySQL access by updating the `DATABASE_URL` environment variable.
Then update the schema of the MySQL database:
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

You can now access the API on [localhost:8000](http://localhost:8000).

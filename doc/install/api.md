# Run the AIP using Docker

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed, and the make utility.

## Serve the API

You can either run the API using the Symfony server (dev env, testing and developing only)
and access it on [localhost:8000](http://localhost:8000):
```bash
$ make develop-api
```

You can also use production like conditions with Nginx + FPM
and access the API on [skeleton-api.docker.local](http://skeleton-api.docker.local)
(you need to setup the URL in your `/etc/hosts` file and have Traefik working):
```bash
$ make serve-api
```

Both commands will build the required Docker iamges, check that composer dependencies are up to date, and setup the MySQL database.

## Debugging the API

The `carcel/skeleton/php` image comes with XDebug installed and configured. It is by default deactivated.

You can debug the API by running:
```bash
$ make develop-api DEBUG=1
```

This will launch the API through the Symfony web server, with XDebug activated.

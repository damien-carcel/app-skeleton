# Run the back-end application using Docker

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

## Build the Docker images

```bash
$ make build-back
```

## Serve the application

You can either run the Symfony server (dev env, testing and developing only)
and access the API on [localhost:8000](http://localhost:8000):
```bash
$ make develop-back
```

Or you can use production like conditions with Nginx + FPM
and access the API on [api.skeleton.docker.local](http://api.skeleton.docker.local)
(you need to setup the URL in your `/etc/hosts` file):
```bash
$ make serve-back
```

Both commands will check that composer dependencies are up to date, and setup the MySQL database.

## Debugging the application

The `carcel/skeleton/php` image comes with XDebug installed and configured. It is by default deactivated.

You can debug the API by running:
```bash
$ make debug-back
```

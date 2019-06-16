# Run the front-end application

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

## Build the Docker images

```bash
$ make build-front
```

## Serve the application

You can either choose to serve the application with the `webpack-dev-server` for development
or with `nginx` to reproduce production like conditions.

Both commands below will also check that `yarn` dependencies are up to date.

### webpack-dev-server

Running the `webpack-dev-server` allows to use live reloading while coding:
```bash
$ make develop-front
```

This option will also launch a fake API using the [JSON server](https://github.com/typicode/json-server).

You can access the front-end application on [localhost:8080](http://localhost:8080).

### nginx

You can start the nginx container by running:
```bash
$ make serve-front
```

This will automatically setup the Symfony API (back-end application) using `nginx` and `FPM`.

You can access the front-end application on [skeleton.docker.local](http://skeleton.docker.local).

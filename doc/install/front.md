# Run the front-end application

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed.

You also need to export the following environment variable:
```bash
export CURRENT_IDS="$(id -u):$(id -g)" 
```

## Build the Docker images

```bash
$ docker-compose build --pull
```

## Install the dependencies

```bash
$ docker-compose run --rm node yarn install
```

## Serve the application

Copy the content of the file `.env.dist` into a new file `.env`, and keep only the line you need (JSON server or back-end application).

You can either choose to serve the application with the `webpack-dev-server` or with `nginx`.

### webpack-dev-server

Running the `webpack-dev-server` allows to use live reloading while coding:
```bash
$ docker-compose run --rm node yarn serve
```

### nginx

Build the application either for development (non minimized JS and CSS files):
```bash
$ docker-compose run --rm node yarn run build:dev
```

or for production (minimized JS and CSS files) by running:
```bash
$ docker-compose run --rm node yarn run build:prod
```

Copy the file `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`.
You may configure the nginx output port as you see fit. Then start the nginx container by running:
```bash
$ docker-compose up -d nginx-front
```

## Run the back-end API

You can launch the fake JSON server by running:
```bash
$ docker-compose run --rm node yarn run serve-api
```

If you chose to use the real back-end application, follow [this documentation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back.md) to run it.

You can now access the application on [localhost:8080](http://localhost:8080) (`8080` being the default output of the `nginx-front` container).

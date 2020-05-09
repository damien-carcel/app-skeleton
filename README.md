# A web application skeleton using React and Symfony

[![CircleCI](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master.svg?style=svg)](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master)

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a client application, written in TypeScript, using ReactJS, and managed with webpack,
- a REST API written in PHP using Symfony 4 and managed with Symfony Flex.

## How to use it?

### Run the full application

To be able to run both the API and the client, you'll first need to setup Traefik as a local reverse proxy.
Please follow [this documentation](https://github.com/damien-carcel/traefik-as-local-reverse-proxy) to achieve that.

Then you can start the full application using docker by running:
```bash
$ make install
```

### Run the API alone

You can either run the API using the Symfony server (dev env, testing and developing only) and access it on
[localhost:8000](http://localhost:8000):
```bash
$ make develop-api
```

Or can use production like Nginx + FPM and access the API on [skeleton-api.docker.local](http://skeleton-api.docker.local)
(you need to set up the URL in your `/etc/hosts` file and have Traefik working):
```bash
$ make serve-api
```

Both commands will build the required Docker images, check that Composer dependencies are up to date, and setup the MySQL database.

### Debugging the API

The `carcel/skeleton/php` image comes with XDebug installed and configured. It is by default deactivated.

You can debug the API by running:
```bash
$ make develop-api DEBUG=1
```

This will launch the API through the Symfony web server, with XDebug activated.

### Serve the application with `webpack-dev-server`

You can start the client using the `webpack-dev-server`:
```bash
$ make develop-client
```

This command will build the required Docker images, check that `yarn` dependencies are up to date, launch the API, and
serve the application using the Webpack dev server with hot reloading.

You can access the client application on [localhost:8080](http://localhost:8080).

## Using Docker BuildKit

To use the more efficient BuildKit toolkit to build the Docker images, export the following environment variables:

```bash
COMPOSE_DOCKER_CLI_BUILD=1
DOCKER_BUILDKIT=1
```

You can export them directly before running `make install`, or make them permanent by adding them in your shell profile.

## Testing

You can run the API and the client application tests with the following commands:

```bash
$ make api-tests
$ make client-tests
```

## License

This repository is under the MIT license. See the complete license in the [LICENSE](https://github.com/damien-carcel/app-skeleton/blob/master/LICENSE) file.

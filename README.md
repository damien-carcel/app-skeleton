# A web application skeleton using React and Symfony

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a client application, written in TypeScript, using ReactJS, and managed with webpack,
- a REST API written in PHP using Symfony 4 and managed with Symfony Flex.

## How to use it?

### Run the application

To be able to run both the API and the client in production-like mode, you'll first need to install
[mkcert](https://github.com/FiloSottile/mkcert).

Then you can start the full application using docker by running:
```bash
$ mkcert -install
$ make serve
```

The full list of commands is available by running:
```bash
$ make
```

This will describe how to serve only the API or the client in development mode, how to run the tests, to update the
dependencies, and more.

### Debugging the API

The `carcel/skeleton/php` image comes with XDebug installed and configured. It is by default deactivated.

You can debug the API by running:
```bash
$ make develop-api DEBUG=1
```

This will launch the API through the PHP development server with XDebug activated.

## Using Docker BuildKit

To use the more efficient BuildKit toolkit to build the Docker images, export the following environment variables:

```bash
COMPOSE_DOCKER_CLI_BUILD=1
DOCKER_BUILDKIT=1
```

You can export them directly before running `make serve`, or make them permanent by adding them to your shell profile.

## License

This repository is under the MIT license. See the complete license in the [LICENSE](https://github.com/damien-carcel/app-skeleton/blob/main/LICENSE) file.

# A web application skeleton using React and Symfony

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a front-end application, written in TypeScript, using ReactJS, and managed with webpack,
- a back-end REST API, written in PHP, using Symfony 4, and managed with Symfony Flex.

## How to use it?

Both front-end and back-end applications can be run alone. 

The back-end application is a REST API, so it can be used directly through HTTP calls. 
Follow these documentations to install the back-end application:
- [locally](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back/local.md) (requires PHP CLI and MySQL or MariaDB),
- [through Docker](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back/docker.md) (requires Docker and Docker Compose).

The front-end application is made to consume an API, the real implementation being provided by the back-end application.
But you can also use a fake API thanks to the [`json-server` library](https://github.com/typicode/json-server) for development.

Follow these documentations to install the front-end application with the JSON server:
- [locally](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/front/local.md) (requires Yarn or NPM),
- [through Docker](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/front/docker.md) (requires Docker and Docker Compose).

You can also run both front-end and back-end applications together. Again, you can install them:
- [locally](https://github.com/damien-carcel/app-skeleton/blob/master/doc/configure/local.md) (the front-end application is run locally, but the back-end application can be run either locally or through Docker),
- [through Docker](https://github.com/damien-carcel/app-skeleton/blob/master/doc/configure/docker.md) (both front-end and back-end application are run through Docker).

## License

This repository is under the MIT license. See the complete license in the [LICENSE](https://github.com/damien-carcel/app-skeleton/blob/master/LICENSE) file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

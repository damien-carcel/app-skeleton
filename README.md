# A web application skeleton using React and Symfony

[![CircleCI](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master.svg?style=svg)](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master)

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a client application, written in TypeScript, using ReactJS, and managed with webpack,
- a REST API written in PHP using Symfony 4 and managed with Symfony Flex.

## How to use it?

The client application is made to consume an API, the real implementation being the Symfony one.
But you can also use a fake API thanks to the [`json-server` library](https://github.com/typicode/json-server) (for development and testing purpose only).

To be able to run the full application (both the API and the client), you'll first need to setup Traefik as a local reverse proxy.
Please follow [this documentation](https://github.com/AymericPlanche/local-reverse-proxy) to achieve that.

Then you can start the full application using docker by running:
```bash
$ make install
```

You can also run the two applications separately.
Follow [this documentation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/api.md) to run the API
and [this one](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/client.md) to run the client.

## Testing

The API is fully tested. You can follow [this documentation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/tests/api.md) for a detailed explanation about how running the tests:

The client application is not tested for now. This will be [coming soon](https://github.com/damien-carcel/app-skeleton/issues/15).

## License

This repository is under the MIT license. See the complete license in the [LICENSE](https://github.com/damien-carcel/app-skeleton/blob/master/LICENSE) file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

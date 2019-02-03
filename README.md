# A web application skeleton using React and Symfony

[![CircleCI](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master.svg?style=svg)](https://circleci.com/gh/damien-carcel/app-skeleton/tree/master)

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a front-end application, written in TypeScript, using ReactJS, and managed with webpack,
- a back-end REST API, written in PHP, using Symfony 4, and managed with Symfony Flex.

## How to use it?

The front-end application is made to consume an API, the real implementation being provided by the back-end application.
But you can also use a fake API thanks to the [`json-server` library](https://github.com/typicode/json-server) (for development and testing purpose only).

Follow [this documentation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/front.md) to run the front-end application (with either the JSON server or the back-end application),
and follow [this one](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back.md) run the back-end application.

## Testing

The back-end application is fully tested. You can follow [this documentation](https://github.com/damien-carcel/app-skeleton/blob/master/doc/test/back.md) for a detailed explanation about how running the tests:

The front-end application is not tested for now. This will be [coming soon](https://github.com/damien-carcel/app-skeleton/issues/15).

## License

This repository is under the MIT license. See the complete license in the [LICENSE](https://github.com/damien-carcel/app-skeleton/blob/master/LICENSE) file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

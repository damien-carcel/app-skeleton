# My application skeleton

This is a basic application skeleton to easily bootstrap a web application. There is currently 3 maintained branches.

- `master`: A basic full front application with native ES 6, managed with webpack.
- `react`: A ReactJS full front application (ES 6), managed with webpack.
  **It inherits from the `master` branch**.
- `react-symfony`: A full stack web application, with ReactJS and webpack for the front-end part, and Symfony 4 (managed with Flex) for the back-end part (no Form nor Twig component).
  **It inherits from the `react` branch**.
- `symfony`: A Symfony 4 application (managed with Flex). Just add TWIG to have an old-school full stack application.
  **It inherits from the `react-symfony` branch**.

## How to use it

**The following documentation is only valid for the `master` branch**

You need to have `node` 6+ and `npm` 5+ installed on your computer, but it is recommended to use `yarn` to manage your dependencies.

First install the dependencies:

```bash
$ yarn install
# or
$ npm install
```
Then run the test server:

```bash
$ yarn serve
# or
$ npm run serve
```

You can build the files for production environments by running:

```bash
$ yarn build:prod
# or
$ npm run build:prod
```

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

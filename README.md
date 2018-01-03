# My application skeleton

This is a basic application skeleton to easily bootstrap a web application. There is currently 3 maintained branches.

- `master`: A basic full front application with native ES 6, managed with webpack.
- `react`: A ReactJS full front application (ES 6), managed with webpack.
  **It inherits from the `master` branch**.
- `symfony`: A Symfony 4 full stack web application (managed with Flex). Twig is used for the front-end part, with Webpack to manage assets.
  **It inherits from the `react` branch**.
- `symfony-api`: A Symfony 4 REST application (managed with Flex), no front-end element.
  **It inherits from the `symfony` branch**.

## How to use it

**The following documentation is only valid for the `react` branch**

You need to have `node` 6+ and `npm` 5+ installed on your computer, but it is recommended to use `yarn` to manage your dependencies.

### Build the application

First install the dependencies:

```bash
$ yarn install
# or
$ npm install
```

Then run the test server, which will open the application in your default browser (at `localhost:9000`):

```bash
$ yarn serve
# or
$ npm run serve
```

If you want to run the application through a web server like `Apache` or `nginx`, you can build it for production by running:

```bash
$ yarn build:prod
# or
$ npm run build:prod
```

or for development (non minimized Javascript and CSS files) by running:

```bash
$ yarn build:dev
# or
$ npm run build:dev
```

### Configure the API access

This application is made to consume an API. You can either use a real API or use the `json-server` library.

- **With a real API**

First, clone the [`symfony-api`](https://github.com/damien-carcel/app-skeleton/tree/symfony-api) branch from this repository and follow its installation instructions.

Then copy the configuration file `config/api-skeleton.json` into `configuration/api.json`. This configuration is made accordingly to the `symfony-api` branch default configuration.

- **With the `json-server` library**

Copy the configuration file `config/json-server.json` into `configuration/api.json`, then run the JSON server:

```bash
$ yarn serve-api
# or
$ npm run serve-api
```

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

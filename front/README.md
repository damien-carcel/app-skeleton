# The front-end application

This README relates only to the front-end application.

## Requirements

You need Yarn 1.x or NPM 6.x with NodeJS 8 or 10.

it could work with other versions, but those are the one tested through continuous integration.

### Configure the API access

This application is made to consume an API. The full skeleton application uses the real API located in the `back` directory. But you can also use a fake API thanks to the `json-server` library.

Copy the content of the file `.env.dist` into a new file `.env`, keep only the line dedicated to the JSON server, then run the server:
```bash
$ yarn serve-api
# or
$ npm run serve-api
```

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
$ yarn run build:prod
# or
$ npm run build:prod
```

or for development (non minimized Javascript and CSS files) by running:
```bash
$ yarn build:dev
# or
$ npm run build:dev
```

You can now access the application on [localhost:3000](http://localhost:8080).

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

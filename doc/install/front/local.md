# Install the front-end application

## Requirements

You need Yarn 1.x or NPM 6.x with NodeJS 8 or 10.

It may work with older versions, but those are the one tested through continuous integration.

## Install

First, launch the JSON server by running:
```bash
$ yarn run serve-api
# or
$ npm run serve-api
```

Then install the dependencies:
```bash
$ yarn install
# or
$ npm install
```

Copy the content of the file `.env.dist` into a new file `.env`, and keep only the line dedicated to the JSON server.

Finally, run the test server, which will open the application in your default browser at [localhost:8080](http://localhost:8080/)`:
```bash
$ yarn run serve
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
$ yarn run build:dev
# or
$ npm run build:dev
```

# Symfony, React and webpack

This is a basic skeleton to easily bootstrap a full stack web application, with ReactJS and webpack on the front-end side, and Symfony 4 on the back-end side.

## How to use it

The easiest way is to use Docker and Docker Compose. Copy the file `docker-compose.yml.dist` as `docker-compose.yml` at the root of your project.

Launch the containers with
```bash
$ docker-compose up -d
```

First, install the dependencies (for both back and front-end):
```bash
$ docker-compose exec fpm composer update
$ docker-compose run --rm node yarn install
```

Then build the front-end:
```bash
$ docker-compose run --rm node yarn build:prod
```

You can alternatively use `build:dev` to have non minified results, or `build:watch` (also non minified) for watching dependencies and recompiling on change.

You can now access the application on [localhost:8080](http://localhost:8080).

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

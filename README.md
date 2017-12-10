# My application skeleton

This is a basic application skeleton to easily bootstrap a web application. There is currently 3 maintained branches.

- `master`: A basic full front application with native ES 6, managed with webpack.
- `react`: A ReactJS full front application (ES 6), managed with webpack.
  **It inherits from the `master` branch**.
- `react-symfony`: A full stack web application, with ReactJS and webpack for the front-end part, and Symfony 4 (managed with Flex) for the back-end part (no Form nor Twig component).
  **It inherits from the `react` branch**.

## How to use it

**The following documentation is only valid for the master branch**

The easiest way is to use Docker and Docker Compose. Copy the file `docker-compose.yml.dist` as `docker-compose.yml` at the root of your project.

Launch the containers with
```bash
$ docker-compose up -d
```

First, install the dependencies (for both back-end and front-end):
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

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

**The following documentation is only valid for the `symfony-api` branch**

The easiest way is to use Docker and Docker Compose. Copy the file `docker-compose.yml.dist` as `docker-compose.yml` at the root of your project.

Launch the containers with
```bash
$ docker-compose up -d
```

First, install the dependencies (for both back-end and front-end):
```bash
$ docker-compose exec fpm composer update
```

Then update the schema of the MySQL database:
```bash
$ docker-compose exec fpm bin/console doctrine:schema:update --force
```

You can optionally load some test fixtures as follow:
```bash
$ docker-compose exec fpm bin/console doctrine:fixtures:load
```

You can now access the application on [localhost:8080](http://localhost:8080).

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

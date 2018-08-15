# A web application skeleton using React and Symfony

This is a skeleton to easily bootstrap a modern web project.

It is composed of two distinct applications:
- a front-end application, written in TypeScript using ReactJS and managed with webpack,
- a back-end REST API, written in PHP using Symfony 4 and managed with Symfony Flex.

## How to use it

Both back-end and front-end applications can be run alone. Their respective behavior is detailed in their own README.md:
[front-end](https://github.com/damien-carcel/app-skeleton/blob/master/front/README.md) and [back-end](https://github.com/damien-carcel/app-skeleton/blob/master/back/README.md).

In the following documentation, we will focus on running both applications together using `docker-compose` using the provided `docker-compose.yaml` files. They are 2 of them, one for the front-end, one for the back-end.

### Configure the application

Copy the content of the file `.env.dist` into a new file `.env`. Keep only the line you need out of the 3 options (everything running with Docker, only back-end running with Docker or all running locally).

Then copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml` and configure it as you see fit. This will allow you to access the application from outside the containers.

### Build the application

First start the containers. Start with those of the front-end, so the Docker network is initialized, and continue with the back-end:
```bash
$ cd /path/to/the/project/front && docker-compose up -d
$ cd /path/to/the/project/back && docker-compose up -d
```

Then install the dependencies and build the front-end application:
```bash
$ cd /path/to/the/project/front
$ docker-compose run --rm node yarn install
$ docker-compose run --rm node yarn build:prod
```

Finally, install the dependencies of the back-end application and setup the database:
```bash
$ cd /path/to/the/project/back
$ docker-compose exec fpm composer update --prefer-dist --optimize-autoloader
$ docker-compose exec fpm composer update-schema
```

You can now access the application on [localhost:8080](http://localhost:8080). You can also directly access the API on [localhost:8000](http://localhost:8000)

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

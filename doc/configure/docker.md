# Run the front-end and back-end applications together using Docker

**DISCLAIMER**: THIS DOCUMENTATION DOESN'T WORK FOR NOW. IT WILL BE FIXED BY ISSUE [#79](https://github.com/damien-carcel/app-skeleton/issues/79).

## Launch the containers

Both applications have their own compose files. This needs a specific configuration so they can both run on the same network and talk to each other.

For both applications, copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml` (you may configure the output ports it as you see fit, but default values should work just fine).
In the override file of the back-end application, uncomment the lines related to the networks. These commented lines allow to link the back-end application network with the one of the front-end application.

Then start the containers. You must first start those of the front-end application, so the Docker network is initialized, and continue with the back-end application:
```bash
$ cd /path/to/the/project/front && docker-compose up -d
$ cd /path/to/the/project/back && docker-compose up -d
```

## Configure the applications

Copy the content of the file `.env.dist` into a new file `.env` for both the front-end and the back-end applications.

In the back-end application, configure the environment variable `DATABASE_URL` as follow: `DATABASE_URL=mysql://app-skeleton:app-skeleton@mysql:3306/app-skeleton`.
Those are the database name, user, and password, already configured in the compose file.

In the front-end application, keep the line dedicated to [full Docker installation](https://github.com/damien-carcel/app-skeleton/blob/master/front/.env.dist#L10).

## Build the applications

You can now install the dependencies and build the front-end application:
```bash
$ cd /path/to/the/project/front
$ docker-compose run --rm node yarn install
$ docker-compose run --rm node yarn run build:prod
```

Then, install the dependencies of the back-end application and setup the database:
```bash
$ cd /path/to/the/project/back
$ docker-compose exec fpm composer update --prefer-dist --optimize-autoloader
$ docker-compose exec fpm bin/console doctrine:schema:update --force
```

You can now access the application on [localhost:8080](http://localhost:8080). You can also directly access the API on [localhost:8000](http://localhost:8000)

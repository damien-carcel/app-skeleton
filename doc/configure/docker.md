# Run the front-end and back-end applications together using Docker

## Configure the applications

For both applications, copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`. You may configure the output ports it as you see fit, but default values should work just fine.

Then copy the content of the file `.env.dist` into a new file `.env`, again for both the front-end and the back-end applications.

In the back-end application, configure the environment variable `DATABASE_URL` as follow: `DATABASE_URL=mysql://app-skeleton:app-skeleton@mysql:3306/app-skeleton`.
Those are the database name, user, and password, already configured in the compose file.

In the front-end application, keep the line dedicated to [full Docker installation](https://github.com/damien-carcel/app-skeleton/blob/master/front/.env.dist#L10).

## Launch the containers

```bash
$ cd /path/to/the/project/front && CURRENT_IDS="$(id -u):$(id -g)" docker-compose up -d nginx-front
$ cd /path/to/the/project/back && CURRENT_IDS="$(id -u):$(id -g)" docker-compose up -d mysql nginx-back
```

## Build the applications

You can now install the dependencies and build the front-end application:
```bash
$ cd /path/to/the/project/front
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose run --rm node yarn install
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose run --rm node yarn run build:prod
```

Then, install the dependencies of the back-end application and setup the database:
```bash
$ cd /path/to/the/project/back
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose exec fpm composer update --prefer-dist --optimize-autoloader
$ CURRENT_IDS="$(id -u):$(id -g)" docker-compose exec fpm bin/console doctrine:schema:update --force
```

You can now access the application on [localhost:8080](http://localhost:8080). You can also directly access the API on [localhost:8000](http://localhost:8000)

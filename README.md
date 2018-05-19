# My application skeleton

This is a skeleton to easily bootstrap a web application.

It is composed of two main part:
- a front-end application, written with ReactJS and managed with webpack,
- a back-end REST API, written with Symfony 4 and managed with Flex.

## How to use it

Both back and front-end applications can be ran alone. Their respective behavior is detailed in their own README.md:
[front](https://github.com/damien-carcel/app-skeleton/blob/master/front/README.md) and [back](https://github.com/damien-carcel/app-skeleton/blob/master/back/README.md).

In the following documentation, we will focus on running both applications together using `docker-compose` using the provided `Makefile` and `docker-compose.yaml` files.

### Configure the API access

Copy the configuration file `config/docker-compose.json` into `config/api.json`. This configuration is made to run with the Symfony API.

Then copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml`. This will allow you to access the application from outside the containers.

### Build the application

First start the containers:
 
```bash
$ make up
```

Then install the dependencies and prepare the database:

```bash
$ make initialize
```

You can now access the application on [localhost:9000](http://localhost:9000). You can also directly access the API on [localhost:8000](http://localhost:8000)

## License

This repository is under the MIT license. See the complete license in the `LICENSE` file.

The "Hack" font provided as asset example is distributed under [the Hack Open Font License v2.0 and the Bitstream Vera License](https://github.com/chrissimpkins/Hack/blob/master/LICENSE.md).

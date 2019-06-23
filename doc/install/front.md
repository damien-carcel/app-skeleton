# Run the front-end application

## Requirements

You need the latest versions of [Docker engine](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/) installed, and the make utility.

## Serve the application with `webpack-dev-server`

You can start the front-end using the `webpack-dev-server`:
```bash
$ make develop-front
```

This command will build the required Docker images, check that `yarn` dependencies are up to date
and launch a fake API using the [JSON server](https://github.com/typicode/json-server).

You can access the front-end application on [localhost:8080](http://localhost:8080).

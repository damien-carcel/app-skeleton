# Testing the back-end application using Docker

To run the tests with Docker, you can follow the documentation for the [local testing procedure](https://github.com/damien-carcel/app-skeleton/blob/master/doc/test/back/local.md) almost to the letter.
The main difference is that the tests must always be run in the `fpm` Docker container.
To do so, simply prefix every command with `docker-compose exec fpm`.

Only the "End to End" tests require more configuration. To run them, you still need to launch a Chrome web browser.
This one is already present in the `fpm` container, so you can launch it with:
```bash
$ docker-compose exec fpm google-chrome-stable --no-sandbox --disable-gpu --headless --remote-debugging-address=0.0.0.0 --remote-debugging-port=9222
```

It is mandatory to add the `--no-sandbox` option, as Chrome sandbox does not work in containers.

Also, you do not need to export the `BEHAT_PARAMS` environment variable, it is already set in the `docker-compose.override.yaml.dist` file.
So by just copying it as `docker-compose.override.yaml`, everything already work.

You can now run the End to End tests with:
```bash
$ docker-compose exec fpm composer end-to-end
```

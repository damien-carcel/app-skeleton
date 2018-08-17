# Run the front-end and back-end applications together

To achieve that, you simply need to install them separately as described in their respective documentations, with one slight modification in the configuration of the front-end application.

First, install the back-end application exactly as described [here](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/back/local.md).

Then, [install the front-end application](https://github.com/damien-carcel/app-skeleton/blob/master/doc/install/front/local.md).
But, when you copy the `.env.dist` file as `.env`, keep the line dedicated to the [real back-end application](https://github.com/damien-carcel/app-skeleton/blob/master/front/.env.dist#L7).

This will work with a locally installed back-end application as well as with one installed through Docker, as the API output is [localhost:8000](http://ocalhost:8000/) by default, in both cases.

###################################
# Node image used for development #
###################################

FROM node:lts-alpine as dev

RUN apk add --no-cache \
    autoconf           \
    automake           \
    bash               \
    g++                \
    libc6-compat       \
    libjpeg-turbo-dev  \
    libpng-dev         \
    make               \
    nasm

RUN mkdir /.npm && chmod 777 /.npm

EXPOSE 3000 8000

#######################################
# Intermediate image used to prepare  #
# the application for production      #
#######################################

FROM dev as builder

ARG API_BASE_URL_FOR_PRODUCTION

USER node
RUN mkdir /home/node/client
WORKDIR /home/node/client

COPY --chown=node:node . .
RUN yarn install && \
    API_BASE_URL=${API_BASE_URL_FOR_PRODUCTION} yarn run webpack:build

###################################
# Nginx image used for production #
###################################

FROM nginx:alpine as client

COPY docker/upload.conf /etc/nginx/conf.d/upload.conf

# Copy the application
COPY --from=builder --chown=root:root /home/node/client/public /usr/share/nginx/html
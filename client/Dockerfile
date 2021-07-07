#######################################
# Intermediate image used to prepare  #
# the application for production      #
#######################################

FROM node:slim as builder

ARG API_BASE_URL_FOR_PRODUCTION

USER node
RUN mkdir /home/node/client
WORKDIR /home/node/client

COPY --chown=node:node . .
RUN yarn install --frozen-lockfile --check-files && \
    REACT_APP_API_BASE_URL=${API_BASE_URL_FOR_PRODUCTION} yarn build

###################################
# Nginx image used for production #
###################################

FROM nginx:alpine as client

COPY docker/nginx.conf /etc/nginx/default.conf

# Copy the application
COPY --from=builder --chown=root:root /home/node/client/build /usr/share/nginx/html

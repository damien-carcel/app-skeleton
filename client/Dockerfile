###################################
# Node image used for development #
###################################

FROM node:12-buster-slim as dev

RUN echo 'APT::Install-Recommends "0" ; APT::Install-Suggests "0" ;' > /etc/apt/apt.conf.d/01-no-recommended && \
    echo 'path-exclude=/usr/share/doc/*' > /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/groff/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/info/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/linda/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/lintian/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/locale/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/man/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    apt-get update && \
    apt-get --yes install \
        bzip2 \
        ca-certificates \
        firefox-esr \
        wget && \
    wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb && \
    apt install -fy ./google-chrome-stable_current_amd64.deb && \
    rm -f google-chrome-stable_current_amd64.deb && \
    apt-get clean && \
    apt-get --yes autoremove --purge && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN mkdir /.npm && chmod 777 /.npm

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
    API_BASE_URL=${API_BASE_URL_FOR_PRODUCTION} yarn run build

###################################
# Nginx image used for production #
###################################

FROM nginx:alpine as client

COPY docker/upload.conf /etc/nginx/conf.d/upload.conf

# Copy the application
COPY --from=builder --chown=root:root /home/node/client/dist /usr/share/nginx/html

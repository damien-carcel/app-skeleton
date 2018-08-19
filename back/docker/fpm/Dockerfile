FROM akeneo/fpm:php-7.2
MAINTAINER Damien Carcel <damien.carcel@gmail.com>

# Install Google Chrome and its dependencies
RUN apt-get update && \
    apt-get install --no-install-recommends --no-install-suggests --yes --quiet \
                    fonts-liberation \
                    libappindicator3-1 \
                    libasound2 \
                    libatk-bridge2.0-0 \
                    libatk1.0-0 \
                    libcairo2 \
                    libcups2 \
                    libdbus-1-3 \
                    libexpat1 \
                    libgdk-pixbuf2.0-0 \
                    libglib2.0-0 \
                    libgtk-3-0 \
                    libnspr4 \
                    libnss3 \
                    libpango-1.0-0 \
                    libpangocairo-1.0-0 \
                    libx11-6 \
                    libx11-xcb1 \
                    libxcb1 \
                    libxcomposite1 \
                    libxcursor1 \
                    libxdamage1 \
                    libxext6 \
                    libxfixes3 \
                    libxi6 \
                    libxrandr2 \
                    libxrender1 \
                    libxss1 \
                    libxtst6 \
                    lsb-release \
                    xdg-utils && \
    cd /tmp && \
    wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb  && \
    dpkg -i google-chrome-stable_current_amd64.deb && \
    apt-get clean && apt-get --yes --quiet autoremove --purge && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
           /usr/share/doc/* /usr/share/groff/* /usr/share/info/* /usr/share/linda/* \
           /usr/share/lintian/* /usr/share/locale/* /usr/share/man/* \
           /tmp/google-chrome-stable_current_amd64.deb

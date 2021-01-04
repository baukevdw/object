FROM php:7.2-cli

WORKDIR /app

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
        install-php-extensions xdebug @composer-2 && \
        apt-get update && apt-get install -y git unzip zip && rm -rf /var/lib/apt/lists/*

COPY . /app

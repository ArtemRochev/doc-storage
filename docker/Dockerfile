FROM serversideup/php:8.3-fpm-nginx

USER root

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        git \
        unzip \
        curl \
        librabbitmq-dev \
        libssl-dev \
        libzip-dev \
        libpq-dev \
        libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install sockets pcntl intl \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
#COPY docker/default.ini /usr/local/etc/php/conf.d/default.ini

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

WORKDIR /var/www/doc_storage

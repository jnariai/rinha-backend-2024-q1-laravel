FROM php:8.3.2-fpm-alpine3.19

WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    build-base \
    openssl-dev \
    postgresql-dev \
    zlib-dev \
    linux-headers \
    oniguruma-dev

RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install sockets
RUN docker-php-ext-install zip
RUN docker-php-ext-install xml
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install dom
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install session

COPY --from=composer:2.7.1 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www -s /bin/bash www

COPY ../app .
COPY ../bootstrap .
COPY ../config .
COPY ../database .
COPY ../public .
COPY ../resources .
COPY ../routes .
COPY ../storage .
COPY ../tests .
COPY ../.env .
COPY ../artisan .
COPY ../composer.json .
COPY ../composer.lock .
COPY ../package.json .
COPY ../vite.config.js .

COPY --chown=www:www . /var/www/html/

USER www

RUN COMPOSER=composer.json composer install \
    --no-cache --no-progress \
    --optimize-autoloader --no-dev --no-interaction && \
    # php artisan cache:clear && \
    # php artisan route:cache && \
    php artisan view:cache && \
    php artisan event:cache && \
    php artisan config:cache && \
    php artisan optimize

EXPOSE 9000

CMD [ "php-fpm" ]
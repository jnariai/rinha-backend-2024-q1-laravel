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
    oniguruma-dev \
    autoconf

RUN docker-php-ext-install gd pdo pdo_pgsql sockets zip xml mbstring dom fileinfo session pcntl

RUN pecl install swoole \
    && docker-php-ext-enable swoole

COPY --from=composer:2.7.1 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www && \
    adduser -D -u 1000 -G www -s /bin/bash www

COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction \
    && php artisan octane:install --server=swoole && \
    php artisan cache:clear && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan event:cache && \
    php artisan config:cache && \
    php artisan optimize

EXPOSE 9000

CMD ["php", "artisan", "octane:start", "--host=0.0.0.0", "--port=9000"]

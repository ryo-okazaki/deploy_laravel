FROM php:8.2-fpm

ENV TZ=Asia/Tokyo \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libzip-dev \
        libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpq-dev \
        git \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql opcache

COPY ./ecs_dockerimages/php-fpm/www.conf /etc/php-fpm.d/www.conf
COPY ./ecs_dockerimages/php-fpm/php.ini /usr/local/etc/php
COPY ./ecs_dockerimages/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY ./laravel /var/www/laravel

WORKDIR /var/www/laravel

RUN composer install

CMD ["php-fpm", "--allow-to-run-as-root"]
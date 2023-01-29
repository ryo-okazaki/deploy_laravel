FROM php:8.2-fpm

#pdoインストール
RUN docker-php-ext-install pdo_mysql

#composerインストール
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

#gitインストール
RUN apt-get update
RUN apt-get install -y git

WORKDIR /var/www/html
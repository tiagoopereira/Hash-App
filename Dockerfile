FROM php:8-fpm
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_sqlite
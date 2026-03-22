FROM php:8.2-apache
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


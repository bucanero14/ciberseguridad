FROM php:8.2-apache
WORKDIR /var/www/html
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./src/ /var/www/html/
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www/html
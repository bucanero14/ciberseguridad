FROM php:7.4-apache
WORKDIR /var/www/html
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli bcmath

COPY ./src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
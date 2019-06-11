FROM php:7.3.6-fpm-stretch AS php-local

RUN apt-get update \
    && apt-get install git unzip libpq-dev -y \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin \
    && php -r "unlink('composer-setup.php');"

RUN usermod -u 1000 www-data
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer/
USER www-data


FROM nginx:1.17.0 AS nginx-local

COPY ./ingress.conf /etc/nginx/conf.d/ingress.conf
RUN rm /etc/nginx/conf.d/default.conf
RUN ls /etc/nginx/conf.d/

RUN mkdir -p /var/www/html/public/
RUN echo "" > /var/www/html/public/index.php

FROM php:8.3-fpm
RUN apt-get -y update
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/api.aftaa.ru
CMD ["php-fpm"]

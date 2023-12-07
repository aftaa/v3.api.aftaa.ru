FROM php:8.3-fpm
RUN apt-get -y update
RUN apt-get install -y libicu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/v3.api.aftaa.ru
CMD ["php-fpm"]

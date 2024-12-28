FROM php:8.4-fpm

WORKDIR /var/www/api.aftaa.ru

RUN set -exv; \
    apt-get update && \
    apt-get install -y --no-install-recommends \
    libzip-dev \
    libicu-dev

RUN docker-php-ext-install opcache \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql

RUN set -exv; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=2.8.4 --filename=composer

CMD ["php-fpm"]

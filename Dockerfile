FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www

COPY . .

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
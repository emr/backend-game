FROM php:8.0-cli
RUN apt-get update && apt-get install -y zip
RUN pecl install redis && docker-php-ext-enable redis
RUN curl https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
WORKDIR /var/www/app
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-scripts
VOLUME /var/www/app/vendor
VOLUME /var/www/app/var
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "public/index.php"]

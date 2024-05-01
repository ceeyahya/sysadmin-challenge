FROM php:8.3-fpm-alpine

RUN apk update; \
    apk upgrade;

RUN apk add libpng libpng-dev linux-headers mysql-client libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev gd && docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    ln -s /root/.composer/vendor/bin/drush /usr/local/bin/drush

RUN composer require drush/drush

RUN apk add --no-cache --virtual .dd-build-deps libpng-dev freetype-dev libjpeg-turbo-dev postgresql-dev libxml2-dev libzip-dev oniguruma-dev $PHPIZE_DEPS \
   && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
   && docker-php-ext-install gd mbstring pdo_mysql pdo_pgsql zip \
   && docker-php-ext-install opcache bcmath soap \
   && pecl install redis-5.3.7 \
   && docker-php-ext-enable redis \
   && apk add --no-cache libpng freetype libjpeg libpq libxml2 libzip \
   && apk del .dd-build-deps

RUN apk --no-cache add autoconf g++ make \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && rm -rf /tmp/pear \
    && apk del autoconf g++ make;

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

COPY drupal-*.ini /usr/local/etc/php/conf.d/


WORKDIR /var/www/html

# RUN composer install

CMD ["php-fpm"]

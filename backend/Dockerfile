FROM composer:1 AS composer

FROM php:7.3-apache

COPY docker/sources.list /etc/apt/sources.list
RUN echo 'Acquire::Retries "5";' > /etc/apt/apt.conf.d/80-retries \
    && apt-get update \
    && apt-get install -y --no-install-recommends --allow-downgrades curl git unzip libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libc-client-dev libkrb5-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr --with-jpeg-dir=/usr \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip imap intl exif bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer 1: el 2 omite clases del proyecto que no cumplen PSR-4.
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1

COPY docker/php.ini /usr/local/etc/php/conf.d/armatrip.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint && chmod +x /usr/local/bin/app-entrypoint

WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --no-scripts --prefer-dist \
    && md5sum composer.lock | cut -d' ' -f1 > vendor/.lock-md5 \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]

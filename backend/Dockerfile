FROM composer:1 AS composer

FROM php:7.3-apache AS base

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
WORKDIR /var/www/html

# Producción (--target prod): código dentro de la imagen, sin dependencias de desarrollo.
FROM base AS prod
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/entrypoint.prod.sh /usr/local/bin/app-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint && chmod +x /usr/local/bin/app-entrypoint
COPY . .
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader \
    && composer clear-cache \
    && php artisan package:discover \
    && chown -R www-data:www-data storage bootstrap/cache
# Build de GitHub Actions (número, commit corto y fecha); se muestra en el panel y en la web. Al final, para no invalidar la caché de las capas anteriores.
ARG BUILD_NUMBER=
ARG BUILD_SHA=
ARG BUILD_DATE=
ENV APP_BUILD_NUMBER=$BUILD_NUMBER APP_BUILD_SHA=$BUILD_SHA APP_BUILD_DATE=$BUILD_DATE
ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]

# Desarrollo: tiene que ser la última etapa, es la que construyen Compose y la verificación del CI.
FROM base AS dev
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint && chmod +x /usr/local/bin/app-entrypoint
COPY . .
RUN composer install --no-interaction --no-scripts --prefer-dist \
    && md5sum composer.lock | cut -d' ' -f1 > vendor/.lock-md5 \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]

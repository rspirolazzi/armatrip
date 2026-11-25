#!/bin/sh
set -e
cd /var/www/html

if [ ! -f .env ]; then
    echo "Falta backend/.env. Ejecutar docker/preparar-entorno.ps1 desde la raíz del proyecto." >&2
    exit 1
fi

# El volumen de vendor sobrevive a los rebuilds: reinstalar si cambió el lock.
lock=$(md5sum composer.lock | cut -d' ' -f1)
if [ "$(cat vendor/.lock-md5 2>/dev/null)" != "$lock" ]; then
    composer install --no-interaction --no-scripts
    echo "$lock" > vendor/.lock-md5
fi

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

php artisan package:discover

if [ "$AUTO_MIGRATE" = "true" ]; then
    php artisan migrate --force
    php docker/seed.php
fi

chown -R www-data:www-data storage bootstrap/cache

exec "$@"

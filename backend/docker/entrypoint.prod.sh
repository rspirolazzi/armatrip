#!/bin/sh
# Producción: la configuración llega como variables de entorno del contenedor (sin backend/.env).
set -e
cd /var/www/html

# El volumen de storage puede venir vacío o con dueño root.
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chown -R www-data:www-data storage bootstrap/cache

# Solo al levantar el servidor: así "run ... php artisan key:generate --show" funciona sin claves.
if [ "$1" = "apache2-foreground" ]; then
    : "${APP_KEY:?Falta APP_KEY}"
    : "${JWT_SECRET:?Falta JWT_SECRET}"

    if [ "$AUTO_MIGRATE" = "true" ]; then
        # Como www-data: si no, la caché y los logs quedan con dueño root.
        runuser -u www-data -- php artisan migrate --force
    fi
fi

exec "$@"

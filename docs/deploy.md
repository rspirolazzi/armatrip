# Armatrip: imágenes y despliegue

> **Servidor demo, no producción.** Cada push a `main` actualiza el servidor demo. Las vulnerabilidades del código se corrigieron en noviembre de 2026; antes de producción faltan las acciones manuales sobre los repositorios anteriores (rotar claves) y la modernización de dependencias.
>
> Estos pasos **no se validaron de punta a punta** (ver [Qué falta probar](#qué-falta-probar)).

Ver también: [Docker local](docker.md) · [Backend](analysis-backend.md) · [Frontend](analysis-frontend.md)

## Imágenes

| Imagen | Contenido |
|---|---|
| `ghcr.io/rspirolazzi/armatrip-backend` | Apache y PHP 7.3, sin dependencias de desarrollo |
| `ghcr.io/rspirolazzi/armatrip-frontend` | Web compilada, servida con nginx |

Tags: `latest` y `sha-<commit>`.

**Versiones visibles.** Cada imagen lleva el build de GitHub Actions: número de ejecución del workflow, commit corto y fecha UTC (sin CI, `dev`). Se muestran en los dos footers:

| Dónde | Qué muestra | De dónde sale |
|---|---|---|
| Web (ambos flujos) | `Web 4.1.10 · build 57 (abc1234, 27/11/2026) · API build 42 (…)` | Versión de `frontend/package.json` y build compilados en la imagen; el de la API llega en `GET /api/config` |
| Panel | `API build 42 (…) · Web 4.1.10 · build 57 (…)` | `config('app.build')` y `version.json` de la web (`APP_WEB_URL/version.json`), leído por el servidor con caché de 10 minutos. Si no responde: "Web: no disponible" |

`version.json` se publica junto a la web (`<APP_BASE_PATH>/version.json`).

## Qué hacen los workflows

[`armatrip-backend.yml`](../.github/workflows/armatrip-backend.yml) y [`armatrip-frontend.yml`](../.github/workflows/armatrip-frontend.yml):

| Paso | Cuándo |
|---|---|
| Verificar | Cada push y pull request a `main` que toque esa parte |
| Publicar la imagen | Solo desde `main` (push o ejecución manual) |
| `pull` y `up` en el servidor demo | Después de publicar, siempre |

## Configurar GitHub

En *Settings → Secrets and variables → Actions*.

| Tipo | Nombre | Uso |
|---|---|---|
| Variable | `REACT_APP_API_URL` | **Obligatoria para publicar el frontend.** URL pública de la API con `/` final, p. ej. `https://api.example.com/api/` |
| Variable | `APP_BASE_PATH` | Ruta de la web. Por defecto `/cotizador`; `/` para la raíz |
| Variable | Resto de `REACT_APP_*` de [`frontend/.env.example`](../frontend/.env.example) | Opcionales y **públicas**: quedan visibles en el navegador |
| Variable | `DEPLOY_PATH` | Carpeta del servidor con `docker-compose.yml` |
| Secreto | `DROPLET_IP`, `DROPLET_USER` | Dirección y usuario SSH |
| Secreto | `SSH_PRIVATE_KEY` | Clave privada exclusiva para el despliegue |
| Secreto | `DROPLET_SSH_FINGERPRINT` | Huella SHA256 de una clave del servidor, de cualquier tipo, con o sin `SHA256:`. En el servidor: `ssh-keygen -lf /etc/ssh/ssh_host_ed25519_key.pub`. Si no coincide, el log del despliegue muestra las huellas que presenta el servidor |

Las variables del frontend se compilan dentro de la imagen: al cambiarlas hay que volver a publicar.

## Preparar el servidor (una vez)

Requiere Docker con un Compose v2 reciente (con `up --wait-timeout`). Los comandos de esta sección se ejecutan **en el servidor** (Linux).

1. Copiar a `DEPLOY_PATH` los archivos de [`deploy/`](../deploy): `docker-compose.yml`, `.env.example` y `backend.env.example`.
2. Crear los archivos de entorno y completarlos:

```bash
cp .env.example .env
cp backend.env.example backend.env
chmod 600 .env backend.env
```

3. Iniciar sesión en el registro con un token de GitHub que solo tenga `read:packages` (lo pide como contraseña):

```bash
docker login ghcr.io -u rspirolazzi
```

4. Descargar las imágenes y generar las claves. Pegar la primera en `APP_KEY` y la segunda en `JWT_SECRET` de `backend.env`:

```bash
docker compose pull
docker compose run --rm --no-deps armatrip-backend php artisan key:generate --show
openssl rand -hex 32
```

5. Levantar, migrar y sembrar. El backend figura `unhealthy` hasta que la base está migrada:

```bash
docker compose up -d
docker compose exec -u www-data armatrip-backend php artisan migrate --force
docker compose exec -u www-data armatrip-backend php docker/seed.php
```

Usar siempre `-u www-data` con `artisan`: como root, la caché queda sin permisos de escritura para Apache y la API responde 500.

La semilla carga datos de ejemplo y usuarios `@armatrip.test` con la contraseña `SEED_USERS_PASSWORD`: cambiarlos.

**Código SMS del registro.** La web entrega la sesión recién al validar un código por SMS. En la demo, sin Twilio real, usar en `backend.env` `SMS_DRIVER=mail`, `SMS_DEMO=true` y `SMS_MAIL_TO` con la casilla que recibe los códigos (requiere `MAIL_*`). En producción: `SMS_DRIVER=twilio` y `SMS_DEMO=false`.

6. Poner delante un proxy con HTTPS (nginx, Caddy o Traefik). Los contenedores solo escuchan en `127.0.0.1`: backend en `8085` y frontend en `3085`.

**Cabeceras de la web.** El frontend ya envía cabeceras de seguridad y una CSP en modo `Content-Security-Policy-Report-Only` ([security-headers.conf](../frontend/docker/security-headers.conf)). Recorrer los dos flujos en la demo, incluido el pago de prueba, con la consola abierta. Si no aparecen violaciones, cambiar el nombre de la cabecera a `Content-Security-Policy` para que bloquee. La sesión web dura `JWT_TTL` minutos (por defecto 240).

### Un solo dominio: web en la raíz y API en `/api/v1`

Ejemplo con `armatrip.example.com`. Laravel atiende la API en `/api/...` y las imágenes subidas en `/storage/...`, así que el proxy reescribe `/api/v1/X` a `/api/X` y `/api/v1/storage/X` a `/storage/X`. Dentro del bloque `server` HTTPS:

```nginx
location /api/v1/ {
    set $backend http://127.0.0.1:8085;   # "set" va antes de "rewrite ... break"
    rewrite ^/api/v1/storage/(.*)$ /storage/$1 break;
    rewrite ^/api/v1/(.*)$         /api/$1     break;
    proxy_pass $backend;
    proxy_set_header Host              $host;
    proxy_set_header X-Real-IP         $remote_addr;
    proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host  $host;
    proxy_set_header X-Forwarded-Port  443;
    client_max_body_size 10m;
}

location / {
    set $frontend http://127.0.0.1:3085;
    proxy_pass $frontend;
    proxy_set_header Host              $host;
    proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    # El contenedor ya las envía: así no se duplican si el server agrega las suyas.
    proxy_hide_header X-Frame-Options;
    proxy_hide_header X-Content-Type-Options;
    proxy_hide_header Referrer-Policy;
}
```

Si nginx corre en un contenedor de la misma red de Docker, usar `resolver 127.0.0.11 valid=30s;` y los nombres de servicio en el puerto interno (`http://armatrip-backend:80`, `http://armatrip-frontend:80`). En ese caso no publicar los puertos del backend.

Además:

| Dónde | Valor |
|---|---|
| `backend.env` | `APP_URL=https://armatrip.example.com/api/v1`, `APP_WEB_URL=https://armatrip.example.com` y `TRUSTED_PROXIES=*`. `TRUSTED_PROXIES=*` es seguro solo si el backend no es accesible sin pasar por el proxy |
| Variables de GitHub | `REACT_APP_API_URL=https://armatrip.example.com/api/v1/` (con `/` final) y `APP_BASE_PATH=/`. Se compilan en la imagen: volver a ejecutar *armatrip-frontend* |

#### Panel en `/admin` del mismo dominio

El panel usa, además de `/admin`, `/login`, `/logout`, `/password/...`, y `asset()` para `/js/` y `/vendor/`. Ninguna choca con la web. Sus CSS, imágenes y el logo de los emails se arman con `APP_URL_FIX_IMAGES`, que sí chocarían con `/css` e `/img` de la web. Por eso van bajo un prefijo propio, `/admin-assets/`, que el proxy quita:

```nginx
# ^~: gana sobre la regex de abajo, que también empieza con /admin.
location ^~ /admin-assets/ {
    set $backend http://127.0.0.1:8085;
    rewrite ^/admin-assets/(.*)$ /$1 break;
    proxy_pass $backend;
    proxy_set_header Host              $host;
    proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}

location ~ ^/(admin(/|$)|login$|logout$|password/|js/|vendor/) {
    set $backend http://127.0.0.1:8085;
    proxy_pass $backend;
    proxy_set_header Host              $host;
    proxy_set_header X-Real-IP         $remote_addr;
    proxy_set_header X-Forwarded-For   $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host  $host;
    proxy_set_header X-Forwarded-Port  443;
    client_max_body_size 50m;   # imágenes y videos del panel
}
```

En `backend.env`: `APP_URL_FIX_IMAGES=https://armatrip.example.com/admin-assets`, y recrear el contenedor. `/register` sigue en la web: el alta pública de Laravel no se publica.

La web es una PWA: su service worker responde `index.html` a las navegaciones sin pasar por el servidor. La imagen del frontend excluye de ese comportamiento `/admin`, `/admin-assets`, `/login`, `/logout`, `/password` y `/api` ([Dockerfile](../frontend/Dockerfile)). Un navegador que ya tenía instalado el service worker anterior sigue mostrando la web en `/admin` hasta que se actualiza: cerrar todas las pestañas del sitio, o en DevTools → *Application → Service workers → Unregister*. Para comprobar sin caché: `curl -sI https://armatrip.example.com/admin` debe responder `302` hacia `/login`.

Para probarlo: `nginx -t`, recargar nginx y verificar que `curl https://armatrip.example.com/api/v1/provinces` devuelva JSON.

## Actualizar: pull y up

Es lo que ejecuta el workflow; sirve igual a mano, desde `DEPLOY_PATH`:

```bash
docker compose pull armatrip-backend armatrip-frontend
docker compose up -d --no-deps --wait --wait-timeout 180 armatrip-backend armatrip-frontend
```

Si la versión trae migraciones, **hacer una copia de la base** y correr:

```bash
docker compose exec -u www-data armatrip-backend php artisan migrate --force
```

Con `AUTO_MIGRATE=true` en `backend.env` se migra solo al arrancar el contenedor, sin copia previa.

**Volver a una versión:** poner `BACKEND_TAG` o `FRONTEND_TAG=sha-xxxxxxx` en `.env` y repetir `pull` y `up`. Las migraciones no se revierten solas. Mientras el tag esté fijo, el workflow no actualiza ese servicio.

## Datos persistentes

| Volumen | Contenido |
|---|---|
| `armatrip_mysql_data` | Base de datos |
| `armatrip_backend_storage` | Imágenes subidas, `settings.json`, sesiones y logs |

No hay copias automáticas. `docker compose down -v` **borra ambos**.

## Qué falta probar

Probado en local (26/11/2026): ambas imágenes `prod` construyen, la web responde bajo `/cotizador`, y `key:generate`, `migrate` y la semilla terminan.

Sin probar:

- La corrección de permisos (`-u www-data`) y el healthcheck sobre `/api/provinces`.
- Persistencia al recrear el contenedor, `AUTO_MIGRATE=true` y URLs `https` detrás de un proxy.
- Los workflows en GitHub, la publicación en `ghcr.io` y el despliegue por SSH.
- El código SMS por email en la demo (`SMS_DEMO=true`) y la CSP en los dos flujos, incluido el pago de prueba.

# Armatrip: imágenes y despliegue

> **Todavía no está listo para producción.** Hay hallazgos de seguridad críticos sin resolver: no exponer el servidor a internet sin revisarlos. Por eso el paso al servidor viene apagado.
>
> Estos pasos **no se validaron de punta a punta** (ver [Qué falta probar](#qué-falta-probar)).

Ver también: [Docker local](docker.md) · [Backend](analysis-backend.md) · [Frontend](analysis-frontend.md)

## Imágenes

| Imagen | Contenido |
|---|---|
| `ghcr.io/rspirolazzi/armatrip-backend` | Apache y PHP 7.3, sin dependencias de desarrollo |
| `ghcr.io/rspirolazzi/armatrip-frontend` | Web compilada, servida con nginx |

Tags: `latest` y `sha-<commit>`.

## Qué hacen los workflows

[`armatrip-backend.yml`](../.github/workflows/armatrip-backend.yml) y [`armatrip-frontend.yml`](../.github/workflows/armatrip-frontend.yml):

| Paso | Cuándo |
|---|---|
| Verificar | Cada push y pull request a `main` que toque esa parte |
| Publicar la imagen | Solo desde `main` (push o ejecución manual) |
| `pull` y `up` en el servidor | Además, solo si la variable `DEPLOY_ENABLED` es `true` |

## Configurar GitHub

En *Settings → Secrets and variables → Actions*.

| Tipo | Nombre | Uso |
|---|---|---|
| Variable | `REACT_APP_API_URL` | **Obligatoria para publicar el frontend.** URL pública de la API con `/` final, p. ej. `https://api.example.com/api/` |
| Variable | `APP_BASE_PATH` | Ruta de la web. Por defecto `/cotizador`; `/` para la raíz |
| Variable | Resto de `REACT_APP_*` de [`frontend/.env.example`](../frontend/.env.example) | Opcionales y **públicas**: quedan visibles en el navegador |
| Variable | `DEPLOY_ENABLED` | `true` para activar el paso al servidor |
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

6. Poner delante un proxy con HTTPS (nginx, Caddy o Traefik). Los contenedores solo escuchan en `127.0.0.1`: backend en `8085` y frontend en `3085`.

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

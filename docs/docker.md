# Armatrip: entorno local con Docker

> Validado el 25/11/2026 en Windows con Docker 24 y Compose 2.22, desde volúmenes vacíos. **Solo para desarrollo local**, no para producción.

Ver también: [README](../README.md) · [Backend](analysis-backend.md) · [Frontend](analysis-frontend.md) · [AGENTS](../AGENTS.md)

## Levantar

Desde la raíz, con Docker Desktop iniciado. La primera vez se crean los archivos de entorno con secretos aleatorios (no pisa los existentes ni muestra valores):

```powershell
powershell -ExecutionPolicy Bypass -File docker/preparar-entorno.ps1
```

```powershell
docker compose up -d --build
```

La primera construcción tarda varios minutos. Al iniciar, el backend migra y, si no hay usuarios, siembra.

| Servicio | Dirección | Qué es |
|---|---|---|
| frontend | http://localhost:3085 | Cotizador (React, recarga en vivo) |
| backend | http://localhost:8085 | API (`/api`) y panel (`/admin`) |
| mailpit | http://localhost:8026 | Emails capturados; no sale ninguno real |
| mysql | Solo red interna | MySQL 5.7 |

Todos los puertos escuchan solo en `127.0.0.1` y se cambian en `.env`. Si se cambian, actualizar también las URL de `backend/.env` y `frontend/.env.local`.

**Entrar al panel:** usuarios de `backend/database/seeds/UsersTableSeeder.php`; contraseña en `SEED_USERS_PASSWORD` de `backend/.env`. Cambiar esa variable después de sembrar no cambia las contraseñas.

## Comandos

| Acción | Comando |
|---|---|
| Estado | `docker compose ps` |
| Logs | `docker compose logs -f backend` |
| Detener (conserva datos) | `docker compose down` |
| Artisan | `docker compose exec backend php artisan ...` |
| Borrar **todos** los datos locales | `docker compose down -v` |
| Dependencias nuevas del frontend | `docker compose build frontend`, y luego `docker compose run --rm frontend npm ci` |

Tras `down -v` se puede volver a levantar con los mismos archivos de entorno.

## Secretos

| Archivo | Contenido | Git |
|---|---|---|
| `.env` | Base y puertos | Ignorado |
| `backend/.env` | Claves de Laravel, JWT e integraciones | Ignorado |
| `frontend/.env.local` | Configuración pública del navegador | Ignorado |
| `*.env.example` | Solo nombres y valores no sensibles | Versionado |

Los `.dockerignore` evitan que los archivos de entorno y los volcados entren en las imágenes. La contraseña de la base debe coincidir en `.env` y `backend/.env`.

## Particularidades

- **Composer 1:** el 2 omite clases que no cumplen PSR-4.
- **Debian 11 sin soporte:** los paquetes salen de `snapshot.debian.org` con fecha fija.
- **Mercado Pago:** `composer.lock` apunta al repositorio renombrado; mismo commit y versión.
- **Arranque:** corre `php artisan migrate --force` y `docker/seed.php`, que siembra solo si no hay usuarios.
- **SMS simulado:** con `SMS_DRIVER=mail` y `APP_ENV=local`, el código llega a Mailpit (http://localhost:8026). En otro entorno ese modo se rechaza; producción usa `twilio`.
- **Subida de archivos:** `backend/docker/php.ini` admite 10 MB por archivo y 32 MB por formulario; fuera de Docker hay que configurar lo mismo.
- **`APP_DEBUG=false`** por defecto: no habilitarlo fuera de un entorno local.
- Sin scheduler: no corren la sincronización IMAP ni los backups.

## Integración continua

GitHub Actions usa este mismo entorno para verificar cada push y pull request a `main` que toque la parte correspondiente (también se puede lanzar a mano). La verificación no necesita secretos: genera `.env` descartables con `docker/preparar-entorno.ps1`. Publicación y despliegue: [deploy.md](deploy.md).

| Workflow | Verifica |
|---|---|
| [`armatrip-backend.yml`](../.github/workflows/armatrip-backend.yml) | Imagen, migraciones y semillas desde cero, `composer validate`, sintaxis PHP, carga de rutas y suite `Startup` |
| [`armatrip-frontend.yml`](../.github/workflows/armatrip-frontend.yml) | Imagen (`npm ci`) y build de producción con ESLint |

Los Dockerfiles tienen una etapa `prod` para publicar; la última, `dev`, es la de este entorno y debe seguir siendo la última.

Fuera del CI hasta corregir el código:

- **Suite `API` de PHPUnit:** falla y aborta ([backend](analysis-backend.md#tests)).
- **`npm test`:** falla ([frontend](analysis-frontend.md#tests)).
- **ESLint estricto:** el build usa `CI=false` porque las advertencias actuales lo harían fallar.

Si cambia el nombre del proyecto Compose o de un servicio, actualizar el `tags` de la imagen en el workflow. Dependabot revisa mensualmente las versiones de las Actions.

## Limitaciones conocidas

- Solo Primarios y Secundarios traen paquetes de prueba; los demás se cargan en el panel. Un paquete necesita un grupo activo ("Agrupar por") para aparecer en la web.
- Las empresas sembradas no tienen imagen: se cargan en el panel (JPG o PNG).
- No se probaron pagos, SMS, IMAP ni reCAPTCHA. De PHPUnit solo pasa la suite `Startup`.

# Armatrip: análisis del backend

> Relevamiento estático del código (noviembre 2026), rama `master`, último commit `0e22916` (2021-01-21).
> La instalación manual no se probó; el entorno [Docker](docker.md) sí (25/11/2026). Los pasos de instalación se deducen del código y de los lockfiles; lo no confirmado se marca con ⚠️.

Ver también: [README](../README.md) · [Análisis del frontend](analysis-frontend.md) · [AGENTS](../AGENTS.md)

## 1. Resumen

Monolito **Laravel 5.6** que cumple dos funciones:

1. **API REST** (`/api/*`, autenticación JWT) que consume el cotizador (frontend React).
2. **Panel de administración** (`/admin/*`, Blade + AdminLTE, sesión + roles y permisos).

Ramas remotas además de `master`: `access_to_sellers`, `codigos_descuentos`, `master_login`, `mercadopago`.

## 2. Stack y versiones

Versiones **instaladas según `composer.lock` / `package-lock.json`**.

| Categoría | Tecnología | Versión |
|---|---|---|
| Lenguaje | PHP | **≥ 7.2.5 y < 7.4** (recomendado 7.3) |
| Framework | laravel/framework | 5.6.40 |
| Base de datos | MySQL (InnoDB, utf8mb4) | 5.7 en Docker |
| Dependencias | Composer | Sin fijar; época de Composer 1 (ver §7) |
| Auth API | tymon/jwt-auth | `dev-develop` (commit `d4cf9fd`, 2020-03-04) |
| Roles y permisos | spatie/laravel-permission | 2.38.0 |
| Repositorios | prettus/l5-repository | 2.6.46 |
| Generador CRUD | infyomlabs/laravel-generator y adminlte-templates | 5.6.x-dev |
| Formularios / flash | laravelcollective/html · laracasts/flash | 5.6.10 · 3.1 |
| Pagos | mercadopago/sdk | 0.5.6 |
| SMS | twilio/sdk | 5.42.2 |
| Tiempo real | pusher/pusher-php-server | 3.4.1 |
| IMAP | webklex/laravel-imap | 1.4.5 |
| Imágenes / media | intervention/image · optix/media | 2.5.1 · 0.2.6 |
| Video embebido | cohensive/embed | 5.5.0 |
| PDF / Excel | barryvdh/laravel-dompdf · maatwebsite/excel | 0.8.6 · 3.1.19 |
| Settings | akaunting/setting | 1.1.2 |
| Tags | rtconner/laravel-tagging | 4.0.2 |
| HTTP / fechas | guzzlehttp/guzzle · nesbot/carbon | 6.5.5 · 1.26.6 |
| Operación | rap2hpoutre/laravel-log-viewer · recca0120/terminal | 1.6.0 · 1.8.0 (terminal web desactivada: sin provider y en `dont-discover`) |
| Testing | phpunit/phpunit · mockery | 7.5.20 · 1.3.1 |

**Por qué ese rango de PHP:** `composer.json` pide `>=7.1.3`, pero el lock restringe más: varios paquetes `symfony/*` exigen `>=7.2.5` y `pusher-php-server` exige `<7.4`.

**Extensiones PHP requeridas por el lock:** `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `iconv`, `imap`, `json`, `libxml`, `mbstring`, `openssl`, `pdo`, `simplexml`, `tokenizer`, `xml`, `xmlreader`, `xmlwriter`, `zip`, `zlib`. Se necesita además `pdo_mysql`. `imap` no suele venir instalada por defecto.

**Assets del panel:** Laravel Mix 2.1.11, Vue 2.5.16, Bootstrap 4.0.0, jQuery 3.3.1, axios 0.18.0, laravel-echo 1.4.0, pusher-js 4.3.1, node-sass 4.8.3. En el layout real se cargan por CDN AdminLTE 2.4.2, Bootstrap 3.3.7, jQuery 3.2.1, Select2 4.0.3 y Font Awesome 4.7. Los compilados de `public/js` y `public/css` están versionados: **no hace falta Node para levantar el backend**.

## 3. Estructura

```text
backend/
├── app/
│   ├── Admin.php, User.php       # dos modelos sobre la misma tabla `users`
│   ├── Console/Commands/         # famTour:sync-emails y db:dump
│   ├── Criteria/                 # filtros de repositorio (tienda, usuario, grupo activo)
│   ├── Events/ Listeners/ Mail/  # reservas, contacto, pedidos internos → emails
│   ├── Helpers/                  # applyTax, códigos de error, dashboard
│   ├── Http/Controllers/API/     # API del cotizador
│   ├── Http/Controllers/Admin/   # panel (IDG/ = pedidos internos)
│   ├── Models/ y Models/Admin/   # Eloquent
│   ├── Repositories/             # lógica de negocio principal
│   └── Services/PackageService   # refactor inconcluso: pa() usa variables indefinidas
├── config/armatrip.php           # configuración propia
├── database/                     # migraciones y seeds
├── packages/Rspirolazzi/         # paquetes propios: Coupons, Sms, MultiCompany
├── resources/views/              # Blade: admin, email, auth
├── routes/api.php, web.php
└── tests/
```

**Patrón:** Controller → Repository → Model, con `Criteria` para filtros. Gran parte del CRUD proviene de InfyOm.

- `Coupons` y `Sms` están registrados en `config/app.php`.
- `MultiCompany` está en el autoload, pero no se registra su provider.
- `Rspirolazzi\InternalOrders` figura en `composer.json`, pero su carpeta **no existe**.

Archivos clave: [OrderRepository](../backend/app/Repositories/OrderRepository.php), [PackageRepository](../backend/app/Repositories/PackageRepository.php), [UserRepository](../backend/app/Repositories/UserRepository.php), [api.php](../backend/routes/api.php), [web.php](../backend/routes/web.php).

## 4. Base de datos

MySQL con 64 migraciones. Tablas por dominio:

| Dominio | Tablas |
|---|---|
| Usuarios y acceso | `users`, `user_verifications`, `password_resets`, `roles`, `permissions` y pivotes |
| Catálogo | `stores`, `package_groups`, `packages`, `products`, `categories`, `package_products` |
| Fechas y precio | `package_date_ranges`, `date_ranges`, `provinces`, `store_provinces`, `currencies` |
| Reservas | `orders`, `order_packages`, `order_products`, `mercado_pagos` |
| Cupones | `coupons`, `coupon_user` |
| Clientes y contacto | `schools`, `contacts`, `fam_tours` |
| Contenido | `brands`, `notifications`, `media`, `mediables`, `videos`, `tagging_*` |
| Pedidos internos (IDG) | `invoices`, `invoice_items`, `invoice_products` |
| Configuración | `settings` (modelo `SettingManager`) |

Estados de `orders.status`: `pending`, `cancelled`, `finished`, `sold`, `contacted`.

Datos incluidos en el repo:


## 5. API

Prefijo `/api`, límite de 60 req/min. Los controladores del cotizador responden con `success`, `data`, `message` y, ante error, código de [Errors.php](../backend/app/Helpers/Errors.php). Los errores usan HTTP 404 por defecto, incluso los de negocio; el éxito exige `success: true`.

El JWT viaja como **parámetro `token`**, no en el header `Authorization`. CORS no habilita ese header.

**Registro y sesión.** La validación la decide solo el servidor: setting `register_validation`, por defecto y única opción implementada `sms`. El `verification_type` que mande el cliente se ignora. `register` sin `token` crea el usuario (o reutiliza uno existente **sin modificarlo**; si el celular no coincide con el guardado, responde error sin revelarlo), crea la orden y envía el código. El JWT se entrega solo en `sms/verify`. En la demo, `SMS_DRIVER=mail` con `SMS_DEMO=true` manda el código por email a `SMS_MAIL_TO`.

**Con JWT** (`jwt.auth`)

| Método y ruta | Uso |
|---|---|
| `POST package/detail` | Cotiza el paquete de la orden activa con los servicios elegidos (y persiste el total) |
| `POST checkout` | Confirma la reserva |
| `POST payment` | Seña con Mercado Pago |
| `POST coupons/check` · `apply` · `remove` | Cupones |
| `POST user` | Usuario autenticado |
| `POST registerWithToken` | Registra otra cotización con sesión existente |
| `POST precheckout` | **Rota:** el método no existe en el controlador |

**Públicas**

| Método y ruta | Uso |
|---|---|
| `POST register`, `continue_with_user` | Alta o continuación, y creación de la orden pendiente. Sin `token`, `register` envía el código por SMS y **no devuelve JWT** |
| `POST sms/verify`, `sms/activation` · `GET verify/{code}` | Verificación. `sms/verify` entrega el JWT |
| `GET stores`, `provinces`, `provinces/{store_code}`, `schools/{term?}`, `brands` | Catálogos |
| `GET packages/{store_code}`, `products`, `products/{code}` | Oferta |
| `GET order/show/{code}` | Cotización compartida |
| `POST contact/country` | Contacto |
| `GET notifications`, `POST notifications/uri` | Mensajes emergentes |
| `GET config`, `POST log/save` | Configuración y logs del cliente |

Las reservas se consultan solo desde el panel (`/admin/orders`, con sesión y permisos). La versión API pública se eliminó en noviembre de 2026 porque exponía los datos sin autenticación.

## 6. Panel, procesos e integraciones

**Panel** (`/admin`): guard de sesión `admin` y permisos con el mismo nombre que la ruta. Roles: `admin`, `admin_idg`, `editor`, `quoting`. Permisos iniciales en `UserACLTableSeeder`; se regeneran desde `admin/permissions`.

**Tareas programadas** (requieren cron con `schedule:run`):

| Comando | Frecuencia | Función |
|---|---|---|
| `famTour:sync-emails` | Cada 5 min | Importa leads desde una casilla IMAP |
| `db:dump` | 23:00 diario | `mysqldump` a `database/backups/AAAA/MM/DD` |

**Integraciones:** Mercado Pago, Twilio, SMTP, IMAP, Pusher, Google reCAPTCHA. Las credenciales de Mercado Pago salen primero de la tabla `settings` y, si faltan, de `.env`.

## 7. Instalación y ejecución

> Con Docker, validada: [docker.md](docker.md). Lo que sigue es la instalación manual, sin validar.

### Requisitos

PHP 7.3 con las extensiones de §2, Composer, MySQL 5.6/5.7. El `.gitmodules` referencia **Laradock**, pero la carpeta no está en esta copia; hay un entorno propio en [docker.md](docker.md).

### Pasos (PowerShell, desde la raíz del proyecto)

```powershell
cd backend
```

```powershell
Copy-Item .env.example .env
```

Completar `.env` (ver abajo) y crear la base. Luego:

```powershell
composer install
```

```powershell
php artisan key:generate
```

```powershell
php artisan jwt:secret
```

```powershell
php artisan migrate --seed
```

```powershell
php artisan storage:link
```

```powershell
php artisan serve
```

Queda en `http://localhost:8000`; `/` redirige a `/admin`. Los usuarios iniciales están en `database/seeds/UsersTableSeeder.php`: **cambiar sus contraseñas** fuera de un entorno local.

El frontend apunta por defecto a `//armatrip.test/api/`: crear ese vhost o cambiar `REACT_APP_API_URL`.

### Alternativa con los datos de 2020

No se distribuyen volcados: la base se crea con migraciones y semillas.

### Variables de entorno

Todas están en `.env.example`, sin valores sensibles. Las propias del proyecto:

| Grupo | Variables |
|---|---|
| App | `APP_WEB_URL`, `APP_URL_STORAGE_IMAGES`, `APP_URL_FIX_IMAGES` |
| JWT | `JWT_SECRET`, `JWT_BLACKLIST_ENABLED` |
| Mail | `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_ADMIN_ADDRESS` |
| Mercado Pago | `MP_ENABLED`, `MP_ENABLED_TEST`, `MP_ACCESS_TOKEN`, `MP_ACCESS_TOKEN_TEST`, `MP_CLIENT_ID`, `MP_CLIENT_SECRET`, `MP_NOTIFICATION_URL`, `MP_CURRENCY_ID` |
| Twilio | `TWILIO_ACCOUNT_SID`, `TWILIO_AUTH_TOKEN`, `TWILIO_NUMBER`, `TWILIO_TEST` y variantes `_TEST` |
| reCAPTCHA | `CAPTCHA_IS_ACTIVE`, `GOOGLE_RECAPTCHA_SECRET` |
| IMAP | `IMAP_HOST`, `IMAP_PORT`, `IMAP_PROTOCOL`, `IMAP_ENCRYPTION`, `IMAP_VALIDATE_CERT`, `IMAP_USERNAME`, `IMAP_PASSWORD`, `IMAP_DEFAULT_ACCOUNT` |

`MAIL_ADMIN_ADDRESS` es obligatoria para los avisos de reserva y `MAIL_INTERNAL_ORDERS_ADDRESS` para los pedidos internos: ya no hay direcciones personales en el código. `SEED_USERS_PASSWORD` es obligatoria para sembrar usuarios; `MP_PUBLIC_KEY` reemplaza a `MP_CLIENT_SECRET` como clave pública en las vistas.

### Riesgos de instalación

- **Composer 2 omite clases que no cumplen PSR-4** al optimizar el autoload. `FamToursSyncEmails` y `SMSException` ya están corregidas (archivo y carpeta coinciden con clase y namespace). Quedan dos casos sin uso: `app/Repositories/API/PackageRepository.php` (namespace `App\Repositories\Api`) y `packages/Rspirolazzi/MultiCompany/src/Requestes/` (namespace `Http\Requests`). Docker sigue con Composer 1 ⚠️.
- En Linux las mayúsculas y minúsculas de las rutas importan; en Windows no.
- `npm` del backend: node-sass 4.8.3 requiere Node muy antiguo. Evitar salvo que haya que recompilar assets.

### Tests

```powershell
vendor/bin/phpunit
```

- ⚠️ `TestCaseWithDatabase` **recrea y siembra la base** (`migrate:fresh --seed`) una vez por ejecución y corre cada test en una transacción que se descarta. Con `APP_ENV=testing` (lo fija `phpunit.xml`) Laravel lee `.env.testing` si existe; si no, `.env`. Verificar a qué base apunta antes de correr: nunca contra datos reales. En Docker, usar un proyecto Compose descartable ([docker.md](docker.md#tests)).
- `phpunit.xml` activa `tests/Startup` (un `assertTrue(true)`) y `tests/API`. Ambas corren en el CI.
- `tests/API/UserTest.php` cubre el registro web (`POST /api/register`) y `sms/verify`: validación vacía (422), alta con pedido pendiente y código SMS sin JWT, `verification_type` del cliente ignorado, email existente sin cambios (y rechazado con otro celular), sesión al validar el código y rollback cuando el paquete no tiene la fecha pedida. El envío se simula con `Sms::shouldReceive`. Usa empresa, provincia y paquete de las semillas (`secundarios`, `buenos-aires`, `bariloche` en agosto del año siguiente).
- Los tests de la raíz de `tests/` y de `tests/Unit` no pertenecen a ninguna suite y no se mantienen: la cobertura sigue siendo mínima.

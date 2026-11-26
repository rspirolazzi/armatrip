# Armatrip: análisis del frontend

> Relevamiento estático del código (noviembre 2026), rama `master`, último commit `a4a31c9` (2021-03-12).
> La instalación manual no se probó; el entorno [Docker](docker.md) sí (25/11/2026). Lo no confirmado se marca con ⚠️.

Ver también: [README](../README.md) · [Análisis del backend](analysis-backend.md) · [AGENTS](../AGENTS.md)

## 1. Resumen

SPA/PWA en **React 16** (Create React App) que implementa el **cotizador**: elegir un viaje, personalizar servicios, reservar y, si está habilitado, pagar una seña. No tiene base de datos propia: consume la API del [backend](analysis-backend.md) y guarda estado en el navegador.

Ramas remotas además de `master`: `access_to_sellers`, `cupones`, `homescreen`, `login`, `mercadopago`.

## 2. Stack y versiones

Versiones instaladas según `package-lock.json` (lockfile v1, npm 6).

| Categoría | Tecnología | Versión |
|---|---|---|
| Lenguaje | JavaScript ES6+ / JSX, SCSS y CSS | Sin TypeScript |
| Runtime de build | Node.js | Sin fijar; **14 recomendado** |
| Toolchain | react-scripts (CRA) | 2.1.5 |
| UI | react · react-dom | 16.13.1 |
| Estilos | bootstrap · react-bootstrap · node-sass | 4.5.2 · 1.3.0 · 4.14.1 |
| Ruteo | react-router · react-router-dom · history | 4.3.1 · 4.3.1 · 4.10.1 |
| Estado | redux · react-redux | 4.0.5 · 6.0.1 |
| Middlewares | redux-thunk · redux-promise-middleware · redux-logger | 2.3.0 · 6.1.2 · 3.0.6 |
| Persistencia | redux-persist · transform-filter | 5.10.0 · 0.0.18 |
| Organización | redux-duck · reselect | 1.2.0 · 4.0.0 |
| HTTP | axios · qs | 0.18.1 · 6.9.4 |
| Formularios | formik · yup | 1.5.8 · 0.26.10 |
| Autocompletado | downshift | 3.4.8 |
| Fechas / utilidades | moment · lodash | 2.27.0 · 4.17.20 |
| Carruseles | react-alice-carousel · react-multi-carousel | 1.19.3 · 2.5.5 |
| Mensajes / compartir | react-toastify · react-share | 5.5.0 · 3.0.1 |
| Analítica | react-ga (Universal Analytics) | 2.7.0 |
| Servidor | express · morgan (`server/`) | `^4.16.2` · `^1.9.0`, sin lockfile |
| Procesos | PM2 (`ecosystem.config.js`) | No declarado |
| Releases | standard-version · commitlint · husky | 7.1.0 · 8.3.5 · 4.3.0 |

Por CDN en `public/index.html`: Font Awesome 4.7 y **Mercado Pago JS SDK v1**.

**Declaradas sin uso encontrado en `src/`:** `i`, `npm`, `react-autosuggest`, `react-facebook-login`, `react-geosuggest`, `react-logger`, `react-recaptcha`, `react-router-redux`, `react-thunk`.

**Por qué Node 14:** node-sass 4.14.1 soporta hasta Node 14 y webpack 4 (CRA 2) falla con el OpenSSL de Node ≥ 17.

## 3. Estructura

```text
frontend/
├── public/            # index.html, manifest PWA, imágenes, css/armatrip.css
├── server/            # Express que sirve build/ (package.json propio)
├── ecosystem.config.js
├── .env.example        # nombres; .env y .env.local no se versionan
└── src/
    ├── index.js       # Provider, PersistGate, Router, service worker
    ├── App.js         # carga config y notificaciones; elige layout
    ├── common/        # constantes, utilidades, Mercado Pago
    ├── components/
    ├── layouts/
    │   ├── Main/              # flujo principal (grupos, egresados)
    │   └── IndividualTourism/ # Turismo Joven / Individual
    ├── pages/         # pantallas; Individual/ para el segundo flujo
    ├── services/      # cliente de la API (Base.js)
    └── store/         # ducks de Redux
```

**Estado.** Cada dominio tiene tipos, reducer, acciones y selectores: `user`, `store`, `province`, `school`, `package`, `product`, `order`, `coupon`, `brand`, `configuration`, `notification`, `core`. `user` persiste en `localStorage`; el resto en `sessionStorage`. `webCart` existe pero no está en el reducer raíz (resto de otro producto).

**API.** `services/Base.js` envía formularios urlencoded con axios; el JWT se guarda en `localStorage` (`ACCESS_TOKEN_SESSION`) y viaja como parámetro `token`. Se espera `success: true` en la respuesta.

**Middlewares.** Manejan loading global, toasts y el envío de errores a `log/save`. **Filtran acciones que contienen la cadena `armatrip`** (prefijo de los ducks).

## 4. Pantallas y flujos

El layout principal atiende todo; la ruta `individual_tourism.url` (por defecto `/turismo-individual`, reemplazable por `GET /config`) usa el layout individual.

**Flujo principal** ([_.routing.js](../frontend/src/layouts/Main/_.routing.js))

| Ruta | Pantalla | Acceso |
|---|---|---|
| `/`, `/stores` | Elegir tipo de viaje | Público |
| `/select-packages` | Elegir destino o paquete | Con empresa elegida |
| `/register`, `/mobile` | Datos del viaje y celular | Con empresa elegida |
| `/package/:code` | Detalle (fotos, videos, información) | Logueado |
| `/package/:code/services`, `/confirm` | Servicios y confirmación | Logueado |
| `/offer`, `/payment` | Oferta y seña con tarjeta | Logueado y pago habilitado |
| `/payment-confirmed`, `/checkout`, `/finish`, `/thanks` | Cierre | Logueado |
| `/share/:code` | Cotización compartida | Público |
| `/register/contact` | Contacto | Logueado |

**Turismo Individual** ([_.routing.js](../frontend/src/layouts/IndividualTourism/_.routing.js)), relativo a su URL base: `/`, `/packs/:group`, `/paquete/:code`, `/register`, `/paquete/:code/servicios`, `/seleccion/`, `/confirmacion`, `/reservar`, `/pago`, `/payment-confirmed`, `/checkout`, `/finish`, `/thanks`, `/share/:code`. Las rutas no aplican guardas de acceso (`authorize: () => true`).

## 5. Instalación y ejecución

> Con Docker, validada: [docker.md](docker.md). Lo que sigue es la instalación manual, sin validar.

Requisitos: Node 14 con npm 6 y el backend accesible. `node-sass` puede requerir Python y compilador C++ si no encuentra binario precompilado.

PowerShell, desde la raíz:

```powershell
cd frontend
```

```powershell
nvm use 14.17.3
```

```powershell
npm ci
```

```powershell
npm start
```

Abre `http://localhost:3080` (el README original dice 3000). El script `start` usa `set PORT=3080&&`, sintaxis de **Windows cmd**; en Linux/macOS usar `PORT=3080 npx react-scripts start`.

### Variables (`.env`, se incorporan al compilar)

| Variable | Uso |
|---|---|
| `REACT_APP_NAME`, `REACT_APP_VERSION` | Título y versión |
| `REACT_APP_API_URL` | URL de la API, con `/` final (por defecto `//armatrip.test/api/`) |
| `REACT_APP_API_BASE_PATH` | Prefijo del router y de imágenes |
| `REACT_APP_API_ABSOLUTE_URL` | Base para compartir (opcional; usa el origen actual) |
| `REACT_APP_API_MERCADOPAGO_KEY` | Clave pública de Mercado Pago |
| `REACT_APP_API_GOOGLE_MAP_KEY`, `REACT_APP_API_GOOGLE_RECAPTCHA_KEY` | Google |
| `REACT_APP_API_GOOGLE_ANALYTICS`, `REACT_APP_FACEBOOK_APP_ID` | Analítica y Facebook |
| `REACT_APP_API_XDEBUG_SESSION_START` | Comentada; `Base.js` espera la opción, pero `_Utils()` no la expone |
| `PUBLIC_URL` | Base de assets en producción |

Copiar `.env.example` a `.env.local` (ignorado por git). `.env` tampoco se versiona: un clon nuevo no lo trae y hay que proveerlo para compilar en producción.

### Producción

```powershell
npm run build
```

Publica `build/` bajo `/cotizador`. Para otro destino, cambiar `PUBLIC_URL`, `homepage` y `REACT_APP_API_BASE_PATH` (hoy vacío en `.env`). `npm run release:build` versiona y compila.

Con Express: instalar también las dependencias de `server/` y ejecutar `node server/index.js` o `pm2 start ecosystem.config.js`. ⚠️ PM2 define `PM2_SERVE_PORT` y `NODE_PORT`, pero el servidor lee `PORT`: escuchará en **3080, no 7080**.

### Tests

```powershell
npm test
```

Solo existe `App.test.js` y **falla** (verificado el 26/11/2026 en Docker): `react-ga` se inicializa al importar `layouts/withTracker.js` y no encuentra un `<script>` en jsdom (`Cannot read property 'parentNode' of undefined`). Además monta `<App/>` sin el `Provider` de Redux, así que probablemente falle también después de eso ⚠️. No corre en el CI.

`npm run build` compila con advertencias de ESLint; con `CI=true` fallan el build.

# AGENTS.md

Guía para cualquier agente que trabaje en este proyecto. Responder y documentar en **español**.

## Qué es

**Armatrip**, "Armalo a tu manera.": cotizador y sistema de reservas online de viajes (egresados, grupales e individuales): frontend React + API y panel Laravel.

## Qué leer

| Si vas a… | Leé |
|---|---|
| Entender producto, vocabulario o reglas de negocio | [README.md](README.md) |
| Tocar API, panel, base de datos, pagos o emails | [docs/analysis-backend.md](docs/analysis-backend.md) |
| Tocar pantallas, estado, rutas o build web | [docs/analysis-frontend.md](docs/analysis-frontend.md) |
| Levantar el proyecto o tocar Docker | [docs/docker.md](docs/docker.md) |
| Tocar logo, colores o textos de marca | [docs/marca.md](docs/marca.md) |
| Analizar código desconocido | [.agents/skills/analyzing-projects/SKILL.md](.agents/skills/analyzing-projects/SKILL.md) |

## Mapa

- `backend/` — Laravel 5.6, PHP 7.2.5–7.3, MySQL. Lógica en `app/Repositories`; rutas en `routes/`.
- `frontend/` — React 16, Redux, CRA 2, Node 14. Rutas en `src/layouts/*/_.routing.js`; API en `src/services`.
- `docs/` — análisis técnico.

Todo vive en **un único repositorio** git, en la raíz.

## Comandos

**Recomendado y validado:** `docker compose up -d --build` desde la raíz ([docs/docker.md](docs/docker.md)). Instalación manual:

| Acción | Backend (`backend/`) | Frontend (`frontend/`) |
|---|---|---|
| Instalar | `composer install` | `npm ci` |
| Levantar | `php artisan serve` (8000) | `npm start` (3080) |
| Migrar | `php artisan migrate --seed` | — |
| Build | No necesario (assets versionados) | `npm run build` |
| Test | `vendor/bin/phpunit` | `npm test` |

La instalación manual aún **no fue validada**. Antes, leer los requisitos y riesgos de cada análisis (Composer 2, Node, `.env`).

## Reglas

1. **Nombre:** el producto se llama **Armatrip**. No reintroducir el nombre anterior.
2. **No actualizar versiones** de framework o dependencias como efecto secundario.
3. **Secretos y datos personales:** no copiar a código, docs, logs ni servicios externos el contenido de `.env*` o volcados de base.
4. **No correr PHPUnit sin verificar la base:** los tests con base la recrean. Nunca contra datos reales.
5. **Pagos, SMS y emails:** usar solo credenciales de prueba; no disparar envíos reales.
6. **Precios:** un cambio debe revisarse en `PackageRepository` **y** `OrderRepository`.
7. **Contrato API:** si cambia, actualizar backend y `frontend/src/services`. El JWT viaja como parámetro `token`.
8. **Permisos del panel:** se llaman igual que la ruta; una ruta nueva necesita su permiso.
9. **Redux:** mantener el prefijo `armatrip` en las acciones; los middlewares filtran por él.
10. **Commits:** Conventional Commits, en español (`feat(frontend): …`, `fix(backend): …`).
11. **Seguridad:** los hallazgos viven en `docs/privado/` (no se publica). Reproducir antes de corregir; no corregir fuera del alcance pedido.
12. **Documentación:** actualizar el documento correspondiente cuando cambie el comportamiento, el stack o la instalación.
13. **Marca:** seguir siempre la identidad elegida, **A. Piezas**, según [docs/marca.md](docs/marca.md): logos SVG, colores, tipografías y voz. No usar las propuestas B ni C, inventar colores ni redibujar el logo. Ya está aplicada: paleta en `frontend/src/styles/_brand.scss` y, como variables CSS, en `frontend/public/css/armatrip.css` y `backend/public/css/{admin,auth}.css`.
14. **Estilos:** ver [Estilos](#estilos). Nada de estilos en línea.
15. **Claves y secretos:** ver [Claves y secretos](#claves-y-secretos). Nunca en el código ni en un commit.

## Estilos

- **No usar estilos en línea:** ni `style="..."` en HTML/Blade, ni `style={...}` en React, ni bloques `<style>` en las vistas. Van en hojas CSS/SCSS.
- **Primero el framework:** usar sus componentes, grilla y utilidades; escribir CSS propio solo si no alcanzan. No agregar otro framework ni mezclar versiones.
- **Frontend:** Bootstrap 4.5 y react-bootstrap. Estilos en SCSS (`src/layouts/*/*.scss`).
- **Panel:** AdminLTE sobre Bootstrap 3.3.7 en las vistas (aunque `composer.json` declare otra versión; verificar el layout). Estilos propios en `backend/public/css`.
- **Colores, tipografías y espacios:** como variables SCSS o CSS reutilizables, no valores repetidos. Los colores salen de la marca.
- **Clases:** nombres por función, no por apariencia; selectores poco específicos; evitar `!important` e ids. Para valores dinámicos, alternar clases.
- **Siempre:** mobile first, contraste WCAG AA y foco visible.
- **Única excepción:** plantillas de email, porque muchos clientes de correo exigen estilos en línea.
- **Código existente:** todavía tiene estilos en línea. No agregar más; migrarlos solo dentro del alcance pedido.

## Claves y secretos

- **Nunca** escribir claves, tokens, contraseñas ni credenciales en código, tests, semillas, documentación o logs.
- **Nunca commitearlos.** Antes de cada commit, revisar `git diff --cached`. Si aparece un secreto, detenerse y avisar sin mostrar su valor.
- **Dónde van:** en archivos de entorno ignorados por git (`backend/.env`, `frontend/.env.local`) o en el gestor de secretos del servidor.
- **Qué se versiona:** solo `.env.example`, con nombres de variables y valores vacíos o ficticios.
- **Laravel:** leer `env()` solo dentro de `config/`; en el resto, `config()`.
- **React:** toda variable `REACT_APP_*` queda visible en el navegador. Solo claves públicas (por ejemplo, la pública de Mercado Pago); las privadas viven en el backend.
- **Si un secreto llegó a git:** borrarlo no alcanza, porque queda en el historial. Avisar y rotarlo.
- **Fuera de git:** `.env*`, volcados (`*.sql`, `*.zip`) y `docs/privado/`.

## Trampas frecuentes

- `User` y `Admin` comparten la tabla `users`.
- `Store` es una unidad de negocio ("Empresa" en el panel), no una tienda física ni el store de Redux.
- Hay código residual: `webCart`, `MultiCompany`, `PackageService::pa()` y endpoints que ya no existen. Verificar que algo se use antes de extenderlo.
- Dos flujos web (`Main` e `IndividualTourism`) comparten API y estado: probar ambos.

## Estado

Entorno Docker y marca aplicados. Pendiente: estilos en línea y modernización de dependencias.

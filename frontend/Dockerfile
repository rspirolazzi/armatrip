FROM node:14-bullseye AS base

ENV HUSKY_SKIP_INSTALL=1
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit

# Compilación de producción. Todo REACT_APP_* queda visible en el navegador: solo claves públicas.
FROM base AS build
# Ruta donde se publica la web; "/" para la raíz del dominio.
ARG APP_BASE_PATH=/cotizador
ARG REACT_APP_NAME=Armatrip
ARG REACT_APP_API_URL
ARG REACT_APP_API_MERCADOPAGO_KEY
ARG REACT_APP_API_GOOGLE_MAP_KEY
ARG REACT_APP_API_GOOGLE_RECAPTCHA_KEY
ARG REACT_APP_API_GOOGLE_ANALYTICS
ARG REACT_APP_FACEBOOK_APP_ID
ARG REACT_APP_CONTACT_EMAIL
ARG REACT_APP_CONTACT_PHONE
ARG REACT_APP_CONTACT_ADDRESS
ARG REACT_APP_AGENCY_LICENSE
ARG REACT_APP_FACEBOOK_URL
ARG REACT_APP_INSTAGRAM_URL
COPY . .
RUN test -n "$REACT_APP_API_URL" || { echo "Falta el build-arg REACT_APP_API_URL" >&2; exit 1; }
RUN base="${APP_BASE_PATH%/}" \
    && PUBLIC_URL="${base:-/}" REACT_APP_API_BASE_PATH="$base" \
       REACT_APP_VERSION="$(node -p "require('./package.json').version")" \
       ./node_modules/.bin/react-scripts build

# Producción (--target prod): archivos estáticos con nginx.
FROM nginx:1.27-alpine AS prod
ARG APP_BASE_PATH=/cotizador
COPY --from=build /app/build /tmp/build
COPY docker/nginx.conf /tmp/nginx.conf
# Con ruta base, "/" redirige a ella.
RUN base="${APP_BASE_PATH%/}" \
    && mkdir -p "/usr/share/nginx/html$base" \
    && cp -r /tmp/build/. "/usr/share/nginx/html$base/" \
    && sed "s#__BASE__#$base#g" /tmp/nginx.conf > /etc/nginx/conf.d/default.conf \
    && if [ -n "$base" ]; then sed -i "s#\# __REDIRECT__#location = / { return 302 $base/; }#" /etc/nginx/conf.d/default.conf; fi \
    && rm -rf /tmp/build /tmp/nginx.conf \
    && nginx -t

# Desarrollo: tiene que ser la última etapa, es la que construyen Compose y la verificación del CI.
# El código llega por volumen (ver docker-compose.yml en la raíz).
FROM base AS dev
EXPOSE 3080
CMD ["./node_modules/.bin/react-scripts", "start"]

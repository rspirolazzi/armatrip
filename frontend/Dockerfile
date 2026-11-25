FROM node:14-bullseye

ENV HUSKY_SKIP_INSTALL=1
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit

# El código llega por volumen (ver docker-compose.yml en la raíz).
EXPOSE 3080
CMD ["./node_modules/.bin/react-scripts", "start"]

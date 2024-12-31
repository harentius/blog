FROM node:20.15

WORKDIR /app

COPY src/publisher/package*.json /app
RUN npm install

COPY src/publisher /app

CMD [ "node", "src/main.mjs" ]
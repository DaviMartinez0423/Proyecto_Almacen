FROM node:20

WORKDIR /ventas
COPY package.json .
RUN npm install

EXPOSE 3009

COPY . .
CMD npm start

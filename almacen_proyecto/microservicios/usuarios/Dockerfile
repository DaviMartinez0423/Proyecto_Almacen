FROM node:20

WORKDIR /usuarios
COPY package.json .
RUN npm install

EXPOSE 3005

COPY . .
CMD npm start

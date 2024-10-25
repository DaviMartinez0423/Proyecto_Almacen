FROM node:20

WORKDIR /carros
COPY package.json .
RUN npm install

EXPOSE 3007

COPY . .
CMD npm start

const mysql = require('mysql2');

const pool = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: 'Marc2602*',
    database: 'ventasproyec'
});

const promisePool = pool.promise();

module.exports = promisePool;
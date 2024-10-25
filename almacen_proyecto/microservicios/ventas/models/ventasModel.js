const pool = require('../dbConnection');

const getVentas = async () => {
    const [rows] = await pool.query('SELECT * FROM ventas');
    return rows;
};


const createVenta = async (venta) => {
    const { car_id, customer_id, color, price, used } = venta;
    const [result] = await pool.query(
        'INSERT INTO ventas (car_id, customer_id, color, price, used) VALUES (?, ?, ?, ?, ?)',
        [car_id, customer_id, color, price, used]
    );
    return result.insertId;
};



// Obtener todas las ventas de un cliente
const getVentasByCustomerId = async (customer_id) => {
    const [rows] = await pool.query('SELECT * FROM ventas WHERE customer_id = ?', [customer_id]);
    return rows;
};

// Obtener todas las ventas de un carro
const getVentasByCarId = async (car_id) => {
    const [rows] = await pool.query('SELECT * FROM ventas WHERE car_id = ?', [car_id]);
    return rows;
};

// Obtener todas las ventas de una lista de car_ids
const getVentasByCarIds = async (car_ids) => {
    const [rows] = await pool.query('SELECT * FROM ventas WHERE car_id IN (?)', [car_ids]);
    return rows;
};

// Eliminar todas las ventas de un cliente
const deleteVentasByCustomerId = async (customer_id) => {
    await pool.query('DELETE FROM ventas WHERE customer_id = ?', [customer_id]);
};

// Eliminar todas las ventas de un carro
const deleteVentasByCarId = async (car_id) => {
    await pool.query('DELETE FROM ventas WHERE car_id = ?', [car_id]);
};

module.exports = {
    getVentas, createVenta, getVentasByCustomerId, getVentasByCarId, getVentasByCarIds,
    deleteVentasByCustomerId, deleteVentasByCarId
};

const pool = require('../dbConnection');

const getCars = async () => {
    const [rows] = await pool.query('SELECT * FROM car');
    return rows;
};

const getCarById = async (id) => {
    const [rows] = await pool.query('SELECT * FROM car WHERE id = ?', [id]);
    return rows[0];
};

const createCar = async (car) => {
    const { company, model, transmission, body_style, aprox_price } = car;
    const [result] = await pool.query(
        'INSERT INTO car (company, model, transmission, body_style, aprox_price) VALUES (?, ?, ?, ?, ?)',
        [company, model, transmission, body_style, aprox_price]
    );
    return result.insertId;
};


const updateCar = async (id, car) => {
    const { company, model, transmission, body_style, aprox_price } = car;
    await pool.query(
        'UPDATE car SET company = ?, model = ?, transmission = ?, body_style = ?, aprox_price = ? WHERE id = ?',
        [company, model, transmission, body_style, aprox_price, id]
    );
};

const deleteCar = async (id) => {
    await pool.query('DELETE FROM car WHERE id = ?', [id]);
};


module.exports = {
    getCars,
    getCarById,
    createCar,
    updateCar,
    deleteCar
};
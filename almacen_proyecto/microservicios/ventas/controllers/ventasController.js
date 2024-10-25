const axios = require('axios'); // Asegúrate de importar axios
const ventasModel = require('../models/ventasModel');

const getVentas = async (req, res) => {
    const ventas = await ventasModel.getVentas();
    res.json(ventas);
};


// URL del microservicio de usuarios
const USERS_SERVICE_URL = 'http://192.168.100.2:3005/api/usuarios'; // Microservicio de usuarios
const CARS_SERVICE_URL = 'http://192.168.100.2:3007/api/cars'; // Microservicio de car

// Crear una nueva venta
const createVenta = async (req, res) => {
    const { car_id, customer_id, color, used } = req.body;

    try {
        // 1. Verificar si el car_id existe en el microservicio de car
        const carResponse = await axios.get(`${CARS_SERVICE_URL}/${car_id}`);
        const carData = carResponse.data;

        if (!carData || !carData.id) {
            return res.status(404).json({ message: 'Carro no encontrado' });
        }

        // Obtener el aprox_price del carro
        const price = carData.aprox_price;

        // 2. Verificar si el customer_id existe y es cliente en el microservicio de usuarios
        const userResponse = await axios.get(`${USERS_SERVICE_URL}/${customer_id}`);
        const userData = userResponse.data;

        if (!userData || !userData.id) {
            return res.status(404).json({ message: 'Usuario no encontrado' });
        }

        if (userData.role !== 'User') {
            return res.status(400).json({ message: 'El usuario no es un cliente válido' });
        }

        // 3. Si todo está correcto, crear la venta
        const newVenta = {
            car_id,
            customer_id,
            color,
            price, // Usamos el aprox_price del carro
            used
        };

        const ventaId = await ventasModel.createVenta(newVenta);

        res.status(201).json({ id: ventaId, message: 'Venta creada exitosamente' });

    } catch (error) {
        console.error('Error al crear venta:', error);
        res.status(500).json({ message: 'Error al crear venta' });
    }
};

// Obtener todas las ventas de un cliente
const getVentasByCustomerId = async (req, res) => {
    const { customer_id } = req.params;
    try {
        const ventas = await ventasModel.getVentasByCustomerId(customer_id);
        res.json(ventas);
    } catch (error) {
        console.error('Error al obtener ventas del cliente:', error);
        res.status(500).json({ message: 'Error al obtener ventas del cliente' });
    }
};

// Obtener todas las ventas de un carro
const getVentasByCarId = async (req, res) => {
    const { car_id } = req.params;
    try {
        const ventas = await ventasModel.getVentasByCarId(car_id);
        res.json(ventas);
    } catch (error) {
        console.error('Error al obtener ventas del carro:', error);
        res.status(500).json({ message: 'Error al obtener ventas del carro' });
    }
};

// Obtener todas las ventas de una lista de car_ids
const getVentasByCarIds = async (req, res) => {
    const { car_ids } = req.body; // car_ids debe ser un array de IDs

    if (!Array.isArray(car_ids)) {
        return res.status(400).json({ message: 'car_ids debe ser una lista de IDs' });
    }

    try {
        const ventas = await ventasModel.getVentasByCarIds(car_ids);
        res.json(ventas);
    } catch (error) {
        console.error('Error al obtener ventas por car_ids:', error);
        res.status(500).json({ message: 'Error al obtener ventas por car_ids' });
    }
};

// Eliminar todas las ventas de un cliente
const deleteVentasByCustomerId = async (req, res) => {
    const customer_id = req.params.customer_id;
    try {
        await ventasModel.deleteVentasByCustomerId(customer_id);
        res.json({ message: 'Ventas del cliente eliminadas exitosamente' });
    } catch (error) {
        console.error('Error al eliminar ventas del cliente:', error);
        res.status(500).json({ message: 'Error al eliminar ventas del cliente' });
    }
};

// Eliminar todas las ventas de un carro
const deleteVentasByCarId = async (req, res) => {
    const car_id = req.params.car_id;
    try {
        await ventasModel.deleteVentasByCarId(car_id);
        res.json({ message: 'Ventas del carro eliminadas exitosamente' });
    } catch (error) {
        console.error('Error al eliminar ventas del carro:', error);
        res.status(500).json({ message: 'Error al eliminar ventas del carro' });
    }
};

module.exports = {
    getVentas, createVenta, getVentasByCustomerId, getVentasByCarId, getVentasByCarIds,
    deleteVentasByCustomerId, deleteVentasByCarId
};

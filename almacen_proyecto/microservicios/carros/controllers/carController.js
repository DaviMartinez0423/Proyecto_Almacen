const carModel = require('../models/carModel');
const axios = require('axios'); // Asegúrate de importar axios

const ventasServiceUrl = 'http://192.168.100.2:3007'; // URL del microservicio de ventas


const getCars = async (req, res) => {
    try {
        const cars = await carModel.getCars();
        res.json(cars);
    } catch (error) {
        console.error('Error al obtener carros:', error);
        res.status(500).json({ message: 'Error al obtener carros' });
    }
};

const getCarById = async (req, res) => {
    const { id } = req.params;
    try {
        const car = await carModel.getCarById(id);
        if (car) {
            res.json(car);
        } else {
            res.status(404).json({ message: 'Carro no encontrado' });
        }
    } catch (error) {
        console.error('Error al obtener carro:', error);
        res.status(500).json({ message: 'Error al obtener carro' });
    }
};

const createCar = async (req, res) => {
    const car = req.body;
    try {
        const id = await carModel.createCar(car);
        res.status(201).json({ id, message: 'Carro creado exitosamente' });
    } catch (error) {
        console.error('Error al crear carro:', error);
        res.status(500).json({ message: 'Error al crear carro' });
    }
};


const updateCar = async (req, res) => {
    const { id } = req.params;
    const car = req.body;
    try {
        await carModel.updateCar(id, car);
        res.json({ message: 'Carro actualizado exitosamente' });
    } catch (error) {
        console.error('Error al actualizar carro:', error);
        res.status(500).json({ message: 'Error al actualizar carro' });
    }
};


const deleteCar = async (req, res) => {
    const { id } = req.params;
    try {
        // Elimina el carro
        await carModel.deleteCar(id);

        // Llama al microservicio de ventas para eliminar todas las ventas relacionadas
        await axios.delete(`${ventasServiceUrl}/api/ventas/car/${id}`);

        res.json({ message: 'Car deleted and related sales removed' });
    } catch (error) {
        console.error('Error al eliminar carro:', error);
        res.status(500).json({ message: 'Error al eliminar carro' });
    }
};






module.exports = {
    getCars,
    getCarById,
    createCar,
    updateCar,
    deleteCar
};
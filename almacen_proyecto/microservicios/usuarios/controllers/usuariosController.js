const usuariosModel = require('../models/usuariosModel');
const axios = require('axios');

const ventasServiceUrl = 'http://192.168.100.2:3009';

async function getAllUsers(req, res) {
    const users = await usuariosModel.getAllUsers();
    res.json(users);
}

async function getUserById(req, res) {
    const user = await usuariosModel.getUserById(req.params.id);
    if (user) {
        res.json(user);
    } else {
        res.status(404).json({ message: 'User not found' });
    }
}

async function createUser(req, res) {
    await usuariosModel.createUser(req.body);
    res.status(201).json({ message: 'User created' });
}

async function updateUser(req, res) {
    await usuariosModel.updateUser(req.params.id, req.body);
    res.json({ message: 'User updated' });
}

async function deleteUser(req, res) {
    const { id } = req.params;
    try {
        // Elimina el usuario
        await usuariosModel.deleteUser(id);

        // Llama al microservicio de ventas para eliminar todas las ventas relacionadas
        await axios.delete(`${ventasServiceUrl}/api/ventas/customer/${id}`);

        res.json({ message: 'User deleted and related sales removed' });
    } catch (error) {
        console.error('Error al eliminar usuario:', error);
        res.status(500).json({ message: 'Error al eliminar usuario' });
    }
}

async function login(req, res) {
    const id = req.params.id;
    const password = req.params.password;

    const result = await usuariosModel.validarUsuario(id, password);
    if (result) {
        res.json(result);  // Devuelve el usuario si lo encuentra
    } else {
        res.status(401).json({ message: 'Invalid credentials' });  // Si no, devuelve error
    }
}

module.exports = { getAllUsers, getUserById, createUser, updateUser, deleteUser, login};

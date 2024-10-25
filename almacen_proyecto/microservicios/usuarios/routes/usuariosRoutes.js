const express = require('express');
const router = express.Router();
const usuariosController = require('../controllers/usuariosController');

router.get('/usuarios', usuariosController.getAllUsers);
router.get('/usuarios/:id', usuariosController.getUserById);
router.post('/usuarios', usuariosController.createUser);
router.put('/usuarios/:id', usuariosController.updateUser);
router.delete('/usuarios/:id', usuariosController.deleteUser);
router.get('/login/:id/:password', usuariosController.login);


module.exports = router;
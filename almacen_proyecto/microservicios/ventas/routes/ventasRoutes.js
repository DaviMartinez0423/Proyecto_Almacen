const express = require('express');
const router = express.Router();
const ventasController = require('../controllers/ventasController');

router.get('/ventas', ventasController.getVentas);
router.post('/ventas', ventasController.createVenta);
router.get('/ventas/customer/:customer_id', ventasController.getVentasByCustomerId);
router.get('/ventas/car/:car_id', ventasController.getVentasByCarId);
router.post('/ventas/cars', ventasController.getVentasByCarIds);
router.delete('/ventas/customer/:customer_id', ventasController.deleteVentasByCustomerId);
router.delete('/ventas/car/:car_id', ventasController.deleteVentasByCarId);

module.exports = router;

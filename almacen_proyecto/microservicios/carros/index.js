const express = require('express');
const morgan = require('morgan');
const app = express();
const carRoutes = require('./routes/carRoutes');

app.use(morgan('dev'));
app.use(express.json());

app.use('/api', carRoutes);

const PORT = process.env.PORT || 3007;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en el puerto ${PORT}`);
});
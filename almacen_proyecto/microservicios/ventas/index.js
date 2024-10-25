const express = require('express');
const morgan = require('morgan');
const ventasRoutes = require('./routes/ventasRoutes');
const db = require('./dbConnection');
const app = express();

app.use(morgan('dev'));
app.use(express.json());

// Usar las rutas de ventas
app.use('/api', ventasRoutes);

db.getConnection()
    .then(() => console.log('Database connected successfully'))
    .catch(err => console.error('Database connection failed:', err));

const PORT = process.env.PORT || 3009;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});

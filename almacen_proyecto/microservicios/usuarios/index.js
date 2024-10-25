const express = require('express');
const morgan = require('morgan');
const usuariosRoutes = require('./routes/usuariosRoutes');
const db = require('./dbConnection');
const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use('/api', usuariosRoutes);

db.getConnection()
    .then(() => console.log('Database connected successfully'))
    .catch(err => console.error('Database connection failed:', err));

const PORT = process.env.PORT || 3005;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
})
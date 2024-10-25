const db = require('../dbConnection');

async function getAllUsers() {
    const [rows] = await db.query('SELECT * FROM usuarios');
    return rows;
}

async function getUserById(id) {
    const [rows] = await db.query('SELECT * FROM usuarios WHERE id = ?', [id]);
    return rows[0];
}

async function createUser(user) {
    const { name, gender, phone, role, password } = user;
    await db.query('INSERT INTO usuarios (name, gender, phone, role, password) VALUES (?, ?, ?, ?, ?)', [name, gender, phone, role, password]);
}

async function updateUser(id, user) {
    const { name, gender, phone, role, password } = user;
    await db.query('UPDATE usuarios SET name = ?, gender = ?, phone = ?, role = ?, password = ? WHERE id = ?', [name, gender, phone, role, password, id]);
}

async function deleteUser(id) {
    await db.query('DELETE FROM usuarios WHERE id = ?', [id]);
}

async function validarUsuario(id, password) {
    const [rows] = await db.query('SELECT * FROM usuarios WHERE id = ? AND password = ?', [id, password]);
    return rows[0];  // Retorna el usuario si coincide el ID y la contraseña
}

module.exports = { getAllUsers, getUserById, createUser, updateUser, deleteUser, validarUsuario };

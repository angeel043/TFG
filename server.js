const express = require('express');
const app = express();
const port = 3000;

// Middleware para servir archivos estáticos
app.use(express.static('public')); // Asegúrate de tener tus archivos HTML en una carpeta llamada "public"

// Ruta de inicio (login)
app.get('/', (req, res) => {
    res.sendFile(__dirname + '/public/login.html'); // Cambiado de index.html a login.html
});

// Ruta para la página principal
app.get('/home', (req, res) => {
    res.sendFile(__dirname + '/public/home.html'); // Archivo de la página principal
});

// Iniciar el servidor
app.listen(port, () => {
    console.log(`Servidor escuchando en http://localhost:${port}`);
});

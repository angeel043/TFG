<?php
// Datos de conexión a tu base de datos MySQL
$host = 'localhost';  // O la IP de tu servidor MySQL si es externo
$db = 'test tfg';  // El nombre de tu base de datos
$user = 'root';  // Usuario de la base de datos
$pass = '';  // La contraseña de tu base de datos (en XAMPP suele ser vacía)

// Conexión usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Configurar el modo de errores de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>

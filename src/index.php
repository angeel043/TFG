<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto por tu nombre de usuario
$password = ""; // Cambia esto por tu contraseña
$dbname = "test tfg"; // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se enviaron las credenciales
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Consulta SQL para verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario='$user' AND password='$pass'";
    $result = $conn->query($sql);

    // Si se encuentra un resultado, iniciar sesión
    if ($result->num_rows > 0) {
        // Redirigir a la página de inicio
        header("Location: ../public/home.html");
        exit();
    } else {
        // Redirigir a la página de inicio de sesión con error
        header("Location: ../public/index.html?error=true");
        exit();
    }
}

$conn->close();
?>

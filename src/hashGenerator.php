<?php
$password = 'natalia';  // Reemplaza con la contraseña que quieras hashear

// Genera el hash de la contraseña
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "La contraseña hasheada es: " . $hashedPassword;
?>

<?php
class UserModel {
    // Método para registrar un usuario
    public static function register($username, $password) {
        $conn = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT); // Encriptar contraseña
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);
        
        if ($stmt->execute()) {
            return ['message' => 'Usuario creado exitosamente'];
        } else {
            return ['message' => 'Error al crear el usuario: ' . $stmt->error];
        }
    }

    // Método para iniciar sesión
    public static function login($username, $password) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['password'])) {
            return ['message' => 'Login exitoso', 'user' => $username];
        } else {
            return ['message' => 'Usuario o contraseña incorrectos'];
        }
    }

    // Método para obtener todos los usuarios
    public static function getAllUsers() {
        $conn = Database::getConnection();
        $result = $conn->query("SELECT * FROM usuarios");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>

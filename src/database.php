<?php
class Database {
    private static $conn;

    // Crear conexión única a la base de datos (Singleton pattern)
    public static function getConnection() {
        if (!self::$conn) {
            self::$conn = new mysqli('localhost', 'root', '', 'test tfg');
            if (self::$conn->connect_error) {
                die("Error en la conexión: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>

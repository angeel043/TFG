<?php
session_start(); // Iniciar la sesión

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

    // Usamos sentencias preparadas para evitar SQL injection
    $stmt = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el usuario existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_usuario = $user['id'];
        $hash_almacenado = $user['password'];
        $role = $user['rol'];  // Obtener el rol del usuario

        // Verificar si la contraseña es correcta
        if (password_verify($pass, $hash_almacenado)) {
            // Guardar el id del usuario y el rol en la sesión
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['rol'] = $role;

            // Manejar la opción de recordar sesión
            if (isset($_POST['rememberMe'])) {
                setcookie('username', $user, time() + (86400 * 30), "/"); // Guardar por 30 días
            }

            // Redirigir según el rol del usuario
            switch ($role) {
                case 0:
                    header("Location: ../src/admin.php"); // Admin
                    break;
                case 1:
                    header("Location: ../src/home.php"); // Usuario
                    break;
                case 2:
                    header("Location: ../src/sv.php"); // Supervisor
                    break;
                case 3:
                    header("Location: ../src/st.php"); // Soporte técnico
                    break;
                default:
                    // En caso de un rol no reconocido
                    header("Location: ../public/index.html?error=true");
                    break;
            }
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../public/index.html?error=true");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: ../public/index.html?error=true");
        exit();
    }
}

// Cerrar la conexión
$conn->close();
?>

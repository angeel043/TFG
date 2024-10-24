<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../public/index.html");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include '../src/database.php';
$conn = Database::getConnection();

$id_usuario = $_SESSION['id_usuario'];

// Recoge los datos enviados desde el formulario (checkbox de completado y extrainfo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['extrainfo'])) {
    $completados = isset($_POST['completado']) ? $_POST['completado'] : []; // Array de IDs de clientes completados
    $extrainfo = $_POST['extrainfo'];    // Array con extrainfo (ID => extrainfo)

    // Recorre todos los clientes que tiene asignados el usuario
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE idUser = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cliente_id = $row['id'];

        // Verifica si este cliente está marcado como completado
        $completado = in_array($cliente_id, $completados) ? 1 : 0;

        // Obtener el texto de extraInfo para este cliente
        $extraInfoTexto = isset($extrainfo[$cliente_id]) ? $extrainfo[$cliente_id] : '';

        // Actualizar la base de datos con los valores
        $update_stmt = $conn->prepare("UPDATE clientes SET completado = ?, extrainfo = ? WHERE id = ?");
        $update_stmt->bind_param("isi", $completado, $extraInfoTexto, $cliente_id);

        if (!$update_stmt->execute()) {
            // Manejar el error si ocurre durante la ejecución
            echo "Error al actualizar: " . $conn->error;
        }
    }

    $stmt->close();
    $update_stmt->close();
}

// Destruir la sesión
session_unset();
session_destroy();

// Cerrar la conexión
$conn->close();

// Redirigir al usuario a la página de inicio de sesión
header("Location: ../public/index.html");
exit();
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php'; 

function registrarLog($datos) {
    $idAccion = $datos[0];
    $idUsuarioAccionador = $datos[1];
    $idEntidadAfectada = $datos[2] ?? null;
    $idUsuarioIT = $datos[3] ?? null;

    $conn = Database::getConnection();

    $stmtUser = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmtUser->bind_param("i", $idUsuarioAccionador);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userAccionador = $resultUser->fetch_assoc()['nombre_usuario'] ?? 'Desconocido';
    $stmtUser->close();

    $mensaje = "";
    $hora = date("H:i:s");

    switch ($idAccion) {
        case 0: case 2: case 4: // Acciones con usuarios (crear, modificar, eliminar)
            $accion = ["creado al usuario", "modificado al usuario", "eliminado al usuario"][$idAccion/2];
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha $accion con ID $idEntidadAfectada a las $hora.";
            break;

        case 1: case 3: case 5: // Acciones con clientes
            $accion = ["creado al cliente", "modificado al cliente", "eliminado al cliente"][($idAccion - 1)/2];
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha $accion con ID $idEntidadAfectada a las $hora.";
            break;

        case 6: // Crear ticket asignado a IT
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha creado un ticket con ID $idEntidadAfectada que se ha asignado al usuario de IT con ID $idUsuarioIT a las $hora.";
            break;

        case 7:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha exportado la tabla de usuarios en formato PDF a las $hora.";
            break;
        case 8:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha exportado la tabla de usuarios en formato XLSX a las $hora.";
            break;
        case 9:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha exportado la tabla de clientes en formato PDF a las $hora.";
            break;
        case 10:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha exportado la tabla de clientes en formato XLSX a las $hora.";
            break;
        case 11:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha importado $idEntidadAfectada usuarios a las $hora.";
            break;
        case 12:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha importado $idEntidadAfectada clientes a las $hora.";
            break;
        case 13:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha iniciado sesión a las $hora.";
            break;
        case 14:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha cerrado sesión a las $hora.";
            break;
        case 16:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha marcado como completado el cliente con ID $idEntidadAfectada a las $hora.";
            break;
        case 17:
            $mensaje = "El usuario $userAccionador con ID $idUsuarioAccionador ha marcado como completado el ticket con ID $idEntidadAfectada a las $hora.";
            break;
        default:
            $mensaje = "Acción desconocida realizada por el usuario $userAccionador con ID $idUsuarioAccionador a las $hora.";
            break;
    }

    $fecha = date("Y-m-d");
    $directorio = __DIR__ . '/../../logs/';
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true); // Crear la carpeta si no existe
    }
    $rutaArchivo = $directorio . $fecha . '.txt';

    file_put_contents($rutaArchivo, $mensaje . PHP_EOL, FILE_APPEND);
}

/***********************************************
* 
* Funcion que consulta a la BBDD el nombre de un usuario
* dado su ID
* 
***********************************************/
function obtenerNombreUsuario($conn, $id) {
    $stmt = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nombre = $result->fetch_assoc()['nombre_usuario'] ?? "Desconocido";
    return $nombre;
}

/***********************************************
* 
* Funcion que consulta a la BBDD el nombre de un cliente
* dado su ID
* 
***********************************************/
function obtenerNombreCliente($conn, $id) {
    $stmt = $conn->prepare("SELECT nombre FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nombre = $result->fetch_assoc()['nombre'] ?? "Desconocido";
    return $nombre;
}
?>

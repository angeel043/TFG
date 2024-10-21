<?php
session_start();

// Verifica si hay una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include '../src/database.php'; // Ajusta la ruta según tu estructura de directorios

// Obtener la conexión
$conn = Database::getConnection();

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener los clientes asociados al usuario
$stmt = $conn->prepare("SELECT nombre, email, telefono FROM clientes WHERE idUser = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">EMPRESA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2f33;
            color: #ffffff;
        }
        .container {
            background-color: #23272a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        .form-label, .navbar-brand, .navbar, .modal-title, .modal-body {
            color: #ffffff;
        }
        .navbar {
            background-color: #23272a;
        }
        .btn-primary {
            background-color: #7289da;
            border-color: #7289da;
        }
        .btn-danger {
            background-color: #ff4757; /* Color rojo para el botón de cerrar sesión */
            border-color: #ff4757; /* Color de borde para el botón de cerrar sesión */
        }
        .modal-content {
            background-color: #2c2f33;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
        <a class="navbar-brand" href="#" id="navTitle">EMPRESA</a>
            <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#ajustesModal">
                <span id="settingsButton">Ajustes</span>
            </button>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 id="clientsTitle">Clientes asociados</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th id="nameColumn">Nombre</th>
                    <th id="mailColumn">Correo</th>
                    <th id="phoneColumn">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($cliente = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($cliente['nombre']) . "</td>"; 
                        echo "<td>" . htmlspecialchars($cliente['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($cliente['telefono']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' id='noClients'>No se encontraron clientes asociados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de ajustes -->
    <div class="modal fade" id="ajustesModal" tabindex="-1" aria-labelledby="ajustesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsTitle">Ajustes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="languageSelect" class="form-label" id="selectLanguageLabel">Seleccionar idioma:</label>
                    <select id="languageSelect" class="form-select">
                        <option value="es">Español</option>
                        <option value="en">English</option>
                    </select>
                    <button type="button" class="btn btn-danger w-100 mt-3" id="logoutButton">Cerrar sesión</button> <!-- Botón de cerrar sesión -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeSettingsButton">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de cierre de sesión -->
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutTitle">Confirmar cierre de sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmLogoutLabel">
                    ¿Está seguro de que desea cerrar sesión?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="yesLogoutButton">Sí</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="noLogoutButton">No</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Verificar si hay un idioma guardado en localStorage al cargar la página
        let currentLanguage = localStorage.getItem('language') || 'es'; // 'es' por defecto

        function loadLanguage(lang) {
            fetch(`../public/idiomas/home/${lang}.txt`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la carga del archivo de idioma: ' + response.status);
                    }
                    return response.text();
                })
                .then(data => {
                    const lines = data.split('\n');
                    console.log('Líneas cargadas:', lines); // Para ver qué se carga

                    // Asegúrate de que los elementos existen antes de intentar cambiar su texto
                    if (document.getElementById('title')) {
                        document.getElementById('title').innerText = lines[0]; // Título de la página
                    }
                    if (document.getElementById('navTitle')) {
                        document.getElementById('navTitle').innerText = lines[0]; // Título de la barra de navegación
                    }
                    if (document.getElementById('errorClients')) {
                        document.getElementById('errorClients').innerText = lines[1]; // Título de la barra de navegación
                    }
                    if (document.getElementById('settingsButton')) {
                        document.getElementById('settingsButton').innerText = lines[2]; // Título de ajustes
                    }
                    if (document.getElementById('settingsTitle')) {
                        document.getElementById('settingsTitle').innerText = lines[3]; // Título de ajustes
                    }
                    if (document.getElementById('selectLanguageLabel')) {
                        document.getElementById('selectLanguageLabel').innerText = lines[4]; // Etiqueta de seleccionar idioma
                    }
                    if (document.getElementById('closeSettingsButton')) {
                        document.getElementById('closeSettingsButton').innerText = lines[5]; // Botón de cerrar ajustes
                    }
                    if (document.getElementById('logoutButton')) {
                        document.getElementById('logoutButton').innerText = lines[6]; // Botón de cerrar sesión
                    }
                    if (document.getElementById('confirmLogoutTitle')) {
                        document.getElementById('confirmLogoutTitle').innerText = lines[7]; // Título de confirmar cerrar sesión
                    }
                    if (document.getElementById('confirmLogoutLabel')) {
                        document.getElementById('confirmLogoutLabel').innerText = lines[8]; // Etiqueta de confirmar cerrar sesión
                    }
                    if (document.getElementById('yesLogoutButton')) {
                        document.getElementById('yesLogoutButton').innerText = lines[9]; // Botón de confirmar cerrar sesión
                    }
                    if (document.getElementById('noLogoutButton')) {
                        document.getElementById('noLogoutButton').innerText = lines[10]; // Botón de denegar cerrar sesión
                    }
                    if (document.getElementById('clientsTitle')) {
                        document.getElementById('clientsTitle').innerText = lines[11]; // Título de la tabla
                    }
                    if (document.getElementById('nameColumn')) {
                        document.getElementById('nameColumn').innerText = lines[12]; // Columna de nombre de clientes
                    }
                    if (document.getElementById('mailColumn')) {
                        document.getElementById('mailColumn').innerText = lines[13]; // Columna de correo de clientes
                    }
                    if (document.getElementById('phoneColumn')) {
                        document.getElementById('phoneColumn').innerText = lines[14]; // Columna de teléfono de clientes
                    }
                })
                .catch(error => console.error('Error al cargar el idioma:', error));
        }

        

        // Cargar idioma al inicio
        loadLanguage(currentLanguage);
        

        // Cambiar idioma y guardar en localStorage al seleccionar un nuevo idioma
        document.getElementById('languageSelect').addEventListener('change', function () {
            currentLanguage = this.value; // Obtener el idioma seleccionado
            localStorage.setItem('language', currentLanguage); // Guardar el idioma en localStorage
            loadLanguage(currentLanguage); // Cargar el nuevo idioma
        });

        // Actualizar el idioma seleccionado en el menú desplegable al abrir el modal
        $('#ajustesModal').on('show.bs.modal', function () {
            document.getElementById('languageSelect').value = currentLanguage; // Ajustar el valor del select al idioma actual
        });

        // Abrir el modal de confirmación de cierre de sesión
        document.getElementById('logoutButton').addEventListener('click', function () {
            $('#confirmLogoutModal').modal('show');
        });

        // Manejar la confirmación de cierre de sesión
        document.getElementById('yesLogoutButton').addEventListener('click', function () {
            window.location.href = 'logout.php'; // Redirigir al logout
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Cierra la conexión
?>

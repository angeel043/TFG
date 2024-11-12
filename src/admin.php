<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">Sinova Call Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2f33;
            color: #ffffff;
            margin: 0;
        }
        .navbar {
            background-color: #23272a;
        }
        .container {
            background-color: #23272a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            width: 800px;
            margin: 50px auto;
        }
        .form-label, .navbar-brand {
            color: #ffffff;
        }
        .btn-primary {
            background-color: #7289da;
            border-color: #7289da;
        }
        .btn-primary:hover {
            background-color: #5b6eae;
        }
        .modal-content {
            background-color: #2c2f33;
            color: #ffffff;
        }
        .modal-header {
            border-bottom: 1px solid #7289da;
        }
        .modal-footer {
            border-top: 1px solid #7289da;
        }
        .form-control {
            width: 100%;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" id="navTitle">SINOVA - Panel de administración</a>
            <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#ajustesModal">
                <span id="settingsButton">Ajustes</span>
            </button>
        </div>
    </nav>

    <div class="row mt-5"> <!-- Añadimos la clase mt-5 para margen superior -->
    <!-- Columna para Opciones de Usuarios -->
    <div class="col-md-6">
        <div class="container bg-dark p-4 rounded">
            <h2 id="userOptionsLabel" class="mb-5 text-center">Opciones de usuarios</h2>

            <!-- Botones para las funcionalidades -->
            <div class="row mb-3 justify-content-center">
                <!-- Primer y segundo botón en la primera fila -->
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal" id="createUserButton">Crear usuario</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#modificarUsuarioModal" id="modifyUserButton">Modificar usuario</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#borrarUsuarioModal" id="deleteUserButton">Borrar usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna para Opciones de Clientes -->
    <div class="col-md-6">
        <div class="container bg-dark p-4 rounded">
            <h2 id="clientOptionsLabel" class="mb-5 text-center">Opciones de clientes</h2>

            <!-- Botones para las funcionalidades -->
            <div class="row mb-3 justify-content-center">
                <!-- Primer y segundo botón en la primera fila -->
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#crearClienteModal" id="createClientButton">Crear cliente</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#modificarClienteModal" id="modifyClientButton">Modificar cliente</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#borrarClienteModal" id="deleteClientButton">Borrar cliente</button>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Modales para las acciones -->
    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserTitle">Crear usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearUsuarioForm">
                        <div class="mb-3">
                            <label for="usernameCreate" class="form-label" id="createUserUsernameLabel">Nombre de usuario</label>
                            <input type="text" class="form-control" id="usernameCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="mailUserCreate" class="form-label" id="createUserEmailLabel">Correo electrónico</label>
                            <input type="email" class="form-control" id="mailUserCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordUserCreate" class="form-label" id="createUserPasswordLabel">Contraseña</label>
                            <input type="email" class="form-control" id="passwordUserCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="roleUserCreate" class="form-label" id="createUserRoleLabel">Seleccionar rol</label>
                            <select class="form-select" id="roleUserCreate" required>
                                <option value="" disabled selected id="createUserRoleSelect">Selecciona un rol</option>
                                <option value="admin" id="createUserRoleAdmin">Administrador</option>
                                <option value="moderator" id="createUserRoleUser">Usuario</option>
                                <option value="agent" id="createUserRoleSuervisor">Supervisor</option>
                                <option value="client" id="createUserRoleIT">Soporte técnico</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="createUserCancelButton">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="createUsersubmitButton">Guardar usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Cliente -->
    <div class="modal fade" id="crearClienteModal" tabindex="-1" aria-labelledby="crearClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearClienteForm">
                        <div class="mb-3">
                            <label for="nameClientCreate" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nameClientCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="mailClientCreate" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="mailClientCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="phoneClientCreate" class="form-label">Telefono</label>
                            <input type="email" class="form-control" id="phoneClientCreate" required>
                        </div>                    
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarClienteBtn">Guardar cliente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Usuario -->
    <div class="modal fade" id="modificarUsuarioModal" tabindex="-1" aria-labelledby="modificarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="usuarioSelect" class="form-select mb-3">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </select>
                    <form id="modificarUsuarioForm" style="display:none;">
                        <div class="mb-3">
                            <label for="modificarUsuarioNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="modificarUsuarioNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarUsuarioEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modificarUsuarioEmail" required>
                        </div>
                        <!-- Agrega más campos según tus necesidades -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="modificarUsuarioBtn">Modificar Usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Cliente -->
    <div class="modal fade" id="modificarClienteModal" tabindex="-1" aria-labelledby="modificarClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="clienteSelect" class="form-select mb-3">
                        <!-- Los clientes se cargarán aquí dinámicamente -->
                    </select>
                    <form id="modificarClienteForm" style="display:none;">
                        <div class="mb-3">
                            <label for="modificarClienteNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="modificarClienteNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="modificarClienteEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modificarClienteEmail" required>
                        </div>
                        <!-- Agrega más campos según tus necesidades -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="modificarClienteBtn">Modificar Cliente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Borrar Usuario -->
    <div class="modal fade" id="borrarUsuarioModal" tabindex="-1" aria-labelledby="borrarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="usuarioBorrarSelect" class="form-select mb-3">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="borrarUsuarioBtn">Borrar Usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Borrar Cliente -->
    <div class="modal fade" id="borrarClienteModal" tabindex="-1" aria-labelledby="borrarClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="clienteBorrarSelect" class="form-select mb-3">
                        <!-- Los clientes se cargarán aquí dinámicamente -->
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="borrarClienteBtn">Borrar Cliente</button>
                </div>
            </div>
        </div>
    </div>

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
                    <button type="button" class="btn btn-danger w-100 mt-3" id="logoutButton">Cerrar sesión</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeSettingsButton">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
        let currentLanguage = localStorage.getItem('language') || 'es'; // 'es' por defecto

        function loadLanguage(lang) {
    const timestamp = new Date().getTime();
    fetch(`../public/idiomas/admin/${lang}.txt?t=${timestamp}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la carga del archivo de idioma: ' + response.status);
            }
            return response.text();
        })
        .then(data => {
            const lines = data.split('\n');
            const elements = [
                'navTitle', 'settingsButton', 'settingsTitle', 'selectLanguageLabel', 
                'closeSettingsButton', 'confirmLogoutLabel', 'userOptionsLabel', 
                'createUserButton', 'modifyUserButton', 'deleteUserButton', 
                'clientOptionsLabel', 'createClientButton', 'modifyClientButton', 
                'deleteClientButton', 'createUserTitle', 'createUserUsernameLabel', 
                'createUserEmailLabel', 'createUserPasswordLabel', 'createUserRoleLabel', 
                'createUserRoleSelect', 'createUserRoleAdmin', 'createUserRoleUser', 
                'createUserRoleSupervisor', 'createUserRoleIT', 'createUserCancelButton', 
                'createUserSubmitButton'
            ];

            elements.forEach((id, index) => {
                const element = document.getElementById(id);
                if (element) {
                    element.innerText = lines[index] || "";  // Asigna el texto solo si existe la línea
                } else {
                    console.warn(`Elemento con ID '${id}' no encontrado en el DOM.`);
                }
            });
        })
        .catch(error => console.error('Error al cargar el idioma:', error));
}

        // Cargar idioma al inicio
        loadLanguage(currentLanguage);

        // Cambiar idioma y guardar en localStorage al seleccionar un nuevo idioma
        document.getElementById('languageSelect').addEventListener('change', function () {
            currentLanguage = this.value;
            localStorage.setItem('language', currentLanguage);
            loadLanguage(currentLanguage);
        });

        // Actualizar el idioma seleccionado en el menú desplegable al abrir el modal
        $('#ajustesModal').on('show.bs.modal', function () {
            document.getElementById('languageSelect').value = currentLanguage;
        });

        // Manejar el cierre de sesión
        document.getElementById('logoutButton').addEventListener('click', function() {
            $('#confirmLogoutModal').modal('show');
        });

        // Confirmar cierre de sesión
        document.getElementById('yesLogoutButton').addEventListener('click', function() {
            window.location.href = '../src/logout.php'; // Asegúrate que esta URL sea correcta
        });

        // Cerrar modal sin hacer nada
        document.getElementById('noLogoutButton').addEventListener('click', function() {
            $('#confirmLogoutModal').modal('hide');
        });

        
        

        document.getElementById("createUserSubmitButton").addEventListener("click", function() {
            const username = document.getElementById("usernameCreate").value;
            const email = document.getElementById("mailUserCreate").value;
            const password = document.getElementById("passwordUserCreate").value;
            const role = document.getElementById("roleUserCreate").value;

            if (username && email && password && role) {
                fetch("crear_usuario.php", {  
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ username, email, password, role }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Usuario creado exitosamente");
                        document.getElementById("crearUsuarioForm").reset();
                        new bootstrap.Modal(document.getElementById("crearUsuarioModal")).hide();
                    } else {
                        alert("Error al crear usuario");
                    }
                })
                .catch(error => console.error("Error:", error));
            } else {
                alert("Por favor completa todos los campos.");
            }
        });

        document.getElementById("guardarClienteBtn").addEventListener("click", function() {
            const fullName = document.getElementById("nameClientCreate").value;
            const email = document.getElementById("mailClientCreate").value;
            const phone = document.getElementById("phoneClientCreate").value;

            if (fullName && email && phone) {
                fetch("crear_cliente.php", {  
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ fullName, email, phone }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Cliente creado exitosamente");
                        document.getElementById("crearClienteForm").reset();
                        new bootstrap.Modal(document.getElementById("crearClienteModal")).hide();
                    } else {
                        alert("Error al crear cliente");
                    }
                })
                .catch(error => console.error("Error:", error));
            } else {
                alert("Por favor completa todos los campos.");
            }
        });

    </script>
</body>
</html>

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
            <a class="navbar-brand" href="#" id="navTitle">SINOVA - Panel de Administración</a>
            <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#ajustesModal">
                <span id="settingsButton">Ajustes</span>
            </button>
        </div>
    </nav>

    <div class="row mt-5"> <!-- Añadimos la clase mt-5 para margen superior -->
    <!-- Columna para Opciones de Usuarios -->
    <div class="col-md-6">
        <div class="container bg-dark p-4 rounded">
            <h2 id="adminTitle" class="mb-5 text-center">Opciones de usuarios</h2>

            <!-- Botones para las funcionalidades -->
            <div class="row mb-3 justify-content-center">
                <!-- Primer y segundo botón en la primera fila -->
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">Crear usuario</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#modificarUsuarioModal">Modificar usuario</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#borrarUsuarioModal">Borrar usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna para Opciones de Clientes -->
    <div class="col-md-6">
        <div class="container bg-dark p-4 rounded">
            <h2 id="adminTitle" class="mb-5 text-center">Opciones de clientes</h2>

            <!-- Botones para las funcionalidades -->
            <div class="row mb-3 justify-content-center">
                <!-- Primer y segundo botón en la primera fila -->
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#crearClienteModal">Crear cliente</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#modificarClienteModal">Modificar cliente</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#borrarClienteModal">Borrar cliente</button>
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
                    <h5 class="modal-title">Crear Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="crearUsuarioForm">
                        <div class="mb-3">
                            <label for="usuarioNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="usuarioNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="usuarioEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="usuarioEmail" required>
                        </div>
                        <!-- Agrega más campos según tus necesidades -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarUsuarioBtn">Guardar Usuario</button>
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
                            <label for="clienteNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="clienteNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="clienteEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="clienteEmail" required>
                        </div>
                        <!-- Agrega más campos según tus necesidades -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarClienteBtn">Guardar Cliente</button>
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

            fetch(`../public/idiomas/admin/${lang}.txt?timestamp=${timestamp}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la carga del archivo de idioma: ' + response.status);
                    }
                    return response.text();
                })
                .then(data => {
                    const lines = data.split('\n');
                    document.getElementById('navTitle').innerText = lines[0];
                    document.getElementById('settingsButton').innerText = lines[1];
                    document.getElementById('settingsTitle').innerText = lines[2];
                    document.getElementById('selectLanguageLabel').innerText = lines[3];
                    document.getElementById('closeSettingsButton').innerText = lines[4];
                    document.getElementById('confirmLogoutLabel').innerText = lines[5];
                    document.getElementById('userOptionsLabel').innerText = lines[6];
                    document.getElementById('createUserButton').innerText = lines[7];
                    document.getElementById('modifyUserButton').innerText = lines[8];
                    document.getElementById('deleteUserButton').innerText = lines[9];
                    document.getElementById('clientOptionsLabel').innerText = lines[10];
                    document.getElementById('createClientButton').innerText = lines[11];
                    document.getElementById('modifyClientButton').innerText = lines[12];
                    document.getElementById('deleteClientButton').innerText = lines[13];
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

        // Funciones para manejar la creación, modificación y eliminación de usuarios y clientes
        document.getElementById('guardarUsuarioBtn').addEventListener('click', function() {
            const nombre = document.getElementById('usuarioNombre').value;
            const email = document.getElementById('usuarioEmail').value;

            // Aquí iría la llamada AJAX para guardar el nuevo usuario
            // Por ejemplo:
            fetch('../src/crear_usuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ nombre, email })
            })
            .then(response => {
                if (response.ok) {
                    alert('Usuario creado exitosamente');
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al crear el usuario');
                }
            });

            $('#crearUsuarioModal').modal('hide');
        });

        document.getElementById('guardarClienteBtn').addEventListener('click', function() {
            const nombre = document.getElementById('clienteNombre').value;
            const email = document.getElementById('clienteEmail').value;

            // Aquí iría la llamada AJAX para guardar el nuevo cliente
            fetch('../src/crear_cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ nombre, email })
            })
            .then(response => {
                if (response.ok) {
                    alert('Cliente creado exitosamente');
                    location.reload(); // Recargar la página
                } else {
                    alert('Error al crear el cliente');
                }
            });

            $('#crearClienteModal').modal('hide');
        });

        // Funciones para cargar usuarios y clientes para modificar y borrar
        function loadUsers() {
            fetch('../src/get_usuarios.php') // Reemplaza con tu endpoint para obtener usuarios
                .then(response => response.json())
                .then(users => {
                    const select = document.getElementById('usuarioModificarSelect');
                    select.innerHTML = '';
                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = `${user.nombre} (${user.email})`;
                        select.appendChild(option);
                    });
                });
        }

        function loadClients() {
            fetch('../src/get_clientes.php') // Reemplaza con tu endpoint para obtener clientes
                .then(response => response.json())
                .then(clients => {
                    const select = document.getElementById('clienteModificarSelect');
                    select.innerHTML = '';
                    clients.forEach(client => {
                        const option = document.createElement('option');
                        option.value = client.id;
                        option.textContent = `${client.nombre} (${client.email})`;
                        select.appendChild(option);
                    });
                });
        }

        document.getElementById('modificarUsuarioBtn').addEventListener('click', function() {
            const id = document.getElementById('usuarioModificarSelect').value;

            fetch(`../src/get_usuario.php?id=${id}`) // Reemplaza con tu endpoint para obtener un usuario específico
                .then(response => response.json())
                .then(user => {
                    document.getElementById('modificarUsuarioNombre').value = user.nombre;
                    document.getElementById('modificarUsuarioEmail').value = user.email;
                });
            
            $('#modificarUsuarioModal').modal('show');
        });

        document.getElementById('modificarClienteBtn').addEventListener('click', function() {
            const id = document.getElementById('clienteModificarSelect').value;

            fetch(`../src/get_cliente.php?id=${id}`) // Reemplaza con tu endpoint para obtener un cliente específico
                .then(response => response.json())
                .then(client => {
                    document.getElementById('modificarClienteNombre').value = client.nombre;
                    document.getElementById('modificarClienteEmail').value = client.email;
                });
            
            $('#modificarClienteModal').modal('show');
        });

        // Llamar a las funciones para cargar usuarios y clientes al cargar la página
        loadUsers();
        loadClients();

        // Funciones para borrar usuario y cliente
        document.getElementById('borrarUsuarioBtn').addEventListener('click', function() {
            const id = document.getElementById('usuarioBorrarSelect').value;

            fetch(`../src/borrar_usuario.php?id=${id}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        alert('Usuario borrado exitosamente');
                        location.reload(); // Recargar la página
                    } else {
                        alert('Error al borrar el usuario');
                    }
                });

            $('#borrarUsuarioModal').modal('hide');
        });

        document.getElementById('borrarClienteBtn').addEventListener('click', function() {
            const id = document.getElementById('clienteBorrarSelect').value;

            fetch(`../src/borrar_cliente.php?id=${id}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        alert('Cliente borrado exitosamente');
                        location.reload(); // Recargar la página
                    } else {
                        alert('Error al borrar el cliente');
                    }
                });

            $('#borrarClienteModal').modal('hide');
        });
    </script>
</body>
</html>

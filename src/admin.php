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
            <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#settingsModal">
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
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#createUserModal" id="createUserBtn">Crear usuario</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#editUserModal" id="editUserBtn">Modificar usuario</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#deleteUserModal" id="deleteUserBtn">Borrar usuario</button>
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
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#createClientModal" id="createClientBtn">Crear cliente</button>
                </div>
                <div class="col-4 text-center">
                    <button class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#editClientModal" id="editClientBtn">Modificar cliente</button>
                </div>
            </div>
            <div class="row">
                <!-- Tercer botón en la segunda fila -->
                <div class="col-12 text-center">
                    <button class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#deleteClientModal" id="deleteClientBtn">Borrar cliente</button>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Modales para las acciones -->
    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserTitle">Crear usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
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
                                <option value="0" id="createUserRoleAdmin">Administrador</option>
                                <option value="1" id="createUserRoleUser">Usuario</option>
                                <option value="2" id="createUserRoleSupervisor">Supervisor</option>
                                <option value="3" id="createUserRoleIT">Soporte técnico</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="createUserCancelButton">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="createUserSubmitButton">Guardar usuario</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Cliente -->
    <div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createClientForm">
                        <div class="mb-3">
                            <label for="nameClientCreate" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nameClientCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="mailClientCreate" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="mailClientCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="phoneClientCreate" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="phoneClientCreate" required>
                        </div>
                        <div class="mb-3">
                            <label for="userIdClientCreate" class="form-label">Id de usuario asociado</label>
                            <input type="text" class="form-control" id="userIdClientCreate" required>
                        </div>                      
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveClientBtn">Guardar cliente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalTitle">Modificar usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th id="editUserIdColumn">ID</th>
                                <th id="editUserNameColumn">Nombre</th>
                                <th id="editUserMailColumn">Correo electrónico</th>
                                <th id="editUserRoleColumn">Rol</th>
                            </tr>
                        </thead>
                        <tbody id="editUserTableBody">
                            <!-- Filas dinámicas aquí -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="editUserCancelBtn">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="editUserSaveBtn">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Modificar Cliente -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Completado</th>
                                <th>Extra Info</th>
                                <th>ID Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="editClientTableBody">
                            <!-- Filas dinámicas aquí -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveClientChangesBtn">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Borrar Usuario -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalTitle">Borrar usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th id="deleteUserNameColumn">Nombre</th>
                                <th id="deleteUserMailColumn">Correo electrónico</th>
                                <th id="deleteUserRoleColumn">Rol</th>
                                <th id="deleteUserActionColumn">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <!-- Filas dinámicas aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Borrar Cliente -->
    <div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrar cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Completado</th>
                                <th>Usuario responsable</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="clientTableBody">
                            <!-- Filas dinámicas aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación de Borrado -->
    <div class="modal fade" id="confirmDeletionModal" tabindex="-1" aria-labelledby="confirmDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar borrado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmDeletionText">¿Estás seguro de que deseas borrar este elemento?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeletionBtn">Sí, borrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
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
                        'createUserBtn', 'editUserBtn', 'deleteUserBtn', 
                        'clientOptionsLabel', 'createClientBtn', 'editClientBtn', 
                        'deleteClientBtn', 'createUserTitle', 'createUserUsernameLabel', 
                        'createUserEmailLabel', 'createUserPasswordLabel', 'createUserRoleLabel', 
                        'createUserRoleSelect', 'createUserRoleAdmin', 'createUserRoleUser', 
                        'createUserRoleSupervisor', 'createUserRoleIT', 'createUserCancelButton', 
                        'createUserSubmitButton', 'editUserModalTitle', 'editUserNameColumn', 
                        'editUserMailColumn', 'editUserRoleColumn', 'editUserAdminRole', 
                        'editUserUserRole', 'editUserSupervisorRole', 'editUserITRole',
                         'editUserCancelBtn', 'editUserSaveBtn', 'deleteUserModalTitle', 
                         'deleteUserNameColumn', 'deleteUserMailColumn', 'deleteUserRoleColumn', 'deleteUserActionColumn',
                    ];

                    elements.forEach((id, index) => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.innerText = lines[index] || ""; // Asigna texto si existe
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
        $('#settingsModal').on('show.bs.modal', function () {
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

        
        

        const submitButton = document.getElementById("createUserSubmitButton");
        if (submitButton) {
            submitButton.addEventListener("click", function() {
                const username = document.getElementById("usernameCreate").value;
                const email = document.getElementById("mailUserCreate").value;
                const password = document.getElementById("passwordUserCreate").value;
                const role = document.getElementById("roleUserCreate").value;

                if (username && email && password && role) {
                    fetch("create_user.php", {  
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
                            document.getElementById("createUserForm").reset();
                            new bootstrap.Modal(document.getElementById("createUserModal")).hide();
                        } else {
                            alert("Error al crear usuario");
                        }
                    })
                    .catch(error => console.error("Error:", error));
                } else {
                    alert("Por favor completa todos los campos.");
                }
            });
        } else {
            console.warn("Elemento con ID 'createUserSubmitButton' no encontrado.");
        }

        document.getElementById("saveClientBtn").addEventListener("click", function() {
            const fullName = document.getElementById("nameClientCreate").value;
            const email = document.getElementById("mailClientCreate").value;
            const phone = document.getElementById("phoneClientCreate").value;
            const userId = document.getElementById("userIdClientCreate").value;


            if (fullName && email && phone && userId) {
                fetch("create_client.php", {  
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ fullName, email, phone, userId: userId }), // Cambiado a "userId"
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Cliente creado exitosamente");
                        document.getElementById("createClientForm").reset();
                        new bootstrap.Modal(document.getElementById("createClientModal")).hide();
                    } else {
                        alert("Error al crear cliente: " + (data.error || "Error desconocido"));
                    }
                })
                .catch(error => console.error("Error:", error));
            } else {
                alert("Por favor completa todos los campos.");
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const userTableBody = document.getElementById('userTableBody');
            const clientTableBody = document.getElementById('clientTableBody');
            let toDelete = null;

            // Función para cargar users en la tabla
            function loadUsers() {
                fetch('get_users.php')
                    .then(response => response.json())
                    .then(users => {
                        userTableBody.innerHTML = '';
                        users.forEach(user => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${user.nombre_user}</td>
                                <td>${user.email}</td>
                                <td>${['Admin', 'Usuario', 'Supervisor', 'Equipo Técnico'][user.rol] || 'Desconocido'}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm borrar-user-btn" data-id="${user.id}">Borrar</button>
                                </td>
                            `;
                            userTableBody.appendChild(fila);
                        });

                        // Agregar eventos a los botones de borrar
                        document.querySelectorAll('.borrar-user-btn').forEach(boton => {
                            boton.addEventListener('click', function () {
                                toDelete = {
                                    tipo: 'user',
                                    id: this.dataset.id
                                };
                                document.getElementById('confirmDeletionText').innerText = `¿Estás seguro de que deseas borrar al usuario ${this.closest('tr').children[0].innerText}?`;
                                new bootstrap.Modal(document.getElementById('confirmDeletionModal')).show();
                            });
                        });
                    });
            }

            // Función para cargar clients en la tabla
            function loadClients() {
                fetch('get_clients.php')
                    .then(response => response.json())
                    .then(clients => {
                        const clientTableBody = document.getElementById('clientTableBody');
                        clientTableBody.innerHTML = '';
                        clients.forEach(client => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${client.nombre}</td>
                                <td>${client.email}</td>
                                <td>${client.phone}</td>
                                <td><input type="checkbox" disabled ${client.completed ? 'checked' : ''}></td>
                                <td>${client.user_responsable}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm borrar-client-btn" data-id="${client.id}">Borrar</button>
                                </td>
                            `;
                            clientTableBody.appendChild(fila);
                        });


                        // Agregar eventos a los botones de borrar
                        document.querySelectorAll('.borrar-client-btn').forEach(boton => {
                            boton.addEventListener('click', function () {
                                const toDelete = {
                                    tipo: 'client',
                                    id: this.dataset.id
                                };
                                document.getElementById('confirmDeletionText').innerText = `¿Estás seguro de que deseas borrar al cliente ${this.closest('tr').children[0].innerText}?`;
                                new bootstrap.Modal(document.getElementById('confirmDeletionModal')).show();
                            });
                        });
                    })
                    .catch(error => console.error('Error al cargar clientes:', error));
            }


            // Confirmar borrado
            document.getElementById('confirmDeletionBtn').addEventListener('click', function () {
                if (toDelete) {
                    fetch(`borrar_${toDelete.tipo}.php`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: toDelete.id })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(`${toDelete.tipo === 'user' ? 'Usuario' : 'Cliente'} borrado con éxito.`);
                                if (toDelete.tipo === 'user') {
                                    loadUsers();
                                } else {
                                    loadClients();
                                }
                            } else {
                                alert(`Error al borrar el ${toDelete.tipo}.`);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Cargar datos al abrir los modales
            document.getElementById('deleteUserBtn').addEventListener('click', loadUsers);
            document.getElementById('deleteClientBtn').addEventListener('click', loadClients);


            function loadUsersEdit() {
                fetch('get_users.php')
                    .then(response => response.json())
                    .then(users => {
                        const userTableBody = document.getElementById('editUserTableBody');
                        userTableBody.innerHTML = '';
                        users.forEach(user => {
                            const roles = ['Admin', 'Usuario', 'Supervisor', 'Equipo Técnico'];
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${user.id}</td>
                                <td><input type="text" class="form-control" value="${user.nombre_user}" data-id="${user.id}" data-field="nombre"></td>
                                <td><input type="email" class="form-control" value="${user.email}" data-id="${user.id}" data-field="email"></td>
                                <td>
                                    <select class="form-select" data-id="${user.id}" data-field="rol">
                                        ${roles.map((rol, index) => `
                                            <option value="${index}" ${index === user.rol ? 'selected' : ''}>${rol}</option>
                                        `).join('')}
                                    </select>
                                </td>
                            `;
                            userTableBody.appendChild(fila);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar usuarios:', error);
                        alert('Error al cargar la lista de usuarios. Por favor, inténtalo de nuevo.');
                    });
            }


            function loadClientsEdit() {
                fetch('get_clients.php')
                    .then(response => response.json())
                    .then(clients => {
                        const clientTableBody = document.getElementById('editClientTableBody');
                        clientTableBody.innerHTML = '';
                        clients.forEach(client => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td><input type="text" class="form-control" value="${client.nombre}" data-id="${client.id}" data-field="nombre"></td>
                                <td><input type="email" class="form-control" value="${client.email}" data-id="${client.id}" data-field="email"></td>
                                <td><input type="text" class="form-control" value="${client.phone}" data-id="${client.id}" data-field="phone"></td>
                                <td><input type="checkbox" ${client.completed ? 'checked' : ''} data-id="${client.id}" data-field="completed"></td>
                                <td><input type="text" class="form-control" value="${client.extra_info}" data-id="${client.id}" data-field="extra_info"></td>
                                <td><input type="text" class="form-control" value="${client.idUser}" data-id="${client.id}" data-field="idUser"></td>
                            `;
                            clientTableBody.appendChild(fila);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar clientes:', error);
                        alert('Error al cargar la lista de clientes. Por favor, inténtalo de nuevo.');
                    });
            }




                document.getElementById('editUserSaveBtn').addEventListener('click', function () {
                    const inputs = document.querySelectorAll('#editUserTableBody input, #editUserTableBody select');
                    const cambios = Array.from(inputs).reduce((acc, input) => {
                        const id = input.dataset.id; // Obtiene el ID del user
                        const field = input.dataset.field; // Obtiene el nombre del campo
                        if (!acc[id]) acc[id] = { id }; // Asegura que el ID está inicializado
                        acc[id][field] = input.value; // Asigna el valor al campo
                        return acc;
                    }, {});

                    // Convierte los cambios en un array y envía la solicitud
                    fetch('modify_user.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(Object.values(cambios)) // Convierte a un array para enviarlo al backend
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Usuarios modificados correctamente');
                            } else {
                                alert(`Error al modificar usuarios: ${data.error || 'Error desconocido'}`);
                            }
                        })
                        .catch(error => {
                            console.error('Error al guardar cambios:', error);
                            alert('Ocurrió un error al intentar guardar los cambios. Por favor, inténtalo de nuevo.');
                        });
                });


                document.getElementById('saveClientChangesBtn').addEventListener('click', function () {
                    // Selecciona todos los inputs y selects del cuerpo de la tabla
                    const inputs = document.querySelectorAll('#editClientTableBody input, #editClientTableBody select');
                    
                    // Agrupa los datos por ID de client
                    const cambios = Array.from(inputs).reduce((acc, input) => {
                        const id = input.dataset.id; // ID del client
                        const field = input.dataset.field; // Campo del client
                        if (!acc[id]) acc[id] = { id }; // Inicializa el objeto si no existe
                        acc[id][field] = input.type === 'checkbox' ? input.checked : input.value; // Asigna el valor
                        return acc;
                    }, {});

                    // Enviar los datos al backend
                    fetch('modify_client.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(Object.values(cambios)) // Convierte a un array para enviar
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Clientes modificados correctamente');
                            } else {
                                alert(`Error al modificar clientes: ${data.error || 'Error desconocido'}`);
                            }
                        })
                        .catch(error => {
                            console.error('Error al guardar cambios:', error);
                            alert('Ocurrió un error al intentar guardar los cambios. Por favor, inténtalo de nuevo.');
                        });
                });

                document.getElementById('editUserBtn').addEventListener('click', loadUsersEdit);
                document.getElementById('editClientBtn').addEventListener('click', loadClientsEdit);

            });

    </script>
</body>
</html>

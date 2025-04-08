<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkUserRole() {
    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol'])) {
        echo json_encode(['success' => false]);
        return;
    }

    echo json_encode([
        'success' => true,
        'rol' => $_SESSION['rol']
    ]);
}

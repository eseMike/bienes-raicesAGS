<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Configuración segura de la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0, // La sesión se cierra al cerrar el navegador
        'path' => '/',
        'domain' => '', // Solo en el dominio actual
        'secure' => isset($_SERVER['HTTPS']), // Solo en HTTPS
        'httponly' => true, // Evita acceso desde JavaScript
        'samesite' => 'Strict' // Protege contra CSRF
    ]);
    session_start();
}

// Regenerar ID de sesión periódicamente para mayor seguridad
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    session_destroy(); // Destruir la sesión si no está autenticado
    header('Location: /login.php');
    exit;
}

// Control de tiempo de inactividad
$tiempoMaximo = 1800; // 30 minutos
if (isset($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso']) > $tiempoMaximo) {
    session_unset(); // Eliminar variables de sesión
    session_destroy(); // Destruir la sesión
    header('Location: /login.php?mensaje=sesion_expirada');
    exit;
}
$_SESSION['ultimo_acceso'] = time(); // Actualizar tiempo de actividad

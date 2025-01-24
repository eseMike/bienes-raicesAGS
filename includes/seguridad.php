<?php
session_start(); // Inicia la sesión

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirigir a la página de inicio de sesión si no está autenticado
    header('Location: /login.php');
    exit;
}

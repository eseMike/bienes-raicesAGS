<?php
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');

define('RUTA_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');

function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/{$nombre}.php";
}

function estaAutenticado(): bool
{
    session_start();
    return $_SESSION['login'] ?? false;
}

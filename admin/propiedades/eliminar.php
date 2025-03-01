<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../vendor/autoload.php';

use App\Propiedad;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /admin/index.php?mensaje=4");
        exit;
    }

    $db = conectadDB();
    $propiedad = new Propiedad($db);

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $resultado = $propiedad->eliminar($id);
        if ($resultado) {
            header("Location: /admin/index.php?mensaje=3");
        } else {
            header("Location: /admin/index.php?mensaje=6");
        }
        exit;
    } else {
        header("Location: /admin/index.php?mensaje=5");
        exit;
    }
}


// Si alguien intenta acceder directamente sin POST, redirigir
header('Location: /admin');
exit;

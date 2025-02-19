<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../classes/Propiedad.php';

use App\Propiedad;

$db = conectadDB();
$propiedad = new Propiedad($db);

// Validar que se envíe un ID válido por la URL
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /admin');
    exit;
}

// Intentar eliminar la propiedad
if ($propiedad->eliminar($id)) {
    header('Location: /admin/index.php?mensaje=3');
    exit;
} else {
    echo "Error al eliminar la propiedad.";
}

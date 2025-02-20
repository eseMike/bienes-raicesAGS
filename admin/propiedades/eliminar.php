<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../classes/Propiedad.php';

session_start(); // Asegurar que la sesión está iniciada

use App\Propiedad;

$db = conectadDB();
$propiedad = new Propiedad($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Location: /admin/index.php?mensaje=4'); // Mensaje 4 = Error CSRF
        exit;
    }

    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

    if ($id) {
        // Obtener la propiedad antes de eliminar
        $stmt = $db->prepare("SELECT imagen FROM propiedades WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $propiedadActual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$propiedadActual) {
            header('Location: /admin/index.php?mensaje=5'); // Mensaje 5 = Propiedad no encontrada
            exit;
        }

        // Eliminar imagen si existe
        if (!empty($propiedadActual['imagen']) && file_exists("../../build/img/" . $propiedadActual['imagen'])) {
            unlink("../../build/img/" . $propiedadActual['imagen']);
        }

        // Intentar eliminar la propiedad
        if ($propiedad->eliminar($id)) {
            header('Location: /admin/index.php?mensaje=3'); // Mensaje 3 = Eliminado correctamente
            exit;
        } else {
            header('Location: /admin/index.php?mensaje=6'); // Mensaje 6 = Error al eliminar
            exit;
        }
    } else {
        header('Location: /admin/index.php?mensaje=5'); // Mensaje 5 = Propiedad no encontrada
        exit;
    }
}

// Si alguien intenta acceder directamente sin POST, redirigir
header('Location: /admin');
exit;

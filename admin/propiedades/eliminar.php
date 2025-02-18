<?php
// Importar la conexión y proteger la página
require '../../includes/seguridad.php';
require '../../includes/config/database.php';

$db = conectadDB();

// Validar que se envíe un ID válido por la URL
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
    exit;
}

try {
    // Consulta para obtener la imagen asociada
    $stmt = $db->prepare("SELECT imagen FROM propiedades WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$propiedad) {
        header('Location: /admin?mensaje=error');
        exit;
    }

    // Eliminar el archivo de imagen si existe
    if (!empty($propiedad['imagen'])) {
        $carpetaImagenes = __DIR__ . "/../../imagenes/";
        $rutaImagen = $carpetaImagenes . $propiedad['imagen'];

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Eliminar la propiedad de la base de datos
    $stmt = $db->prepare("DELETE FROM propiedades WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $resultado = $stmt->execute();

    if ($resultado) {
        header('Location: /admin/index.php?mensaje=3');
        exit;
    } else {
        throw new Exception("Error al eliminar la propiedad.");
    }
} catch (Exception $e) {
    error_log("Error en la eliminación: " . $e->getMessage());
    header('Location: /admin/index.php?mensaje=error');
    exit;
}

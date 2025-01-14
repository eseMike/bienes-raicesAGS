<?php

// Importar la conexión
require '../../includes/config/database.php';
$db = conectadDB();

// Validar que se envíe un ID válido por la URL
$id = $_GET['id'] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin'); // Redirigir si no hay ID válido
    exit;
}

// Consulta para obtener la imagen asociada
$queryImagen = "SELECT imagen FROM propiedades WHERE id = ?";
$stmt = $db->prepare($queryImagen);
$stmt->execute([$id]);
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

// Eliminar el archivo de imagen si existe
if (!empty($propiedad['imagen'])) {
    $carpetaImagenes = __DIR__ . "/../../imagenes/";
    $rutaImagen = $carpetaImagenes . $propiedad['imagen'];

    if (file_exists($rutaImagen)) {
        unlink($rutaImagen);
    }
}

// Eliminar la propiedad de la base de datos
$query = "DELETE FROM propiedades WHERE id = ?";
$stmt = $db->prepare($query);
$resultado = $stmt->execute([$id]);

if ($resultado) {
    header('Location: /admin/index.php?mensaje=3'); // Redirigir con un mensaje de éxito
    exit;
} else {
    echo "Error al eliminar la propiedad.";
}

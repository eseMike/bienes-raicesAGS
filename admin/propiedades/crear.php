<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require '../../classes/Propiedad.php';

use App\Propiedad;

// Iniciar sesión si es necesario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$db = conectarDB();
$propiedad = new Propiedad($db);

// Obtener vendedores
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errores[] = "❌ Error: Token CSRF no válido.";
    }

    // Validar y asignar campos
    $propiedad->titulo = trim(htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8'));
    $propiedad->precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $propiedad->descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
    $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

    // Imagen
    if ($_FILES['imagen']['tmp_name']) {
        $imagen = $_FILES['imagen'];
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            $errores[] = "Formato de imagen no permitido.";
        }

        if ($imagen['size'] > 2 * 1024 * 1024) {
            $errores[] = "La imagen excede los 2MB.";
        }

        if (empty($errores)) {
            $nombreImagen = md5(uniqid(rand(), true)) . '.webp';
            $ruta = __DIR__ . '/../../build/img/' . $nombreImagen;
            // Crear una imagen en memoria desde el archivo subido
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($imagen['tmp_name']);
                    break;
                case 'png':
                    $image = imagecreatefrompng($imagen['tmp_name']);
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($imagen['tmp_name']);
                    break;
                default:
                    $image = false;
                    $errores[] = "Formato de imagen no soportado para conversión.";
                    break;
            }

            // Convertir y guardar como .webp
            if ($image !== false && imagewebp($image, $ruta)) {
                $propiedad->imagen = $nombreImagen;
                imagedestroy($image); // liberar memoria
            } else {
                $errores[] = "Error al convertir la imagen a .webp.";
            }
        }
    }

    // Guardar
    if (empty($errores)) {
        $resultado = $propiedad->crear();
        if ($resultado) {
            header("Location: /admin/index.php?mensaje=1");
            exit;
        } else {
            $errores[] = "Error al guardar en la base de datos.";
        }
    }
}

// Variables para el formulario reutilizable
$accion = 'Crear';
$formAction = '/admin/propiedades/crear.php';
$mostrarImagen = false;

incluirTemplate('header');
include '../../includes/templates/formulario_propiedad.php';
incluirTemplate('footer');

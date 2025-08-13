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

    // Múltiples Imágenes
    $imagenesGuardadas = [];

    if (!empty($_FILES['imagen']['name'][0])) {
        $imagenes = $_FILES['imagen'];
        $total = count($imagenes['name']);

        if ($total > 10) {
            $errores[] = "Solo se permiten hasta 10 imágenes.";
        } else {
            for ($i = 0; $i < $total; $i++) {
                $nombreOriginal = $imagenes['name'][$i];
                $tmpName = $imagenes['tmp_name'][$i];
                $size = $imagenes['size'][$i];
                $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
                $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($extension, $extensionesPermitidas)) {
                    $errores[] = "Formato no permitido en imagen #$i.";
                    continue;
                }

                if ($size > 2 * 1024 * 1024) {
                    $errores[] = "La imagen #$i excede los 2MB.";
                    continue;
                }

                $nombreFinal = md5(uniqid(rand(), true)) . '.webp';
                $ruta = __DIR__ . '/../../build/img/' . $nombreFinal;

                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $image = imagecreatefromjpeg($tmpName);
                        break;
                    case 'png':
                        $image = imagecreatefrompng($tmpName);
                        break;
                    case 'webp':
                        $image = imagecreatefromwebp($tmpName);
                        break;
                    default:
                        $image = false;
                        $errores[] = "Formato no soportado para imagen #$i.";
                        break;
                }

                if ($image !== false && imagewebp($image, $ruta)) {
                    $imagenesGuardadas[] = $nombreFinal;
                    imagedestroy($image);
                } else {
                    $errores[] = "Error al convertir imagen #$i.";
                }
            }

            // Si se cargaron al menos una imagen válida
            if (!empty($imagenesGuardadas)) {
                $propiedad->imagen = $imagenesGuardadas[0]; // Principal
                $propiedad->galeria = json_encode($imagenesGuardadas); // Guarda todas
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

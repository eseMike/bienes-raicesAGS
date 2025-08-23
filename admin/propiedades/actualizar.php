<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require '../../classes/Propiedad.php';

use App\Propiedad;

$db = conectarDB();

// Validar ID
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
      header('Location: /admin');
      exit;
}

// Obtener propiedad actual
$stmt = $db->prepare("SELECT * FROM propiedades WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$datosPropiedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datosPropiedad) {
      header('Location: /admin');
      exit;
}

// Instanciar objeto propiedad con datos actuales
$propiedad = new Propiedad($db);
$propiedad->titulo = $datosPropiedad['titulo'];
$propiedad->precio = $datosPropiedad['precio'];
$propiedad->descripcion = $datosPropiedad['descripcion'];
$propiedad->habitaciones = $datosPropiedad['habitaciones'];
$propiedad->wc = $datosPropiedad['wc'];
$propiedad->estacionamiento = $datosPropiedad['estacionamiento'];
$propiedad->vendedor_id = $datosPropiedad['vendedores_id'];
$propiedad->imagen = $datosPropiedad['imagen'];

// Obtener vendedores
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errores[] = "❌ Token CSRF inválido.";
      }

      $propiedad->titulo = trim(htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8'));
      $propiedad->precio = filter_var(trim($_POST['precio'] ?? ''), FILTER_VALIDATE_FLOAT);
      $propiedad->descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
      $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

      // Imagen nueva
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
                  $nuevaImagen = md5(uniqid(rand(), true)) . ".webp";
                  $rutaDestino = __DIR__ . '/../../build/img/' . $nuevaImagen;

                  if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                        // Eliminar imagen anterior solo si es distinta y existe
                        $rutaAnterior = __DIR__ . '/../../build/img/' . $propiedad->imagen;
                        if (!empty($propiedad->imagen) && file_exists($rutaAnterior)) {
                              unlink($rutaAnterior);
                        }
                        $propiedad->imagen = $nuevaImagen;
                  } else {
                        $errores[] = "Error al subir la nueva imagen.";
                  }
            }
      }

      // Actualizar
      if (empty($errores)) {
            $resultado = $propiedad->actualizar($id);
            if ($resultado) {
                  header("Location: /admin/index.php?mensaje=2");
                  exit;
            } else {
                  $errores[] = "❌ No se pudo actualizar la propiedad.";
            }
      }
}

// Variables para el formulario reutilizable
$accion = 'Actualizar';
$formAction = "/admin/propiedades/actualizar.php?id=$id";
$mostrarImagen = true;

// Render
incluirTemplate('header');
include '../../includes/templates/formulario_propiedad.php';
incluirTemplate('footer');

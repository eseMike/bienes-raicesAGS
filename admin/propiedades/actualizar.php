<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require '../../classes/Propiedad.php';

session_start(); // Asegurar que la sesión está iniciada

use App\Propiedad;

$db = conectadDB();

// Validar ID de la propiedad
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
      header('Location: /admin');
      exit;
}

// Obtener datos de la propiedad actual
$stmt = $db->prepare("SELECT * FROM propiedades WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$datosPropiedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$datosPropiedad) {
      header('Location: /admin');
      exit;
}

// Crear instancia de Propiedad con los datos actuales
$propiedad = new Propiedad($db);
$propiedad->titulo = $datosPropiedad['titulo'];
$propiedad->precio = $datosPropiedad['precio'];
$propiedad->descripcion = $datosPropiedad['descripcion'];
$propiedad->habitaciones = $datosPropiedad['habitaciones'];
$propiedad->wc = $datosPropiedad['wc'];
$propiedad->estacionamiento = $datosPropiedad['estacionamiento'];
$propiedad->vendedor_id = $datosPropiedad['vendedores_id'];
$propiedad->imagen = $datosPropiedad['imagen']; // Imagen actual

// Obtener vendedores
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Validación CSRF
      if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $errores[] = "Token CSRF no válido.";
      }

      // Asignar los nuevos valores
      $propiedad->titulo = trim(htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8'));
      $propiedad->precio = filter_var(trim($_POST['precio'] ?? ''), FILTER_VALIDATE_FLOAT);
      $propiedad->descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
      $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

      // Manejo de imagen
      if ($_FILES['imagen']['tmp_name']) {
            $imagen = $_FILES['imagen'];
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
            $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensionesPermitidas)) {
                  $errores[] = "Formato de imagen no permitido (solo jpg, jpeg, png, webp).";
            }

            if ($imagen['size'] > 2 * 1024 * 1024) {
                  $errores[] = "El tamaño de la imagen no debe superar los 2MB.";
            }

            if (empty($errores)) {
                  // Generar nuevo nombre de imagen
                  $nuevaImagen = md5(uniqid(rand(), true)) . ".webp";
                  $rutaDestino = "../../build/img/" . $nuevaImagen;

                  if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                        // Eliminar la imagen anterior solo si la nueva se subió correctamente
                        if (!empty($propiedad->imagen) && file_exists("../../build/img/" . $propiedad->imagen)) {
                              unlink("../../build/img/" . $propiedad->imagen);
                        }
                        $propiedad->imagen = $nuevaImagen;
                  } else {
                        $errores[] = "Error al subir la imagen.";
                  }
            }
      }
}

incluirTemplate('header');

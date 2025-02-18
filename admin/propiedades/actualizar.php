<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
      header('Location: /admin');
      exit;
}

$db = conectadDB();

// Obtener la propiedad
try {
      $stmt = $db->prepare("SELECT * FROM propiedades WHERE id = :id");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$propiedad) {
            header('Location: /admin');
            exit;
      }

      // Obtener vendedores
      $stmtVendedores = $db->prepare("SELECT * FROM vendedores");
      $stmtVendedores->execute();
      $vendedores = $stmtVendedores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
      die("Hubo un error en la aplicación. Inténtalo más tarde.");
}

// Inicialización de variables
$titulo = $propiedad['titulo'] ?? '';
$precio = $propiedad['precio'] ?? '';
$descripcion = $propiedad['descripcion'] ?? '';
$habitaciones = $propiedad['habitaciones'] ?? '';
$wc = $propiedad['wc'] ?? '';
$estacionamiento = $propiedad['estacionamiento'] ?? '';
$vendedor_id = $propiedad['vendedores_id'] ?? null;
$nombreImagen = $propiedad['imagen'] ?? null;

// Errores
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $titulo = htmlspecialchars($_POST['titulo'] ?? '');
      $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
      $descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
      $habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
      $wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
      $estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
      $vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);
      $imagen = $_FILES['imagen'] ?? null;

      // Validaciones
      $validaciones = [
            'titulo' => "El título es obligatorio.",
            'precio' => (!$precio || $precio <= 0) ? "El precio debe ser válido y mayor a 0." : null,
            'descripcion' => (strlen($descripcion) < 50) ? "La descripción debe contener al menos 50 caracteres." : null,
            'habitaciones' => (!$habitaciones || $habitaciones <= 0) ? "Debe especificar un número válido de habitaciones." : null,
            'wc' => (!$wc || $wc <= 0) ? "Debe especificar un número válido de baños." : null,
            'estacionamiento' => (!$estacionamiento || $estacionamiento <= 0) ? "Debe especificar un número válido de estacionamientos." : null,
            'vendedor_id' => (!$vendedor_id) ? "Debe seleccionar un vendedor." : null
      ];

      foreach ($validaciones as $key => $mensaje) {
            if ($mensaje) $errores[] = $mensaje;
      }

      // Manejo de imágenes
      if ($imagen && $imagen['tmp_name']) {
            $carpetaImagenes = '../../imagenes';
            if (!is_dir($carpetaImagenes)) {
                  mkdir($carpetaImagenes, 0755, true);
            }

            if ($imagen['size'] > 2 * 1024 * 1024) {
                  $errores[] = "La imagen es muy pesada. Debe ser menor a 2 MB.";
            } else {
                  $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                  $tipoImagen = mime_content_type($imagen['tmp_name']);

                  if (!in_array($tipoImagen, $formatosPermitidos)) {
                        $errores[] = "El formato de la imagen no es válido.";
                  } else {
                        if ($nombreImagen) {
                              unlink("../../imagenes/$nombreImagen");
                        }
                        $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
                        move_uploaded_file($imagen['tmp_name'], "$carpetaImagenes/$nombreImagen");
                  }
            }
      }

      if (empty($errores)) {
            try {
                  $query = $db->prepare("UPDATE propiedades SET 
                titulo = :titulo, 
                precio = :precio, 
                descripcion = :descripcion, 
                habitaciones = :habitaciones, 
                wc = :wc, 
                estacionamiento = :estacionamiento, 
                vendedores_id = :vendedor_id, 
                imagen = :imagen 
                WHERE id = :id");

                  $query->bindValue(':titulo', $titulo);
                  $query->bindValue(':precio', $precio);
                  $query->bindValue(':descripcion', $descripcion);
                  $query->bindValue(':habitaciones', $habitaciones, PDO::PARAM_INT);
                  $query->bindValue(':wc', $wc, PDO::PARAM_INT);
                  $query->bindValue(':estacionamiento', $estacionamiento, PDO::PARAM_INT);
                  $query->bindValue(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
                  $query->bindValue(':imagen', $nombreImagen);
                  $query->bindValue(':id', $id, PDO::PARAM_INT);

                  $query->execute();

                  header('Location: /admin/index.php?mensaje=2');
                  exit;
            } catch (PDOException $e) {
                  error_log("Error al actualizar la propiedad: " . $e->getMessage());
                  $errores[] = "Error al actualizar los datos. Intente nuevamente.";
            }
      }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
      <h1>Actualizar Propiedad</h1>
      <a href="/admin" class="boton boton-verde btn-admin">Volver</a>

      <?php foreach ($errores as $error): ?>
            <div class="alerta error">
                  <?php echo htmlspecialchars($error); ?>
            </div>
      <?php endforeach; ?>

      <form class="form" method="POST" enctype="multipart/form-data">
            <fieldset>
                  <legend>Información General</legend>
                  <label for="titulo">Título:</label>
                  <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>">

                  <label for="precio">Precio:</label>
                  <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>">

                  <label for="descripcion">Descripción:</label>
                  <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($descripcion); ?></textarea>

                  <label for="imagen">Imagen:</label>
                  <input type="file" id="imagen" name="imagen" accept="image/*">
                  <?php if ($nombreImagen): ?>
                        <img src="/imagenes/<?php echo htmlspecialchars($nombreImagen); ?>" alt="Imagen" style="width:200px;">
                  <?php endif; ?>
            </fieldset>

            <fieldset>
                  <legend>Información Propiedad</legend>
                  <label for="habitaciones">Habitaciones:</label>
                  <input type="number" id="habitaciones" name="habitaciones" value="<?php echo htmlspecialchars($habitaciones); ?>">

                  <label for="wc">Baños:</label>
                  <input type="number" id="wc" name="wc" value="<?php echo htmlspecialchars($wc); ?>">

                  <label for="estacionamiento">Estacionamiento:</label>
                  <input type="number" id="estacionamiento" name="estacionamiento" value="<?php echo htmlspecialchars($estacionamiento); ?>">
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
      </form>
</main>

<?php incluirTemplate('footer'); ?>
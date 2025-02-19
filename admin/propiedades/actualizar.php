<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require '../../classes/Propiedad.php';

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
      // Asignar los nuevos valores
      $propiedad->titulo = htmlspecialchars($_POST['titulo'] ?? '');
      $propiedad->precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
      $propiedad->descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
      $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
      $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

      // Validar la imagen solo si se subió una nueva
      if ($_FILES['imagen']['tmp_name']) {
            $erroresImagen = $propiedad->validarImagen($_FILES['imagen']);
            if (empty($erroresImagen)) {
                  // Eliminar la imagen anterior
                  if (!empty($propiedad->imagen) && file_exists("../../imagenes/" . $propiedad->imagen)) {
                        unlink("../../imagenes/" . $propiedad->imagen);
                  }

                  // Guardar nueva imagen
                  $propiedad->imagen = md5(uniqid(rand(), true)) . ".webp";
                  move_uploaded_file($_FILES['imagen']['tmp_name'], "../../imagenes/" . $propiedad->imagen);
            } else {
                  $errores = array_merge($errores, $erroresImagen);
            }
      }

      // Validaciones del formulario
      if (!$propiedad->titulo) $errores[] = "Debes añadir un título";
      if (!$propiedad->precio || $propiedad->precio <= 0) $errores[] = "El precio debe ser válido y mayor a 0.";
      if (strlen($propiedad->descripcion) < 50) $errores[] = "La descripción debe contener al menos 50 caracteres.";
      if (!$propiedad->habitaciones || $propiedad->habitaciones <= 0) $errores[] = "Debe especificar un número válido de habitaciones.";
      if (!$propiedad->wc || $propiedad->wc <= 0) $errores[] = "Debe especificar un número válido de baños.";
      if (!$propiedad->estacionamiento || $propiedad->estacionamiento <= 0) $errores[] = "Debe especificar un número válido de estacionamientos.";
      if (!$propiedad->vendedor_id) $errores[] = "Debe seleccionar un vendedor.";

      // Si no hay errores, actualizar en la base de datos
      if (empty($errores)) {
            if ($propiedad->actualizar($id)) {
                  header("Location: /admin/index.php?mensaje=2");
                  exit;
            } else {
                  $errores[] = "Error al actualizar la propiedad.";
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
                  <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($propiedad->titulo); ?>">

                  <label for="precio">Precio:</label>
                  <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($propiedad->precio); ?>">

                  <label for="descripcion">Descripción:</label>
                  <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($propiedad->descripcion); ?></textarea>

                  <label for="imagen">Imagen:</label>
                  <input type="file" id="imagen" name="imagen" accept="image/*">
                  <?php if ($propiedad->imagen): ?>
                        <img src="/imagenes/<?php echo htmlspecialchars($propiedad->imagen); ?>" alt="Imagen" style="width:200px;">
                  <?php endif; ?>
            </fieldset>

            <fieldset>
                  <legend>Información Propiedad</legend>
                  <label for="habitaciones">Habitaciones:</label>
                  <input type="number" id="habitaciones" name="habitaciones" value="<?php echo htmlspecialchars($propiedad->habitaciones); ?>">

                  <label for="wc">Baños:</label>
                  <input type="number" id="wc" name="wc" value="<?php echo htmlspecialchars($propiedad->wc); ?>">

                  <label for="estacionamiento">Estacionamiento:</label>
                  <input type="number" id="estacionamiento" name="estacionamiento" value="<?php echo htmlspecialchars($propiedad->estacionamiento); ?>">
            </fieldset>

            <fieldset>
                  <legend>Vendedor</legend>
                  <select name="vendedor">
                        <option value="" disabled>--Seleccione--</option>
                        <?php foreach ($vendedores as $vendedor): ?>
                              <option value="<?php echo $vendedor['id']; ?>" <?php echo ($propiedad->vendedor_id == $vendedor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vendedor['nombre'] . ' ' . $vendedor['apellido']); ?>
                              </option>
                        <?php endforeach; ?>
                  </select>
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
      </form>
</main>

<?php incluirTemplate('footer'); ?>
<?php

// Validar que sea un ID válido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
      header('Location: /admin');
      exit;
}

// Base de Datos
require '../../includes/config/database.php';
$db = conectadDB();

try {
      // Obtener los datos de la propiedad
      $stmt = $db->prepare("SELECT * FROM propiedades WHERE id = :id");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$propiedad) {
            header('Location: /admin');
            exit;
      }

      // Consultar para obtener los vendedores
      $stmtVendedores = $db->prepare("SELECT * FROM vendedores");
      $stmtVendedores->execute();
      $resultadoVendedores = $stmtVendedores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
      error_log("Error al consultar la base de datos: " . $e->getMessage());
      die("Hubo un error en la aplicación. Inténtalo más tarde.");
}

// Variables iniciales obtenidas de la base de datos
$titulo = $propiedad['titulo'] ?? '';
$precio = $propiedad['precio'] ?? '';
$descripcion = $propiedad['descripcion'] ?? '';
$habitaciones = $propiedad['habitaciones'] ?? '';
$wc = $propiedad['wc'] ?? '';
$estacionamiento = $propiedad['estacionamiento'] ?? '';
$vendedor_id = $propiedad['vendedores_id'] ?? null;
$nombreImagen = $propiedad['imagen'] ?? null;

// Arreglo con mensajes de errores
$errores = [];

// Si el formulario se envió (POST), procesamos los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $titulo = $_POST['titulo'] ?? '';
      $precio = $_POST['precio'] ?? '';
      $descripcion = $_POST['descripcion'] ?? '';
      $habitaciones = $_POST['habitaciones'] ?? '';
      $wc = $_POST['wc'] ?? '';
      $estacionamiento = $_POST['estacionamiento'] ?? '';
      $vendedor_id = $_POST['vendedor'] ?? '';
      $creado = date('Y/m/d');

      // Validación para la imagen
      $imagen = $_FILES['imagen'] ?? null;

      if ($imagen && $imagen['tmp_name']) {
            $tamanoMaximo = 2 * 1024 * 1024; // 2 MB

            if ($_FILES['imagen']['size'] > $tamanoMaximo) {
                  $errores[] = "La imagen es muy pesada. Debe ser menor a 2 MB.";
            } else {
                  $formatosPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                  $tipoImagen = $imagen['type'];

                  if (!in_array($tipoImagen, $formatosPermitidos)) {
                        $errores[] = "El formato de la imagen no es válido.";
                  } else {
                        $carpetaImagenes = __DIR__ . "/../../imagenes/";
                        if (!is_dir($carpetaImagenes)) {
                              mkdir($carpetaImagenes, 0755, true);
                        }

                        // **Eliminar la imagen anterior si existe**
                        if (!empty($propiedad['imagen'])) {
                              $rutaImagen = $carpetaImagenes . "/" . $propiedad['imagen'];
                              if (file_exists($rutaImagen) && !unlink($rutaImagen)) {
                                    $errores[] = "No se pudo eliminar la imagen anterior.";
                              }
                        }

                        // Crear un nombre único para la imagen nueva
                        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
                        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . "/" . $nombreImagen);
                  }
            }
      }


      if (!$imagen || !$imagen['tmp_name']) {
            $nombreImagen = $propiedad['imagen'] ?? null;
      }

      // Validaciones
      // [Validaciones igual que tu código original]

      // Actualización en la base de datos
      if (empty($errores)) {
            try {
                  $query = $db->prepare("UPDATE propiedades SET titulo = :titulo, precio = :precio, descripcion = :descripcion, habitaciones = :habitaciones, wc = :wc, estacionamiento = :estacionamiento, vendedores_id = :vendedor_id, imagen = :imagen WHERE id = :id");

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

                  header("Location: /admin/index.php?mensaje=2");
                  exit;
            } catch (PDOException $e) {
                  $errores[] = "Error al actualizar los datos: " . $e->getMessage();
            }
      }
}

// Resto de tu código del formulario

require '../../includes/funciones.php';
incluirTemplate('header');
?>




<main class="contenedor seccion">
      <h1>Actualizar</h1>
      <a style="margin-bottom: 1.5rem;" href="/admin" class="boton boton-verde btn-admin">Volver</a>

      <?php foreach ($errores as $error): ?>
            <div class="alerta error">
                  <?php echo $error; ?>
            </div>
      <?php endforeach; ?>


      <form style="margin-top: 3rem;" class="form" method="POST" enctype="multipart/form-data">
            <fieldset>
                  <legend>Información General</legend>
                  <label for="titulo">Titulo:</label>
                  <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo htmlspecialchars($titulo ?? ''); ?>">

                  <label for="precio">Precio:</label>
                  <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo htmlspecialchars($precio ?? ''); ?>">

                  <label for="descripcion">Descripción:</label>
                  <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>
                  <p id="mensaje-descripcion" style="color: red; display: none;">La descripción debe contener al menos <span id="contador-caracteres">50</span> caracteres.</p>


                  <label for="imagen">Imagen:</label>
                  <input type="file" id="imagen" name="imagen" accept="image/*">

                  <?php if (!empty($nombreImagen)): ?>
                        <p>Imagen actual:</p>
                        <img src="/imagenes/<?php echo htmlspecialchars($nombreImagen); ?>" alt="Imagen de la propiedad" style="width: 200px; height: auto; margin-top: 10px; border: 1px solid #ddd;">
                  <?php endif; ?>
            </fieldset>

            <fieldset>
                  <legend>Información Propiedad</legend>
                  <label for="habitaciones">Habitaciones:</label>
                  <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($habitaciones ?? ''); ?>">

                  <label for="wc">Baños:</label>
                  <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($wc ?? ''); ?>">

                  <label for="estacionamiento">Estacionamiento:</label>
                  <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo htmlspecialchars($estacionamiento ?? ''); ?>">
            </fieldset>

            <fieldset>
                  <legend>Vendedor</legend>
                  <select name="vendedor">
                        <option selected disabled>--Seleccione--</option>
                        <?php foreach ($resultadoVendedores as $vendedor): ?>
                              <option value="<?php echo $vendedor['id']; ?>"
                                    <?php echo ($vendedor_id == $vendedor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vendedor['nombre'] . " " . $vendedor['apellido']); ?>
                              </option>
                        <?php endforeach; ?>
                  </select>
            </fieldset>


            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
      </form>


</main>

<?php incluirTemplate('footer'); ?>
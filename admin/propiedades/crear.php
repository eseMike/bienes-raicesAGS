<?php
require '../../includes/seguridad.php'; // Protege la página
require '../../includes/config/database.php';
require '../../includes/funciones.php';

$db = conectadDB();

// Obtener vendedores con prepared statement
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicialización de variables y validaciones
$titulo = $_POST['titulo'] ?? '';
$precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
$descripcion = $_POST['descripcion'] ?? '';
$habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
$wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
$estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
$vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);
$creado = date('Y/m/d');

// Manejo de errores
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carpetaImagenes = '../../imagenes';
    if (!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes, 0755, true);
    }

    // Manejo de imagen
    $imagen = $_FILES['imagen'] ?? null;
    $nombreImagen = null;

    if ($imagen && $imagen['tmp_name']) {
        $tamanoMaximo = 2 * 1024 * 1024;

        if ($imagen['size'] > $tamanoMaximo) {
            $errores[] = "La imagen es muy pesada. Debe ser menor a 2 MB.";
        } else {
            $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipoImagen = mime_content_type($imagen['tmp_name']);

            if (!in_array($tipoImagen, $formatosPermitidos)) {
                $errores[] = "El formato de la imagen no es válido.";
            } else {
                $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
                move_uploaded_file($imagen['tmp_name'], "$carpetaImagenes/$nombreImagen");
            }
        }
    } else {
        $errores[] = "La imagen es obligatoria.";
    }

    // Validaciones
    if (!$titulo) $errores[] = "Debes añadir un título";
    if (!$precio || $precio <= 0) $errores[] = "El precio debe ser un número válido mayor a 0.";
    if (strlen($descripcion) < 50) $errores[] = "La descripción debe tener al menos 50 caracteres.";
    if (!$habitaciones || $habitaciones <= 0) $errores[] = "El número de habitaciones debe ser un número válido.";
    if (!$wc || $wc <= 0) $errores[] = "El número de baños debe ser un número válido.";
    if (!$estacionamiento || $estacionamiento <= 0) $errores[] = "El número de estacionamientos debe ser un número válido.";
    if (!$vendedor_id) $errores[] = "Debes seleccionar un vendedor.";

    // Si no hay errores, insertar en la BD
    if (empty($errores)) {
        try {
            $query = $db->prepare("INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id, imagen) 
                                   VALUES (:titulo, :precio, :descripcion, :habitaciones, :wc, :estacionamiento, :creado, :vendedor_id, :imagen)");

            $query->bindParam(':titulo', $titulo);
            $query->bindParam(':precio', $precio);
            $query->bindParam(':descripcion', $descripcion);
            $query->bindParam(':habitaciones', $habitaciones, PDO::PARAM_INT);
            $query->bindParam(':wc', $wc, PDO::PARAM_INT);
            $query->bindParam(':estacionamiento', $estacionamiento, PDO::PARAM_INT);
            $query->bindParam(':creado', $creado);
            $query->bindParam(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
            $query->bindParam(':imagen', $nombreImagen);

            $query->execute();

            header("Location: /admin/index.php?mensaje=1");
            exit;
        } catch (PDOException $e) {
            error_log("Error al guardar la propiedad: " . $e->getMessage());
            $errores[] = "Error al guardar los datos. Intente nuevamente.";
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear Propiedad</h1>
    <a href="/admin" class="boton boton-verde btn-admin">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endforeach; ?>

    <form class="form" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo htmlspecialchars($titulo); ?>">

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo htmlspecialchars($precio); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($descripcion); ?></textarea>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
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

        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor">
                <option value="" disabled selected>--Seleccione--</option>
                <?php foreach ($vendedores as $vendedor): ?>
                    <option value="<?php echo $vendedor['id']; ?>" <?php echo ($vendedor_id == $vendedor['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . ' ' . $vendedor['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php incluirTemplate('footer'); ?>
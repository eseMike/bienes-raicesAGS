// crear.php
<?php
require '../../includes/seguridad.php'; // Protege la página
// Base de Datos
require '../../includes/config/database.php';


$db = conectadDB();

// Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = $db->query($consulta); // Consulta con PDO

// Evitar error de variable indefinida
$vendedor_id = $_POST['vendedor'] ?? null;

// Arreglo con mensajes de errores
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carpetaImagenes = '../../imagenes';
    if (!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes, 0755, true);
    }

    $titulo = htmlspecialchars($_POST['titulo'] ?? '');
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
    $habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
    $wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
    $estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
    $vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);
    $creado = date('Y/m/d');

    $imagen = $_FILES['imagen'] ?? null;
    $nombreImagen = null;

    if ($imagen && $imagen['tmp_name']) {
        $tamanoMaximo = 2 * 1024 * 1024;

        if ($_FILES['imagen']['size'] > $tamanoMaximo) {
            $errores[] = "La imagen es muy pesada. Debe ser menor a 2 MB.";
        } else {
            $formatosPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
            $tipoImagen = $imagen['type'];

            if (!in_array($tipoImagen, $formatosPermitidos)) {
                $errores[] = "El formato de la imagen no es válido.";
            } else {
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . "/" . $nombreImagen);
            }
        }
    } else {
        $errores[] = "La imagen es obligatoria.";
    }

    if (!$titulo) {
        $errores[] = "Debes añadir un título";
    }
    if (!$precio || $precio <= 0) {
        $errores[] = "El precio debe ser un número válido mayor a 0.";
    }
    if (strlen($descripcion) < 50) {
        $errores[] = "La descripción debe tener al menos 50 caracteres.";
    }
    if (!$habitaciones || $habitaciones <= 0) {
        $errores[] = "El número de habitaciones debe ser un número válido.";
    }
    if (!$wc || $wc <= 0) {
        $errores[] = "El número de baños debe ser un número válido.";
    }
    if (!$estacionamiento || $estacionamiento <= 0) {
        $errores[] = "El número de estacionamientos debe ser un número válido.";
    }
    if (!$vendedor_id) {
        $errores[] = "Debes seleccionar un vendedor.";
    }

    if (empty($errores)) {
        try {
            $query = $db->prepare("INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id, imagen) 
                                   VALUES (:titulo, :precio, :descripcion, :habitaciones, :wc, :estacionamiento, :creado, :vendedor_id, :imagen)");

            $query->bindValue(':titulo', $titulo);
            $query->bindValue(':precio', $precio);
            $query->bindValue(':descripcion', $descripcion);
            $query->bindValue(':habitaciones', $habitaciones, PDO::PARAM_INT);
            $query->bindValue(':wc', $wc, PDO::PARAM_INT);
            $query->bindValue(':estacionamiento', $estacionamiento, PDO::PARAM_INT);
            $query->bindValue(':creado', $creado);
            $query->bindValue(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
            $query->bindValue(':imagen', $nombreImagen);

            $query->execute();

            header("Location: /admin/index.php?mensaje=1");
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al guardar los datos: " . $e->getMessage();
        }
    }
}

require '../../includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton boton-verde btn-admin">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endforeach; ?>

    <form class="form" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">

        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo htmlspecialchars($titulo ?? ''); ?>">

            <label for="precio">Precio:</label>
            <input type="text" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo htmlspecialchars($precio ?? ''); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>
            <p id="mensaje-descripcion" style="color: red; display: none;">
                La descripción debe contener al menos <span id="contador-caracteres">50</span> caracteres.
            </p>
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>
            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" value="<?php echo htmlspecialchars($habitaciones ?? ''); ?>">

            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" value="<?php echo htmlspecialchars($wc ?? ''); ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" value="<?php echo htmlspecialchars($estacionamiento ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor">
                <option value="" disabled selected>--Seleccione--</option>
                <?php while ($vendedor = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $vendedor['id']; ?>"
                        <?php echo ($vendedor_id == $vendedor['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . ' ' . $vendedor['apellido']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">

    </form>
</main>

<?php incluirTemplate('footer'); ?>
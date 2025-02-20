<script>
    function actualizarContador() {
        const textarea = document.getElementById('descripcion');
        const contadorTexto = document.getElementById('contador-caracteres');
        const contadorNumero = document.getElementById('restantes');
        const minCaracteres = 54;

        const caracteresIngresados = textarea.value.length;
        const caracteresRestantes = minCaracteres - caracteresIngresados;

        if (caracteresRestantes > 0) {
            contadorTexto.style.display = "block"; // Muestra el contador si faltan caracteres
            contadorNumero.textContent = caracteresRestantes;
        } else {
            contadorTexto.style.display = "none"; // Oculta el mensaje cuando se llega al mínimo
        }
    }

    // Ejecutar la función cuando se cargue la página para reflejar el estado inicial
    document.addEventListener('DOMContentLoaded', actualizarContador);
</script>




<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require __DIR__ . '/../../vendor/autoload.php';


use App\Propiedad;

$db = conectadDB();
$propiedad = new Propiedad($db);

// Obtener vendedores
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asignar valores desde el formulario
    $propiedad->titulo = htmlspecialchars($_POST['titulo'] ?? '');
    $propiedad->precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
    $propiedad->descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
    $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT);
    $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

    // Validar imagen usando la clase Propiedad
    $erroresImagen = $propiedad->validarImagen($_FILES['imagen'] ?? null);
    if (!empty($erroresImagen)) {
        $errores = array_merge($errores, $erroresImagen);
    } else {
        // Guardar la imagen con un nombre único
        $propiedad->imagen = md5(uniqid(rand(), true)) . ".webp";
        $rutaDestino = __DIR__ . "/../../build/img/" . $propiedad->imagen;

        // Intentar mover la imagen a la carpeta de destino
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $errores[] = "Hubo un error al subir la imagen.";
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

    // Si no hay errores, intentar guardar en la base de datos
    if (empty($errores)) {
        $resultado = $propiedad->crear();
        if ($resultado === true) {
            header("Location: /admin/index.php?mensaje=1");
            exit;
        } else {
            $errores[] = "Error al guardar la propiedad: " . $resultado;
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear Propiedad</h1>
    <a href="/admin" class="boton boton-verde btn-admin" style="margin-bottom: 2rem;">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endforeach; ?>

    <form class="form" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad"
                value="<?php echo htmlspecialchars($propiedad->titulo ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <input type="text" id="precio" name="precio" placeholder="Precio Propiedad"
                value="<?php echo htmlspecialchars($propiedad->precio ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5" oninput="actualizarContador()">
    <?php echo htmlspecialchars($propiedad->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?>
</textarea>

            <p id="contador-caracteres" style="color: red; font-size: 1.2rem;">
                Mínimo <span id="restantes">50</span> caracteres.
            </p>



            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
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
                <option value="" disabled selected>--Seleccione--</option>
                <?php foreach ($vendedores as $vendedor): ?>
                    <option value="<?php echo $vendedor['id']; ?>" <?php echo ($propiedad->vendedor_id == $vendedor['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . ' ' . $vendedor['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php incluirTemplate('footer'); ?>
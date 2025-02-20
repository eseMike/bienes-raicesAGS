<?php
require '../../includes/seguridad.php';
require '../../includes/config/database.php';
require '../../includes/funciones.php';
require __DIR__ . '/../../vendor/autoload.php';

use App\Propiedad;

session_start(); // Asegurar que la sesión está iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$db = conectadDB();
$propiedad = new Propiedad($db);

// Obtener vendedores
$stmt = $db->prepare("SELECT * FROM vendedores");
$stmt->execute();
$vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errores[] = "Token CSRF no válido.";
    }

    // Asignar valores desde el formulario y limpiar entradas
    $propiedad->titulo = trim(htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8'));
    $propiedad->precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT) ?: 0;
    $propiedad->descripcion = trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
    $propiedad->habitaciones = filter_var($_POST['habitaciones'] ?? '', FILTER_VALIDATE_INT) ?: 0;
    $propiedad->wc = filter_var($_POST['wc'] ?? '', FILTER_VALIDATE_INT) ?: 0;
    $propiedad->estacionamiento = filter_var($_POST['estacionamiento'] ?? '', FILTER_VALIDATE_INT) ?: 0;
    $propiedad->vendedor_id = filter_var($_POST['vendedor'] ?? '', FILTER_VALIDATE_INT);

    // Validación extra
    if (!$propiedad->precio || $propiedad->precio <= 0) $errores[] = "El precio debe ser mayor a 0.";
    if (strlen($propiedad->descripcion) < 50) $errores[] = "La descripción debe tener al menos 50 caracteres.";
    if (!$propiedad->habitaciones || $propiedad->habitaciones <= 0) $errores[] = "Debe especificar un número válido de habitaciones.";
    if (!$propiedad->wc || $propiedad->wc <= 0) $errores[] = "Debe especificar un número válido de baños.";
    if (!$propiedad->estacionamiento || $propiedad->estacionamiento <= 0) $errores[] = "Debe especificar un número válido de estacionamientos.";
    if (!$propiedad->vendedor_id) $errores[] = "Debe seleccionar un vendedor.";

    // **VALIDACIÓN DE IMAGEN**
    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen'];

        // Extensiones permitidas
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            $errores[] = "Formato de imagen no permitido (solo jpg, jpeg, png, webp).";
        }

        // Validar tamaño (máximo 2MB)
        if ($imagen['size'] > 2 * 1024 * 1024) {
            $errores[] = "El tamaño de la imagen no debe superar los 2MB.";
        }

        // Si no hay errores, subir imagen
        if (empty($errores)) {
            $propiedad->imagen = md5(uniqid(rand(), true)) . ".webp";
            $rutaDestino = __DIR__ . "/../../build/img/" . $propiedad->imagen;

            if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                $errores[] = "Hubo un error al subir la imagen.";
            }
        }
    }

    // Si no hay errores, guardar en la base de datos
    if (empty($errores)) {
        $resultado = $propiedad->crear();
        if ($resultado === true) {
            header("Location: /admin/index.php?mensaje=1");
            exit;
        } else {
            $errores[] = "Error al guardar la propiedad.";
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
        <!-- Token CSRF para seguridad -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <fieldset>
            <legend>Información General</legend>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad"
                value="<?php echo htmlspecialchars($propiedad->titulo ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <input type="text" id="precio" name="precio" placeholder="Precio Propiedad"
                value="<?php echo htmlspecialchars($propiedad->precio ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5" oninput="actualizarContador()"><?php echo htmlspecialchars($propiedad->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

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
<?php
// Asegurarnos que estas variables estén definidas al incluir este archivo
$propiedad = $propiedad ?? null;
$vendedores = $vendedores ?? [];
$errores = $errores ?? [];
$accion = $accion ?? 'Crear';
$formAction = $formAction ?? '';
$mostrarImagen = $mostrarImagen ?? false;
?>

<main class="contenedor seccion">
    <h1><?php echo $accion; ?> Propiedad</h1>
    <a href="/admin" class="boton boton-verde btn-admin" style="margin-bottom: 2rem;">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endforeach; ?>

    <form class="form" method="POST" action="<?php echo $formAction; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

        <fieldset>
            <legend>Información General</legend>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad"
                value="<?php echo htmlspecialchars($propiedad->titulo ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="text" id="precio" name="precio" placeholder="Precio Propiedad"
                value="<?php echo htmlspecialchars($propiedad->precio ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="5"><?php echo htmlspecialchars($propiedad->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">

            <?php if ($mostrarImagen && is_object($propiedad) && !empty($propiedad->imagen)): ?>
                <p>Imagen actual:</p>
                <img src="/build/img/<?php echo basename($propiedad->imagen); ?>" class="imagen-small" style="max-width: 200px;">

            <?php endif; ?>


        </fieldset>

        <fieldset>
            <legend>Información Propiedad</legend>
            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones"
                value="<?php echo htmlspecialchars($propiedad->habitaciones ?? ''); ?>">
            <label for="wc">Baños:</label>
            <input type="number" id="wc" name="wc"
                value="<?php echo htmlspecialchars($propiedad->wc ?? ''); ?>">
            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" id="estacionamiento" name="estacionamiento"
                value="<?php echo htmlspecialchars($propiedad->estacionamiento ?? ''); ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>
            <select name="vendedor">
                <option value="" disabled>--Seleccione--</option>
                <?php foreach ($vendedores as $vendedor): ?>
                    <option value="<?php echo $vendedor['id']; ?>"
                        <?php echo ($propiedad->vendedor_id ?? '') == $vendedor['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendedor['nombre'] . ' ' . $vendedor['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <input type="submit" value="<?php echo $accion; ?> Propiedad" class="boton boton-verde">
    </form>
</main>
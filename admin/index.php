<?php

//Importar la conexión
require '../includes/seguridad.php'; // Validar sesión
require '../includes/config/database.php';
$db = conectadDB();

//Escribir el queryurl 
$query = "SELECT * FROM propiedades";
//Consultar la BD
$resultadoConsulta = $db->query($query);

// Verificar errores en la consulta
if (!$resultadoConsulta) {
      die("Error al consultar la base de datos.");
}

//Muestra mensaje condicional
$resultado = $_GET['mensaje'] ?? null;

//Incluye un template
require '../includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">
      <h1>Administrador de Bienes Raices</h1>
      <?php if ($resultado && intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
      <?php elseif ($resultado && intval($resultado) === 2): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
      <?php elseif ($resultado && intval($resultado) === 3): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
      <?php endif; ?>

      <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
      <table class="propiedades">
            <thead>
                  <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Imágen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                  </tr>
            </thead>

            <tbody>
                  <!-- Mostrar los resultados  -->
                  <?php while ($propiedad = $resultadoConsulta->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                              <td><?php echo htmlspecialchars($propiedad['id']); ?></td>
                              <td><?php echo htmlspecialchars($propiedad['titulo']); ?></td>
                              <td>
                                    <?php if (!empty($propiedad['imagen'])): ?>
                                          <img src="/imagenes/<?php echo htmlspecialchars($propiedad['imagen']); ?>" class="imagen-tabla">
                                    <?php else: ?>
                                          <p>Sin imagen</p>
                                    <?php endif; ?>
                              </td>
                              <td><?php echo '$ ' . number_format($propiedad['precio'], 2, '.', ','); ?></td>
                              <td>
                                    <!-- <a class="boton-verde-block" href="admin/propiedades/actualizar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>">Actualizar</a>   -->
                                    <a class="boton-verde-block" href="<?php echo RUTA_URL; ?>admin/propiedades/actualizar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>">Actualizar</a>
                                    <a class="boton-rojo-block" href="/admin/propiedades/eliminar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                              </td>
                        </tr>
                  <?php endwhile; ?>
            </tbody>

      </table>
</main>

<?php
//Cerrar la conexión
$db = null;

include '../includes/templates/footer.php';
?>
<?php

// Importar la conexión y validar sesión
require '../includes/seguridad.php';
require '../includes/config/database.php';
$db = conectadDB();

// Escribir la consulta
$query = "SELECT * FROM propiedades";

// Ejecutar la consulta con manejo de errores
try {
      $stmt = $db->prepare($query);
      $stmt->execute();
      $resultadoConsulta = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
      error_log("Error al consultar la base de datos: " . $e->getMessage());
      die("Error al obtener los datos. Intenta más tarde.");
}

// Mensaje condicional validado
$resultado = filter_input(INPUT_GET, 'mensaje', FILTER_VALIDATE_INT) ?? null;

// Incluir template
require '../includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">
      <h1>Administrador de Bienes Raíces</h1>

      <?php if ($resultado): ?>
            <p class="alerta exito">
                  <?php
                  switch ($resultado) {
                        case 1:
                              echo "Anuncio Creado Correctamente";
                              break;
                        case 2:
                              echo "Anuncio Actualizado Correctamente";
                              break;
                        case 3:
                              echo "Anuncio Eliminado Correctamente";
                              break;
                        default:
                              echo "Operación realizada correctamente";
                              break;
                  }
                  ?>
            </p>
      <?php endif; ?>

      <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

      <table class="propiedades">
            <thead>
                  <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                  </tr>
            </thead>

            <tbody>
                  <?php foreach ($resultadoConsulta as $propiedad): ?>
                        <tr>
                              <td><?php echo htmlspecialchars($propiedad['id']); ?></td>
                              <td><?php echo htmlspecialchars($propiedad['titulo']); ?></td>
                              <td>
                                    <?php if (!empty($propiedad['imagen'])): ?>
                                          <img src="/imagenes/<?php echo htmlspecialchars($propiedad['imagen']); ?>" class="imagen-tabla" alt="Imagen Propiedad">
                                    <?php else: ?>
                                          <p>Sin imagen</p>
                                    <?php endif; ?>
                              </td>
                              <td><?php echo '$ ' . number_format($propiedad['precio'], 2, '.', ','); ?></td>
                              <td>
                                    <a class="boton-verde-block" href="<?php echo RUTA_URL; ?>admin/propiedades/actualizar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>">Actualizar</a>
                                    <a class="boton-rojo-block" href="/admin/propiedades/eliminar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                              </td>
                        </tr>
                  <?php endforeach; ?>
            </tbody>
      </table>
</main>

<?php
// Cerrar conexión
$db = null;

include '../includes/templates/footer.php';
?>
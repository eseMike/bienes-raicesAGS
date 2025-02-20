<?php

// Importar la conexión y validar sesión
require '../includes/seguridad.php';
require '../includes/config/database.php';
require '../includes/funciones.php';

session_start(); // Asegurar que la sesión está iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$db = conectadDB();

// Escribir la consulta optimizada
$query = "SELECT id, titulo, imagen, precio FROM propiedades";

try {
      $stmt = $db->prepare($query);
      $stmt->execute();
      $resultadoConsulta = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
      error_log("Error al consultar la base de datos: " . $e->getMessage());
      $resultadoConsulta = [];
}

// Mensaje condicional validado
$resultado = filter_input(INPUT_GET, 'mensaje', FILTER_VALIDATE_INT) ?? null;

// Incluir template
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
                        case 4:
                              echo "Error: Token CSRF inválido.";
                              break;
                        case 5:
                              echo "Error: Propiedad no encontrada.";
                              break;
                        case 6:
                              echo "Error al eliminar la propiedad.";
                              break;
                        default:
                              echo "Operación realizada correctamente.";
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
                                          <img src="/build/img/<?php echo htmlspecialchars($propiedad['imagen']); ?>" class="imagen-tabla" alt="Imagen Propiedad">
                                    <?php else: ?>
                                          <p>Sin imagen</p>
                                    <?php endif; ?>
                              </td>
                              <td><?php echo '$ ' . number_format($propiedad['precio'], 2, '.', ','); ?></td>
                              <td>
                                    <a class="boton-verde-block" href="/admin/propiedades/actualizar.php?id=<?php echo htmlspecialchars($propiedad['id']); ?>">Actualizar</a>

                                    <!-- Formulario para eliminar con CSRF -->
                                    <form method="POST" action="/admin/propiedades/eliminar.php" style="display:inline;">
                                          <input type="hidden" name="id" value="<?php echo htmlspecialchars($propiedad['id']); ?>">
                                          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                          <button type="submit" class="boton-rojo-block" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</button>
                                    </form>
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
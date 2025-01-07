<?php

//Importar la conexión
require '../includes/config/database.php';
$db = conectadDB();

//Escribir el query
$query = "SELECT * FROM propiedades";
//Consultar la BD
$resultadoConsulta = $db->query($query);;

//Muestra mensaje condicional
$resultado = $_GET['mensaje'] ?? null;
//Incluye un template
require '../includes/funciones.php';
incluirTemplate('header');
?>


<main class="contenedor seccion">
      <h1>Administrador de Bienes Raices</h1>
      <?php if (intval($resultado) === 1):  ?>
            <p class="alerta exito">Anuncio creado correctamente</p>
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
                              <td> <?php echo $propiedad['id']; ?> </td>
                              <td> <?php echo $propiedad['titulo']; ?> </td>
                              <td><img src="/imagenes/<?php echo $propiedad['imagen'] ?>" class="imagen-tabla"></td>
                              <td> $ <?php echo $propiedad['precio']; ?> </td>
                              <td>
                                    <a class="boton-verde-block" href="admin/propiedades/actualizar.php">Actualizar</a>
                                    <a class="boton-rojo-block" href="#">Elminar</a>
                              </td>
                        </tr>
                  <?php endwhile; ?>
            </tbody>
      </table>
</main>



<?php
//Cerar la conexión
$db = null;

include '../includes/templates/footer.php';
?>
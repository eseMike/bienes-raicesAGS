<?php

// Validamos que exista el id
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
   die('ID no válido');
}

// Importar la conexión
require __DIR__ . '/includes/config/database.php';

$db = conectadDB();

// Consultar
$query = "SELECT * FROM propiedades WHERE id = $id";

// Preparar la consulta 
$stmt = $db->prepare($query);
$stmt->execute();

// Obtener resultados
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si la propiedad existe antes de acceder a sus datos
if (!$propiedad) {
   die('No se encontró la propiedad.');
}

// Depuración de la imagen
var_dump($propiedad['imagen']);
die();

require 'includes/funciones.php';
incluirTemplate('header');
?>

<div class="whats-container">
   <a
      target="_blank"
      class="whatsIcon"
      href="https://api.whatsapp.com/send?phone=525578139893&text=Hola, me interesan sus servicios!">
      <i class="fa-brands fa-square-whatsapp whats-icon"></i>
   </a>
   <main class="contenedor seccion">
      <h1><?php echo $propiedad['titulo']; ?></h1>
      <img loading="lazy" src="/build/img/<?php echo $propiedad['imagen']; ?>" alt="anuncio" />
      <div class="resumen-propiedad">
         <p class="precio">$<?php echo $propiedad['precio']; ?></p>
         <ul class="iconos-caracteristicas">
            <li>
               <i class="fa-solid fa-toilet"></i>
               <p><?php echo $propiedad['wc']; ?></p>
            </li>
            <li>
               <i class="fa-solid fa-car"></i>
               <p><?php echo $propiedad['estacionamiento']; ?></p>
            </li>
            <li>
               <i class="fa-solid fa-bed"></i>
               <p><?php echo $propiedad['habitaciones']; ?></p>
            </li>
         </ul>

         <p>
            <?php echo $propiedad['descripcion']; ?>
         </p>
      </div>
   </main>
</div>

<?php
$db = null;
include 'includes/templates/footer.php';
?>
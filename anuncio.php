<?php

// Validamos que exista el id y que sea un número entero válido
$id = $_GET['id'] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
   die('ID no válido');
}

require 'includes/app.php';

$db = conectarDB();

// Consultar de manera segura con `prepare()`
$query = "SELECT * FROM propiedades WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Obtener resultados
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

$imagenesGaleria = json_decode($propiedad['galeria'] ?? '[]');

// Verificar si la propiedad existe antes de acceder a sus datos
if (!$propiedad) {
   die('No se encontró la propiedad.');
}



// Importar el header
incluirTemplate('header');
?>

<div class="whats-container">
   <a
      target="_blank"
      class="whatsIcon"
      href="https://api.whatsapp.com/send?phone=525578139893&text=Hola, me interesan sus servicios!">
      <i class="fa-brands fa-square-whatsapp whats-icon"></i>
   </a>
</div>




<main class="contenedor seccion">
   <div class="galeria-propiedad">
      <div class="galeria-principal">
         <img id="imagen-principal" src="/build/img/<?php echo htmlspecialchars($propiedad['imagen']); ?>" alt="Imagen principal propiedad">
      </div>

      <?php if (!empty($imagenesGaleria)) : ?>
         <div class="galeria-miniaturas">
            <?php foreach ($imagenesGaleria as $img) : ?>
               <img class="miniatura" src="/build/img/<?php echo htmlspecialchars($img); ?>" alt="Imagen propiedad" onclick="cambiarImagen('<?php echo htmlspecialchars($img); ?>')">
            <?php endforeach; ?>
         </div>
      <?php endif; ?>
   </div>

   <script>
      function cambiarImagen(nombreImagen) {
         const imgPrincipal = document.getElementById('imagen-principal');
         imgPrincipal.src = '/build/img/' + nombreImagen;
      }
   </script>


   <div class="resumen-propiedad">
      <p class="precio">$<?php echo number_format($propiedad['precio'], 2); ?></p>
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
         <?php echo nl2br(htmlspecialchars($propiedad['descripcion'])); ?>
      </p>
   </div>
</main>

<?php
// Cerrar conexión
$db = null;
include 'includes/templates/footer.php';
?>
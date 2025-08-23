<?php
require 'includes/app.php';
incluirTemplate('header');

// Consulta de propiedades
use App\Propiedad;

Propiedad::setDB($db); // ← Esta línea inicializa la conexión estática

$limite = 10;
$anuncios = Propiedad::allLimit($limite);
?>

c
<div class="whats-container">
   <a
      target="_blank"
      class="whatsIcon"
      href="https://api.whatsapp.com/send?phone=525578139893&text=Hola, me interesan sus servicios!">
      <i class="fa-brands fa-square-whatsapp whats-icon"></i>
   </a>
</div>

<main class="contenedor seccion">
   <h2>Propiedades en venta</h2>

   <div class="contenedor-anuncios">
      <?php foreach ($anuncios as $anuncio): ?>
         <div class="anuncio">
            <?php
            // Obtenemos el nombre sin extensión para mostrar .webp
            $rutaWebp = "/build/img/" . $anuncio->imagen;


            ?>
            <img loading="lazy" src="<?php echo $rutaWebp; ?>" alt="<?php echo $anuncio->titulo; ?>">

            <div class="contenido-anuncio">
               <h3><?php echo $anuncio->titulo; ?></h3>
               <p><?php echo $anuncio->descripcion; ?></p>
               <p class="precio">$<?php echo number_format($anuncio->precio, 2); ?></p>

               <ul class="iconos-caracteristicas">
                  <li>
                     <img loading="lazy" src="/build/img/icono_wc.svg" alt="icono wc">
                     <p><?php echo $anuncio->wc; ?></p>
                  </li>
                  <li>
                     <img loading="lazy" src="/build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                     <p><?php echo $anuncio->estacionamiento; ?></p>
                  </li>
                  <li>
                     <img loading="lazy" src="/build/img/icono_dormitorio.svg" alt="icono habitaciones">
                     <p><?php echo $anuncio->habitaciones; ?></p>
                  </li>
               </ul>

               <a href="anuncio.php?id=<?php echo $anuncio->id; ?>" class="boton boton-blue">
                  Ver propiedad
               </a>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
</main>

<?php
incluirTemplate('footer');
?>
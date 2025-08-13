<?php
require 'includes/app.php';
incluirTemplate('header',  $inicio = true);
?>

<div class="whats-container">
   <a
      target="_blank"
      class="whatsIcon"
      href="https://api.whatsapp.com/send?phone=525578139893&text=Hola, me interesan sus servicios!">
      <i class="fa-brands fa-square-whatsapp whats-icon"></i>
   </a>

   <main class="contenedor seccion">
      <div class="video-container">
         <video autoplay muted loop playsinline>
            <source src="./src/videoVIBO.mp4" type="video/mp4">
            Tu navegador no soporta videos HTML5.
         </video>
      </div>
   </main>



   <section id="nosotros" class="nosotros-seccion contenedor">
      <div class="nosotros-cuadricula">
         <div class="bloque nosotros-texto">
            <h2>NOSOTROS</h2>
            <p>
               Creemos con el compromiso genuino de transformar una idea en un espacio único, funcional y moderno.
               Apasionados atendemos cada obra, cuidando de los sueños, esfuerzos y decisiones importantes de nuestros clientes.
               Como resultado, más de una década de trayectoria nos respalda.
            </p>
            <p>
               Nuestro sinónimo, la calidad. Evolucionamos, paso a paso, con cada obra, con cada proyecto.
            </p>
         </div>
         <div class="bloque bloque-imagen"></div>
         <div class="bloque">
            <h3>MISIÓN</h3>
            <p>
               Construir relaciones basadas en la confianza de nuestros clientes. Trabajar en equipo para lograr resultados que cambien la perspectiva de cada proyecto.
            </p>
         </div>
         <div class="bloque">
            <h3>VISIÓN</h3>
            <p>
               Hacer de VIBO Construcciones la firma constructora comprometida en redefinir el sector inmobiliario con innovación, sustentabilidad, responsabilidad y diseño moderno.
            </p>
         </div>
      </div>
   </section>



   <!-- img contacto  -->
   <section class="imagen-contacto">
      <!-- <h2>Foto equipo back</h2>  -->
      <!-- <p>
         Llena el formulario de contacto y un asesor se pondrá en contacto contigo a
         la brevedad
      </p> -->
      <!-- <a class="btn-yellow" href="contacto.php">Contáctanos</a> -->
   </section>
   <!-- end of img contacto -->

   <section class="proyectos-seccion contenedor">
      <div class="proyectos-grid">

         <!-- Bloque 1 -->
         <div class="bloque-texto">
            <h3>PROYECTOS COMERCIALES E INDUSTRIALES</h3>
            <p>
               Creemos con el compromiso genuino de transformar una idea en un espacio único, funcional y moderno.
               Apasionados atendemos cada obra, cuidando de los sueños, esfuerzos y decisiones importantes de nuestros clientes.
               Como resultado, más de una década de trayectoria nos respalda.
            </p>
         </div>

         <!-- Bloque 2 (Slider) -->
         <div class="bloque-slider">
            <div class="slider swiper">
               <div class="swiper-wrapper">
                  <div class="swiper-slide"><img src="src/comercial/mmrl-2499.jpg" alt="Nave industrial fachada frontal VIBO"></div>
                  <div class="swiper-slide"><img src="src/comercial/mmrl-2621.jpg" alt="Interior de nave industrial en construcción"></div>
                  <div class="swiper-slide"><img src="src/comercial/mmrl-2624.jpg" alt="Detalles estructurales de nave comercial"></div>
                  <div class="swiper-slide"><img src="src/comercial/mmrl-2757.jpg" alt="Avance de obra comercial VIBO Construcciones"></div>
               </div>
               <div class="swiper-pagination"></div>
            </div>
         </div>

         <!-- Bloque 3 (Slider) -->
         <div class="bloque-slider">
            <div class="slider swiper">
               <div class="swiper-wrapper">
                  <div class="swiper-slide"><img src="src/residencial/DSC03677And8more.jpg" alt="Vista exterior de residencia moderna VIBO"></div>
                  <div class="swiper-slide"><img src="src/residencial/DSC03704And8more.jpg" alt="Residencia con diseño contemporáneo de fachada amplia"></div>
                  <div class="swiper-slide"><img src="src/residencial/DSC03740And8more.jpg" alt="Interior residencial de lujo VIBO Construcciones"></div>
                  <div class="swiper-slide"><img src="src/residencial/lino1.jpg" alt="Casa residencial Lino construida por VIBO"></div>
               </div>
               <div class="swiper-pagination"></div>
            </div>
         </div>

         <!-- Bloque 4 -->
         <div id="residenciales" class="bloque-texto">
            <h3>PROYECTOS RESIDENCIALES</h3>
            <p>
               Como fruto de una estrecha colaboración con arquitectos y diseñadores de importante solidez,
               garantizamos desde la cimentación hasta los pequeños detalles que cada proyecto sea único.
            </p>
            <p>
               Con presencia en las mejores zonas residenciales, hemos desarrollado obras que trascienden generaciones,
               y a la fecha hemos construido +17mil m².
            </p>
         </div>

         <!-- Bloque 5 -->
         <div id="vivienda" class="bloque-texto">
            <h3>VIVIENDA</h3>
            <p>
               Impulsamos el futuro del desarrollo habitacional con creatividad, eficiencia y compromiso.
               Con +110 viviendas, seguimos construyendo con propósito y funcionalidad.
            </p>
            <p>
               <strong>+32,000 m² de construcción en vivienda.</strong>
            </p>
            <p>
               Nuestra firma en los mejores desarrollos: Rincón de los Encinos, Monte Sabino, Seterra, Hacienda Santa Ana Residencial,
               Privanzas Acacia, Privanzas del Campestre, entre otros.
            </p>
         </div>

         <!-- Bloque 6 (Slider) -->
         <div class="bloque-slider">
            <div class="slider swiper">
               <div class="swiper-wrapper">
                  <div class="swiper-slide"><img src="src/vivienda/acaciaCocina.jpg" alt="Cocina moderna en desarrollo Acacia"></div>
                  <div class="swiper-slide"><img src="src/vivienda/acaciaFachada.jpg" alt="Fachada de vivienda Acacia por VIBO"></div>
                  <div class="swiper-slide"><img src="src/vivienda//acaciaPatio.jpg" alt="Patio trasero en vivienda residencial Acacia"></div>
                  <div class="swiper-slide"><img src="src/vivienda/COMEDOR.jpg" alt="Comedor amplio y funcional en desarrollo residencial"></div>
               </div>
               <div class="swiper-pagination"></div>
            </div>
         </div>

      </div>
   </section>


   <!-- propiedades  -->
   <section id="catalogo" class="seccion contenedor">
      <h2>Catálogo</h2>
      <?php
      $limite = 3;
      include 'includes/templates/anuncios.php';
      ?>
      <div class="ver-todas">
         <a class="btn-yellow" href="anuncios.php">Ver todas</a>
      </div>
   </section>
   <!-- end of propiedades  -->

</div>


<?php
include 'includes/templates/footer.php';
?>
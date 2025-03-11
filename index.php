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
      <h1>¿Qué ofrecemos?</h1>
      <div class="iconos-nosotros">
         <div data-aos="fade-up" data-aos-duration="3000" class="icono">
            <i class="fa-solid fa-shield-halved"></i>
            <h3>Seguridad</h3>
            <p>
               Porque sabemos que lo más importante para ti es la seguridad,
               cuidamos tu patrimonio respaldados por nuestra experiencia en el
               sector inmobiliario.
            </p>
         </div>

         <div data-aos="fade-up" data-aos-duration="2500" class="icono">
            <i class="fa-solid fa-user-tie"></i>
            <h3>Profesionalismo</h3>
            <p>
               Deja que nuestros Asesores Certificados se encarguen de guiarte con
               atención personalizada en cada etapa del proceso.
            </p>
         </div>

         <div data-aos="fade-up" data-aos-duration="2000" class="icono">
            <i class="fa-solid fa-clock"></i>
            <h3>A tiempo</h3>
            <p>
               Tu tiempo es parte de nuestra prioridad, por eso agilizamos procesos
               para ofrecerte soluciones rápidas y efectivas.
            </p>
         </div>
      </div>
   </main>

   <!-- propiedades  -->
   <section class="seccion contenedor">
      <h2>Propiedades en venta</h2>
      <?php
      $limite = 3;
      include 'includes/templates/anuncios.php';
      ?>
      <div class="ver-todas">
         <a class="btn-yellow" href="anuncios.php">Ver todas</a>
      </div>
   </section>
   <!-- end of propiedades  -->

   <!-- img contacto  -->
   <section class="imagen-contacto">
      <h2>Encuentra la casa que soñabas</h2>
      <p>
         Llena el formulario de contacto y un asesor se pondrá en contacto contigo a
         la brevedad
      </p>
      <a class="btn-yellow" href="contacto.php">Contáctanos</a>
   </section>
   <!-- end of img contacto -->

   <!-- testimonials  -->
   <div class="contenedor seccion seccion-inferior">
      <section class="blog">
         <h3>Nuestro blog</h3>

         <article data-aos="fade-up-right" class="entrada-blog">
            <div class="imagen">
               <picture>
                  <source srcset="build/img/blog1.webp" type="image/webp" />
                  <source srcset="build/img/blog1.webp" type="image/jpeg" />
                  <img loading="lazy" src="build/img/blog1.webp" alt="Entrada blog" />
               </picture>
            </div>

            <div class="texto-entrada">
               <a href="entrada.php">
                  <h4>Terraza en el techo de tu casa</h4>
                  <p class="informacion-meta">
                     escrito el: <span>01-Dic-2024</span> por:
                     <span>Kaboz Ricoy</span>
                  </p>
                  <p>
                     Consejos para construir una terraza en el techo de tu casa con
                     los mejores materiales y ahorrando dinero
                  </p>
               </a>
            </div>
         </article>

         <article data-aos="fade-down-right" class="entrada-blog">
            <div class="imagen">
               <picture>

                  <source srcset="build/img/blog2.webp" type="image/webp" />
                  <source srcset="build/img/blog2.webp" type="image/jpeg" />
                  <img loading="lazy" src="build/img/anuncio2.webp" alt="Entrada blog" />
               </picture>
            </div>

            <div class="texto-entrada">
               <a href="entrada.php">
                  <h4>Guía para la decoración de tu hogar</h4>
                  <p class="informacion-meta">
                     escrito el: <span>01-Dic-2024</span> por:
                     <span>Kaboz Ricoy</span>
                  </p>
                  <p>
                     Maximiza el espacio en tu hogar con esta guía, aprende a
                     combinar muebles y colores para darle vida a tu espacio
                  </p>
               </a>
            </div>
         </article>
      </section>

      <!-- testimonials  -->
      <section class="testimoniales">
         <h3>Testimoniales</h3>
         <div class="testimonial">
            <blockquote>
               "El personal se comportó de una excelente forma, muy buena atención y
               la casa que me ofrecieron cumple con las espectativas"
            </blockquote>

            <p>-- Juanita Martinez</p>
         </div>
      </section>
      <!-- End of testimonials  -->
   </div>

   <!-- agregando comentario de prueba para VPS  -->
</div>


<?php
include 'includes/templates/footer.php';
?>
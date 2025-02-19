<?php
require 'includes/app.php';
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
      <h1 style="margin-bottom: 3rem">Conoce sobre nosotros</h1>
      <div class="contenido-nosotros">
         <div class="imagen">
            <picture>
               <source srcset="build/img/nosotros.webp" type="image/webp" />
               <source srcset="build/img/nosotros.jpg" type="image/jpeg" />
               <img loading="lazy" src="build/img/nosotros.jpg" alt="Sobre nosotros" />
            </picture>

         </div>

         <div class="texto-nosotros">
            <blockquote>
               Líderes en el Sector Inmobiliario en Aguascalientes
            </blockquote>
            <p>
               En Servicios Inmobiliarios Aguascalientes sabemos que adquirir,
               vender o administrar una propiedad representa una oportunidad única
               para alcanzar tus metas. Por eso, trabajamos cada día para ofrecerte
               un servicio basado en la confianza, la experiencia y el
               profesionalismo. Tu tranquilidad es una parte fundamental de nuestras
               prioridades.
            </p>

            <p>
               Contamos con un equipo de asesores certificados y altamente
               capacitados que te acompañarán durante todo el proceso. Desde el
               primer contacto hasta la conclusión de la operación, nos aseguramos
               de que cada paso sea claro, sencillo y seguro, brindándote la
               atención personalizada que mereces.
            </p>
            <p>
               Con años de experiencia en el sector inmobiliario y un profundo
               conocimiento del mercado, nuestro objetivo es ser tu aliado de
               confianza. Nos esforzamos por superar tus expectativas, ofreciendo
               soluciones rápidas, efectivas y adaptadas a tus necesidades. Déjanos
               ayudarte a construir tus sueños.
            </p>
         </div>
      </div>
   </main>

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
</div>

<?php
include 'includes/templates/footer.php';
?>
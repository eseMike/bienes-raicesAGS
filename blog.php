<?php
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
   <main class="contenedor seccion contenido-centrado">
      <h1>Nuestro blog</h1>

      <article class="entrada-blog">
         <div class="imagen">
            <picture>
               <source srcset="build/img/blog1.webp" type="image/webp" />
               <source srcset="build/img/blog1.jpg" type="image/jpeg" />
               <img loading="lazy" src="build/img/blog1.jpg" alt="Entrada blog" />
            </picture>

         </div>

         <div class="texto-entrada">
            <a href="entrada.php">
               <h4>Terraza en el techo de tu casa</h4>
               <p>
                  escrito el: <span>01-Dic-2024</span> por:
                  <span>Kaboz Ricoy</span>
               </p>
               <p>
                  Consejos para construir una terraza en el techo de tu casa con los
                  mejores materiales y ahorrando dinero
               </p>
            </a>
         </div>
      </article>

      <article class="entrada-blog">
         <div class="imagen">
            <picture>
               <source srcset="build/img/blog2.webp" type="image/webp" />
               <source srcset="build/img/blog2.jpg" type="image/jpeg" />
               <img loading="lazy" src="build/img/blog2.jpg" alt="Entrada blog" />
            </picture>
         </div>

         <div class="texto-entrada">
            <a href="entrada.php">
               <h4>Terraza en el techo de tu casa</h4>
               <p>
                  escrito el: <span>01-Dic-2022</span> por:
                  <span>Kaboz Ricoy</span>
               </p>
               <p>
                  Consejos para construir una terraza en el techo de tu casa con los
                  mejores materiales y ahorrando dinero
               </p>
            </a>
         </div>
      </article>

      <article class="entrada-blog">
         <div class="imagen">
            <picture>
               <source srcset="build/img/blog3.webp" type="image/webp" />
               <source srcset="build/img/blog3.jpg" type="image/jpeg" />
               <img loading="lazy" src="build/img/blog3.jpg" alt="Entrada blog" />
            </picture>
         </div>

         <div class="texto-entrada">
            <a href="entrada.php">
               <h4>Terraza en el techo de tu casa</h4>
               <p>
                  escrito el: <span>01-Dic-2024</span> por:
                  <span>Kaboz Ricoy</span>
               </p>
               <p>
                  Consejos para construir una terraza en el techo de tu casa con los
                  mejores materiales y ahorrando dinero
               </p>
            </a>
         </div>
      </article>

      <article class="entrada-blog">
         <div class="imagen">
            <picture>
               <source srcset="build/img/blog4.webp" type="image/webp" />
               <source srcset="build/img/blog4.jpg" type="image/jpeg" />
               <img loading="lazy" src="build/img/blog4.jpg" alt="Entrada blog" />
            </picture>
         </div>

         <div class="texto-entrada">
            <a href="entrada.php">
               <h4>Terraza en el techo de tu casa</h4>
               <p>
                  escrito el: <span>01-Dic-2024</span> por:
                  <span>Kaboz Ricoy</span>
               </p>
               <p>
                  Consejos para construir una terraza en el techo de tu casa con los
                  mejores materiales y ahorrando dinero
               </p>
            </a>
         </div>
      </article>
   </main>
</div>


<?php
include 'includes/templates/footer.php';
?>
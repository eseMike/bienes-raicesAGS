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
   <main class="contenedor seccion">
      <h1>Casa en venta frente al bosque</h1>

      <picture>
         <img
            loading="lazy"
            src="build/img/destacada.avif"
            alt="Imagen propiedad" />
         <source srcset="build/img/destacada.webp" type="image/webp" />
         <source srcset="build/img/destacada.jpg" type="image/jpeg" />
      </picture>
      <div class="resumen-propiedad">
         <p class="precio">$3,000,000</p>
         <ul class="iconos-caracteristicas">
            <li>
               <i class="fa-solid fa-toilet"></i>
               <p>3</p>
            </li>
            <li>
               <i class="fa-solid fa-car"></i>
               <p>2</p>
            </li>
            <li>
               <i class="fa-solid fa-bed"></i>
               <p>4</p>
            </li>
         </ul>

         <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Soluta dolorem
            voluptatibus aliquam blanditiis earum itaque suscipit molestias
            aspernatur omnis, fugit, reprehenderit similique alias quidem? Totam
            maxime vero porro dolorem maiores. Omnis, ipsa? Quas qui ad accusantium
            molestias aliquid consequatur autem saepe nesciunt soluta eveniet ipsam,
            minima omnis rerum quod iure! Reprehenderit tempore magnam repellat
            animi iure obcaecati eum placeat officiis? Ipsam cupiditate ea at qui
            quaerat libero, nisi dolorem expedita ullam optio cum totam explicabo
            doloremque eveniet maiores? Porro dolorum facilis obcaecati quod fugit,
            distinctio nostrum dolorem totam molestiae aliquid.
         </p>
      </div>
   </main>
</div>


<?php
include 'includes/templates/footer.php';
?>
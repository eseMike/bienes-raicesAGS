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
      <h2>Propiedades en venta</h2>
      <?php
      $limite = 10;
      // Consultar
      include 'includes/templates/anuncios.php';
      ?>

   </main>
</div>

<footer class="footer">

   <?php
   include 'includes/templates/footer.php';
   ?>
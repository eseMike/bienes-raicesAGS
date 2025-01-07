<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <!-- <link rel="stylesheet" href="/build/css/app.css" /> -->

      <!-- bootstrap  -->
      <link
         href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
         rel="stylesheet"
         integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
         crossorigin="anonymous" />
       <link rel="stylesheet" href="<?php echo RUTA_URL; ?>build/css/app.css">
      <title>Bienes y Raíces</title>
   </head>

   <body>
      <header class="header <?php echo $inicio ? 'inicio': ''; ?> ">
         <div class="contenedor contenido-header">
            <!-- navbar  -->
            <nav class="navbar navbar-expand-lg bg-light">
               <span class="btnDark"> <i class="fa-solid fa-moon"></i> </span>
               <div class="container-fluid">
                  <a class="navbar-brand" href="index.php">
                     <img src="/src/img/logos/logoWhite.png" alt="Logotipo" />
                  </a>
                  <button
                     class="navbar-toggler navbar-dark"
                     type="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#navbarText"
                     aria-controls="navbarText"
                     aria-expanded="false"
                     aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse" id="navbarText">
                     <span class="navbar-text me-auto mb-2 mb-lg-0"> </span>
                     <ul class="navbar-nav">
                        <li class="nav-item">
                           <a class="nav-link" aria-current="page" href="nosotros.php"
                              >Nosotros</a
                           >
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="anuncios.php">Catálogo</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="blog.php">Blog</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="contacto.php">Contácto</a>
                        </li>
                     </ul>
                  </div>
               </div>
            </nav>
            <!-- end of navbar -->
         </div>
</header>

      <!-- Incluye el CSS de AOS en el <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

<!-- Incluye el JS de AOS antes del cierre del <body> -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>

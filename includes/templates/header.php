<?php

// Asegurar que la sesión solo se inicie si no está activa
if (session_status() === PHP_SESSION_NONE) {
   session_start();
   session_regenerate_id(true); // 🔹 Evita ataques de fijación de sesión
}

$auth = isset($_SESSION['login']) && $_SESSION['login'] === true;

?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="stylesheet" href="<?php echo RUTA_URL; ?>build/css/app.css">
   <title>Bienes y Raíces</title>

   <!-- Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
</head>

<body>
   <header class="header <?php echo isset($inicio) && $inicio ? 'inicio' : ''; ?>">
      <div class="contenedor contenido-header">
         <!-- Navbar -->
         <nav class="navbar navbar-expand-lg bg-light">
            <span class="btnDark"><i class="fa-solid fa-moon"></i></span>
            <div class="container-fluid">
               <a class="navbar-brand" href="index.php">
                  <img src="/src/img/logos/logoWhite.png" alt="Logotipo">
               </a>
               <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                  aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
               </button>

               <div class="collapse navbar-collapse" id="navbarText">
                  <span class="navbar-text me-auto mb-2 mb-lg-0"></span>
                  <ul class="navbar-nav">
                     <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_URL; ?>nosotros.php">Nosotros</a></li>
                     <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_URL; ?>anuncios.php">Catálogo</a></li>
                     <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_URL; ?>blog.php">Blog</a></li>
                     <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_URL; ?>contacto.php">Contacto</a></li>
                     <?php if ($auth) : ?>
                        <li class="nav-item">
                           <a class="nav-link" href="<?php echo RUTA_URL; ?>cerrar-sesion.php">Cerrar Sesión</a>
                        </li>
                     <?php endif; ?>
                  </ul>
               </div>
            </div>
         </nav>
      </div>
      <?php echo isset($inicio) && $inicio ? "<h1 class='mi-clase'>Venta de casas y departamentos en la zona Metropolitana de Aguascalientes</h1>" : ''; ?>
   </header>

   <!-- AOS Animation -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
   <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
   <script>
      AOS.init();
   </script>

   <!-- JS del proyecto -->
   <script src="<?php echo RUTA_URL; ?>build/js/bundle.js" defer></script>

</body>

</html>
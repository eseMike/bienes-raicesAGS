<?php
require 'includes/app.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $nombre = $_POST['nombre'] ?? '';
   $email = $_POST['email'] ?? '';
   $telefono = $_POST['telefono'] ?? '';
   $mensajeUsuario = $_POST['mensaje'] ?? '';
   $tipo = $_POST['tipo'] ?? '';
   $precio = $_POST['precio'] ?? '';
   $contacto = $_POST['contacto'] ?? '';
   $fecha = $_POST['fecha'] ?? '';
   $hora = $_POST['hora'] ?? '';

   // Construir mensaje
   $contenido = "Has recibido un nuevo mensaje de contacto:\n\n";
   $contenido .= "Nombre: $nombre\n";
   $contenido .= "Email: $email\n";
   $contenido .= "Teléfono: $telefono\n";
   $contenido .= "Mensaje: $mensajeUsuario\n";
   $contenido .= "Tipo de operación: $tipo\n";
   $contenido .= "Presupuesto/Precio: $precio\n";
   $contenido .= "Medio de contacto preferido: $contacto\n";

   if ($contacto === 'telefono') {
      $contenido .= "Fecha para llamada: $fecha\n";
      $contenido .= "Hora para llamada: $hora\n";
   }

   // Enviar correo (reemplaza por tu correo real de pruebas)
   $destino = 'elforasteromexicano@gmail.com';
   $asunto = 'Nuevo mensaje desde el sitio web';
   $headers = "From: contacto@tuweb.com";

   $exito = mail($destino, $asunto, $contenido, $headers);

   if ($exito) {
      $mensaje = '✅ ¡Mensaje enviado correctamente!';
   } else {
      $mensaje = '❌ Hubo un error al enviar el mensaje.';
   }
}
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
      <h1>Contacto</h1>

      <picture>
         <source srcset="build/img/destacada3.webp" type="image/webp" />
         <source srcset="build/img/destacada3.jpg" type="image/jpeg" />
         <img loading="lazy" src="build/img/destacada3.webp" alt="Imagen propiedad" />
      </picture>


      <h2>Llene el formulario y nos pondremos en contacto de inmediato con ud.</h2>

      <form class="form" action="contacto.php" method="POST">
         <fieldset>
            <legend>Información personal</legend>

            <label for="name">Nombre</label>
            <input id="name" name="nombre" type="text" placeholder="Tu nombre" />

            <label for="email">E-mail</label>
            <input id="email" name="email" type="text" placeholder="Tu E-mail" />

            <label for="phone">Teléfono</label>
            <input id="phone" name="telefono" type="tel" placeholder="55 5555 5555" />

            <label for="message">Mensaje:</label>
            <textarea name="mensaje" id="message"></textarea>
         </fieldset>

         <fieldset>
            <legend>Información sobre la propiedad</legend>

            <label for="options">Vende o Compra</label>
            <select id="options" name="tipo">
               <option value="" disabled selected>-- Seleccione --</option>
               <option value="Compra">Compra</option>
               <option value="Vende">Vende</option>
            </select>

            <label for="price">Precio o Presupuesto</label>
            <input id="price" name="precio" type="number" placeholder="$" />
         </fieldset>

         <fieldset>
            <legend>Contacto</legend>

            <p>¿Cómo desea ser contactado?</p>
            <div class="forma-contacto">
               <label for="contact-phone">Teléfono</label>
               <input name="contacto" type="radio" value="telefono" id="contact-phone" />

               <label for="contact-email">E-mail</label>
               <input name="contacto" type="radio" value="email" id="contact-email" />
            </div>

            <p>Si eligió teléfono, por favor indique fecha y hora para ser contactado</p>

            <label for="date">Fecha:</label>
            <input id="date" name="fecha" type="date" />

            <label for="time">Hora:</label>
            <input id="time" name="hora" type="time" min="09:00" max="20:00" />
         </fieldset>

         <input type="submit" class="btn-yellow" value="Enviar" />
      </form>


   </main>
</div>

<?php
include 'includes/templates/footer.php';
?>
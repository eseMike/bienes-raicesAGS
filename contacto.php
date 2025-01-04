
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
            <h1>Contacto</h1>

            <picture>
               <img
                  loading="lazy"
                  src="build/img/destacada3.avif"
                  alt="Imagen propiedad" />
               <source srcset="build/img/destacada3.webp" type="image/webp" />
               <source srcset="build/img/destacada3.jpg" type="image/jpeg" />
            </picture>

            <h2>Llene el formulario y nos pondremos en contacto de inmediato con ud.</h2>

            <form class="form" action="">
               <fieldset>
                  <legend>Información personal</legend>

                  <label for="name">Nombre</label>
                  <input id="name" type="text" placeholder="Tu nombre" />

                  <label for="email">E-mail</label>
                  <input id="email" type="text" placeholder="Tu E-mail" />

                  <label for="phone">Teléfono</label>
                  <input id="phone" type="tel" placeholder="55 5555 5555" />

                  <label for="message">Mensaje:</label>
                  <textarea name="" id="message"></textarea>
               </fieldset>

               <fieldset>
                  <legend>Información sobre la propiedad</legend>
                  <label for="options">Vende o Compra</label>
                  <select id="optiones" name="" id="">
                     <option value="" disabled selected>-- Seleccione --</option>
                     <option value="Compra">Compra</option>
                     <option value="Vende">Vende</option>
                  </select>

                  <label for="price">Precio o Presupuesto</label>
                  <input id="price" type="number" placeholder="$" />
               </fieldset>

               <fieldset>
                  <legend>Contacto</legend>

                  <p>¿Cómo desea ser contactado?</p>
                  <div class="forma-contacto">
                     <label for="contact-phone">Teléfono</label>
                     <input
                        name="contacto"
                        type="radio"
                        value="telfono"
                        id="contact-phone" />

                     <label for="contact-email">E-mail</label>
                     <input
                        name="contacto"
                        type="radio"
                        value="email"
                        id="contact-email" />
                  </div>

                  <p>
                     Si eligió teléfono, por favor indique fecha y hora para ser
                     contactado
                  </p>

                  <label for="date">Fecha:</label>
                  <input type="date" />

                  <label for="time">Hora:</label>
                  <input id="time" type="time" min="09:00 " max="20:00" />
               </fieldset>

               <input type="submit" class="btn-yellow" value="Enviar" />
            </form>
         </main>
      </div>
       
<?php  
      include 'includes/templates/footer.php'; 
?>

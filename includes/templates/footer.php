<footer class="footer">
   <div class="footer-bg">
      <div class="footer-grid">
         <!-- Izquierda: Dots -->
         <div class="footer-dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
         </div>
         <!-- Centro: Logo -->
         <div class="footer-logo">
            <!-- <img style="margin: auto;" src="src/img/logos/logoWhite.webp" alt="VIBO">  -->
            <img style="margin: auto;" src="/src/logoViboB.png" alt="Logotipo" style="height: 60px;">

            <!-- <div class="footer-tagline">Bienes Raíces & Construcción</div>  -->
         </div>
         <!-- Derecha: Datos -->
         <div class="footer-contact">
            <div>DIRECCIÓN:</div>
            <div>TELÉFONO: (844) 0000000</div>
            <div>MAIL:</div>
         </div>
      </div>
   </div>

   <!-- <p class="copyright">Todos los derechos reservados <?php echo date('Y') ?> &copy</p>  -->
</footer>

<script src="build/js/bundle.js"></script>
<!-- fontawesome  -->
<script
   src="https://kit.fontawesome.com/bbd581d529.js"
   crossorigin="anonymous"></script>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
   AOS.init();
</script>

<!-- bootstrap  -->
<!-- JavaScript Bundle with Popper -->
<script
   src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
   integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
   crossorigin="anonymous"></script>


<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      var sliders = document.querySelectorAll('.slider');
      sliders.forEach(slider => {
         new Swiper(slider, {
            loop: false,
            speed: 700,
            pagination: {
               el: slider.querySelector('.swiper-pagination'),
               clickable: true
            },
            observer: true,
            observeParents: true,
            slidesPerView: 1,
            spaceBetween: 0,
            watchSlidesProgress: true
         });
      });
   });
</script>
</body>

</html>
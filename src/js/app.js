document.addEventListener("DOMContentLoaded", () => {
   console.log("DOMContentLoaded ejecutado");
   darkMode();
});

function darkMode() {
   console.log("darkMode ejecutado");
   const preferDarkMode = window.matchMedia("(prefers-color-scheme: dark)");

   if (preferDarkMode.matches) {
      document.body.classList.add("dark-mode");
   } else {
      document.body.classList.remove("dark-mode");
   }

   preferDarkMode.addEventListener("change", function () {
      if (preferDarkMode.matches) {
         document.body.classList.add("dark-mode");
      } else {
         document.body.classList.remove("dark-mode");
      }
   });

   const btnDarkMode = document.querySelector(".btnDark");
   if (btnDarkMode) {
      btnDarkMode.addEventListener("click", function () {
         document.body.classList.toggle("dark-mode");

         if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("modo-oscuro", "true");
         } else {
            localStorage.setItem("modo-oscuro", "false");
         }
      });
   }

   if (localStorage.getItem("modo-oscuro") === "true") {
      document.body.classList.add("dark-mode");
   } else {
      document.body.classList.remove("dark-mode");
   }
}

// Validar el campo de descripción en tiempo real
const descripcion = document.getElementById("descripcion");
const mensaje = document.getElementById("mensaje-descripcion");
const contador = document.getElementById("contador-caracteres");
let haEscrito = false;

if (descripcion) {
   console.log("Campo descripción detectado");
   descripcion.addEventListener("input", () => {
      const caracteresRestantes = 50 - descripcion.value.length;
      contador.textContent = caracteresRestantes > 0 ? caracteresRestantes : 0;

      if (descripcion.value.length > 0) {
         haEscrito = true; // Detectamos que el usuario empezó a escribir
      }

      if (caracteresRestantes > 0 && haEscrito) {
         mensaje.style.display = "block";
      } else {
         mensaje.style.display = "none";
      }
   });
}

// Validación básica del campo de precio (solo permite números)
const precioInput = document.getElementById("precio");
if (precioInput) {
   console.log("Campo precio detectado");

   // Prevenir caracteres no válidos
   precioInput.addEventListener("keypress", (event) => {
      if (!/[0-9]/.test(event.key)) {
         event.preventDefault();
      }
   });
}

// Asegúrate de que ningún preventDefault() detenga la acción
document.querySelectorAll("a").forEach((link) => {
   link.addEventListener("click", (event) => {
      console.log("Redirigiendo a:", event.target.href);
   });
});

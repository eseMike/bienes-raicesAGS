document.addEventListener("DOMContentLoaded", () => {
   darkMode();
});

function darkMode() {
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
   btnDarkMode.addEventListener("click", function () {
      document.body.classList.toggle("dark-mode");

      if (document.body.classList.contains("dark-mode")) {
         localStorage.setItem("modo-oscuro", "true");
      } else {
         localStorage.setItem("modo-oscuro", "false");
      }
   });

   if (localStorage.getItem("modo-oscuro") === "true") {
      document.body.classList.add("dark-mode");
   } else {
      document.body.classList.remove("dark-mode");
   }
}

document.querySelector(".bg-light").classList.remove("bg-light");

const btnDarkMode = document.querySelector(".btnDark");

// Validar el campo de descripción en tiempo real
const descripcion = document.getElementById("descripcion");
const mensaje = document.getElementById("mensaje-descripcion");
const contador = document.getElementById("contador-caracteres");
let haEscrito = false;

if (descripcion) {
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

// Deja el campo de precio sin formato
const precioInput = document.getElementById("precio");
if (precioInput) {
   precioInput.addEventListener("keypress", (event) => {
      // Permitir solo números
      if (!/[0-9]/.test(event.key)) {
         event.preventDefault();
      }
   });
}

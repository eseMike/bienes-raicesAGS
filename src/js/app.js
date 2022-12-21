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
    console.log("test");

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

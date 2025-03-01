import imagemin from "imagemin";
import imageminWebp from "imagemin-webp";
import fs from "fs";

(async () => {
    await imagemin(["src/img/header.jpg"], {
        destination: "build/img",
        plugins: [imageminWebp({ quality: 80 })],
    });

    console.log("✅ Conversión manual a WebP con imagemin terminada");

    if (fs.existsSync("build/img/header.webp")) {
        console.log("✅ El archivo header.webp se generó correctamente");
    } else {
        console.log("❌ La conversión falló");
    }
})();

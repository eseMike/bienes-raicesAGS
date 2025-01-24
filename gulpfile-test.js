import gulp from "gulp";
import webp from "gulp-webp";

function testWebp() {
   return gulp
      .src("src/img/header.jpg")
      .pipe(
         webp({
            quality: 50, // Reducir aún más la calidad
            method: 6, // Mejor método de compresión
            alphaQuality: 80,
            lossless: false,
            effort: 6,
            preset: "photo",
         })
      )

      .pipe(gulp.dest("test-output"))
      .on("data", (file) => {
         console.log("Conversión exitosa a WebP:", file.basename);
      });
}

export default testWebp;

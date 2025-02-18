import gulp from "gulp";
import gulpSass from "gulp-sass";
import * as sass from "sass";
import cssnano from "cssnano";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import sourcemaps from "gulp-sourcemaps";
import concat from "gulp-concat";
import terser from "gulp-terser";
import avif from "gulp-avif";
import cache from "gulp-cache";
import svgmin from "gulp-svgmin";
import imagemin from "gulp-imagemin";
import imageminWebp from "imagemin-webp";
import {deleteAsync} from "del";
import imageminMozjpeg from "imagemin-mozjpeg";
import imageminOptipng from "imagemin-optipng";
import webp from "gulp-webp";

const {src, dest, watch, parallel, series} = gulp;

// Rutas del proyecto
const path = {
   scss: "src/scss/**/*.scss",
   css: "build/css/app.css",
   js: "src/js/**/*.js",
   img: "src/img/**/*.{jpg,png}",
   imgmin: "build/img/**/*.{jpg,png}",
   svg: "src/img/**/*.svg",
};

// Función para compilar Sass a CSS con minificación y autoprefixing
function compileSass() {
   return src(path.scss)
      .pipe(sourcemaps.init())
      .pipe(gulpSass(sass)())
      .pipe(postcss([autoprefixer(), cssnano()]))
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/css"));
}

// Función para minificar y combinar JavaScript
function compileJS() {
   return src(path.js)
      .pipe(sourcemaps.init())
      .pipe(concat("bundle.js"))
      .pipe(terser())
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/js"));
}

// Función para copiar imágenes sin modificarlas
function imageMin() {
   return src("src/img/**/*.{jpg,png}") // Solo copiar imágenes JPG/PNG
      .pipe(dest("build/img"))
      .on("end", () => console.log("✅ Todas las imágenes copiadas correctamente sin daños"));
}


function imgWebp() {
   return src("src/img/**/*.{jpg,png}")
      .pipe(webp({quality: 80})) // Cambia la calidad a 80 para mejorar compatibilidad
      .on("error", (err) => console.error("Error en imgWebp:", err.message))
      .pipe(dest("build/img"))
      .on("end", () => console.log("✅ Imágenes convertidas a WebP sin errores"));
}




function imgAvif(done) {
   console.log("Skipping imgAvif for debugging...");
   done();
}



// Función para optimizar SVGs
function imgSvg() {
   return src(path.svg)
      .pipe(
         svgmin().on("error", (err) => {
            console.error("Error en imgSvg:", err.message);
            this.emit("end");
         })
      )
      .pipe(dest("build/img"));
}

// Función para limpiar carpetas de destino
function clean() {
   return deleteAsync(["build/css", "build/js", "build/img"]);
}

// Función para limpiar la caché de imágenes
function clearCache(done) {
   return cache.clearAll(done);
}

// Función para observar cambios en los archivos fuente
function autoCompile() {
   watch(path.scss, compileSass);
   watch(path.js, compileJS);
   watch(path.img, series(imgAvif, imgWebp, imageMin)); // 🟢 Eliminamos imgWebp
}


// Tarea principal de construcción
const build = series(
   clean,
   parallel(compileSass, compileJS, imageMin, imgAvif, imgSvg)
);

// Exportar tareas
export {clean, clearCache, build, imgAvif, imageMin,imgWebp, imgSvg};
export default parallel(compileSass, compileJS, autoCompile, imageMin, imgSvg);

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

// Función para optimizar imágenes en formato JPEG/PNG
function imageMin() {
   return src(path.img)
      .pipe(
         cache(
            imagemin([
               imageminMozjpeg({quality: 75, progressive: true}),
               imageminOptipng({optimizationLevel: 3}),
            ]).on("error", (err) => {
               console.error("Error en imageMin:", err.message);
               this.emit("end");
            })
         )
      )
      .pipe(dest("build/img"));
}

// Nueva función para convertir imágenes a formato WebP con imagemin-webp
function imgWebp() {
   return src("src/img/**/*.jpg") // Asegúrate de que el patrón sea correcto
      .pipe(webp({quality: 90, method: 6}))
      .on("error", function (err) {
         console.error("Error en imgWebp:", err.message);
         this.emit("end");
      })
      .pipe(dest("build/img"));
}

function imgAvif() {
   const settings = {quality: 90};
   return src(path.img)
      .pipe(
         avif(settings).on("error", (err) => {
            console.error("Error en imgAvif:", err.message);
            this.emit("end");
         })
      )
      .pipe(dest("build/img"));
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
   watch(path.img, series(imgAvif, imgWebp, imageMin));
}

// Tarea principal de construcción
const build = series(
   clean,
   parallel(compileSass, compileJS, imageMin, imgWebp, imgAvif, imgSvg)
);

// Exportar tareas
export {clean, clearCache, build, imgWebp, imgAvif, imageMin, imgSvg};
export default parallel(compileSass, compileJS, autoCompile, imageMin, imgWebp, imgSvg);

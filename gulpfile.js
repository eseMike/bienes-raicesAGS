import gulp from "gulp";
import gulpSass from "gulp-sass";
import * as sass from "sass";
import cssnano from "cssnano";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import sourcemaps from "gulp-sourcemaps";
import concat from "gulp-concat";
import terser from "gulp-terser";
import webp from "gulp-webp";
import avif from "gulp-avif";
import cache from "gulp-cache";
import svgmin from "gulp-svgmin";
import imagemin from "gulp-imagemin";
import {deleteAsync} from "del";
import imageminMozjpeg from "imagemin-mozjpeg";

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
// Importa imagemin-mozjpeg

// Función para optimizar imágenes en formato JPEG/PNG
function imageMin() {
   const settings = {
      optimizationLevel: 3,
   };

   return src(path.img)
      .pipe(
         cache(
            imagemin([
               imageminMozjpeg({quality: 75, progressive: true}),
               imagemin.optipng({optimizationLevel: 3}),
            ]).on("error", (err) => {
               console.error("Error en imageMin:", err.message);
               this.emit("end");
            })
         )
      )
      .pipe(dest("build/img"));
}

// Función para convertir imágenes a formato WebP
function imgWebp() {
   const settings = {quality: 50};
   return src(path.img)
      .pipe(
         webp(settings).on("error", (err) => {
            console.error("Error en imgWebp:", err.message);
            this.emit("end");
         })
      )
      .pipe(dest("build/img"));
}

// Función para convertir imágenes a formato AVIF
function imgAvif() {
   const settings = {quality: 50};
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
export {clean, clearCache, build};
export default parallel(compileSass, compileJS, autoCompile, imageMin, imgWebp, imgSvg);

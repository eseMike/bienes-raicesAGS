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

const {src, dest, watch, parallel} = gulp;

// Rutas del proyecto
const path = {
   scss: "src/scss/**/*.scss",
   css: "build/css/app.css",
   js: "src/js/**/*.js",
   img: "src/img/**/*.{jpg,png}",
   imgmin: "build/img/**/*.{jpg,png}",
   svg: "src/img/**/*.svg",
};

// Funciones para tareas de Gulp (como antes)
function compileSass() {
   return src(path.scss)
      .pipe(sourcemaps.init())
      .pipe(gulpSass(sass)())
      .pipe(postcss([autoprefixer(), cssnano()]))
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/css"));
}

function compileJS() {
   return src(path.js)
      .pipe(sourcemaps.init())
      .pipe(concat("bundle.js"))
      .pipe(terser())
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/js"));
}

function imageMin() {
   const settings = {
      optimizationLevel: 3,
   };

   return src(path.img)
      .pipe(cache(imagemin(settings)))
      .pipe(dest("build/img"));
}

function imgWebp() {
   const settings = {quality: 50};
   return src(path.img).pipe(webp(settings)).pipe(dest("build/img"));
}

function imgAvif() {
   const settings = {quality: 50};
   return src(path.img).pipe(avif(settings)).pipe(dest("build/img"));
}

function imgSvg() {
   return src(path.svg).pipe(svgmin()).pipe(dest("build/img"));
}

function autoCompile() {
   watch(path.scss, compileSass);
   watch(path.js, compileJS);
   watch(path.img, parallel(imgAvif, imgWebp, imageMin));
}

// Exportar tareas
export default parallel(compileSass, compileJS, autoCompile, imageMin, imgWebp, imgSvg);

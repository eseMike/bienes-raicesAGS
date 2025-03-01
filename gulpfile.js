import gulp from "gulp";
import gulpSass from "gulp-sass";
import * as sass from "sass";
import cssnano from "cssnano";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import sourcemaps from "gulp-sourcemaps";
import concat from "gulp-concat";
import terser from "gulp-terser";
import svgmin from "gulp-svgmin";
import imagemin from "gulp-imagemin";
import imageminWebp from "imagemin-webp";
import { deleteAsync } from "del";
import shell from "gulp-shell"; // ✅ Eliminamos `gulp-webp`

const { src, dest, watch, parallel, series } = gulp;

// Rutas del proyecto
const path = {
   scss: "src/scss/**/*.scss",
   js: "src/js/**/*.js",
   img: "src/img/**/*.{jpg,png}",
   svg: "src/img/**/*.svg",
};

// ✅ Función para compilar Sass con minificación
function compileSass() {
   return src(path.scss)
      .pipe(sourcemaps.init())
      .pipe(gulpSass(sass)())
      .pipe(postcss([autoprefixer(), cssnano()]))
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/css"));
}

// ✅ Función para minificar y combinar JavaScript
function compileJS() {
   return src(path.js)
      .pipe(sourcemaps.init())
      .pipe(concat("bundle.js"))
      .pipe(terser())
      .pipe(sourcemaps.write("."))
      .pipe(dest("build/js"));
}

// ✅ Función para copiar imágenes sin modificarlas
function imageMin() {
   return src(path.img)
      .pipe(dest("build/img"))
      .on("end", () => console.log("✅ Imágenes copiadas sin modificaciones"));
}

// ✅ Función para convertir imágenes a WebP usando `cwebp`
function imgWebp() {
   return shell.task([
       "mkdir -p build/img", // Asegurar que la carpeta existe
       "find src/img -type f -name '*.jpg' -exec sh -c 'cwebp -q 80 \"$1\" -o build/img/$(basename \"$1\" .jpg).webp' _ {} \\;",
       "find src/img -type f -name '*.png' -exec sh -c 'cwebp -q 80 \"$1\" -o build/img/$(basename \"$1\" .png).webp' _ {} \\;"
   ])();
}


// ✅ Función para optimizar SVGs
function imgSvg() {
   return src(path.svg)
      .pipe(svgmin())
      .pipe(dest("build/img"))
      .on("end", () => console.log("✅ SVGs optimizados"));
}

// ✅ Función para limpiar carpetas de destino
function clean() {
   return deleteAsync(["build/css", "build/js", "build/img"]);
}

// ✅ Función para observar cambios en los archivos
function autoCompile() {
   watch(path.scss, compileSass);
   watch(path.js, compileJS);
   watch(path.img, series(imageMin, imgWebp)); // Convertir imágenes a WebP después de copiarlas
   watch(path.svg, imgSvg);
}

// ✅ Tarea principal de construcción
const build = series(
   clean,
   parallel(compileSass, compileJS, imageMin, imgWebp, imgSvg)
);

// ✅ Exportar tareas
export { clean, build, imageMin, imgWebp, imgSvg };
export default parallel(compileSass, compileJS, autoCompile, imageMin, imgSvg);

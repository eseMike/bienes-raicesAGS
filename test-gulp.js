import gulp from "gulp";
import webp from "gulp-webp";

gulp.task("test-webp", () => {
   return gulp
      .src("src/img/header.jpg")
      .pipe(webp({quality: 80}))
      .pipe(gulp.dest("test-output"))
      .on("end", () => console.log("Conversión exitosa"));
});

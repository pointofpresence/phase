"use strict";

//TODO: Only needed and compress
gulp.task("images", function () {
    return gulp.src(["src/images/**/*"])
        .pipe(gulp.dest("dist/images"));
});
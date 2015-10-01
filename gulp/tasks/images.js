"use strict";

gulp.task("images", function () {
    $.mkdirp(config.imgDst);

    return gulp.src([
        config.imgSrc + "**/*.jpg",
        config.imgSrc + "**/*.jpeg",
        config.imgSrc + "**/*.gif",
        config.imgSrc + "**/*.png"
    ])
        .pipe($.imagemin())
        .pipe($.size({title: "Images"}))
        .pipe(gulp.dest(config.imgDst));
});
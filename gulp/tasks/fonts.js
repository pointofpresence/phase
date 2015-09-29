"use strict";

gulp.task("fonts", function () {
    $.mkdirp(config.fonts);

    return gulp
        .src(config.vendor.fontAwesome + "fonts/*.*")
        .pipe(gulp.dest(config.fonts));
});
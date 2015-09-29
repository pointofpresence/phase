"use strict";

gulp.task("fonts", function () {
    $.util.log("Copying fonts to " + $.chalk.magenta(config.fonts) + " ...");
    $.mkdirp(config.fonts);

    return gulp
        .src(config.fa + "/*.*")
        .pipe(gulp.dest(config.fonts));
});
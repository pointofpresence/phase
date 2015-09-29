"use strict";

gulp.task("build", function () {
    return $.runSequence(["fonts", "images", "browserify", "less", "jade", "readme"]);
});
"use strict";

gulp.task("build", function () {
    return $.runSequence(["images", "browserify", "less", "jade", "readme"]);
});
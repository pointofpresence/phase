"use strict";

gulp.task("watch", function () {
    gulp.watch(config.readMeSrc, ["readme"]);
    gulp.watch(config.package, ["readme"]);
    gulp.watch(config.jade + "**/*.jade", ["jade"]);
    gulp.watch(config.less + "**/*.less", ["less"]);
    gulp.watch(config.imgSrc + "**/*", ["images"]);
});
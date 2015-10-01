"use strict";

gulp.task("watch", function () {
    gulp.watch(config.root + config.readMeSrc, ["readme"]);
    gulp.watch(config.jade + "**/*.jade", ["jade"]);
    gulp.watch(config.less + "**/*.less", ["less"]);
    gulp.watch(config.imgSrc + "**/*", ["images"]);
});
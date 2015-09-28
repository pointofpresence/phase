"use strict";

gulp.task("watch", function () {
    gulp.watch(config.root + config.readMeSrc, ["readme"]);
    gulp.watch(config.root + config.jade + "**/*.jade", ["jade"]);
    gulp.watch(config.root + config.less + "**/*.less", ["less"]);
});
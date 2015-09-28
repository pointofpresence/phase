"use strict";

gulp.task("webServer", function () {
    return gulp
        .src(config.root)
        .pipe($.webserver(config.webServer.server));
});
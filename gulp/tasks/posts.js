"use strict";

var FS   = require("fs"),
    Path = require("path");

gulp.task("posts", function () {
    $.mkdirp(config.fonts);

    return gulp
        .src(config.vendor.fontAwesome + "fonts/*.*")
        .pipe(gulp.dest(config.fonts));
});
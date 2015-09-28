"use strict";

gulp.task("less", function () {
    var distCss = config.root + config.css,
        srcLess = config.root + config.less;

    $.mkdirp(distCss);

    return gulp
        .src(srcLess + "/main.less")
        .pipe($.less())
        .pipe($.autoprefixer({
            browsers: [
                "Android 2.3",
                "Android >= 4",
                "Chrome >= 20",
                "Firefox >= 24",
                "Explorer >= 8",
                "iOS >= 6",
                "Opera >= 12",
                "Safari >= 6"
            ]
        }))
        .pipe(isProd ? $.csso() : $.util.noop())
        .pipe($.header(banner, {pkg: pkg}))
        .pipe($.out(distCss + "/app.css"));
});
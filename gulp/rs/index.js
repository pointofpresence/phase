"use strict";

var buffer = require("vinyl-buffer"),
    source = require("vinyl-source-stream");

module.exports = {
    versionIncrement: function (pkg) {
        var v = (pkg.version || "0.0.0").split(".");

        pkg.version = [
            v[0] ? v[0] : 0,
            v[1] ? v[1] : 0,
            (v[2] ? parseInt(v[2]) : 0) + 1
        ].join(".");

        jsonHelper.write("./package.json", pkg);
    },

    dateUpdate: function (pkg) {
        var d                  = new Date();
        pkg.lastBuildDate      = dateFormat(d, "isoUtcDateTime");
        pkg.lastBuildDateHuman = dateFormat(d);

        jsonHelper.write("./package.json", pkg);
    },

    getBytes: function (bytes) {
        var whichSize = 0;
        var sizes     = ["bytes", "kb", "mb"];

        if (bytes > 1000) {
            bytes     = bytes / 1000;
            whichSize = 1;
        }

        if (bytes > 1000) {
            bytes     = bytes / 1000;
            whichSize = 2;
        }

        return $.util.log(
            $.util.colors.magenta("Bundle"),
            "is",
            $.util.colors.magenta(bytes, sizes[whichSize])
        );
    },

    rebundle: function (ids, bundler, bundleConfig) {
        if (ids) {
            $.util.log(
                $.util.colors.magenta("Rebundling"),
                $.util.colors.magenta(ids.join(", "))
            );
        } else {
            $.util.log($.util.colors.magenta("Bundling..."));
        }

        return bundler
            .bundle()
            .on("error", $.util.log)
            .on("bytes", this.getBytes)
            .on("time", this.getTime)
            .pipe(source(bundleConfig.outputName))
            .pipe(buffer())
            .pipe(isProd ? $.uglify({mangle: false}) : $.util.noop())
            .pipe(gulp.dest(bundleConfig.dest));
    },

    getTime: function (time) {
        return $.util.log(
            $.util.colors.magenta("Bundle"),
            "completed in", $.util.colors.magenta(time + " milliseconds")
        );
    }
};
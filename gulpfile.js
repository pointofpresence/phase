"use strict";

global.$ = require("gulp-load-plugins")();
$.mkdirp = require("mkdirp");
$.runSequence = require("run-sequence");
$.rs = require("./gulp/rs");

global.isProd = $.util.env.prod;
global.config = require("./gulp/config.js");
global.pkg = require("./package.json");
global.gulp = require("gulp");

global.banner = [
    '/**',
    ' * Copyright (c) <%= new Date().getFullYear() %> <%= pkg.author %>',
    ' * <%= pkg.title %> (<%= pkg.name %>) - <%= pkg.description %>',
    ' * @version v<%= pkg.version %>',
    ' * @link <%= pkg.repository %>',
    ' * @license <%= pkg.license %>',
    ' */',
    ''
].join('\n');

require("require-dir")("./gulp/tasks", {recurse: true});

gulp.task("default", function () {
    if (!isProd) {
        return $.runSequence("build", ["watch" /*, "webServer"*/]);
    }

    return $.runSequence("build" /*, "webServer"*/);
});
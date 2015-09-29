"use strict";

var browserify = require("browserify"),
    watchify   = require("watchify"),
    _          = require("lodash");

gulp.task("browserify", function () {
    var bundleConfig = config.browserify.bundleConfig;

    if (!isProd) {
        _.extend(bundleConfig, watchify.args, {debug: true});
        bundleConfig = _.omit(bundleConfig, ["external", "require"]);
    }

    var bundler = browserify(bundleConfig);

    if (!isProd) {
        bundler = watchify(bundler);

        // Rebundle when any included file is changed
        bundler.on("update", rebundle);
    } else {
        // Sort out shared dependencies.
        // bundler.require exposes modules externally
        if (bundleConfig.require) {
            bundler.require(bundleConfig.require);
        }

        // bundler.external excludes modules from the bundle, and expects
        // they'll be available externally
        if (bundleConfig.external) {
            bundler.external(bundleConfig.external);
        }
    }

    function rebundle(ids) {
        return $.rs.rebundle(ids, bundler, bundleConfig);
    }

    return rebundle();
});
var gulp       = require("gulp"),
    dateFormat = require("dateformat");

global.$ = require("gulp-load-plugins")();
$.mkdirp = require("mkdirp");
$.chalk  = require("chalk");

//noinspection JSUnresolvedVariable
global.isProd = $.util.env.prod;

var NODE       = "./node_modules",
    BOWER      = NODE + "/startbootstrap-sb-admin-2/bower_components",
    SRC        = "./src",
    SRC_JS     = SRC + "/js",
    SRC_LESS   = SRC + "/less",
    DIST       = "./dist",
    DIST_JS    = DIST + "/js",
    DIST_CSS   = DIST + "/css",
    DIST_FONTS = DIST + "/fonts";

var pkg = require('./package.json');

var banner = [
    '/**',
    ' * Copyright (c) <%= dateFormat(now, "yyyy") %> <%= pkg.author %>',
    ' * <%= pkg.name %> - <%= pkg.description %>',
    ' * @version v<%= pkg.version %>',
    ' */',
    ''
].join('\n');

function buildFonts() {
    $.util.log("Copying fonts to " + $.chalk.magenta(DIST_FONTS) + " ...");
    $.mkdirp(DIST_FONTS);

    return gulp
        .src(BOWER + "/font-awesome/fonts/*.*")
        .pipe($.size({title: "Fonts"}))
        .pipe(gulp.dest(DIST_FONTS));
}

function buildJs() {
    $.util.log("Bundling scripts to " + $.chalk.magenta(DIST_JS) + " ...");
    $.mkdirp(DIST_JS);

    return gulp
        .src([
            // Vendor
            BOWER + "/jquery/dist/jquery.js",
            NODE + "/underscore/underscore.js",
            NODE + "/backbone/backbone.js",
            BOWER + "/bootstrap/dist/js/bootstrap.js",
            BOWER + "/metisMenu/dist/metisMenu.js",
            BOWER + "/raphael/raphael.js",
            BOWER + "/morrisjs/morris.js",
            NODE + "/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js",
            NODE + "/summernote/dist/summernote.js",

            // App
            SRC_JS + "/mixins.js",
            SRC_JS + "/models/**/*.js",
            SRC_JS + "/collections/**/*.js",
            SRC_JS + "/views/**/*.js",
            SRC_JS + "/main.js"
        ])
        .pipe(!isProd ? $.sourcemaps.init({loadMaps: true}) : $.util.noop())
        .pipe($.concat("bundle.js"))
        .pipe(isProd ? $.uglify() : $.util.noop())
        .pipe($.header(banner, {pkg: pkg, dateFormat: dateFormat, now: new Date()}))
        .pipe(!isProd ? $.sourcemaps.write() : $.util.noop())
        .pipe($.size({title: "JS"}))
        .pipe(gulp.dest(DIST_JS));
}

function buildLess() {
    $.util.log("Bundling styles to " + $.chalk.magenta(DIST_CSS) + " ...");
    $.mkdirp(DIST_CSS);

    return gulp
        .src(
        SRC_LESS + "/main.less"
    )
        .pipe(!isProd ? $.sourcemaps.init({loadMaps: true}) : $.util.noop())
        .pipe($.less())
        .on("error", $.notify.onError({
            message: "LESS error: <%= error.message %>"
        }))
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
        .pipe($.header(banner, {pkg: pkg, dateFormat: dateFormat, now: new Date()}))
        .pipe(!isProd ? $.sourcemaps.write() : $.util.noop())
        .pipe($.size({title: "CSS"}))
        .pipe($.out(DIST_CSS + "/bundle.css"));
}

function buildJade() {
    $.util.log("Compiling HTML...");

    var opts = {
        conditionals: true,
        spare:        true,
        empty:        true,
        cdata:        true,
        quotes:       true,
        loose:        false
    };

    return gulp.src(SRC + "/jade/index.jade")
        .pipe($.jade({pretty: !$.isProd}))
        .pipe(isProd ? $.minifyHtml(opts) : $.util.noop())
        .pipe($.size({title: "HTML"}))
        .pipe($.out("./index.html"));
}

gulp.task("build_jade", buildJade);
gulp.task("build_js", buildJs);
gulp.task("build_less", buildLess);
gulp.task("build_fonts", buildFonts);

gulp.task("build", function () {
    buildFonts();
    buildJs();
    buildJade();
    buildLess();
});

gulp.task("watch", ["build"], function () {
    gulp.watch(SRC + "/jade/index.jade", ["build_jade"]);
    gulp.watch(SRC_JS + "/**/*.js", ["build_js"]);
    gulp.watch(SRC_LESS + "/**/*.less", ["build_less"]);
});

gulp.task("default", ["build"]);


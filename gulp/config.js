var root  = "./",
    jsSrc = "src/js/",
    jsDst = "dist/js/";

module.exports = {
    "root":      root,
    "jade":      "src/jade/",
    "less":      "src/less/",
    "css":       "dist/css/",
    "readMeSrc": "src/README.md",
    "readMeDst": "README.md",
    "jsSrc":     jsSrc,
    "jsDst":     jsDst,
    "webServer": {
        "server": {
            "livereload": true,
            "port":       1282,
            "open":       false,
            "fallback":   "index.html"
        }
    },
    browserify:  {
        bundleConfig: {
            entries:    root + jsSrc + "main.js",
            dest:       root + jsDst,
            outputName: "bundle.js",
            // list of modules to make require-able externally
            require: ["jquery"]
            // See https://github.com/greypants/gulp-starter/issues/87 for note about
            // why this is 'backbone/node_modules/underscore' and not 'underscore'
            //
            // list of externally available modules to exclude from the bundle
            // external:   ['jquery', 'underscore']
        },
        workerConfig: {
            entries:    root + jsSrc + "workercjs.js",
            dest:       root + jsDst,
            outputName: "worker.js"
            // list of modules to make require-able externally
            //require: ["jquery"]
            // See https://github.com/greypants/gulp-starter/issues/87 for note about
            // why this is 'backbone/node_modules/underscore' and not 'underscore'
            //
            // list of externally available modules to exclude from the bundle
            // external:   ['jquery', 'underscore']
        }
    }
};
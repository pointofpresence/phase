var root  = "./",
    src   = root + "src/",
    dist  = root + "dist/",
    jsSrc = src + "js/",
    jsDst = dist + "js/",
    node  = root + "node_modules/";

module.exports = {
    "root":      root,
    "jade":      src + "jade/",
    "less":      src + "less/",
    "css":       dist + "css/",
    "fonts":     dist + "fonts/",
    "readMeSrc": "src/README.md",
    "readMeDst": "README.md",
    "jsSrc":     jsSrc,
    "jsDst":     jsDst,
    "imgSrc":    src + "images/",
    "imgDst":    dist + "images/",
    "vendor":    {
        "bootstrap":   node + "bootstrap/",
        "fontAwesome": node + "font-awesome/"
    },
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
            entries:    jsSrc + "main.js",
            dest:       jsDst,
            outputName: "bundle.js",
            // list of modules to make require-able externally
            require:    ["jquery"]
            // See https://github.com/greypants/gulp-starter/issues/87 for note about
            // why this is 'backbone/node_modules/underscore' and not 'underscore'
            //
            // list of externally available modules to exclude from the bundle
            // external:   ['jquery', 'underscore']
        }
    }
};
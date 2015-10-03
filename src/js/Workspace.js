"use strict";

var Backbone = require("backbone"),
    Content  = require("./views/Content");

module.exports = Backbone.Router.extend({
    routes: {
        "":            "home",
        "!home":       "home",
        "!about":      "about",
        "!contact":    "contact",
        "!video":      "video",
        "!post/:slug": "post",

        '*notFound': 'notFound'
    },

    initialize: function () {
        this.content = new Content;
    },

    home: function () {
        this.content.render("home");
    },

    about: function () {
        this.content.render("about");
    },

    contact: function () {
        this.content.render("contact");
        require("./lib/Validation");
    },

    video: function () {
        var VideoCollection = require("./collections/Video"),
            collection      = new VideoCollection();

        collection.fetch({
            success: (function (_collection) {
                this.content.render("video", _collection);
            }).bind(this)
        });
    },

    post: function (slug) {
    },

    notFound: function () {
        this.content.render("notfound");
    }
});

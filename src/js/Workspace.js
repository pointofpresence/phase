"use strict";

var Backbone = require("backbone"),
    Content  = require("./views/Content");

module.exports = Backbone.Router.extend({
    routes: {
        "":         "home",
        "home":     "home",
        "about":    "about",
        "contact":  "contact",
        "video":    "video",
        "post/:id": "post",

        '*notFound': "notFound"
    },

    initialize: function () {
        this.content = new Content;
    },

    home: function () {
        var PostCollection = require("./collections/Post"),
            collection     = new PostCollection();

        collection.fetch({
            success: (function (_collection) {
                this.content.render("home", _collection);
            }).bind(this)
        });
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

    post: function (id) {
        var PostModel = require("./models/Post"),
            model     = new PostModel({id: id});

        model.fetch({
            success: (function (_model) {
                this.content.render("post", _model);
            }).bind(this)
        });
    },

    notFound: function () {
        this.content.render("notfound");
    }
});

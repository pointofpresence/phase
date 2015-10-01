"use strict";

var Backbone = require("backbone"),
    Content  = require("./views/Content");

module.exports = Backbone.Router.extend({
    routes: {
        "":            "home",
        "!home":       "home",
        "!about":      "about",
        "!contact":    "contact",
        "!post":       "list",
        "!post/:slug": "post"
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

    list: function () {
    },

    post: function (slug) {
    }
});

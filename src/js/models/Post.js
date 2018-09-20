"use strict";

var Backbone = require("backbone"),
    _        = require("underscore");

module.exports = Backbone.Model.extend({
    sync: function (method, model, options) {
        options = options || {};

        switch (method) {
            case "read":
                options.url = this.url() + "/?id=" + this.get("id");
        }

        Backbone.sync(method, model, options);
    },

    url: function () {
        return "/api/post";
    }
});
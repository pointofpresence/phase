"use strict";

var Backbone  = require("backbone"),
    templates = require("../templates");

Backbone.$ = jQuery;

module.exports = Backbone.View.extend({
    el: "#content",

    render: function (template, data) {
        console.log(template)
        this.$el.html(templates[template]({data: data}));
        return this;
    }
});
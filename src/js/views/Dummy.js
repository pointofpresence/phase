"use strict";

var Backbone  = require("backbone"),
    _         = require("underscore"),
    templates = require("../templates");

Backbone.$ = jQuery;

module.exports = Backbone.View.extend({
    el: "#dummy",

    initialize: function () {
        console.log("Wizard::initialize")
    },

    events: {},

    render: function () {
        var html = templates.options.Select({
            id: id
        });
    }
});
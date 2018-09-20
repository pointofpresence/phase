"use strict";

global.jQuery = require("jquery");
global.$      = jQuery;

var Backbone  = require("backbone"),
    Workspace = require("./Workspace"),
    bootstrap = require("bootstrap");

require("./lib/Navigation");

$(function () {
    // fixes
    $(document).on("click", "a[href=#]", function (e) {
        e.preventDefault();
    });

    // bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on("click", "a[data-toggle=\"tab\"]", function (e) {
        e.preventDefault();
        $(this).tab("show");
    });

    new Workspace;
    Backbone.history.start({pushState: true});
});
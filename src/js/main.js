"use strict";

global.jQuery = require("jquery");
global.$      = jQuery;

var Backbone = require("backbone"),
    Workspace = require("./Workspace"),
    bootstrap = require("bootstrap");

require("./lib/Navigation");

$(function () {
    // # fix
    $(document).on("click", "a[href=#]", function (e) {
        e.preventDefault();
    });

    // bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    new Workspace();
    Backbone.history.start();
});
"use strict";

global.jQuery = require("jquery");
global.$      = jQuery;

var WizardView = require("./views/Wizard"),
    bootstrap  = require("bootstrap");

//    "share",
require("./lib/Fileinput");

$(function () {
    // # fix
    $(document).on("click", "a[href=#]", function (e) {
        e.preventDefault();
    });

    // bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    $("#wizard").show();

    new WizardView;
});
"use strict";

var Backbone   = require("backbone"),
    PostModel = require("../models/Post");

module.exports = Backbone.Collection.extend({
    model: PostModel,
    url:   "/api/list"
});
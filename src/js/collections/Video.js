"use strict";

var Backbone   = require("backbone"),
    VideoModel = require("../models/Video");

module.exports = Backbone.Collection.extend({
    model: VideoModel,
    url:   "/api/video.json"
});
var app = (typeof app == "undefined") ? {} : app;

app.VideosCollection = Backbone.Collection.extend({
    model: app.VideoModel,

    initialize: function (options) {
        this.api = options.api || "";
    },

    url: function () {
        return this.api + "/admin_videos";
    }
});
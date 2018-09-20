var app = (typeof app == "undefined") ? {} : app;

app.PostsCollection = Backbone.Collection.extend({
    model: app.PostModel,

    initialize: function (options) {
        this.api = options.api || "";
    },

    url: function () {
        return this.api + "/admin_posts";
    }
});
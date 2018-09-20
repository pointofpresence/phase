var app = (typeof app == "undefined") ? {} : app;

app.PostModel = Backbone.Model.extend({
    defaults: {
        status: 0
    },

    sync: function (method, model, options) {
        options = options || {};

        switch (method) {
            case "read":
            case "update":
                options.url = this.url() + "/?id=" + this.get("id");
        }

        Backbone.sync(method, model, options);
    },

    url: function () {
        return app.router.api + "/admin_post";
    }
});
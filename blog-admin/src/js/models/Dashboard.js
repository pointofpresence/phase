var app = (typeof app == "undefined") ? {} : app;

app.DashboardModel = Backbone.Model.extend({
    initialize: function(options) {
        this.api = options.api || "";
    },

    url: function () {
        return this.api + "/admin_dashboard";
    }
});
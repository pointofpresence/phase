var app = (typeof app == "undefined") ? {} : app;

app.DashboardView = Backbone.View.extend({
    id: "home-page",

    initialize: function (options) {
        this.$parent = options.parent;
        this.api = options.api;

        this.model.fetch();
        this.listenTo(this.model, "change", this.render);
    },

    render: function () {
        console.log(this.model.toJSON())

        $(this.el).html(this.template(this.model.toJSON()));
        this.$parent.html(this.el);
        return this;
    }
});
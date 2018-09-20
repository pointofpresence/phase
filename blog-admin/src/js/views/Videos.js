var app = (typeof app == "undefined") ? {} : app;

app.VideosView = Backbone.View.extend({
    id: "videos-page",

    events: {
        "click .action.restore": "restore",
        "click .action.decline": "decline"
    },

    initialize: function (options) {
        this.api = options.api;

        this.collection.fetch();
        this.listenTo(this.collection, "update", this.render);
    },

    render: function () {
        this.$el.html(this.template({items: this.collection}));
        return this;
    },

    restore: function (e) {
        e.preventDefault();

        var el = e.currentTarget,
            id = el.dataset.id;

        if (!id) {
            return
        }

        var model = this.collection.get(id);
        this.listenTo(model, "sync", this.render);
        model.save({status: 1});
    },

    decline: function (e) {
        e.preventDefault();

        var el = e.currentTarget,
            id = el.dataset.id;

        if (!id) {
            return
        }

        var model = this.collection.get(id);
        this.listenTo(model, "sync", this.render);
        model.save({status: 0});
    }
});
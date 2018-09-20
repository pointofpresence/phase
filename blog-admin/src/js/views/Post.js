var app = (typeof app == "undefined") ? {} : app;

app.PostView = Backbone.View.extend({
    id: "post-page",

    events: {
        "click .action.save": "save"
    },

    initialize: function (options) {
        this.api     = options.api;
        this.$parent = options.$parent;

        if (this.model.id) {
            this.model.fetch();
        } else {
            this.render();
        }

        this.listenTo(this.model, "change", this.render);
    },

    render: function () {
        this.$el.html(this.template({item: this.model.toJSON()}));
        this.$parent.empty();
        this.$parent.html(this.el);

        this.$('#teaser').summernote({minHeight: 350, styleWithSpan: false});
        this.$('#body').summernote({minHeight: 350, styleWithSpan: false});

        return this;
    },

    save: function (e) {
        e.preventDefault();

        this.model.save({
            title:  this.$('#title').val(),
            teaser: this.$('#teaser').code(),
            body:   this.$('#body').code()
        });
    }
});
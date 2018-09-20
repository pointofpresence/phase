var app = (typeof app == "undefined") ? {} : app;

app.LayoutView = Backbone.View.extend({
    id: "layout",

    events: {"click a[href='#cache']": "onCacheClick"},

    onCacheClick: function (e) {
        e.preventDefault();

        if (!confirm("Are you sure?")) {
            return;
        }

        $.get("/api/recache", function(data){
            alert(data);
        })
    },

    render: function () {
        $(this.el).html(this.template());
        return this;
    }
});
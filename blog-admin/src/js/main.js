var app = (typeof app == "undefined") ? {} : app;

// Tell jQuery to watch for any 401 or 403 errors and handle them appropriately
$.ajaxSetup({
    statusCode: {
        401: function () {
            // Redirect the to the login page.
            window.location.replace('#login');

        },
        403: function () {
            // 403 -- Access denied
            window.location.replace('#denied');
        }
    }
});

window.Router = Backbone.Router.extend({
    api: "/api",

    routes: {
        "":          "home",
        "home":      "home",
        "login":     "login",
        "logout":    "logout",
        "posts":     "posts",
        "post/add":  "postAdd",
        "post/:id":  "post",
        "videos":    "videos",
        "video/add": "videoAdd",
        "video/:id": "video"
    },

    initialize: function () {
        this.$wrapper = $("#wrapper");
    },

    execute: function (callback, args) {
        $.ajax({
            url:      this.api + "/admin_auth",
            type:     "POST",
            dataType: "json",
            success:  (function (data) {
                if (data.result) {
                    if (!this.$wrapper.find("#layout").length) {
                        this.$wrapper.html(new app.LayoutView().render().el);
                        this.$layout = $("#page-wrapper");
                    }
                }

                this.logged = data.result;

                if (callback) {
                    callback.apply(this, args);
                }
            }).bind(this)
        });
    },

    _navigate: function (to) {
        this.navigate(to, {
            trigger: true
        });
    },

    home: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        new app.DashboardView({
            parent: this.$layout,
            api:    this.api,
            model:  new app.DashboardModel({api: this.api})
        });
    },

    login: function () {
        if (this.logged) {
            this._navigate("/");
            return;
        }

        this.$wrapper.html(new app.LoginView({api: this.api}).render().el);
    },

    logout: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        $.ajax({
            url:      this.api,
            type:     "GET",
            dataType: "json",
            data:     {action: "Admin_logout"},
            success:  (function () {
                this._navigate("/");
            }).bind(this)
        });
    },

    posts: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        this.$layout.html(new app.PostsView({
            parent:     this.$layout,
            api:        this.api,
            collection: new app.PostsCollection({api: this.api})
        }).render().el);
    },

    postAdd: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        var view = new app.PostView({
            $parent: this.$layout,
            model:   new app.PostModel
        });
    },

    post: function (id) {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        var view = new app.PostView({
            $parent: this.$layout,
            model:   new app.PostModel({
                id: id
            })
        });
    },

    videos: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        this.$layout.html(new app.VideosView({
            parent:     this.$layout,
            api:        this.api,
            collection: new app.VideosCollection({api: this.api})
        }).render().el);
    },

    videoAdd: function () {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        new app.VideoView({
            $parent: this.$layout,
            model:   new app.VideoModel
        });
    },

    video: function (id) {
        if (!this.logged) {
            this._navigate("/login");
            return;
        }

        new app.VideoView({
            $parent: this.$layout,
            model:   new app.VideoModel({
                id: id
            })
        });
    }
});

_.templateLoad([
        "LoginView",
        "LayoutView",
        "DashboardView",
        "PostsView",
        "PostView",
        "VideosView",
        "VideoView"
    ], function () {
        app.router = new Router();
        Backbone.history.start(/*{pushState: true}*/);
    }, null, app
);
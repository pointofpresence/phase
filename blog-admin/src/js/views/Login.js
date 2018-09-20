var app = (typeof app == "undefined") ? {} : app;

app.LoginView = Backbone.View.extend({
    initialize: function (options) {
        this.api = options.api;
    },

    events: {
        "click #loginButton": "login"
    },

    render: function () {
        $(this.el).html(this.template());
        return this;
    },

    login: function (e) {
        e.preventDefault();

        $(".alert").hide();

        var formValues = {
            username:  this.$("[name=username]").val(),
            password:  this.$("[name=password]").val(),
            user_type: "admin",
            device_id: null
        };

        $.ajax({
            url:      this.api + "/login",
            type:     "POST",
            dataType: "json",
            data:     formValues,
            success:  function (data) {
                if (data.error) {
                    $(".alert").text(data.error.text).show();
                } else {
                    var url = "/";

                    Backbone.history.navigate(url, {
                        trigger: true
                    });
                }
            }
        });
    }
});
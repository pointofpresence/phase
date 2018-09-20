_.mixin({
    /**
     * @param {string} selector
     * @returns {Function}
     */
    t: function (selector) {
        var $container = $(selector);

        if (!$container || !$container.length) {
            console.log("Template " + selector + " not found.");
            return null;
        }

        return this.template($container.html());
    },

    /**
     * @param {Event} e
     * @returns {jQuery}
     */
    $: function (e) {
        return $(e.currentTarget);
    },

    /**
     * @returns {boolean}
     */
    isiPhone: function () {
        return (
        (navigator.platform.indexOf("iPhone") != -1) ||
        (navigator.platform.indexOf("iPod") != -1)
        );
    },

    /**
     * @returns {boolean}
     */
    isiPad: function () {
        return (navigator.platform.indexOf("iPad") != -1);
    },

    guid: function () {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    },

    /**
     * var someHtml = _.templateFromUrl("http://example.com/template.html", {"var": "value"});
     *
     * @param url
     * @param data
     * @param settings
     * @returns {*}
     */
    templateFromUrl: function (url, data, settings) {
        var templateHtml = "";
        this.cache = this.cache || {};

        url = "/media/templates/" + url + ".ejs";

        if (this.cache[url]) {
            templateHtml = this.cache[url];
        } else {
            $.ajax({
                url:     url,
                method:  "GET",
                async:   false,
                success: function (data) {
                    templateHtml = data;
                }
            });

            this.cache[url] = templateHtml;
        }

        return _.template(templateHtml, data, settings);
    },

    loadImageWithFallback: function (src, fallbackSrc) {
        var image = _.extend(new Image(), Backbone.Events);

        image.onload = function () {
            this.trigger('load');
        };

        image.onerror = function () {
            if (image.src !== fallbackSrc) {
                image.src = fallbackSrc;
            }
            this.trigger('error');
        };

        // let others hook their event listeners first
        setTimeout(function () {
            image.src = src;
        }, 0);

        return image;
    },

    loadImage: function (src) {
        var image = _.extend(new Image(), Backbone.Events);

        image.onload = function () {
            this.trigger('load');
        };

        image.onerror = function () {
            this.trigger('error');
        };

        // let others hook their event listeners first
        setTimeout(function () {
            image.src = src;
        }, 0);

        return image;
    },

    templateLoad: function (views, callback, path, scope) {
        var deferreds = [];

        scope = scope || window;
        path = path || 'tpl/';

        $.each(views, function (index, view) {
            if (scope[view]) {
                deferreds.push(
                    $.get((path) + view + '.ejs', function (data) {
                        scope[view].prototype.template = _.template(data);
                    }, 'html')
                );
            } else {
                alert(view + " not found");
            }
        });

        $.when.apply(null, deferreds).done(callback);
    }
});
/**
 * Created with JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 2:37 AM
 * To change this template use File | Settings | File Templates.
 */

Binder = function() {
    this.clearBtn = $("#clearCache");
    this.loadBtn = $(".dbLoad");
}

Binder.prototype.initEvents = function InitEvents() {
    var self = this;
    this.clearBtn.click(function() {
        self.clearCache();
    })
    this.loadBtn.click(function() {
        self.databaseLoad();
    })
    $("#progressbar").progressbar({
        value: true,
        change: function() {
            $(".progress-label").text($("#progressbar").progressbar("option", "value") + "%");
        },
        complete: function() {
            $(".progress-label").text("Complete!");
        }
    });


}

Binder.prototype.clearCache = function ClearCache() {
    $.ajax({
        type: "POST",
        url: "/binder/clear/"
    }).done(function(){
            window.location.replace("http://symfony.union-progress.com/binder/");
        });
}

Binder.prototype.databaseLoad = function DatabaseLoad() {
    $.ajax({
        type: "POST",
        url: "/binder/load/"
    }).done(function() {
            $.ajax({
                type: "GET",
                url: "/binder/"
            });
            process();
        });

    function process() {
        $.ajax({
            type: "GET",
            url: "/binder/check/dbLoading",
            async: true
        }).done(function(msg) {
                var arr = JSON.parse(msg);
                var val = Math.round((arr[0] / arr[1]) * 100);
                $("#progressbar").progressbar("option", "value", val);
                if (val < 99) {
                    console.log(val);
                    setTimeout(process, 200);
                } else {
                    window.location.replace("http://symfony.union-progress.com/binder/");
                }
            }).fail(function(msg) {
                console.log('Fails!!!' + msg);
            })
    }
}
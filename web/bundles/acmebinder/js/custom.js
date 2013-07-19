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
    this.approve = $('.approve');
    this.ebayTemp = $("#ebay_temp tr")
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

    this.approve.click(function() {
        self.setApprove($(this).parent().parent(), $(this).attr("class").split(' '));
    });

    this.ebayTemp.click(function() {
        self.ebayCheck($(this));
    })

    this.ebayTemp.hover(function(){
        $(this).css("background-color", "#E6E3E3");
    }, function() {
        $(this).css("background-color", "#FFFFFF");
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
                process();
            })
    }
}

Binder.prototype.setApprove = function SetApprove(block, approve) {
    console.log(block);
    if (approve[1] == 'decline') {
        block.css("background", "#ff7b78");
    } else {
        block.css("background", "#ffffff");
    }
    var url = "/binder/changeapprove/" + block.find($("td#upc")).text();
    $.ajax({
        type: "POST",
        url: url,
        data: { approve: approve[1]}
    }).done(function(msg) {
            console.log(msg);
        })
}

Binder.prototype.ebayCheck = function EbayCheck(block) {
    console.log(block);
    var upc = block.find($("td.itemid")).attr("upc");
    var itemId = block.find($("td.itemid")).text();

    console.log(upc);
    console.log(itemId);
}
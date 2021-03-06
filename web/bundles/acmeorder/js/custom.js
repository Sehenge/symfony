/**
 * Created with JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 2:37 AM
 * To change this template use File | Settings | File Templates.
 */

Order = function() {
    this.approve = $('.approve');
}

Order.prototype.initEvents = function InitEvents() {
    var self = this;
    $("#contact_form .symfony-button-grey").remove();
    $("#contact_form").append('<button type="submit" class="sf-button">' +
        '<span class="border-l">' +
        '<span class="border-r">' +
        '<span class="btn-bg">Check Availability</span>' +
        '</span>' +
        '</span>' +
        '</button>');

    $('.model_img').click(function() {
        $(this).empty();
        $(this).append('<img src="' + $(this).attr("rel") + '" width="400px"/>');
    });

    $("span").each(function(){
        if ($(this).css("background-color") != "rgba(0, 0, 0, 0)") {
            $(this).parent().css("background-color", $(this).css("background-color"));
            $(this).css("background-color", "transparent");
        }
    });

    $(".inner").hover(
        function () {
            var rel = $(this).find($("span[rel]")).attr("rel");
            $(this).find($("span[rel]")).append($('<span style="display:inline;margin:0;color:green;font-weight:bold;"> ' + rel + '</span>'));
        },
        function () {
            $(this).find("span:last").remove();
        }
    );

    this.approve.click(function() {
        self.setApprove($(this).parent(), $(this).attr("class").split(' '));
    });

    /*$("span a").each(function() {
        if ($(this).text().length < 2) {
            $(this).parent().parent().remove();
        }
    })*/
}

Order.prototype.setApprove = function SetApprove(block, approve) {
    console.log(block);
    if (approve[1] == 'decline') {
        block.css("background-color", "rgba(255, 0, 0, 1)");
    } else if (approve[1] == 'accept') {
        block.css("background-color", "rgba(0, 0, 0, 0)");
    }
    var asin = block.find($("a")).text();
    var url = "/order/amazonalert/" + asin;
    $.ajax({
        type: "POST",
        url: url,
        data: { approve: approve[1]}
    }).done(function(msg) {
            console.log(msg);
        })
}

function getModelsByBrand(brand) {
    $.ajax({
        type: "POST",
        url: "/order/getmodels/",
        data: { brand: brand }
    }).done(function( msg ) {
            //console.log(msg);
            var array = JSON.parse(msg);
            var models = '<div class="sdiv"><span class="slabel">Model</span><select id=models onchange="getColorByModel($(this).val())"><option></option>';
            array['models'].forEach(function(a){
               models += '<option>' + a['model'] + '</option>';
            });
            models += '</select></div>';
            $("#models").parent().remove();
            $("#colors").parent().remove();
            $("#sizes").parent().remove();
            $("#brands").parent().after(models);
            $(".symfony-content.timeline").parent().find('h2').remove();
            $(".symfony-content.timeline").empty().before('<h2>' + brand + '</h2>');
            array['timeline'].forEach(function(a) {
                $(".symfony-content.timeline").append(
                    a['model'] + ' - ' +
                    a['color_code'] + ' - ' +
                    a['size'] + ' - ' +
                    a['availability'] + ' - ' +
                    a['event_date']['date'] + '<br />'
                );
            });
        });
}

function getColorByModel(model) {
    if (model == '') {
        getModelsByBrand($("#brands").val());
    } else {

        $.ajax({
            type: "POST",
            url: "/order/getmodels/",
            data: { model: model }
        }).done(function( msg ) {
                //console.log(msg);
                var array = JSON.parse(msg);
                var colors = '<div class="sdiv"><span class="slabel">Color</span><select id=colors onchange="getSizeByColor($(this).val(), \'' + model + '\')"><option></option>';
                array['models'].forEach(function(a){
                    colors += '<option>' + a['color_code'] + '</option>';
                });
                colors += '</select></div>';
                $("#colors").parent().remove();
                $("#sizes").parent().remove();
                $("#models").parent().after(colors);
                $(".symfony-content.timeline").parent().find('h2').remove();
                $(".symfony-content.timeline").empty().before('<h2>' + array['timeline'][0]['brand'] + ' -> ' + model + '</h2>');
                array['timeline'].forEach(function(a) {
                    $(".symfony-content.timeline").append(
                        a['model'] + ' - ' +
                            a['color_code'] + ' - ' +
                            a['size'] + ' - ' +
                            a['availability'] + ' - ' +
                            a['event_date']['date'] + '<br />'
                    );
                });
            });
    }
}

function getSizeByColor(color, model) {
    $.ajax({
        type: "POST",
        url: "/order/getmodels/",
        data: { color: color, model: model }
    }).done(function( msg ) {
            console.log(msg);
            var array = JSON.parse(msg);
            var sizes = '<div class="sdiv"><span class="slabel">Size</span><select id=sizes onchange="getModelsBySize($(this).val())"><option></option>';
            array['models'].forEach(function(a){
                sizes += '<option>' + a['size'] + '</option>';
            });
            sizes += '</select></div>';
            $("#sizes").parent().remove();
            $("#colors").parent().after(sizes);
            $(".symfony-content.timeline").parent().find('h2').remove();
            $(".symfony-content.timeline").empty().before('<h2>' + array['timeline'][0]['brand'] + ' -> ' + array['timeline'][0]['model'] + ' -> ' + array['timeline'][0]['color_code'] + '</h2>');
            array['timeline'].forEach(function(a) {
                $(".symfony-content.timeline").append(
                    a['model'] + ' - ' +
                    a['color_code'] + ' - ' +
                    a['size'] + ' - ' +
                    a['availability'] + ' - ' +
                    a['event_date']['date'] + '<br />'
                );
            });
        });
}

function getModelsBySize(size) {
    var model = $("#models").val();
    var color = $("#colors").val();
    $.ajax({
        type: "POST",
        url: "/order/getmodels/",
        data: { size: size, model: model, color: color }
    }).done(function( msg ) {
            console.log(msg);
            var array = JSON.parse(msg);
            $(".symfony-content.timeline").parent().find('h2').remove();
            $(".symfony-content.timeline").empty().before('<h2>' + array['timeline'][0]['brand'] + ' -> ' + array['timeline'][0]['model'] + ' -> ' + array['timeline'][0]['color_code'] + ' -> ' + array['timeline'][0]['size'] + '</h2>');
            array['timeline'].forEach(function(a) {
                $(".symfony-content.timeline").append(
                    a['model'] + ' - ' +
                    a['color_code'] + ' - ' +
                    a['size'] + ' - ' +
                    a['availability'] + ' - ' +
                    a['event_date']['date'] + '<br />'
                );
            });
        });
}
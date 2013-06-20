/**
 * Created with JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 2:37 AM
 * To change this template use File | Settings | File Templates.
 */

$(window).load(function() {
    $("button").click(function(){
        $(".windows8").show();
    });
})

function getEwcFeed() {
    $.ajax({
        type: "POST",
        url: "/sync/getewc/",
        data: { site: 'ewc' }
    }).done(function( msg ) {
            console.log(msg);
            $(".ewcfeedcheck").text(msg).css("color", "green");
            $(".windows8").hide();
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}

function importToExb() {
    $.ajax({
        type: "POST",
        url: "/sync/importexb/",
        data: { site: 'exb' }
    }).done(function( msg ) {
            console.log(msg);
            $(".exbfeedcheck").text(msg).css("color", "green");
            $(".windows8").hide();
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}

function importToShdx() {
    $.ajax({
        type: "POST",
        url: "/sync/importshdx/",
        data: { site: 'shdx' }
    }).done(function( msg ) {
            console.log(msg);
            $(".shdxfeedcheck").text(msg).css("color", "green");
            $(".windows8").hide();
            window.open("http://www.shadesexpo.com/converter/import_all_products.csv");
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}

function syncExbWStatus() {
    $.ajax({
        type: "POST",
        url: "/sync/syncexbw/",
        data: { site: 'exbw' }
    }).done(function( msg ) {
            console.log(msg);
            $(".exbwsynccheck").text(msg).css("color", "green");
            $(".windows8").hide();
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}

function syncExbStatus() {
    $.ajax({
        type: "POST",
        url: "/sync/syncexb/",
        data: { site: 'exb' }
    }).done(function( msg ) {
            console.log(msg);
            $(".exbsynccheck").text(msg).css("color", "green");
            $(".windows8").hide();
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}

function syncShdxStatus() {
    $.ajax({
        type: "POST",
        url: "/sync/syncshdx/",
        data: { site: 'shdx' }
    }).done(function( msg ) {
            console.log(msg);
            $(".shdxsynccheck").text(msg).css("color", "green");
            $(".windows8").hide();
        }).fail(function(){
            $(".exbfeedcheck").text('Error').css("color", "red");
            $(".windows8").hide();
        });
}
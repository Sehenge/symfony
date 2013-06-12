/**
 * Created with JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 2:37 AM
 * To change this template use File | Settings | File Templates.
 */

$(window).load(function() {
})

function getEwcFeed() {
    $.ajax({
        type: "POST",
        url: "/sync/getewc/",
        data: { site: 'ewc' }
    }).done(function( msg ) {
            console.log(msg);
            $(".ewcfeedcheck").text(msg).css("color", "green");
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
            window.open("http://www.shadesexpo.com/converter/import_all_products.csv");
        });
}

function importToExbW() {
    $.ajax({
        type: "POST",
        url: "/sync/importexbw/",
        data: { site: 'exbw' }
    }).done(function( msg ) {
            console.log(msg);
            $(".exbwfeedcheck").text(msg).css("color", "green");
        });
}
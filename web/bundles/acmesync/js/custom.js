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
    $(".exbfeedcheck").text('Successfully imported to Exboutique').css("color", "green");
}

function importToShdx() {
    $(".shdxfeedcheck").text('Successfully imported to Shadesexpo').css("color", "green");
}

function importToExbW() {
    $(".exbwfeedcheck").text('Successfully imported to Exboutique (watches)').css("color", "green");
}
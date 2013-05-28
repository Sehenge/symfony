/**
 * Created with JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 2:37 AM
 * To change this template use File | Settings | File Templates.
 */

$(window).load(function() {
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
    })
})

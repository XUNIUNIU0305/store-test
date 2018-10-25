$(function() {
    var type = parseInt(url('?type'));
    $('.nav-tabs li a').eq(type).click();
}())
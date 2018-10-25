/**
 * Created by Administrator on 2018/8/23.
 */
$(function(){
    var type = window.location.search.split("=")[1];
    if(type == 1) {
        $("#ziti").removeClass("hidden");
    }

    if(type == 2) {
        $("#songhuo").removeClass("hidden");
    }
})
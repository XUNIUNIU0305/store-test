$(function() {
    $("#service-policy-detail-wrap").hide();
    // 服务说明详情的显示隐藏
    $("#service-policy-more").on("click",function(){
        $("#service-policy-detail-wrap").show();
    })
    $("#service-policy-detail-wrap-close").on("click",function(){
        $("#service-policy-detail-wrap").hide();
    })

    // 拼购规则详情的显示隐藏
    $("#count-down-rule-detail-wrap").hide();
    $("#rule-more").on("click",function(){
        $("#count-down-rule-detail-wrap").show();
    })
    // $("#service-policy-detail-wrap-close").on("click",function(){
    //     $("#service-policy-detail-wrap").hide();
    // })

    // 规格详情的显示隐藏
    $("#specifications-detail-wrap").hide();
    $("#specifications-more").on("click",function(){
        $("#specifications-detail-wrap").show();
    })

    $("#specifications-detail-wrap-close").on("click",function(){
        $("#specifications-detail-wrap").hide();
    })

    // 快速导航列表的显示隐藏
    $("#fast-guid-list").hide();
    $("#fast-guid").on("click",function() {
        $("#fast-guid-list").show();
    })
    $("#pack-up").on("click",function() {
        $("#fast-guid-list").hide();
    })

})
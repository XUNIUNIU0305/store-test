//查询信息
function queryInfo(_ele, _obj){
    _ele.on("click",function(){
        _obj.show();
    });
}

//确认关闭页面
function closePage(_ele, _obj){
    _ele.on("click",function(){
        _obj.hide();
    });
}

function detailedPrice(_pics, _conts, _flag){
    _pics.on("click",function(){
        var $this = $(this);
        var $index = $this.data("id");
        if(!$this.data("flag")){
            _pics.removeClass("c-coefficient-pic-active");
            $this.addClass("c-coefficient-pic-active");
            _conts.hide();
            _conts.eq($index-1).show(); 
            _flag && $("#gate-gear-cont").show();
            $("#custom-combination").removeClass("c-coefficient-pic-active");
            $this.data("flag","true");
        }else{
            $this.removeClass("c-coefficient-pic-active");
            _conts.eq($index-1).hide();
            $this.data("flag","false");
        }
    });
}

//选择系数
function seleCoefficient(_lis){
    _lis.on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    });
}

// 自定义组合车档选择
function carGearSele(_list, _close){
    var $gateLis = _list.children("li");
    $gateLis.on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    });
    
    _close.on("click",function(){
        var $txt = _list.find("li.active").text();
        $("#gate-gear-cont").hide();
        var $index = $(this).data("id");
        $("#custom-combination-txt-" + $index).text($txt);
    });
}

//计算数量
function calculation(_ele, _obj){
    _ele.on("click",function(){
        var $txt = parseInt(_obj.text());
        if(_ele.data("val") === 1){
            var result = $txt - 1;
            result >= 1 ? _obj.text(result) : _obj.text("1").prev().addClass("btn-disabled");
            result >= 1 ? $("#total").text(result) : $("#total").text("1");
        }else{
            var result = $txt + 1;
            _ele.prev().prev().removeClass("btn-disabled");
            _obj.text(result);
            $("#total").text(result);
        }
    });
}

$(function(){
    // tab页切换
    $("#myTab>li").on("click",function(){
        var $index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $("#myTabContent>div").eq($index).addClass('active').siblings().removeClass('active');
    });

    //显示套餐/系数
    queryInfo($("#ty-coefficient"), $("#ty-choose-set-meal-box"));
    queryInfo($("#ops-coefficient"), $("#ops-choose-set-meal-box"));

    //关闭套餐/系数
    closePage($("#ty-set-meal-confirmation"), $("#ty-choose-set-meal-box"));
    closePage($("#ops-set-meal-confirmation"), $("#ops-choose-set-meal-box"));
    
    //显示售价参考
    queryInfo($("#ty-price-reference"), $("#ty-price-reference-cont"));
    queryInfo($("#ops-price-reference"), $("#ops-price-reference-cont"));
    
    //详细售价参考
    var $tyPics_1 = $("#ty-price-reference-cont").find(".c-coefficient-pic");
    var $tyConts_1 = $("#ty-price-reference-cont").find(".c-coefficient-cont");
    var $opsPics_1 = $("#ops-price-reference-cont").find(".c-coefficient-pic");
    var $opsConts_1 = $("#ops-price-reference-cont").find(".c-coefficient-cont");
    detailedPrice($tyPics_1, $tyConts_1);
    detailedPrice($opsPics_1, $opsConts_1);
    
    //详细套餐/系数
    var $tyPics_2 = $("#ty-choose-set-meal-box").find(".c-coefficient-pic");
    var $tyConts_2 = $("#ty-choose-set-meal-box").find(".stall-parameters-cont");
    var $opsPics_2 = $("#ops-choose-set-meal-box").find(".c-coefficient-pic");
    var $opsConts_2 = $("#ops-choose-set-meal-box").find(".stall-parameters-cont");
    detailedPrice($tyPics_2, $tyConts_2);
    detailedPrice($opsPics_2, $opsConts_2);
    
    //显示自定义组合车档信息
    queryInfo($("#custom-combination"), $("#custom-combination-cont"));
    
    //关闭自定义组合并获取参数
    $("#custom").on("click",function(){
        $tyPics_2.removeClass("c-coefficient-pic-active").data("flag","false");
        $(".stall-parameters-cont").hide();
        var $carGearItem = $("#custom-combination-list").children().children("span");
        var $listItem = $("#custom-param-list").children().children("span");
        var $txt = '';
        for(var i = 0 , length = $carGearItem.length ; i < length ; i++){
            $txt = $carGearItem.eq(i).text();
            $listItem.eq(i).text($txt);
        }
        if($txt !== "请选择"){
            $("#custom-combination-cont").hide();
            $("#custom-combination").addClass("c-coefficient-pic-active");
            $("#custom-param-list").parent().show();
        }
    });
    
    //自定义组合详细信息选择
    var $customPics = $("#custom-combination-cont").find(".custom-combination-pic");
    var $gateGears = $("#gate-gear-cont").find(".gate-gear");
    detailedPrice($customPics, $gateGears, true);

    //自定义组合车档选择
    carGearSele($("#gate-parameter-list-1"), $("#gate-parameter-close-1"));
    carGearSele($("#gate-parameter-list-2"), $("#gate-parameter-close-2"));
    carGearSele($("#gate-parameter-list-3"), $("#gate-parameter-close-3"));
    carGearSele($("#gate-parameter-list-4"), $("#gate-parameter-close-4"));
    carGearSele($("#gate-parameter-list-5"), $("#gate-parameter-close-5"));
    carGearSele($("#gate-parameter-list-6"), $("#gate-parameter-close-6"));

    //选择套餐/系数
    seleCoefficient($("#ty-coefficient-list-cont>li"));
    seleCoefficient($("#ops-coefficient-list-cont>li"));

    //计算数量
    calculation($("#ty-del-number"), $("#ty-purchase-quantity"));
    calculation($("#ty-add-number"), $("#ty-purchase-quantity"));
    calculation($("#ops-del-number"), $("#ops-purchase-quantity"));
    calculation($("#ops-add-number"), $("#ops-purchase-quantity"));

    //支付方式选择
    $(".payment-method-sele").on("click",function(){
        var $this = $(this);
        if(!$this.data("flag")){
            $this.addClass("active");
            $this.data("flag","true");
        }else{
            $this.removeClass("active");
            $this.data("flag","false");
        }
    });

    //选择售价参考系数并退出
    var $tyList = $("#ty-price-reference-cont").find(".c-coefficient-txt");
    var $opsList = $("#ops-price-reference-cont").find(".c-coefficient-txt");
    $tyList.on("click",function(){
        $("#ty-price-reference-cont").hide();
    });
    $opsList.on("click",function(){
        $("#ops-price-reference-cont").hide();
    });

});
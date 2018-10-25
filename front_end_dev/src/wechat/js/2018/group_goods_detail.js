$(function () {
    // 两行文本超出显示...
    function fontsize(biaoqian) {
        var Size = parseInt($('.' + biaoqian).css('font-size'));
        var Width = parseInt($('.' + biaoqian).css('width'));
        var num = Width / Size * 2;
        // 单行字数
        var num1 = Width / Size;
        // 显示字数
        var num2 = Width / Size * 2 - 3;

        $('.' + biaoqian).each(function () {
            if ($(this).text().length > num1) {
                $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
            }
        })
    }
    // 传入字符串
    fontsize('goods-intro');
    fontsize('goods-intro-mess');
    // var price = {};
    // price = {max:529.73
    //     ,min:213.321};
    //     console.log(typeof pric)
    // var max_L = price.max.split(".")[0];
    // var max_m = price.max.split(".")[1];
    // var min_L = price.min.split(".")[0];
    // var min_m = price.min.split(".")[1];

    $("#service-policy-detail-wrap").hide();
    // 服务说明详情的显示隐藏
    $("#service-policy-more").on("click", function () {
        $("#service-policy-detail-wrap").show();
    })
    $("#service-policy-detail-wrap-close").on("click", function () {
        $("#service-policy-detail-wrap").hide();
    })

    // 拼购规则详情的显示隐藏
    $("#count-down-rule-detail-wrap").hide();
    $("#rule-more").on("click", function () {
        $("#count-down-rule-detail-wrap").show();
    })

    // 规格详情的显示隐藏
    $("#specifications-detail-wrap").hide();
    $("#specifications-more").on("click", function () {
        $("#specifications-detail-wrap").show();
    })

    $("#specifications-detail-wrap-close").on("click", function () {
        $("#specifications-detail-wrap").hide();
    })

    // 快速导航列表的显示隐藏
    $("#fast-guid-list").hide();
    $("#fast-guid").on("click", function () {
        $("#fast-guid-list").show();
    })
    $("#pack-up").on("click", function () {
        $("#fast-guid-list").hide();
    })
    var num = 1;
    // 商品数量的加减
    $("#deliver-goods-add").on("click", function () {
        num += 1;
        $('#deliver-goods-reduce').css({
            display: 'inline-block',
            width: '60 * $baseV',
            height: '60 * $baseV',
            background: 'url(/images/group_goods_detail/reduce_active.png)  no-repeat',
            backgroundSize: 'contain'
        });
        goodsNum(num);
    })
    $("#deliver-goods-reduce").on("click", function () {
        num -= 1;
        if ($("#deliver-goods-text").attr("value") == 1) {
            num = 1;
        }
        goodsNum(num);

    })

    function goodsNum(num) {
        $("#deliver-goods-text").attr("value", num);
        if ($("#deliver-goods-text").attr("value") == 1) {
            $('#deliver-goods-reduce').css({
                display: 'inline-block',
                width: '60 * $baseV',
                height: '60 * $baseV',
                background: 'url(/images/group_goods_detail/reduce_failure.png)  no-repeat',
                backgroundSize: 'contain'
            });
        }
    }
    $(".choose-specifications-color>.yet-choose").hide();
    // 规格详情中的选择规格
    $("#standard>.item>li").each(function (item, i) {
        $(this).click(function () {
            $(".choose-specifications-color>.please-choose").hide();
            $(".choose-specifications-color>.yet-choose").show();
            $("#standard>.item>li").removeClass("standard-active");
            $(this).addClass("standard-active");
            var text = '"' + $(this)[0].innerText + '"';
            $(".choose-specifications-color>.yet-choose>.guige").html(text)
        })
    })
    // 规格详情中的选择颜色
    $("#standard-color>.item>li").each(function () {
        $(this).click(function () {
            $("#standard-color>.item>li").removeClass("standard-color-active");
            $(this).addClass("standard-color-active");
            var text = '"' + $(this)[0].innerText + '"';
            $(".choose-specifications-color>.yet-choose>.color").html(text);

        })

    })



})
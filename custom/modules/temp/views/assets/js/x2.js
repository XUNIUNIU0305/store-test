$(function () {
    var end = new Date('2018/12/31 23:59:59');
    function timerss(){
        var now = new Date().getTime();
        if (now < end) {
            var time = end - now;
            var day = parseInt(time / 1000 / 60 / 60 / 24,10);
            if(day<10){
                day = '0'+ day;
            }
            $(".bannertop .times").text(day)
        }
    }
    timerss()
    
    function djs() {
        setInterval(function(){
            timerss()
        },1000)
    }
    djs();

    function activity1017(params){
        $.ajax({
            type: 'get',
            url: '/product-recommend/goods',
            data: params,
            success: function (data) {
                console.log(data)
                // 获取模板
                var ProList = $('#ProList').html();
                // 填入数据
                var sellAttr = juicer(ProList, data);
                // 模板添进标签
                $("#container").html(sellAttr);
            }
        });
    }
    activity1017({id:[1156,2148,1708,2204,2377,2403,1951,2384,2407,1209,1696,1210]})
    // 

    $(".histop").click(function () {
        $("html,body").scrollTop(0);
     });
})












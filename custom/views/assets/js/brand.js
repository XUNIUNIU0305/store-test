$(function(){
    //轮播数据加载
    function getCarouselList() {
        function listCarousel(data) {
            var htmlol = '',htmlimg = '';
            for(var i in data){
                if(i == 0){
                    htmlol += '<li data-target="#apx-brands-carousel" data-slide-to="'+i+'" class="active"></li>'
                    htmlimg += '<div class="item active"><a href="'+data[i].url+'" target="_blank"><img src="'+data[i].path+'" class="img-brand-img"></a> </div>'
                }else{
                    htmlol += '<li data-target="#apx-brands-carousel" data-slide-to="'+i+'"></li>'
                    htmlimg += '<div class="item"><a href="'+data[i].url+'" target="_blank"><img src="'+data[i].path+'" class="img-brand-img"></a> </div>'
                }
            }
            $('.carousel-indicators').html(htmlol);
            $('.carousel-inner').html(htmlimg)
        }
        requestUrl('/brand/get-top-adv', 'GET', '', listCarousel);
    }
    getCarouselList();
    //大小广告位加载
    function getAdvertisingList(){
        function listAdvertising(data){
            for(var i in data){
                if(data[i].position==1){
                    $('#media_left_img').find('img').attr('src',data[i].path);
                    $('#media_left_img').find('a').attr('href',data[i].url);
                }
                if(data[i].position==2){
                    $('#J_banner_img').find('img').attr('src',data[i].path);
                    $('#J_banner_img').find('a').attr('href',data[i].url);
                }
            }
        }
        requestUrl('/brand/get-big-small-adv', 'GET', '', listAdvertising);
    }
    getAdvertisingList();
    //热销品牌加载
    //当前页数和总页数,每页条数
    var current = 1,count = 1,size=9;
    var hotbrand_list = $('#J_Hotbrand_list').html();
    var compiled_hotbrand = juicer(hotbrand_list);
    function getHotbrandList(){
        function listHotbrand(data) {
            var html = compiled_hotbrand.render(data.codes);
            $('#J_media-body').html(html);
            $('#J_for_a_change').attr({"data-current":current,"data-count":data.total_count});
        }
        requestUrl('/brand/get-hot-brand', 'GET', {current_page: current, page_size: size},listHotbrand);
    }
    getHotbrandList();

    //品牌特辑加载
    var edit_list = $('#J_Edit_list').html();
    var compiled_edit = juicer(edit_list);
    function getEditList() {
        function listEdit(data) {
            var html = compiled_edit.render(data);
            $('#J_Edit').html(html);
         }
        requestUrl('/brand/get-brand-album', 'GET', '',listEdit);
    }
    getEditList();
    //换一下点击事件
    $('.media-body').on('click','#J_for_a_change',function(){
            var _this = this;
            $(_this).addClass('refreshing');
            setTimeout(function () {
                $(_this).removeClass('refreshing');
                current = $(_this).attr('data-current');
                count = Math.ceil($(_this).attr('data-count')/9);
                if(Number(current) == count){
                    current = 0;
                }
                current = Number(current)+1;
                getHotbrandList();
            }, 1000)
    })
})
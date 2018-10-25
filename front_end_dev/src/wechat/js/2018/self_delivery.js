$(function(){

    var $items = $('#head-list').find('.head-item'),
        $sections = $('#section').find('.self-delivery-section');

    function fontSize (biaoqian){
        var Size = parseInt($('.' + biaoqian).css('font-size'));
        var Width = parseInt($('.' + biaoqian).css('width'));
        var num = Width / Size * 2;
        var num1 = Width / Size;
        var num2 = Width / Size * 2 - 5; 
        $('.' + biaoqian).each(function() {
            if ($(this).text().length > num1) {
                $(this).html($(this).text().replace(/\s+/g, "").substr(0, num2) + "...")
            }
        })
    }

    fontSize('item-txt-1');

    $items.on('click',function(){
        var $index = $(this).data('index');
        $(this).addClass('active').siblings().removeClass('active');
        $sections.eq($index).removeClass('hidden').siblings().addClass('hidden');
    });

});
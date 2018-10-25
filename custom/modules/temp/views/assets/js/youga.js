$(function(){
    var selectedNum = [];
    var listPickedShow = false;
    var currectZodiacIdx = 1;
    // fix the number pad to 3
    function padNumberThree(num){
        num ='00'+num;
        return num.substring(num.length-3,num.length);
    }

    // render the list of number all
    function renderList(numObj){
        var $container = $('[data-view="J_zodiac_pick"]');
        $container.empty();
        $.each(numObj, function(idx, obj){
            $container.append('<li>\
                                    <label class="btn btn-default ' + (obj.selected ? 'disabled': '') + '">\
                                        <input value="' + obj.id + '" type="checkbox"> ' + padNumberThree(obj.num) + '\
                                    </label>\
                                </li>');
        })
        // show the list
        setTimeout(function(){
            $('[data-view="J_zodiac_pick"]').fadeIn();
        },0)
    }

    // render the list of number selected
    function renderListSelect(numObj){
        var selected = 0;
        var $container = $('[data-view="J_zodiac_chosen"]');
        $container.empty();
        $container.append('<li class="title"><i class="text-info glyphicon glyphicon-info-sign"></i>该系列您已选择的号码:</li>')
        $.each(numObj, function(idx, obj){
            // filter
            // if(Math.floor(obj.id / 899) + 1 == currectZodiacIdx){
                selected++;
                $container.append('<li>\
                                    <label class="btn btn-default active">' + padNumberThree(obj.num) + '</label>\
                                </li>');
            // }
        })
        !selected && $container.append('<li class="title text-muted small">没有记录</li>');
        // show the list
        setTimeout(function(){
            $('[data-view="J_zodiac_chosen"]').fadeIn();
        },0)
    }
    
    // request the zodiac number all
    function getZodiacNumbers(zod, cb){
        requestUrl('/temp/youga/number', 'get', {'zodiac': zod}, cb || function(numObj){
            $('.J_zodiac_loading').fadeOut(function(){
                renderList(numObj);
                $('[data-control="J_zodiac_chosen"]').removeClass('disabled')
                clickGetZodiacAllBindOnce();
            });
        })
    };

    // request the zodiac number select
    function getZodiacNumbersSelect(zod, cb){
        requestUrl('/temp/youga/selected', 'get', {'zodiac': zod}, cb || function(numObj){
            $('.J_zodiac_loading').fadeOut(function(){
                renderListSelect(numObj);
                $('[data-control="J_zodiac_chosen"]').removeClass('disabled')
                clickGetZodiacSelectBindOnce();
            });
        })
    };

    // choose the number
    $('body').on('change', '.apx-zodiac-container input[type="checkbox"]', function(){
        selectedNum = [];
        $('input[type="checkbox"]:checked').each(function(){
            selectedNum.push($(this).val());
        });
        selectedNum.length ? $('[data-control="J_zodiac_pick"]').removeClass('disabled') : $('[data-control="J_zodiac_pick"]').addClass('disabled');
    })

    // toggle list
    $('[data-control="J_zodiac_chosen"]').click(function(){
        if($(this).hasClass('disabled')) return false;
        if($(this).addClass('disabled'));
        $('[data-zodiac]').off();
        if(listPickedShow){
            $(this).text('查看已选号码');
            listPickedShow = false;
            $('.J_zodiac_loading').fadeIn();
            $('[data-view="J_zodiac_chosen"]').empty().fadeOut();
            getZodiacNumbers(currectZodiacIdx);
        }
        else{
            $(this).text('返回号码选择');
            $('[data-control="J_zodiac_pick"]').addClass('disabled');
            listPickedShow = true;
            $('.J_zodiac_loading').fadeIn();
            $('[data-view="J_zodiac_pick"]').empty().fadeOut();
            getZodiacNumbersSelect(currectZodiacIdx);
        }
    })

    // submit the number 
    $('[data-control="J_zodiac_pick"]').click(function(){
        if($(this).hasClass('disabled')) return false;
        requestUrl(
            '/temp/youga/order', 
            'post', 
            {'numbers_id': selectedNum}, 
            function(data){
                location.href = data.url;
            }
        )
        $(this).addClass('disabled');
        $('[data-control="J_zodiac_chosen"]').addClass('disabled');
    });

    // add click evt
    function clickGetZodiacAllBindOnce(){
        $('[data-zodiac]').off();
        $('[data-zodiac]').one('click.getZodiacAll', function(e){
            currectZodiacIdx = $(this).attr('data-zodiac');
            $('[data-zodiac]').find('.zodiac-icon').removeClass('active');
            $(this).find('.zodiac-icon').addClass('active');
            $('[data-view="J_zodiac_pick"]').empty();
            $('.J_zodiac_loading').fadeIn();
            $('[data-zodiac]').off('.getZodiacAll');
            getZodiacNumbers($(this).data('zodiac'));
        })
    }
    
    function clickGetZodiacSelectBindOnce(){
        $('[data-zodiac]').off();
        $('[data-zodiac]').one('click.getZodiacSelect', function(e){
            currectZodiacIdx = $(this).attr('data-zodiac');
            $('[data-zodiac]').find('.zodiac-icon').removeClass('active');
            $(this).find('.zodiac-icon').addClass('active');
            $('[data-view="J_zodiac_chosen"]').empty();
            $('.J_zodiac_loading').fadeIn();
            $('[data-zodiac]').off('.getZodiacSelect');
            getZodiacNumbersSelect($(this).data('zodiac'));
        })
    }

    // first attemp
    getZodiacNumbers(1)
})
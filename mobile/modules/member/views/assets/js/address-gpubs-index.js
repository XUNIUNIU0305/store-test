$(function(){
    
    _g = {
        id : ''
    }
    $('.bottom-nav').addClass('hidden');

    var $box = $('#self-lifting-manage-box');

    //拉取自提点列表信息
    function getSelfLifting(){
        requestUrl('/member/spot-address/gpubs-list','GET',{},function(data){
            $box.html(juicer($('#self-lefting-manage').html(),{data : data}));
        });
    }
    getSelfLifting();

    //设置默认地址
    $box.on('click','.addr-sele',function(){
        var _this = $(this);
        var $id = _this.data('id');
        requestUrl('/member/spot-address/set-default','POST',{id : $id},function(data){
            var $href = $box.find('.edit-btn').data('attr');
            var $parents = _this.parents('.addr-item');
            $box.find('.addr-default').addClass('hidden');
            $box.find('.addr-sele').attr('src','/images/self_lifting_manage/checkbox_no_44_icon.png');
            $box.find('.set-default').text('设为默认');
            $box.find('.edit-btn').removeClass('sele');
            $box.find('.del-btn').removeClass('active');
            _this.attr('src','/images/self_lifting_manage/checkbox_sele_44_icon.png');
            $parents.find('.set-default').text('已设为默认');
            $parents.find('.edit-btn').addClass('sele');
            $parents.find('.del-btn').addClass('active');
            $parents.find('.addr-default').removeClass('hidden');
        });
    });

    $box.on('click','.edit-btn',function(){
        var _id = $(this).data('id');
        var _flag;
        if($(this).hasClass('sele')){
            _flag = 1;
        }else {
            _flag = 0;
        }
        window.location.href = '/member/spot-address/gpubs-add?id='+ _id +'&flag=' + _flag;
    })

    $box.on('click','.del-btn',function(){
        _g.id = $(this).data('id');
        var _flag = confirm('是否确认删除？');
        if(_flag){
            requestUrl('/member/spot-address/gpubs-delete','POST',{id : _g.id},function(){
                getSelfLifting();
            });
        }else {
            return false;
        }
    });

})
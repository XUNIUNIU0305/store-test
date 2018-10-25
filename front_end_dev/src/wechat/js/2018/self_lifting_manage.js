$(function(){

    //收货地址管理
    $('.add-btn').on('click',function(){
        $('#self-lifting-manage').removeClass('hidden');
    });
    
    // 新增收货地址
    $('.p-btn').on('click',function(){
        $('#newly-added').removeClass('hidden');
        $('#self-lifting-manage').addClass('hidden');
    });
    
    // 编辑
    $('#addr-list .edit-btn').on('click',function(){
        $('#newly-added').removeClass('hidden');
        $('#self-lifting-manage').addClass('hidden');
    });

    // 删除
    $('#addr-list .del-btn').on('click',function(){
        $(this).parents('.addr-item').remove();
    });

    $('#push-btn').on('click',function(){
        $('#newly-added').addClass('hidden');
    });
    
    //设置默认地址
    $('#addr-list .addr-sele').on('click',function(){
        var $this = $(this),
            $delBtn = $this.parent().find('.del-btn');
        if($this.data('flag')){
            $this.attr('src','/images/self_lifting_manage/checkbox_no_44_icon.png');
            $this.data('flag',false);
            $this.next().text('设为默认');
            $delBtn.removeClass('active');
        }else {
            $this.attr('src','/images/self_lifting_manage/checkbox_sele_44_icon.png');
            $this.data('flag',true);
            $this.next().text('已设为默认');
            $delBtn.addClass('active');
        }
    });

    $('#default-btn').on('click',function(){
        var $this = $(this);
        if($this.data('flag')){
            $this.attr('src','/images/self_lifting_manage/checkbox_sele_44_icon.png');
            $this.data('flag',false);
        }else {
            $this.attr('src','/images/self_lifting_manage/checkbox_no_44_icon.png');
            $this.data('flag',true);
        }
    });

})
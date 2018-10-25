$(function() {
    // 团员拼购信息
    function groupActivityMembersInfo (params){
        requestUrl('/gpubs/share/info', 'GET',{group_id:params}, function(data) {
            $('.head-personnel-information .head-personnel-pic').css({"background":"url("+data.member[0].header_img+") no-repeat","background-size": "100% 100%"});
            $('.head-personnel-information .head-personnel-name').text(data.member[0].custom_user_account);
            $('.head-personnel-information .head-personnel-starttime span').text(data.group_start_datetime);
            if(data.gpubs_rule_type==1){
                $('.personnel-group-info .personnel-group-premise').text(data.target_member+'人起拼');
                $('.personnel-group-nows .personnel-group-now').text(data.present_member+'人');
            }else if(data.gpubs_rule_type==2){
                $('.personnel-group-info .personnel-group-premise').text(data.target_quantity+'件起拼');
                $('.personnel-group-nows .personnel-group-now').text(data.present_quantity+'件');
            }else if(data.gpubs_rule_type==3){
                $('.personnel-group-info .personnel-group-premise').text(data.target_member+'人'+data.min_quanlity_per_member_of_group+'件起拼');
                $('.personnel-group-nows .personnel-group-now').text(data.present_member+'人共'+data.present_quantity+'件');
            }
            if(data.gpubs_type == 1){
                $('.personnel-group-info .personnel-group-way').text('自提');
                $('.personnel-group-nows .personnel-group-now').text(data.present_quantity+'件')
            }else if(data.gpubs_type == 2){
                $('.personnel-group-info .personnel-group-way').text('送货')
            }
           
            $('.personnel-group .personnel-num span').text(data.group_number);
            $('.personnel-group .personnel-introduce').text(data.product.title);
            if(data.member.length<=1){
                $('.personnel-information-num .none-personnel-group').removeClass('hidden').siblings().addClass('hidden');
            }else if(data.member.length>=2){
                $('.personnel-information-num .have-personnel-group').removeClass('hidden').siblings().addClass('hidden');
                var activityGroupMemberInfo = document.getElementById('activityGroupMemberInfo').innerHTML;
                var memberInfo = juicer(activityGroupMemberInfo, data);
                $("#have-personnel-group").html(memberInfo);
                $.each(data.member,function(i,v){
                    var conStr = $('.have-personnel-spe .have-personnel-Model').eq(i).text();
                    var condeletewhite = conStr .replace(/\s+/g,"");
                    var conArr = condeletewhite.split(";");
                    var newconArr = conArr.slice(0,conArr.length-1);
                    var newconStr = newconArr.join(';');
                    var newconStr1 = newconStr.split(";").join("; ")
                    $('.have-personnel-spe .have-personnel-Model').eq(i).text(newconStr1);
                })
            }
        })
    }
        // url
        function getSearchAtrr(attr) {
            var attrArr = window.location.search.substr(1).split('&');
            var newArr = attrArr.map((item) => item.split('='));
            var i, len = newArr.length;
            for (i = 0; i < len; i++) {
                if (newArr[i][0] == attr) {
                    return newArr[i][1];
                }
            }
        }
    groupActivityMembersInfo(getSearchAtrr("id"));












})

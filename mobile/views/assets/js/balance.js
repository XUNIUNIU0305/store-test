$(function(){
         // url
    function getSearchAtrr(attr){
        var attrArr = window.location.search.substr(1).split('&');
        var newArr = attrArr.map((item) => item.split('='));
        var i, len = newArr.length;
        for(i = 0 ; i < len ; i++) {
            if(newArr[i][0] == attr){
                return newArr[i][1];
            }
        }
    }
    if(getSearchAtrr("group_id")){
        var pid = getSearchAtrr("p_id");
        var gid = getSearchAtrr("group_id");
        var url = "/gpubs/share/inviting-friends?id="+gid+"&p_id="+pid;
        setTimeout(() => {
            window.location.href = url;
        }, 1000);
    }else {
        $(".payment-but-href1").attr("href","/member/index")
        $(".payment-but-href2").attr("href","/")
    }
})
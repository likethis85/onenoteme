$(function(){
	Api_Onenote.windowInit();
    
    $('.on-toolbar').delegate('#newcategory', 'click', function(event){Api_Onenote.newCategory();});
    $('.on-toolbar').delegate('#newtopic', 'click', function(event){Api_Onenote.newTopic();});
    $('.on-toolbar').delegate('#newpost', 'click', function(event){Api_Onenote.newPost();});
	
    $('.on-toolbar').delegate('#logout', 'click', function(event){Api_Onenote.logout();});
    $('.on-toolbar').delegate('#login', 'click', function(event){Api_Onenote.login();});
    $('.on-toolbar').delegate('#signup', 'click', function(event){Api_Onenote.signup();});
    

//	$(document).ajaxStart(function(){
//		$('#on-tip').stop(true, true);
//		Api_Tip.show('开始请求');
//	});
//	$(document).ajaxSend(function(event, jqXHR, ajaxSetting){
//		Api_Tip.show('发送请求');
//	});
//	$(document).ajaxError(function(event, jqXhr, ajaxSetting, throwError){
//		Api_Tip.errorShow('请求错误');
//    });
//	$(document).ajaxComplete(function(event, xhr, ajaxSetting){
//        Api_Tip.hide(5000, 'slow');
//    });
	
	
    
});


/*
 * @todo 暂时没用
 */
function userShare(info, tab){
    var mediaUrl = info.srcUrl;
	var pageUrl = info.pageUrl;
	//alert(mediaUrl + pageUrl);
	var url = 'http://onenote.sinaapp.com/share.php?url=' + mediaUrl;
	$.get(url, function(data){
		alert(data);
	});
};

/**
 * sns share function
 */

function postToWb(title)
{
    var _url = encodeURIComponent(document.location);
    var _assname = encodeURI("cdcchen");
    var _appkey = encodeURI("8fcd940fb4e54897ad5d07320468d8fd");
    var _pic = encodeURI('');
    var _t = title;
    if (_t.length > 120){
        _t= _t.substr(0,117)+'...';
    }
    _t = encodeURI(_t);

    var _u = 'http://share.v.t.qq.com/index.php?c=share&a=index&url='+_url+'&appkey='+_appkey+'&pic='+_pic+'&assname='+_assname+'&title='+_t;
    window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}

function shareToQQT(url, content)
{
	var _url = encodeURIComponent(url);
	var _assname = encodeURI("cdcchen");
    var _appkey = encodeURI("8fcd940fb4e54897ad5d07320468d8fd");
    var _t = content;
    if (_t.length > 180)
        _t= _t.substr(0,177) + '...';
    var _pic = encodeURI('');
	var _u = 'http://share.v.t.qq.com/index.php?c=share&a=index&url='+_url+'&appkey='+_appkey+'&pic='+_pic+'&assname='+_assname+'&title='+_t;
    window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}

function shareToWeibo(url, content)
{
	var _url = encodeURIComponent(url);
	var _ralateUid = encodeURI("1639121454");
    var _appkey = encodeURI("2981913360");
    var _t = content;
    if (_t.length > 170)
        _t= _t.substr(0,167) + '...';
    var _pic = encodeURI('');
    var _u = 'http://service.weibo.com/share/share.php?url='+_url+'&appkey='+_appkey+'&title='+_t+'&pic='+_pic+'&ralateUid=' + _ralateUid;
    window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}

function shareToQzone(url, content)
{
	var _url = encodeURIComponent(url);
	var _desc = encodeURIComponent('哈哈，一点也不好笑。');
	var _ralateUid = encodeURI("1639121454");
    var _appkey = encodeURI("3658366445");
    var _site = encodeURIComponent('挖段子');
    var _t = content;
//    if (_t.length > 120)
//        _t= _t.substr(0,117) + '...';
    var _pic = encodeURI('');
    var _u = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+_url+'&desc='+_desc+'&summary='+_t+'&title='+_desc+'&site='+_site+'&pics=' + _pic;
    window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}

$(function(){
	$('.post-item .weibo').click(function(e){
		var url = $(this).parents('.post-item').find('.item-link').attr('href');
		var content = encodeURIComponent('#挖段子冷笑话#') + $.trim($(this).parents('.post-item').find('.item-content').text());
	    shareToWeibo(url, content);
	});

	$('.post-item .qqt').click(function(e){
		var url = $(this).parents('.post-item').find('.item-link').attr('href');
		var content = encodeURIComponent('#挖段子冷笑话#') + $.trim($(this).parents('.post-item').find('.item-content').text());
	    shareToQQT(url, content);
	});

	$('.post-item .qzone').click(function(e){
		var url = $(this).parents('.post-item').find('.item-link').attr('href');
		var content = encodeURIComponent('#挖段子冷笑话#') + $.trim($(this).parents('.post-item').find('.item-content').text());
	    shareToQzone(url, content);
	});
});
var tab;
chrome.extension.onRequest.addListener(function(request, sender, sendResponse) {
    if (request.method) {
		tab = request.tab;
		requestMethods[request.method](request, sender, sendResponse);
	}
	else 
		console.log('request is invalid.');
});

var requestMethods = {
	showPostPage: function(request, sender, sendResponse){
		if (request.context)
			postPages[request.context](request);
		else
			console.log('request context is invalid.');
	},
	closePostPage: function(request, sender, sendResponse){
		console.log(request);
		if (request.result == 1)
            $('#chrome-close').click();
	}
};


var postPages = {
	selection: function(request){
		var html = '<div class="chrome-container" id="chrome-container">'
		+ '<form action="" class="form-stacked" id="chrome-post-form">'
		+ 	'<div class="clearfix">'
        +       '<label for="ontags">标题</label>'
        +       '<div class="input">'
        +           '<input type="text" class="chrome-txt" name="ontitle" id="ontitle" />'
        +       '</div>'
		+ 		'<label for="content">内容</label>'
		+ 		'<div class="input">'
		+ 			'<textarea class="chrome-content" name="oncontent" id="oncontent" rows="7">' + request.info.selectionText + '</textarea>'
		+ 			'<span class="help-block">内容中不允许出现html代码和广告链接</span>'
		+ 		'</div>'
		+ 		'<label for="ontags">标签</label>'
		+ 		'<div class="input">'
		+ 			'<input type="text" class="chrome-txt" name="ontags" id="ontags" />'
		+ 			'<span class="help-block">多个用逗号或空格分隔</span>'
		+ 		'</div>'
		+ 			'<input type="hidden" name="onpic" value="" />'
        +       '<label for="onchannel">频道</label>'
        +       '<div>'
        +           '<select name="onchannel" id="onchannel"><option value="0">挖段子</option><option value="50">挖热点</option></select>'
        +       '</div>'
		+ 			'<input type="hidden" name="oncategory" value="20" />'
		+ 	'</div>'
		+	'<div class="actions">'
		+		'<input type="button" class="btn primary" id="chrome-post" value="发布" />&nbsp;<input type="button" class="btn" id="chrome-close" value="关闭" />'
		+ 	'</div>'
		+ '</form></div>';
		
		$('body').append(html);
	},
	image: function(request){
		var html = '<div class="chrome-container" id="chrome-container">'
        + '<form action="" class="form-stacked" id="chrome-post-form">'
        +   '<div class="clearfix">'
        +       '<label for="ontags">标题</label>'
        +       '<div class="input">'
        +           '<input type="text" class="chrome-txt" name="ontitle" id="ontitle" />'
        +       '</div>'
        +       '<label for="content">内容</label>'
        +       '<div class="input">'
        +           '<textarea class="chrome-content" name="oncontent" id="oncontent" rows="7"></textarea>'
        +           '<span class="help-block">内容中不允许出现html代码和广告链接</span>'
        +       '</div>'
        +       '<label for="ontags">标签</label>'
        +       '<div class="input">'
        +           '<input type="text" class="chrome-txt" name="ontags" id="ontags" />'
        +           '<span class="help-block">多个用逗号或空格分隔</span>'
        +       '</div>'
        +       '<label for="ontags">图片</label>'
        +       '<div class="input">'
        +           '<input type="text" class="chrome-txt" name="onpic" id="ontags" value="' + request.info.srcUrl + '" />'
        +           '<span class="help-block">多个用逗号或空格分隔</span>'
        +       '</div>'
        +       '<label for="onchannel">频道</label>'
        +       '<div>'
        +           '<select name="onchannel" id="onchannel"><option value="20" selected="selected">挖冷图</option><option value="30">挖福利</option><option value="50">挖热点</option></select>'
        +       '</div>'
        +           '<input type="hidden" name="oncategory" value="20" />'
        +   '</div>'
        +   '<div class="actions">'
        +       '<input type="button" class="btn primary" id="chrome-post" value="发布" />&nbsp;<input type="button" class="btn" id="chrome-close" value="关闭" />'
        +   '</div>'
        + '</form></div>';
        
        $('body').append(html);
	},
	page: function(request){
		console.log('page');
	},
	link: function(request){
		console.log('link');
	}
};

$(function(){
	$('#chrome-close').live('click', function(e){
		$('#chrome-container').fadeOut('slow', function(){
			$(this).remove();
		});
	});
	
	$('#chrome-post').live('click', function(e){
		var data = $('#chrome-post-form').serializeArray();
		console.log(data);
		var request = {
			method: 'shareText',
			data: data,
			tab: tab
		};
		chrome.extension.sendRequest(request);
	});
});

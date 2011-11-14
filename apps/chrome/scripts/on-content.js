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
		+ 		'<label for="content">内容</label>'
		+ 		'<div class="input">'
		+ 			'<textarea class="chrome-content" name="oncontent" id="oncontent" rows="10">' + request.info.selectionText + '</textarea>'
		+ 			'<span class="help-block">内容中不允许出现html代码和广告链接</span>'
		+ 		'</div>'
		+ 		'<label for="ontags">标签</label>'
		+ 		'<div class="input">'
		+ 			'<input type="text" class="chrome-tags" name="ontags" id="ontags" />'
		+ 			'<span class="help-block">多个用逗号或空格分隔</span>'
		+ 		'</div>'
		+ 		'<label for="oncategories">分类</label>'
		+ 		'<div>'
		+ 			'<select name="oncategory" id="oncategory"><option value="">请选择分类</option><option value="1">太糗了</option><option value="2">太搞了</option><option value="3">太冷了</option><option value="4">太经典了</option></select>'
		+ 		'</div>'
		+ 	'</div>'
		+	'<div class="actions">'
		+		'<input type="button" class="btn primary" id="chrome-post" value="发布" />&nbsp;<input type="button" class="btn" id="chrome-close" value="关闭" />'
		+ 	'</div>'
		+ '</form></div>';
		
		$('body').append(html);
	},
	image: function(request){
		console.log('image');
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

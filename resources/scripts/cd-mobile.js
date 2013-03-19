var CDMobile = {};

CDMobile.urlValidate = function(url) {
	var pattern = /http:\/\/[\w-]*(\.[\w-]*)+/ig;
	return pattern.test(url);
};
CDMobile.emailValidate = function(email) {
	var pattern = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/ig;
	return pattern.test(email);
};
CDMobile.shareToWeixinFriend = function(event){
	if (typeof WeixinJSBridge == 'undefined') {
		return false;
	}
	else {
		var imgurl = $(this).attr('data-image');
		var title = $(this).attr('data-title');
		var desc = $(this).attr('data-desc');
		WeixinJSBridge.invoke('shareTimeline', {
			'img_url': imgurl || '',
			'link': location.href,
			'desc': desc,
			'title': title
		}, function(res) {
			// 返回res.err_msg,取值
			// share_timeline:cancel 用户取消
			// share_timeline:fail　发送失败
			// share_timeline:ok 发送成功
			WeixinJSBridge.log(res.err_msg);
		});
	}
	return false;
};

CDMobile.increaseVisitNums = function(id, url) {
	if (id <= 0 || url.length == 0) return false;
	var data = 'id=' + id;
	var jqXhr = $.post(url, data, undefined, 'json');
	jqXhr.done(function(data){
		$('.beta-post-detail .beta-visit-nums').text(data);
	});
};

CDMobile.switchImageSize = function(event){
    event.preventDefault();
    _hmt && _hmt.push(['_trackEvent', '图片', '缩略图与大图切换点击']);
    
    var itemDiv = $(this).parents('.post-item');
    itemDiv.find('.post-image .thumbnail-more').toggle();
    itemDiv.find('.post-image .thumb a .thumb').toggle();
    itemDiv.find('.post-image .thumb-pall').toggle();
    var originalUrl = itemDiv.find('.post-image .thumb a').attr('href');
    itemDiv.find('.post-image .thumb a .original').attr('src', originalUrl).toggle();
    var itemPos = itemDiv.position();
    $('body').scrollTop(parseInt(itemPos.top) - 75);
};

CDMobile.ratingPost = function(event){
	event.preventDefault();
	
	_hmt && _hmt.push(['_trackEvent', '文章评价', '点击按钮']);
	var tthis = $(this);
	var itemDiv = tthis.parents('.post-box');
	var pid = tthis.attr('data-id');
	var score = tthis.attr('data-score');
	var url = tthis.attr('data-url');
	
	var jqXhr = $.ajax({
		type: 'POST',
		url: url,
		data: {pid: pid, score: score},
		dataType: 'json',
		beforeSend: function(){
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.done(function(data){
		if (data.errno == 0) {
			var old = parseInt(tthis.text());
			var newScore = 0;
			if (tthis.hasClass('upscore'))
				newScore = old + 1;
			else if (tthis.hasClass('downscore'))
				newScore = old - 1;
				
			tthis.text(newScore);
			itemDiv.find('a.upscore, a.downscore').addClass('disabled');
			itemDiv.find('.item-toolbar').off('click', 'a.upscore, a.downscore');
		}
		else {
			alert('评价出错');
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.fail(function(){
		alert('x');
		tthis.toggleClass('voted');
	});
};

CDMobile.showShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '显示']);
	
	var bdshare = $(this).parents('.item-toolbar').find('#bdshare');
	if (bdshare.attr('data').length == 0) {
		var item = $(this).parents('.post-box');
		var bddata = {
			"url": item.find('.item-title a').attr('href'),
			"text": '转自@挖段子网：' + $.trim(item.find('.item-title').text()),
			"pic": item.find('.post-image .thumb a').attr('href'),
		};
		bdshare.attr('data', JSON.stringify(bddata));
	}
	$(this).parents('.item-toolbar').find('.sharebox:hidden').stop(true, true).delay(50).show();
};

CDMobile.hideShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '隐藏']);
	
	$(this).parents('.item-toolbar').find('.sharebox:visible').stop(true, true).delay(50).hide();
};

CDMobile.fetchComments = function(event) {
	event.preventDefault();
	var tthis = $(this);
	var commentBlock = tthis.parents('.item-toolbar').next('.comment-list');
	if (commentBlock.filter(':visible').length > 0) {
		commentBlock.hide();
		_hmt && _hmt.push(['_trackEvent', '评论', '隐藏评论列表']);
		return true;
	}
	else if (commentBlock.html().length > 0) {
		commentBlock.show();
		_hmt && _hmt.push(['_trackEvent', '评论', '查看评论列表']);
		return true;
	}
	_hmt && _hmt.push(['_trackEvent', '评论', '查看评论列表']);
	
	var url = tthis.attr('data-url');
	var commentCount = tthis.text();
	var jqXhr = $.ajax({
		url: url,
		dataType: 'json',
		type: 'get',
		cache: true,
		beforeSend: function(jqXHR, settings){
			tthis.text('...');
		}
	});
	
	jqXhr.done(function(data, textStatus, jqXHR){
		commentBlock.html(data.html).show();
		commentBlock.find('textarea').focus();
	});
	
	jqXhr.fail(function(jqXHR, textStatus, errorThrown){
		alert('fail');
	});
	
	jqXhr.always(function(){
		tthis.text(commentCount);
	});
};

CDMobile.RatingComment = function(event){
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '评论评价按钮', '点击']);
	
	var tthis = $(this);
	var pid = parseInt(tthis.attr('data-id'));
	var score = parseInt(tthis.attr('data-value'));
	var scoreEl = tthis.parents('.comment-item').find('.comment-score');
	var url = tthis.attr('data-url');

	var this_pushed = tthis.hasClass('pushed');
	var up_pushed = tthis.parent().find('a.arrow-up').hasClass('pushed');
	var down_pushed = tthis.parent().find('a.arrow-down').hasClass('pushed');
	var score_step = 0;
	if (up_pushed || down_pushed) {
		if (this_pushed) {
			tthis.toggleClass('pushed');
			score_step = (score > 0) ? -1 : 1;
		}
		else {
			tthis.parent().find('a').toggleClass('pushed');
			score_step = (score > 0) ? 2 : -2;
		}
	}
	else {
		tthis.toggleClass('pushed');
		if (score > 0)
			score_step = 1;
		else {
			score_step = -1;
		}
	}

	// 此处先更新页面数字，不管成功与否
	scoreEl.text(parseInt(scoreEl.text()) + score_step);

	var xhr = $.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: {id:pid, score:score}
	});
	xhr.done(function(data){
		if (parseInt(data.errno) == 0) {
			// success
		}
	});
};

CDMobile.PostComment = function(event) {
	event.preventDefault();
	var form = $(this).parents('form');
	var contentElement = form.find('.comment-content');
	var errorElement = form.next('.caption-error');
	var loadingElement = form.find('.save-caption-loader');
	var placeholder = contentElement.attr('data-placeholder');
	var content = $.trim(contentElement.val());
	
	var postid = parseInt(form.find('input[name=postid]').val());
	if (postid <= 0 || content.length <= 0 || content == placeholder)
		return false;

	var xhr = $.ajax({
		url: form.attr('action'),
		type: 'POST',
		dataType: 'json',
		data: form.serialize(),
		beforeSend: function(){
			errorElement.empty().hide();
			loadingElement.show();
		}
	});
	xhr.done(function(data){
		loadingElement.hide();
		if (data.errno == 0) {
			errorElement.after(data.html);
			contentElement.val('').removeClass('expand');
			_hmt && _hmt.push(['_trackEvent', '评论', '评论成功']);
		}
		else {
			errorElement.html(data.error).show();
			_hmt && _hmt.push(['_trackEvent', '评论', '评论失败']);
		}
	});
	xhr.fail(function(){
		loadingElement.hide();
		errorElement.html('发送请求错误！').show();
		_hmt && _hmt.push(['_trackEvent', '评论', '评论失败']);
	});
};







var BetaPost = {
	digg: function(event) {
		event.preventDefault();
		var tthis = $(this);
		var postid = parseInt(tthis.attr('data-id'));
		var url = tthis.attr('data-url');
		
		var digg_cookie_name = 'beta_digg';
		var _cookies = JSON.parse($.cookie(digg_cookie_name));
		if (!$.isArray(_cookies)) _cookies = [];
		if ($.inArray(postid, _cookies) > -1) return false;
		
		var jqXhr = $.ajax({
			type: 'post',
			url: url,
			data: {pid: postid},
			dataType: 'json',
		});
		
		jqXhr.done(function(data){
			if (data.errno != 0) {
				tthis.find('.digg-count').text(data.digg_nums);
				_cookies.push(postid);
				$.unique(_cookies);
				$.cookie(digg_cookie_name, JSON.stringify(_cookies), {expires:7, path:'/'});
			}
		});
		
		$(document).off('click', '#beta-digg-button');
	}
};

var BetaComment = {
	create: function(event){
		event.preventDefault();
		var form = $(this);
		var msg = form.next('.beta-alert-message');
		if (msg.length == 0)
			msg = $('#beta-create-message').clone().removeAttr('id');
		var msgtext = msg.find('.text');
		form.after(msg);

		var captchaEl = form.find('.beta-captcha');
		if (captchaEl.length > 0)
			form.find('input.beta-captcha').blur();

		if (form.find('.help-block.error').length > 0) {
			msgtext.html('请输入评论内容和验证码后再发布');
			msg.removeClass('alert-success').addClass('alert-error').show();
			return false;
		}
		else {
			var contentEl = form.find('.comment-content');
			var content = $.trim(contentEl.val());
			var minlen = parseInt(contentEl.attr('minlen'));
			minlen = (isNaN(minlen) || minlen == 0) ? 5 : minlen;
			if (content.length < minlen) {
				msgtext.html('请输入评论内容和验证码后再发布');
				msg.removeClass('alert-success').addClass('alert-error').show();
				contentEl.focus();
				return false;
			}
			if (captchaEl.length > 0) {
				var captcha = $.trim(captchaEl.val());
				if (captcha.length != 4) {
					msgtext.html('请输入评论内容和验证码后再发布');
					msg.removeClass('alert-success').addClass('alert-error').show();
					captchaEl.focus();
					return false;
				}
			}
		}

		var button = form.find('button[type=submit]');
		var jqXhr = $.ajax({
			type: 'post',
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
			cache: false,
			beforeSend: function(jqXhr){
				button.button('loading');
				msgtext.html('发送数据中...');
				msg.removeClass('alert-error').addClass('alert-success').show();
			}
		});
		jqXhr.done(function(data){
			msgtext.html(data.text);
			if (data.errno == 0) {
				form.find(':text, textarea').val('');
				form.find('.beta-control-group').removeClass('success error');
				form.find('.beta-control-group').removeClass('alert-success alert-error');
				msg.removeClass('alert-error').addClass('alert-success').show();
				var lastComment = $('.beta-comment-item:last');
				if (lastComment.length == 0)
					lastComment = $('#beta-comment-list');
				lastComment.after(data.html);
				if (form.attr('id') == undefined) form.remove();
				form.find('.comment-content').addClass('mini');
				$('form .comment-captcha').hide();
				$('.beta-no-comments').remove();
			}
			else {
				msg.removeClass('alert-success').addClass('alert-error').show();
				form.find('.refresh-captcha').trigger('click');
			}
		});
		jqXhr.fail(function(event, jqXHR, ajaxSettings, thrownError){
			jqXhr.abort();
			msgtext.html('请求错误.');
			msg.removeClass('alert-success').addClass('alert-error').show();
		});
		jqXhr.always(function(){
			button.button('reset');
		});
		return false;
	},
	reply: function() {
		var form = $(this).parents('.beta-comment-item').next('form');
		if (form.length == 0) {
			form = $('#comment-form').clone().removeAttr('id').addClass('comment-reply-form').attr('action', $(this).attr('data-url'));;
			form.find('.comment-captcha').hide();
			form.find(':text, textarea').val('');
			$(this).parents('.beta-comment-item').after(form);
//			form.find('.comment-content').focus();
			form.find('.beta-control-group').removeClass('error success');
		}
		else if (form.filter(':visible').length == 0) {
			form.show();
//			form.find('.comment-content').focus();
		}
		else
			form.hide();

		form.next('.beta-alert-message:visible').hide();
	},
	rating: function(event) {
		event.preventDefault();
		var tthis = $(this);
		var commentItem = tthis.parents('.beta-comment-item');
		var msg = commentItem.next('.beta-alert-message');
		if (msg.length == 0)
			msg = $('#beta-comment-message').clone().removeAttr('id');
		var msgtext = msg.find('.text');

		if (tthis.attr('data-clicked')) {
			msgtext.html('您已经参与过了，谢谢');
			if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
			if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			commentItem.after(msg);
			msg.show();
			return false;
		}

		var url = tthis.attr('data-url');
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			type: 'post',
			cache: false,
			beforeSend: function(jqXhr) {
				msgtext.html('发送数据中...');
				msg.removeClass('alert-error').addClass('alert-success');
				commentItem.after(msg);
				msg.show();
			}
		});
		jqXhr.done(function(data){
			if (data.errno == 0) {
				var jqnums = tthis.find('.beta-comment-join-nums');
				jqnums.text(parseInt(jqnums.text()) + 1);
				tthis.attr('data-clicked', 1);
				msgtext.html(data.text);
				if (msg.hasClass('alert-error')) msg.removeClass('alert-error');
				if (!msg.hasClass('alert-success')) msg.addClass('alert-success');
			}
			else {
				if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
				if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			}
			commentItem.after(msg);
			msg.show();
		});
		jqXhr.fail(function(event, jqXHR, ajaxSettings, thrownError){
			msgtext.html('请求错误.');
			if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
			if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			commentItem.after(msg);
			msg.show();
		});
	},
	captchaValidate: function(event){
		var tthis = $(this);
		var captcha = $.trim(tthis.val());
		var help = tthis.parents('.beta-controls').find('.beta-help-inline');
		var helperror = tthis.parents('.beta-controls').find('.beta-help-error');
		var group = tthis.parents('.beta-control-group');

		help.hide();
		if (captcha.length == 4) {
			group.removeClass('error').addClass('success');
			return true;
		}
		else {
			helperror.show();
			group.removeClass('success').addClass('error');
			return false;
		}
	},
	contentValidate: function(event) {
		var tthis = $(this);
		var content = $.trim(tthis.val());
		var minlen = parseInt(tthis.attr('minlen'));
		var help = tthis.parents('form').find('.help-block');
		minlen = (isNaN(minlen) || minlen == 0) ? 5 : minlen;
		if (content.length > minlen) {
			help.removeClass('error').addClass('success');
			return true;
		}
		else {
			help.removeClass('success').addClass('error');
			if (content.length == 0) tthis.addClass('mini');
			return false;
		}
	},
	showCaptcha: function(event) {
		var tthis = $(this);
		tthis.removeClass('mini');
		var captchaRow = tthis.parents('form').find('.comment-captcha:hidden');
		if (captchaRow.length == 0) return false;

		var captchaEl = tthis.parents('form').find('img.beta-captcha-img');
		captchaEl.attr('src', captchaEl.attr('lazy-src'));
		captchaEl.trigger('click');
		captchaRow.fadeIn('fast');
	},
	refreshCaptcha: function(event) {
		event.preventDefault();
		var url = $(this).parents('.beta-controls').find('.refresh-captcha').attr('href');
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			cache: false
		});
		jqXhr.done(function(data){
			$('.beta-captcha-img').attr('src', data['url']);
			$('body').data('captcha.hash', [data['hash1'], data['hash2']]);
		});
		return false;
	},
	loadMore: function(event){
		var tthis = $(this);
		var page = parseInt(tthis.attr('data-page')) + 1;
		var url = tthis.attr('data-url');
		var data = {page: page};
		
		var jqXhr = $.ajax({
			url: url,
			dataType: 'json',
			type: 'get',
			data: data,
			cache: false,
			beforeSend: function(jqXhr) {
				tthis.button('loading');
			}
		});
		
		jqXhr.done(function(data){
			if (data.errno == 0) {
				var lastComment = $('.beta-comment-item:last');
				if (lastComment.length == 0)
					lastComment = $('#beta-comment-list');
				lastComment.after(data.html);
				
				if (data.html.length > 0)
					tthis.attr('data-page', page);
			}
			else
				alert('Load more comments error');
		});
		
		jqXhr.always(function(){
			tthis.button('reset');
		});
	}
};


CDMobile.addContact = function(wxid, cb) { 
	if (typeof WeixinJSBridge == 'undefined') return false;
	WeixinJSBridge.invoke('addContact', {
		webtype: '1',
		username: wxid
	}, function(d) {
		// 返回d.err_msg取值，d还有一个属性是err_desc
		// add_contact:cancel 用户取消
		// add_contact:fail　关注失败
		// add_contact:ok 关注成功
		// add_contact:added 已经关注
		WeixinJSBridge.log(d.err_msg);
		cb && cb(d.err_msg);
	});
};






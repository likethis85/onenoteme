var Beta24 = {
	urlValidate: function(url) {
		var pattern = /http:\/\/[\w-]*(\.[\w-]*)+/ig;
		return pattern.test(url);
	},
	emailValidate: function(email) {
		var pattern = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/ig;
		return pattern.test(email);
	}
};

var BetaPost = {
	increaseVisitNums: function(id, url) {
		if (id <= 0 || url.length == 0) return false;
		var data = 'id=' + id;
		var jqXhr = $.post(url, data, undefined, 'jsonp');
		jqXhr.done(function(data){
			$('.beta-post-detail .beta-visit-nums').text(data);
		});
	},
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
			dataType: 'jsonp',
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
			msgtext.html($('.ajax-jsstr .ajax-rules-invalid').text());
			msg.removeClass('alert-success').addClass('alert-error').show();
			return false;
		}
		else {
			var contentEl = form.find('.comment-content');
			var content = $.trim(contentEl.val());
			var minlen = parseInt(contentEl.attr('minlen'));
			minlen = (isNaN(minlen) || minlen == 0) ? 5 : minlen;
			if (content.length < minlen) {
				msgtext.html($('.ajax-jsstr .ajax-rules-invalid').text());
				msg.removeClass('alert-success').addClass('alert-error').show();
				contentEl.focus();
				return false;
			}
			if (captchaEl.length > 0) {
				var captcha = $.trim(captchaEl.val());
				if (captcha.length != 4) {
					msgtext.html($('.ajax-jsstr .ajax-rules-invalid').text());
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
			dataType: 'jsonp',
			cache: false,
			beforeSend: function(jqXhr){
				button.button('loading');
				msgtext.html($('.ajax-jsstr .ajax-send').text());
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
			msgtext.html($('.ajax-jsstr .ajax-fail').text());
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
			msgtext.html($('.ajax-jsstr .ajax-has-joined').text());
			if (msg.hasClass('alert-success')) msg.removeClass('alert-success');
			if (!msg.hasClass('alert-error')) msg.addClass('alert-error');
			commentItem.after(msg);
			msg.show();
			return false;
		}

		var url = tthis.attr('data-url');
		var jqXhr = $.ajax({
			url: url,
			dataType: 'jsonp',
			type: 'post',
			cache: false,
			beforeSend: function(jqXhr) {
				msgtext.html($('.ajax-jsstr .ajax-send').text());
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
			msgtext.html($('.ajax-jsstr .ajax-fail').text());
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
			dataType: 'jsonp',
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



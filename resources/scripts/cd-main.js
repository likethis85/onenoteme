var Waduanzi = {
	RatingPost: function(event){
		event.preventDefault();
		var tthis = $(this);
		var pid = parseInt(tthis.attr('data-id'));
		var score = parseInt(tthis.attr('data-value'));
		var url = tthis.attr('data-url');

		var this_pushed = tthis.hasClass('pushed');
		var up_pushed = tthis.parent().find('a.arrow-up').hasClass('pushed');
		var down_pushed = tthis.parent().find('a.arrow-down').hasClass('pushed');
		var score_step = like_step = 0;
		if (up_pushed || down_pushed) {
			if (this_pushed) {
				tthis.toggleClass('pushed');
				score_step = (score > 0) ? -1 : 1;
				like_step = (score > 0) ? -1 : 0;
			}
			else {
				tthis.parent().find('a').toggleClass('pushed');
				score_step = (score > 0) ? 2 : -2;
				like_step = (score > 0) ? 1 : -1;
			}
		}
		else {
			tthis.toggleClass('pushed');
			if (score > 0)
				score_step = like_step = 1;
			else {
				score_step = -1;
				like_step = 0;
			}
		}

		// 此处先更新页面数字，不管成功与否
		$('#score-count').text(parseInt($('#score-count').text()) + score_step);
		$('#like-count').text(parseInt($('#like-count').text()) + like_step);

		var xhr = $.ajax({
			url: url,
			type: 'post',
			dataType: 'text',
			data: {id:pid, score:score},
		});
		xhr.done(function(data){
			if (parseInt(data) > 0) {
				// success
			}
		});
	},
	RatingComment: function(event){
		event.preventDefault();
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
			type: 'post',
			dataType: 'text',
			data: {id:pid, score:score},
		});
		xhr.done(function(data){
			if (parseInt(data) > 0) {
				// success
			}
		});
	},
	AjustImgWidth: function(selector, max){
		if ($.browser.msie && parseInt($.browser.version) < 7) {
			if (selector.width() > max)
				selector.css('width', max);
		}
	},
	PostComment: function(event) {
		event.preventDefault();
		var content = $.trim($('#comment-content').val());
		var postid = parseInt($('input[name=postid]').val());
		if (postid <= 0 || content.length <= 0)
			return false;
		var form = $(this).parents('form');

		var xhr = $.ajax({
			url: form.attr('action'),
			type: 'post',
			dataType: 'json',
			data: $('#comment-form').serialize(),
			beforeSend: function(){
				$('#caption-error').empty().hide();
				form.find('.save-caption-loader').show();
			}
		});
		xhr.done(function(data){
			form.find('.save-caption-loader').hide();
			if (data.errno == 0) {
				$('#comments').prepend(data.html);
				$('#comment-content').val('');
				$('#comment-content').removeClass('expand');
			}
			else
				$('#caption-error').html(data.error).show();
		});
		xhr.fail(function(){
			form.find('.save-caption-loader').hide();
			$('#caption-error').html('发送请求错误！').show();
		});
	}
};
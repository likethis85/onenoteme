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

		console.log(score_step);
		console.log(like_step);
		// 此处先更新页面数字，不管成功与否
		$('#score-count').text(parseInt($('#score-count').text()) + score_step);
		$('#like-count').text(parseInt($('#like-count').text()) + like_step);
		
		var xhr = $.ajax({
			url: url,
			type: 'post',
			dataType: 'text',
			data: {pid:pid, score:score},
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
	}
};
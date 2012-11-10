
var Waduanzi = {
	IncreasePostViewNums: function(postid, url){
		var xhr = $.ajax({
			url: url,
			type: 'post',
			dataType: 'jsonp',
			data: {id:postid}
		});
	},
	RatingPost: function(event){
		event.preventDefault();
		_hmt && _hmt.push(['_trackEvent', '文章评价按钮', '点击']);
		
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
			data: {id:pid, score:score}
		});
		xhr.done(function(data){
			if (parseInt(data) > 0) {
				// success
			}
		});
	},
	RatingComment: function(event){
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
			type: 'post',
			dataType: 'text',
			data: {id:pid, score:score}
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

Waduanzi.initDialog = function(){
	if ($('#quick-login').length > 0) {
		$('#quick-login').dialog({
			autoOpen: false,
		    show: 'fade',
		    modal: true,
		    draggable: false,
		    resizable: false,
		    width: 540,
		    height:280,
		    dialogClass: 'quick-login'
		});
	}
};

Waduanzi.switchImageSize = function(event){
    event.preventDefault();
    _hmt && _hmt.push(['_trackEvent', '图片', '缩略图与大图切换点击']);
    
    var itemDiv = $(this).parents('.post-item');
    itemDiv.find('.post-image .thumbnail-more').toggle();
    itemDiv.find('.post-image .thumb a .thumb').toggle();
    itemDiv.find('.post-image .thumb-pall').toggle();
    var originalUrl = itemDiv.find('.post-image .thumb a').attr('href');
    itemDiv.find('.post-image .thumb a .original').attr('src', originalUrl).toggle();
    var itemPos = itemDiv.position();
    $('body').scrollTop(itemPos.top);
};

Waduanzi.postUpDownScore = function(event){
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '文章评价按钮', '点击']);
	
	//$('#quick-login').dialog('open');
	var tthis = $(this);
	var itemDiv = tthis.parents('.post-item');
	var pid = itemDiv.attr('data-id');
	var score = tthis.attr('data-score');
	var url = tthis.attr('data-url');
	
	var jqXhr = $.ajax({
		type: 'POST',
		url: url,
		data: {pid: pid, score: score},
		dataType: 'text',
		beforeSend: function(){
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.done(function(data){
		if (data > 0) {
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
			alert('x');
			tthis.toggleClass('voted');
		}
	});
	
	jqXhr.fail(function(){
		alert('x');
		tthis.toggleClass('voted');
	});
};

Waduanzi.fixedAdBlock = function() {
	var adblock = $('.cd-sidebar .ad-block').last();
	if (adblock.length == 0) return false;
	var adtop = adblock.position().top + 73; // 73为顶部fixed导航的高度
	var lastblock = $('.cd-sidebar > div').last();
	var lastbottom = lastblock.position().top + lastblock.height() + 73;
	
	$(window).scroll(function(event){
		if ($('body').scrollTop() >= lastbottom)
			!adblock.hasClass('fixed') && adblock.addClass('fixed');
		else
			adblock.hasClass('fixed') && adblock.removeClass('fixed');
	});
};

Waduanzi.showShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '显示']);
	
	var bdshare = $(this).parents('.item-toolbar').find('#bdshare');
	if (bdshare.attr('data').length == 0) {
		var item = $(this).parents('.post-item');
		var bddata = {
			"url": item.find('.item-content h2 a').attr('href'),
			"text": '转自@挖段子网：' + item.find('.item-content .content').text(),
			"pic": item.find('.post-image .thumb a').attr('href'),
		};
		bdshare.attr('data', JSON.stringify(bddata));
	}
	$(this).parents('.item-toolbar').find('.sharebox:hidden').stop(true, true).delay(50).show();
};

Waduanzi.hideShareBox = function(event) {
	event.preventDefault();
	_hmt && _hmt.push(['_trackEvent', '分享列表', '隐藏']);
	
	$(this).parents('.item-toolbar').find('.sharebox:visible').stop(true, true).delay(50).hide();
};

$(function(){
	Waduanzi.fixedAdBlock();
});



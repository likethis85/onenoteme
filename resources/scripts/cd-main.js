/**
 * 
 */
$(function(){
	$('.item-toolbar .upscore').click(Waduanzi.upScore);
	$('.item-toolbar .downscore').click(Waduanzi.downScore);
	$('.buttons #refuse-post').click(Waduanzi.refusePost);
	$('.buttons #accept-post').click(Waduanzi.acceptPost);
	$('.comment-nums .view-comments').click(Waduanzi.loadComments);
	$(document).on('click', '.submit-button', Waduanzi.createComment);
});

var Waduanzi = {
	upScore: function(event) {
		var pid = parseInt($(this).attr('pid'));
		if (pid < 1) return false;
		var url = $('#jqvar').attr('scoreurl');
		var data = 'id=' + pid + '&score=1';
		var jqXhr = $.post(url, data);
		var tthis = this;
		jqXhr.success(function(data){
			if (data == 1)
				$(tthis).html(parseInt($(tthis).html()) + 1)
		});
		jqXhr.fail(function(){
//			console.log('fail');
		});
	},
	downScore: function(event) {
		var pid = parseInt($(this).attr('pid'));
		if (pid < 1) return false;
		var url = $('#jqvar').attr('scoreurl');
		var data = 'id=' + pid + '&score=-1';
		var jqXhr = $.post(url, data);
		var tthis = this;
		jqXhr.success(function(data){
			if (data == 1)
				$(tthis).html(parseInt($(tthis).html()) + 1);
		});
		jqXhr.fail(function(){
//			console.log('fail');
		});
	},
	acceptPost: function(){
		var pid = parseInt($(this).attr('pid'));
		if (pid < 1) return false;
		var url = $('#jqvar').attr('scoreurl');
		var data = 'id=' + pid + '&score=1';
		var jqXhr = $.post(url, data);
		var tthis = this;
		jqXhr.success(function(data){
			window.location.reload();
		});
		jqXhr.fail(function(){
//			console.log('fail');
		});
	},
	refusePost: function(){
		var pid = parseInt($(this).attr('pid'));
		if (pid < 1) return false;
		var url = $('#jqvar').attr('scoreurl');
		var data = 'id=' + pid + '&score=-1';
		var jqXhr = $.post(url, data);
		var tthis = this;
		jqXhr.success(function(data){
			window.location.reload();
		});
		jqXhr.fail(function(){
//			console.log('fail');
		});
	},
	loadComments: function(event){
		event.preventDefault();
		var key = '.comment-list-' + $(this).attr('pid');
		var comment = $(this).parents('.item-toolbar').next(key);
		if (comment.html()) {
			comment.toggle();
			return true;
		}
		var html = $('body').data(key);
		if (html) {
			comment.html(html).show();
		}
		else {
			var url = $(this).attr('url');
			var jqXhr = $.get(url);
			var tthis = this;
			jqXhr.success(function(data){
				comment.html(data).show();
				$('body').data(key, data);
			});
			jqXhr.fail(function(){
//				console.log('fail');
			});
		}
	},
	createComment: function(){
		var content = $(this).parents('.tbl-comment').find('.ccontent');
//		console.log(content.val());
		if ($.trim(content.val()) == '')
			return false;
		
		var data = $(this).parents('.comment-form').serialize();
//		console.log(data);
		var url = $(this).parents('.comment-form').attr('action');
		var tip = $(this).parents('.tbl-comment').find('.result-tip'); 
		tip.removeClass('cred').removeClass('cgreen');
		var jqXhr = $.post(url, data);
		var tthis = this;
		jqXhr.success(function(data){
			if (data == 1) {
				tip.html('谢谢<br />参与').addClass('cgreen').delay(3000).fadeOut('slow');
				content.val('');
			}
			else
				tip.html('发布<br />错误').addClass('cred').delay(3000).fadeOut('slow');
		});
		jqXhr.fail(function(){
			tip.html('发布<br />错误').addClass('cred').delay(3000).fadeOut('slow');
		});
	}
};
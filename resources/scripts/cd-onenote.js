/**
 * 
 */
$(function(){
	$('.item-toolbar').delegate('.upscore', 'click', Onenote.upScore);
	$('.item-toolbar').delegate('.downscore', 'click', Onenote.downScore);
	$('.buttons').delegate('#refuse-post', 'click', Onenote.refusePost);
	$('.buttons').delegate('#accept-post', 'click', Onenote.acceptPost);
	$('.comment-nums').delegate('.view-comments', 'click', Onenote.loadComments);
});

var Onenote = {
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
			console.log('fail');
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
			console.log('fail');
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
			console.log('fail');
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
			console.log('fail');
		});
	},
	loadComments: function(event){
		event.preventDefault();
		var key = 'comment-list-' + $(this).attr('pid');
		var comment = $(this).parents('.item-toolbar').next('.comment-list');
		if (comment.html()) {
			comment.toggle();
			return true;
		}
		var html = $('body').data(key);
		if (html) {
			comment.html(html).show();
		}
		else {
			var url = $(this).attr('href');
			var jqXhr = $.get(url);
			var tthis = this;
			jqXhr.success(function(data){
				comment.html(data).show();
				$('body').data(key, data);
			});
			jqXhr.fail(function(){
				console.log('fail');
			});
		}
	}
};
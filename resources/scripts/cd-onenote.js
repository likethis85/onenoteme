/**
 * 
 */
$(function(){
	$('.item-toolbar').delegate('.upscore', 'click', Onenote.upScore);
	$('.item-toolbar').delegate('.downscore', 'click', Onenote.downScore);
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
				$(tthis).html(parseInt($(tthis).html()) + 1)
		});
		jqXhr.fail(function(){
			console.log('fail');
		});
	},
	loadComments: function(event){
		event.preventDefault();
		var key = 'comment-list-' + $(this).attr('pid');
		if ($(key)) {
			$(key).toggle();
			return false;
		}
		var html = $('body').data(key);
		if (html) {
			$(this).parents('.item-toolbar').after(html);
		}
		else {
			var url = $(this).attr('href');
			var jqXhr = $.get(url);
			var tthis = this;
			jqXhr.success(function(data){
				console.log(data);
				$(tthis).parents('.item-toolbar').after(html);
				$('body').data(key, html);
			});
			jqXhr.fail(function(){
				console.log('fail');
			});
		}
	}
};
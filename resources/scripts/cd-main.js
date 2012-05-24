var Waduanzi = {
	RatingPost: function(event){
		event.preventDefault();
		var tthis = $(this);
		tthis.siblings().removeClass('pushed');
		tthis.addClass('pushed');

		var pid = parseInt(tthis.attr('data-id'));
		var score = parseInt(tthis.attr('data-value'));
		var url = tthis.attr('data-url');
		var xhr = $.ajax({
			url: url,
			type: 'post',
			dataType: 'text',
			data: {pid:pid, score:score},
		});
		xhr.done(function(data){
			;
		});
	}
};
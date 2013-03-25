$(function(){
	var tip = $('#beta-admin-tip');
	if (tip.length == 0) {
		var html = '<div id="beta-admin-tip"></div>';
		$('body').append(html);
		tip = $('#beta-admin-tip');
	}
	$(document).ajaxStart(function(){
		tip.stop(true, true);
		tip.html('发送数据中...').fadeIn('fast');
	});

	$(document).ajaxSuccess(function(){
		tip.html('请求成功.');
	});

	$(document).ajaxError(function(){
		tip.html('发生错误.');
	});

	$(document).ajaxStop(function(){
		tip.html('请求完成.').delay(5000).fadeOut('slow');
	});

	$('[rel=tooltip]').tooltip();
});

var BetaAdmin = {
	showAjaxMessage: function(text){
		if (text.length > 0) {
			$('#beta-admin-tip').html(text);
		}
	},
	selectAll: function(event){
		var checked = ($('.item-checkbox :checkbox:not(:checked)').length > 0) ? true : false;
		$('.item-checkbox :checkbox').attr('checked', checked);
	},
	reverseSelect: function(event) {
		$('.item-checkbox :checkbox').each(function(index, element){
			$(element).attr('checked', !$(element).attr('checked'));
		});
	},
	handleRow: function(event){
		event.preventDefault();

		var tthis = $(event.currentTarget);
		var jqXhr = $.ajax({
			url: tthis.attr('href'),
			dataType: 'jsonp',
			type: 'post',
			cache: false,
			beforeSend: function(){}
		});
		jqXhr.fail(function(){
			BetaAdmin.showAjaxMessage('Request error.');
		});

		return jqXhr;
	},
	deleteRow: function(event){
		event.preventDefault();
		var tthis = $(event.currentTarget);
		var confirm = window.confirm('您确定要执行删除操作？');
		if (!confirm) return ;

		var jqXhr = BetaAdmin.handleRow(event);
		jqXhr.done(function(data){
			if (data.errno == 0) {
				tthis.parents('tr').fadeOut('fast', function(){$(this).remove();});
			}
			else
				BetaAdmin.showAjaxMessage('Occur error.');
		});
	},
	handleMultiRows: function(event) {
		event.preventDefault();

		var checkboxs = $('input:checked');
		if (checkboxs.length == 0) return;

		var rowIds = [];
		checkboxs.each(function(index, element){
			rowIds.push($(element).val());
		});

		var tthis = $(event.currentTarget);
		var jqXhr = $.ajax({
			url: tthis.attr('data-src'),
			dataType: 'jsonp',
			type: 'post',
			cache: false,
			data: $.param({ids:rowIds}),
			beforeSend: function(){}
		});
		jqXhr.fail(function(){
			BetaAdmin.showAjaxMessage('request error.');
		});

		return jqXhr;
	},
	deleteMultiRows: function(event) {
		event.preventDefault();

		var confirm = window.confirm('您确定要执行删除操作？');
		if (!confirm) return ;

		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			$.each(data.success, function(index, value){
				$(':checkbox[value='+ value +']').parents('tr').remove();
			});
		});
	},
	verifyMultiRows: function(event) {
		event.preventDefault();
		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			$.each(data.success, function(index, value){
				$(':checkbox[value='+ value +']').parents('tr').remove();
			});
		});
	},
	setMultiRowsMark: function(event) {
		event.preventDefault();
		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			$.each(data.success, function(index, value){
				window.location.reload();
			});
		});
	},
	setMultiRowsState: function(event, success) {
		var confirm = window.confirm('您确定要执行此操作？');
		if (!confirm) return ;

		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			if (typeof(success) == 'function')
				success(data);
		});
	},
	ajaxSetBooleanColumn: function(event) {
		event.preventDefault();
		var tthis = $(event.currentTarget);
		var jqXhr = BetaAdmin.handleRow(event);
		jqXhr.done(function(data){
			if (data.errno == CD_NO) {
			    tthis.text(data.label).toggleClass('label-important label-success');
			}
			else
				BetaAdmin.showAjaxMessage('发生错误.');
		});
	},
	rejectMultiPosts: function(event) {
		event.preventDefault();

		var confirm = window.confirm('您确定要执行删除操作？');
		if (!confirm) return ;

		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			$.each(data.success, function(index, value){
				$(':checkbox[value='+ value +']').parents('tr').remove();
			});
		});
	},
	quickUpdate: function(event){
		event.preventDefault();
		var tthis = $(event.currentTarget);
		var form = tthis.parents('form');
		var jqXhr = $.ajax({
			url: form.attr('action'),
			dataType: 'jsonp',
			type: 'post',
			cache: false,
			data: form.serialize(),
			beforeSend: function(){
				tthis.button('loading');
			}
		});
		jqXhr.done(function(data){
			tthis.button('complete');
		});
		jqXhr.fail(function(){
			tthis.button('error');
		});
		jqXhr.always(function(){
			setTimeout(function(){
				tthis.button('reset');
				tthis.button('toggle');
			}, 1000);
		});
	},
	updateFilterKeywordRow: function(event){
		event.preventDefault();
		var tthis = $(event.currentTarget);
		var tr = tthis.parents('tr');
		var url = tr.attr('data-url');
		if (url.length == 0) return false;

		var data = tr.find('input').serialize();
		var jqXhr = $.ajax({
			url: url,
			dataType: 'jsonp',
			type: 'post',
			cache: false,
			data: data,
			beforeSend: function(){
				tthis.button('loading');
			}
		});
		jqXhr.done(function(data){
			if (data.errno == 0)
				tthis.button('complete');
			else
				tthis.button('error');
		});
		jqXhr.fail(function(){
			BetaAdmin.showAjaxMessage('request error.');
		});
		jqXhr.always(function(){
			setTimeout(function(){
				tthis.button('reset');
				tthis.button('toggle');
			}, 1000);
		});
	},
	switchMultiUserState: function(event){
		event.preventDefault();
		var jqXhr = BetaAdmin.handleMultiRows(event);
		jqXhr.done(function(data){
			$.each(data.success, function(index, value){
				$(':checkbox[value='+ value +']').parents('tr').find('.row-state').toggleClass('label-important label-success').text(data.label);
			});
		});
	}
};


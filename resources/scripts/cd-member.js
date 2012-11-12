var CDMember = {};

CDMember.executeAjax = function(url, data, before, success, fail){
	var jqXhr = $.ajax({
		type: 'post',
		dataType: 'jsonp',
		url: url,
		data: data || {},
		before: (typeof(before) == 'funciton') && before
	});
	
	if ((typeof(success) == 'function'))
		jqXhr.done(success(data));
	
	if ((typeof(fail) == 'function'))
		jqXhr.fail(fail(jqXhr));
};
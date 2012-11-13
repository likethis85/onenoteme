var CDMember = {};

CDMember.executeAjax = function(url, data, before, success, fail){
	var jqXhr = $.ajax({
		type: 'post',
		dataType: 'jsonp',
		url: url,
		data: data || {},
		before: (typeof(before) == 'funciton') && before()
	});
	
	jqXhr.done(function(data){
		if ((typeof(success) == 'function'))
			success(data);
	});
	
	jqXhr.fail(function(jqXhr, textStatus){
		if ((typeof(fail) == 'function'))
			fail(jqXhr, textStatus);
	});
};

CDMember.executeAjaxDelete = function(event, success, fail){
    var confirm = window.confirm('确定要执行删除操作吗？');
    if (!confirm) return false;
	
	var tthis = $(event.currentTarget);
	var url = tthis.attr('data-url');
	CDMember.executeAjax(url, null, null, success || function(data){
		tthis.parents('tr').fadeOut('slow');
	}, fail || function(jqXhr, textStatus){
		$('#ajax-tip').html('删除段子失败').addClass('cred').delay(5000).fadeOut('slow').html();
	});
};
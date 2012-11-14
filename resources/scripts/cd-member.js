var CDMember = {};

CDMember.executeAjax = function(url, data, before, success, fail){
	var jqXhr = $.ajax({
		type: 'POST',
		dataType: 'jsonp',
		url: url,
		cache: false,
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
		if (data.errno == 0)
			tthis.parents('tr').fadeOut('slow');
		else
			$('#ajax-tip').html('删除失败').addClass('cred').delay(5000).fadeOut('slow').html();
	}, fail || function(jqXhr, textStatus){
		$('#ajax-tip').html('删除失败').addClass('cred').delay(5000).fadeOut('slow').html();
	});
};
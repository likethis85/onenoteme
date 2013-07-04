<table class="table table-striped table-bordered post-list-table">
    <thead>
        <tr>
            <th class="span7">内容</th>
            <th class="span1">#</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ((array)$models as $model):?>
        <tr>
            <td class="weibo-text">
                <p><?php echo $model->content;?></p>
                <?php echo l(image($model->thumbnail_pic, ''), $model->bmiddle_pic, array('target'=>'_blank'));?>
            </td>
            <td>
                <p><a href="<?php echo aurl('admin/wbpost/weiboVerify', array('id'=>$model->id, 'media_type'=>MEDIA_TYPE_TEXT));?>" class="btn btn-small btn-success row-verify">段子</a></p>
                <p><a href="<?php echo aurl('admin/wbpost/weiboVerify', array('id'=>$model->id, 'media_type'=>MEDIA_TYPE_IMAGE));?>" class="btn btn-small btn-success row-verify">趣图</a></p>
            </td>
            <td>
                <p><a href="<?php echo aurl('admin/wbpost/weiboDelete', array('id'=>$model->id));?>" class="btn btn-small btn-warning row-delete">删除</a></p>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<a class="btn btn-small btn-success" href=''>重新载入</a>

<script type="text/javascript">
$(function(){
    $(document).on('dblclick', '.weibo-text', function(event){
        event.preventDefault();
        var p = $(this).find('p');
        var html = '<textarea name="newtext">' + $.trim(p.html()) + '</textarea>';
        p.html(html);
        p.find('textarea').height(p.height() + 30);
    });
	
	$(document).on('click', '.row-verify, .row-delete', function(event){
		event.preventDefault();
		
		var tthis = $(this);
		var jqXhrOptions = {
			url: tthis.attr('href'),
			type: 'post',
			dataType: 'jsonp',
			cache: false
		};
		if (tthis.parents('tr').find('.weibo-text textarea').length > 0)
			jqXhrOptions.data = 'weibotext=' + tthis.parents('tr').find('.weibo-text textarea').val();
		var jqXhr = $.ajax(jqXhrOptions);
		jqXhr.done(function(data){
			if (data == 1)
				tthis.parents('tr').remove();
			else
				alert('操作失败，请重试一下');
		});
		
		jqXhr.fail(function(){
			alert('请求失败，请重试一下');
		});
	});
});
</script>
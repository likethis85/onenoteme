<table class="table table-striped table-bordered post-list-table">
    <thead>
        <tr>
            <th class="span7">内容</th>
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
                <p><a href="<?php echo aurl('admin/weibo/create', array('id'=>$model->id));?>" class="btn btn-small btn-success row-create">发布</a></p>
                <p>&nbsp;</p>
                <p><a href="<?php echo aurl('admin/weibo/skip', array('id'=>$model->id));?>" class="btn btn-small row-skip">跳过</a></p>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<a class="btn btn-small btn-success" href=''>重新载入</a>
<ul>
    <li>新浪UID：<?php app()->cache->get('sina_weibo_user_id');?></li>
    <li>新浪Access Token：<?php app()->cache->get('sina_weibo_access_token');?></li>
    <li>腾讯UID：<?php app()->cache->get('qq_weibo_user_id');?></li>
    <li>腾讯Access Token：<?php app()->cache->get('qq_weibo_access_token');?></li>
</ul>


<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-create, .row-skip', function(event){
		event.preventDefault();
		
		var tthis = $(this);
		var jqXhrOptions = {
			url: tthis.attr('href'),
			type: 'post',
			dataType: 'text',
			cache: false
		};
		var jqXhr = $.ajax(jqXhrOptions);
		jqXhr.done(function(data){
			if (data > 0)
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
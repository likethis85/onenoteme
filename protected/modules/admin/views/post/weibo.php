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
            <td>
                <p><?php echo $model->content;?></p>
                <?php echo l(image($model->thumbnail_pic, ''), $model->bmiddle_pic, array('target'=>'_blank'));?>
            </td>
            <td>
                <p><a href="<?php echo aurl('admin/post/weiboVerify', array('id'=>$model->id, 'channel_id'=>CHANNEL_DUANZI));?>" class="btn btn-small btn-success row-verify">段子</a></p>
                <p><a href="<?php echo aurl('admin/post/weiboVerify', array('id'=>$model->id, 'channel_id'=>CHANNEL_LENGTU));?>" class="btn btn-small btn-success row-verify">冷图</a></p>
                <p><a href="<?php echo aurl('admin/post/weiboVerify', array('id'=>$model->id, 'channel_id'=>CHANNEL_GIRL));?>" class="btn btn-small btn-success row-verify">福利</a></p>
            </td>
            <td>
                <p><a href="<?php echo aurl('admin/post/weiboDelete', array('id'=>$model->id));?>" class="btn btn-small btn-warning row-delete">删除</a></p>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="pages">
<?php $this->widget('CLinkPager', array('pages'=>$pages));?>
</div>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-verify, .row-delete', function(event){
		event.preventDefault();
		
		var tthis = $(this);
		var jqXhr = $.ajax({
			url: tthis.attr('href'),
			type: 'post',
			dataType: 'jsonp',
			cache: false,
		});
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
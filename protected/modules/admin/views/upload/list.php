<h4><?php echo $this->adminTitle;?></h4>
<div class="btn-toolbar">
    <button class="btn btn-small" id="select-all">全选</button>
    <button class="btn btn-small" id="reverse-select">反选</button>
    <button class="btn btn-small btn-danger" id="batch-delete">删除</button>
    <a class="btn btn-small" href="<?php echo url('admin/upload/search');?>">搜索</a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="item-checkbox align-center">#</th>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span1"><?php echo $sort->link('post_id');?></th>
            <th class="span1"><?php echo $sort->link('user_id');?></th>
            <th class="span1 align-center"><?php echo $sort->link('file_type');?></th>
            <th class="span2 align-center"><?php echo $sort->link('create_time');?></th>
            <th class="span4">描述</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr class="file-item">
            <td class="item-checkbox"><input type="checkbox" name="itemid[]" value="<?php echo $model->id;?>" /></td>
            <td class="align-center"><?php echo $model->id;?></td>
            <td class="align-center"><?php echo $model->post_id;?></td>
            <td class="align-center"><?php echo $model->user_id;?></td>
            <td class="align-center"><?php echo $model->fileTypeText;?></td>
            <td class="align-center"><?php echo $model->createTimeText;?></td>
            <td><?php echo h($model->desc);?></td>
            <td>
                <?php echo $model->editLink;?>
                <?php echo $model->deleteLink;?>
            </td>
        </tr>
        <tr class="file-info hide"><td colspan="8"><?php echo $model->fileUrl;?><?php echo $model->previewLink;?></td></tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.file-item', function(event){
		$(this).next('tr').toggle();
	});
	
	$(document).on('click', '#select-all', BetaAdmin.selectAll);
	$(document).on('click', '#reverse-select', BetaAdmin.reverseSelect);
});
</script>

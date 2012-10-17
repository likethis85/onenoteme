<?php if (user()->hasFlash('clear_advert_all_cache_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('clear_advert_all_cache_result');?>
</div>
<?php endif;?>
<h4><?php echo user()->getFlash('table_caption', '广告位列表');?></h4>
<div class="btn-toolbar">
    <a class="btn btn-danger btn-small" href="<?php echo url('admin/advert/clearAllCache');?>">清除缓存</a>
    <a class="btn btn-success btn-small" href="<?php echo url('admin/advert/create');?>">新建广告位</a>
    <a href="" class="btn btn-small">刷新</a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span3"><?php echo $sort->link('name');?></th>
            <th class="span3"><?php echo $sort->link('solt');?></th>
            <th class="span1 align-center"><?php echo $sort->link('state');?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="align-center"><?php echo $model->id;?></td>
            <td><?php echo $model->nameLink;?></td>
            <td><?php echo $model->solt;?></td>
            <td class="align-center"><?php echo $model->stateLink;?></td>
            <td>
                <?php echo $model->editLink;?>
                <?php echo $model->deleteLink;?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>

<div class="alert alert-block alert-info">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <ul>
        <li>广告位在编辑过程中会自动处理缓存，如没有必要，不必清除所有缓存。</li>
        <li>警告：删除广告位会将该广告位下所有广告代码一并删除！</li>
    </ul>
</div>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-state', BetaAdmin.ajaxSetBooleanColumn);
	$(document).on('click', '.set-delete', BetaAdmin.deleteRow);
	$(document).on('hover', '#clear-cache', function(event){
		$('#clear-cache').popover('toggle');
	});
});
</script>